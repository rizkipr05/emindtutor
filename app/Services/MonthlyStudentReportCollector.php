<?php

namespace App\Services;

use App\Models\Company;
use App\Models\MonthlyStudentReport;
use App\Models\Student;
use App\Models\TeacherAttendance;
use App\Models\TeachingReport;
use Carbon\Carbon;

class MonthlyStudentReportCollector
{
    public function collectDueReports(?Carbon $date = null): int
    {
        $date = ($date ?: now('Asia/Kuala_Lumpur'))->startOfDay();
        $created = 0;

        Company::query()->where('status', true)->get()->each(function (Company $company) use ($date, &$created) {
            if (! $this->isDue($company, $date)) {
                return;
            }

            $periodDate = $company->monthly_report_schedule === 'last_day'
                ? $date
                : $date->copy()->subMonthNoOverflow();

            $company->students()
                ->whereIn('status', ['active', 'expired'])
                ->with(['user', 'classScheduleStudents.classSchedule.course'])
                ->get()
                ->each(function (Student $student) use ($company, $periodDate, $date, &$created) {
                    $report = $this->collectStudentReport($company, $student, $periodDate, $date);
                    if ($report->wasRecentlyCreated) {
                        $created++;
                    }
                });
        });

        return $created;
    }

    public function collectStudentReport(Company $company, Student $student, Carbon $periodDate, ?Carbon $collectedAt = null): MonthlyStudentReport
    {
        $periodStart = $periodDate->copy()->startOfMonth();
        $periodEnd = $periodDate->copy()->endOfMonth();
        $classes = [];
        $teachingReports = TeachingReport::where('student_id', $student->id)
            ->whereBetween('class_date', [$periodStart->toDateString(), $periodEnd->toDateString()])
            ->get()
            ->keyBy(fn (TeachingReport $report) => $report->class_schedule_id . '|' . $report->class_date->toDateString());

        $student->classScheduleStudents
            ->pluck('classSchedule')
            ->filter()
            ->unique('id')
            ->each(function ($classSchedule) use ($student, $periodStart, $periodEnd, $teachingReports, &$classes) {
                $sessions = TeacherAttendance::query()
                    ->where('class_schedule_id', $classSchedule->id)
                    ->whereBetween('check_in', [$periodStart, $periodEnd->copy()->endOfDay()])
                    ->whereNotNull('check_in')
                    ->orderBy('check_in')
                    ->get();

                $attendanceByDate = $classSchedule->studentAttendances()
                    ->where('user_id', $student->user_id)
                    ->whereBetween('attendance_date', [$periodStart->toDateString(), $periodEnd->toDateString()])
                    ->get()
                    ->keyBy(fn ($attendance) => Carbon::parse($attendance->attendance_date)->toDateString());

                $details = $sessions->map(function ($session) use ($attendanceByDate, $teachingReports) {
                    $attendance = $attendanceByDate->get($session->check_in->toDateString());
                    $present = in_array($attendance?->status, ['present', 'hadir'], true);

                    $teachingReport = $teachingReports->get($session->class_schedule_id . '|' . $session->check_in->toDateString());

                    return [
                        'date' => $session->check_in->toDateString(),
                        'status' => $present ? 'hadir' : 'tidak_hadir',
                        'learning_topic' => $teachingReport?->learning_topic,
                        'teacher_note' => $teachingReport?->teacher_note ?: $session->note,
                        'student_note' => $attendance?->note,
                    ];
                })->values()->all();

                $totalSessions = count($details);
                $attendedSessions = collect($details)->where('status', 'hadir')->count();

                $classes[] = [
                    'class_id' => $classSchedule->id,
                    'class_name' => $classSchedule->nama_kelas,
                    'course_name' => $classSchedule->course?->nama_kos,
                    'total_sessions' => $totalSessions,
                    'attended_sessions' => $attendedSessions,
                    'attendance_percentage' => $totalSessions > 0 ? round(($attendedSessions / $totalSessions) * 100, 2) : 0,
                    'sessions' => $details,
                ];
            });

        $totalSessions = collect($classes)->sum('total_sessions');
        $attendedSessions = collect($classes)->sum('attended_sessions');

        $report = MonthlyStudentReport::updateOrCreate(
            [
                'student_id' => $student->id,
                'report_month' => $periodDate->month,
                'report_year' => $periodDate->year,
            ],
            [
                'company_id' => $company->id,
                'period_start' => $periodStart->toDateString(),
                'period_end' => $periodEnd->toDateString(),
                'collected_at' => $collectedAt ?: now('Asia/Kuala_Lumpur'),
                'summary' => [
                    'student_name' => $student->user?->name,
                    'total_sessions' => $totalSessions,
                    'attended_sessions' => $attendedSessions,
                    'attendance_percentage' => $totalSessions > 0 ? round(($attendedSessions / $totalSessions) * 100, 2) : 0,
                    'classes' => $classes,
                ],
            ]
        );

        return app(MonthlyLearningStatementPdfService::class)->generate($report);
    }

    private function isDue(Company $company, Carbon $date): bool
    {
        return match ($company->monthly_report_schedule) {
            'last_day' => $date->isLastOfMonth(),
            'custom_day' => $date->day === min((int) $company->monthly_report_custom_day, $date->daysInMonth),
            default => $date->day === 1,
        };
    }
}

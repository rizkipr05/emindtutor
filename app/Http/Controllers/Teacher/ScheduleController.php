<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\ClassScheduleDay;
use App\Models\ClassScheduleException;
use App\Models\ClassScheduleStudent;
use App\Models\ClassScheduleSession;
use App\Models\Course;
use App\Models\Sallary;
use App\Models\StudentAttendance;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index()
    {
        // Get current teacher
        $teacher = Teacher::where('user_id', Auth::id())->first();
        
        if (!$teacher) {
            return redirect()->back()->with('error', 'Teacher profile not found');
        }

        $today = Carbon::today('Asia/Kuala_Lumpur');
        $selectedView = request('view', 'weekly');
        if (!in_array($selectedView, ['weekly', 'monthly'], true)) {
            $selectedView = 'weekly';
        }

        $selectedMonth = request('month', Carbon::now('Asia/Kuala_Lumpur')->format('Y-m'));
        try {
            $selectedMonthDate = Carbon::createFromFormat('Y-m', $selectedMonth, 'Asia/Kuala_Lumpur')->startOfMonth();
        } catch (\Throwable $e) {
            $selectedMonthDate = Carbon::now('Asia/Kuala_Lumpur')->startOfMonth();
        }

        $calendarStart = $selectedMonthDate->copy()->startOfMonth()->startOfWeek(Carbon::MONDAY);
        $calendarEnd = $selectedMonthDate->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        // Get all class schedules for this teacher
        $classSchedules = ClassSchedule::with([
            'course',
            'classScheduleDays',
            'exceptions',
            'sessions',
            'classScheduleStudent.student.user',
            'classScheduleStudent.student.invoices'
        ])
        ->where('teacher_id', $teacher->id)
        ->get();

        $this->ensureSessionsGenerated($classSchedules, $calendarStart, $calendarEnd);
        $classSchedules->load([
            'exceptions',
            'sessions' => function ($query) use ($calendarStart, $calendarEnd) {
                $query->whereBetween('session_date', [
                    $calendarStart->toDateString(),
                    $calendarEnd->toDateString(),
                ]);
            },
        ]);

        // Tandai student aktif untuk setiap jadwal, tetapi tetap tampilkan semua jadwal.
        $classSchedules = $classSchedules->map(function ($schedule) use ($today) {
            $activeStudents = [];

            foreach ($schedule->classScheduleStudent as $classStudent) {
                $student = $classStudent->student;

                if (!$student) {
                    continue;
                }

                if ($student->hasActiveCourseAccess($schedule->course_id, $today)) {
                    $activeStudents[] = $student;
                }
            }

            // simpan daftar student aktif ke dalam schedule
            $schedule->active_students = $activeStudents;

            return $schedule;
        });

        // Organize schedules by day and time
        $weeklySchedule = $this->organizeScheduleByWeek($classSchedules);
        $monthlySchedule = $this->organizeScheduleByMonth($classSchedules, $selectedMonthDate);
        $scheduleSummaries = $this->buildScheduleSummaries($classSchedules);
        $monthNavigation = [
            'current' => $selectedMonthDate->format('Y-m'),
            'label' => $selectedMonthDate->translatedFormat('F Y'),
            'previous' => $selectedMonthDate->copy()->subMonth()->format('Y-m'),
            'next' => $selectedMonthDate->copy()->addMonth()->format('Y-m'),
        ];

        return view('teacher.schedule.index', compact(
            'weeklySchedule',
            'monthlySchedule',
            'scheduleSummaries',
            'selectedView',
            'monthNavigation'
        ));
    }

    public function create()
    {
        $teacher = Teacher::with(['user', 'company'])->where('user_id', Auth::id())->firstOrFail();
        $courses = Course::where('company_id', $teacher->company_id)
            ->orderBy('nama_kos')
            ->get();

        return view('teacher.schedule.create', compact('teacher', 'courses'));
    }

    public function store(Request $request)
    {
        $teacher = Teacher::with('company')->where('user_id', Auth::id())->firstOrFail();

        $scheduleDays = $request->input('schedule_days', []);

        foreach ($scheduleDays as &$day) {
            if (!empty($day['jam_mulai'])) {
                try {
                    $time = Carbon::createFromFormat('H:i:s', $day['jam_mulai']);
                } catch (\Exception $e) {
                    try {
                        $time = Carbon::createFromFormat('H:i', $day['jam_mulai']);
                    } catch (\Exception $e2) {
                        continue;
                    }
                }

                $day['jam_mulai'] = $time->format('H:i:s');
            }
        }

        $request->merge([
            'schedule_days' => $scheduleDays,
        ]);

        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'nama_kelas' => 'required|string|max:255',
            'link_group' => 'nullable|url|max:500',
            'notes' => 'nullable|string|max:1000',
            'recurrence_type' => 'required|in:weekly,monthly',
            'recurrence_start_date' => 'nullable|date',
            'recurrence_end_date' => 'nullable|date|after_or_equal:recurrence_start_date',
            'schedule_days' => 'required|array|min:1',
            'schedule_days.*.hari' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'schedule_days.*.jam_mulai' => 'required|date_format:H:i:s',
            'schedule_days.*.durasi_menit' => 'required|integer|min:15|max:480',
        ]);

        $course = Course::where('company_id', $teacher->company_id)
            ->findOrFail($request->course_id);

        DB::transaction(function () use ($request, $teacher, $course) {
            $classSchedule = ClassSchedule::create([
                'company_id' => $teacher->company_id,
                'course_id' => $course->id,
                'teacher_id' => $teacher->id,
                'nama_kelas' => $request->nama_kelas,
                'link_group' => $request->link_group,
                'notes' => $request->notes,
                'status' => 'akan_datang',
                'recurrence_type' => $request->recurrence_type,
                'recurrence_start_date' => $request->recurrence_start_date ?: now('Asia/Kuala_Lumpur')->toDateString(),
                'recurrence_end_date' => $request->recurrence_end_date,
            ]);

            foreach ($request->schedule_days as $day) {
                ClassScheduleDay::create([
                    'class_schedule_id' => $classSchedule->id,
                    'hari' => $day['hari'],
                    'jam_mulai' => $day['jam_mulai'],
                    'durasi_menit' => $day['durasi_menit'],
                ]);
            }

            $this->generateSessionsForSchedule(
                $classSchedule->fresh('classScheduleDays', 'exceptions'),
                Carbon::parse($classSchedule->recurrence_start_date, 'Asia/Kuala_Lumpur'),
                $classSchedule->recurrence_end_date
                    ? Carbon::parse($classSchedule->recurrence_end_date, 'Asia/Kuala_Lumpur')
                    : Carbon::parse($classSchedule->recurrence_start_date, 'Asia/Kuala_Lumpur')->addMonths(3)
            );
        });

        return redirect()->route('teacher.schedule.index')
            ->with('success', 'Jadwal kelas berjaya ditambah.');
    }

    public function edit($id)
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        
        $classSchedule = ClassSchedule::with(['classScheduleDays', 'course', 'teacher', 'exceptions'])
            ->where('teacher_id', $teacher->id)
            ->findOrFail($id);

        $upcomingOccurrences = $this->buildUpcomingOccurrences($classSchedule);

        return view('teacher.schedule.edit', compact('classSchedule', 'upcomingOccurrences'));
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        
        // Ensure the schedule belongs to this teacher
        $classSchedule = ClassSchedule::where('teacher_id', $teacher->id)
            ->findOrFail($id);

        // Ambil data schedule_days dari request
        $scheduleDays = $request->input('schedule_days', []);

        // Normalisasi jam supaya selalu HH:MM:SS
        foreach ($scheduleDays as &$day) {
            if (!empty($day['jam_mulai'])) {
                try {
                    // Coba parse "H:i:s"
                    $time = Carbon::createFromFormat('H:i:s', $day['jam_mulai']);
                } catch (\Exception $e) {
                    try {
                        // Kalau gagal, berarti formatnya "H:i"
                        $time = Carbon::createFromFormat('H:i', $day['jam_mulai']);
                    } catch (\Exception $e2) {
                        // Handle other potential formats or let validation fail
                        continue;
                    }
                }

                // Simpan ulang ke format konsisten H:i:s
                $day['jam_mulai'] = $time->format('H:i:s');
            }
        }

        // Gabungkan lagi ke request
        $request->merge([
            'schedule_days' => $scheduleDays
        ]);

        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'link_group' => 'nullable|url|max:500',
            'notes' => 'nullable|string|max:1000',
            'recurrence_type' => 'required|in:weekly,monthly',
            'recurrence_start_date' => 'nullable|date',
            'recurrence_end_date' => 'nullable|date|after_or_equal:recurrence_start_date',
            'exceptions' => 'nullable|array',
            'exceptions.*.exception_date' => 'nullable|date',
            'exceptions.*.reason' => 'nullable|string|max:255',

            // schedule_days wajib array
            'schedule_days' => 'required|array|min:1',

            // setiap item di dalam schedule_days harus punya format ini
            'schedule_days.*.hari' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'schedule_days.*.jam_mulai' => 'required|date_format:H:i:s',
            'schedule_days.*.durasi_menit' => 'required|integer|min:15|max:480',
        ]);

        DB::transaction(function () use ($request, $id) {
            // Update class schedule
            ClassSchedule::where('id', $id)->update([
                'nama_kelas' => $request->nama_kelas,
                'link_group' => $request->link_group,
                'notes' => $request->notes,
                'recurrence_type' => $request->recurrence_type,
                'recurrence_start_date' => $request->recurrence_start_date ?: now('Asia/Kuala_Lumpur')->toDateString(),
                'recurrence_end_date' => $request->recurrence_end_date,
            ]);

            // Delete existing schedule days
            ClassScheduleDay::where('class_schedule_id', $id)->delete();

            // Create new schedule days
            foreach ($request->schedule_days as $day) {
                ClassScheduleDay::create([
                    'class_schedule_id' => $id,
                    'hari' => $day['hari'],
                    'jam_mulai' => $day['jam_mulai'],
                    'durasi_menit' => $day['durasi_menit'],
                ]);
            }

            ClassScheduleException::where('class_schedule_id', $id)->delete();
            foreach ($request->input('exceptions', []) as $exception) {
                if (empty($exception['exception_date'])) {
                    continue;
                }

                ClassScheduleException::create([
                    'class_schedule_id' => $id,
                    'exception_date' => $exception['exception_date'],
                    'action' => 'cancel',
                    'reason' => $exception['reason'] ?? null,
                ]);
            }

            $classSchedule = ClassSchedule::with('classScheduleDays', 'exceptions')->findOrFail($id);
            $startDate = Carbon::parse($classSchedule->recurrence_start_date, 'Asia/Kuala_Lumpur');
            $endDate = $classSchedule->recurrence_end_date
                ? Carbon::parse($classSchedule->recurrence_end_date, 'Asia/Kuala_Lumpur')
                : $startDate->copy()->addMonths(3);

            ClassScheduleSession::where('class_schedule_id', $id)
                ->where('status', 'scheduled')
                ->delete();

            $this->generateSessionsForSchedule($classSchedule, $startDate, $endDate);
        });

        return redirect()->route('teacher.schedule.index')
            ->with('success', 'Jadual kelas berjaya dikemaskini.');
    }

    public function destroy($id)
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();

        $classSchedule = ClassSchedule::where('teacher_id', $teacher->id)
            ->findOrFail($id);

        DB::transaction(function () use ($classSchedule) {
            Sallary::where('class_schedule_id', $classSchedule->id)->delete();
            TeacherAttendance::where('class_schedule_id', $classSchedule->id)->delete();
            StudentAttendance::where('class_schedule_id', $classSchedule->id)->delete();
            ClassScheduleStudent::where('class_schedule_id', $classSchedule->id)->delete();
            ClassScheduleSession::where('class_schedule_id', $classSchedule->id)->delete();
            ClassScheduleException::where('class_schedule_id', $classSchedule->id)->delete();
            ClassScheduleDay::where('class_schedule_id', $classSchedule->id)->delete();
            $classSchedule->delete();
        });

        return redirect()->route('teacher.schedule.index')
            ->with('success', 'Jadual kelas berjaya dihapus.');
    }

    private function organizeScheduleByWeek($classSchedules)
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $timeSlots = ['Morning', 'Afternoon', 'Night'];
        
        $weeklySchedule = [];
        
        // Initialize empty schedule
        foreach ($timeSlots as $timeSlot) {
            foreach ($days as $day) {
                $weeklySchedule[$timeSlot][$day] = [];
            }
        }

        foreach ($classSchedules as $schedule) {
            foreach ($schedule->classScheduleDays as $scheduleDay) {
                // Calculate end time
                $startTime = Carbon::createFromFormat('H:i:s', $scheduleDay->jam_mulai);
                // Tambahkan (int)
                $endTime = $startTime->copy()->addMinutes((int) $scheduleDay->durasi_menit);
                
                // Determine time slot based on start time
                $hour = $startTime->hour;
                $timeSlot = $this->getTimeSlot($hour);
                
                // Get students for this class
                $students = $schedule->classScheduleStudent->map(function($css) {
                    return $css->student->user->name ?? 'Unknown Student';
                })->toArray();

                $scheduleItem = [
                    'id' => $schedule->id,
                    'course_name' => $schedule->course->nama_kos,
                    'class_name' => $schedule->nama_kelas,
                    'start_time' => $startTime->format('g:i A'),
                    'end_time' => $endTime->format('g:i A'),
                    'time_range' => $startTime->format('g:i A') . ' - ' . $endTime->format('g:i A'),
                    'students' => $students,
                    'student_count' => count($students),
                    'status' => $schedule->status
                ];

                $weeklySchedule[$timeSlot][$scheduleDay->hari][] = $scheduleItem;
            }
        }

        return $weeklySchedule;
    }

    private function organizeScheduleByMonth($classSchedules, Carbon $selectedMonthDate): array
    {
        $calendarStart = $selectedMonthDate->copy()->startOfMonth()->startOfWeek(Carbon::MONDAY);
        $calendarEnd = $selectedMonthDate->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);
        $weeks = [];
        $currentDate = $calendarStart->copy();

        while ($currentDate->lte($calendarEnd)) {
            $week = [];

            for ($i = 0; $i < 7; $i++) {
                $daySchedules = [];
                $dayName = $currentDate->format('l');
                $dateString = $currentDate->toDateString();

                foreach ($classSchedules as $schedule) {
                    foreach ($schedule->sessions as $session) {
                        if ($session->session_date->toDateString() !== $dateString) {
                            continue;
                        }

                        $startTime = Carbon::createFromFormat('H:i:s', $session->start_time);
                        $endTime = Carbon::createFromFormat('H:i:s', $session->end_time);

                        $daySchedules[] = [
                            'id' => $schedule->id,
                            'course_name' => $schedule->course->nama_kos,
                            'class_name' => $schedule->nama_kelas,
                            'time_range' => $startTime->format('g:i A') . ' - ' . $endTime->format('g:i A'),
                            'student_count' => count($schedule->active_students ?? []),
                            'status' => $session->status,
                            'cancellation_reason' => $session->cancellation_reason,
                        ];
                    }
                }

                usort($daySchedules, fn ($a, $b) => strcmp($a['time_range'], $b['time_range']));

                $week[] = [
                    'date' => $currentDate->copy(),
                    'is_current_month' => $currentDate->month === $selectedMonthDate->month,
                    'is_today' => $currentDate->isToday(),
                    'schedules' => $daySchedules,
                ];

                $currentDate->addDay();
            }

            $weeks[] = $week;
        }

        return $weeks;
    }

    private function buildScheduleSummaries($classSchedules): array
    {
        return $classSchedules
            ->map(function ($schedule) {
                $days = $schedule->classScheduleDays
                    ->sortBy(fn ($day) => array_search($day->hari, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'], true))
                    ->map(function ($day) {
                        $startTime = Carbon::createFromFormat('H:i:s', $day->jam_mulai);
                        $endTime = $startTime->copy()->addMinutes((int) $day->durasi_menit);

                        return [
                            'day' => $day->hari,
                            'summary' => sprintf(
                                '%s, %s - %s',
                                $day->hari,
                                $startTime->format('g:i A'),
                                $endTime->format('g:i A')
                            ),
                        ];
                    })
                    ->values()
                    ->all();

                return [
                    'id' => $schedule->id,
                    'class_name' => $schedule->nama_kelas,
                    'course_name' => $schedule->course->nama_kos,
                    'student_count' => count($schedule->active_students ?? []),
                    'days' => $days,
                    'recurrence_type' => $schedule->recurrence_type ?? 'weekly',
                    'recurrence_start_date' => $schedule->recurrence_start_date,
                    'recurrence_end_date' => $schedule->recurrence_end_date,
                ];
            })
            ->values()
            ->all();
    }

    private function buildUpcomingOccurrences(ClassSchedule $classSchedule): array
    {
        if ($classSchedule->classScheduleDays->isEmpty()) {
            return [];
        }

        $today = Carbon::today('Asia/Kuala_Lumpur');
        $upcomingOccurrences = [];
        $cursor = $today->copy();

        while (count($upcomingOccurrences) < 8) {
            foreach ($classSchedule->classScheduleDays as $scheduleDay) {
                if ($cursor->format('l') !== $scheduleDay->hari) {
                    continue;
                }

                $startTime = Carbon::createFromFormat('H:i:s', $scheduleDay->jam_mulai);
                $endTime = $startTime->copy()->addMinutes((int) $scheduleDay->durasi_menit);

                $upcomingOccurrences[] = [
                    'date' => $cursor->copy(),
                    'day' => $scheduleDay->hari,
                    'time_range' => $startTime->format('g:i A') . ' - ' . $endTime->format('g:i A'),
                ];
            }

            $cursor->addDay();
        }

        usort($upcomingOccurrences, fn ($a, $b) => $a['date']->timestamp <=> $b['date']->timestamp);

        return array_slice($upcomingOccurrences, 0, 8);
    }

    private function ensureSessionsGenerated($classSchedules, Carbon $from, Carbon $to): void
    {
        foreach ($classSchedules as $schedule) {
            $this->generateSessionsForSchedule($schedule, $from, $to);
        }
    }

    private function generateSessionsForSchedule(ClassSchedule $classSchedule, Carbon $from, Carbon $to): void
    {
        $classSchedule->loadMissing('classScheduleDays', 'exceptions');

        if ($classSchedule->classScheduleDays->isEmpty()) {
            return;
        }

        $startBoundary = $classSchedule->recurrence_start_date
            ? Carbon::parse($classSchedule->recurrence_start_date, 'Asia/Kuala_Lumpur')
            : $from->copy();
        $endBoundary = $classSchedule->recurrence_end_date
            ? Carbon::parse($classSchedule->recurrence_end_date, 'Asia/Kuala_Lumpur')
            : $to->copy();

        $cursor = $from->copy()->max($startBoundary)->startOfDay();
        $end = $to->copy()->min($endBoundary)->startOfDay();

        if ($cursor->gt($end)) {
            return;
        }

        $exceptions = $classSchedule->exceptions
            ->keyBy(fn ($exception) => $exception->exception_date->toDateString());

        while ($cursor->lte($end)) {
            foreach ($classSchedule->classScheduleDays as $scheduleDay) {
                if (!$this->shouldGenerateOccurrence($classSchedule, $scheduleDay, $cursor, $startBoundary)) {
                    continue;
                }

                $startTime = Carbon::createFromFormat('H:i:s', $scheduleDay->jam_mulai);
                $endTime = $startTime->copy()->addMinutes((int) $scheduleDay->durasi_menit);
                $dateString = $cursor->toDateString();
                $exception = $exceptions->get($dateString);

                $session = ClassScheduleSession::firstOrNew([
                    'class_schedule_id' => $classSchedule->id,
                    'session_date' => $dateString,
                    'start_time' => $startTime->format('H:i:s'),
                ]);

                if ($session->exists && $session->status === 'completed') {
                    continue;
                }

                $session->fill([
                    'class_schedule_day_id' => $scheduleDay->id,
                    'end_time' => $endTime->format('H:i:s'),
                    'status' => $exception ? 'cancelled' : 'scheduled',
                    'cancellation_reason' => $exception?->reason,
                ])->save();
            }

            $cursor->addDay();
        }
    }

    private function shouldGenerateOccurrence(
        ClassSchedule $classSchedule,
        ClassScheduleDay $scheduleDay,
        Carbon $date,
        Carbon $startBoundary
    ): bool {
        if ($date->format('l') !== $scheduleDay->hari) {
            return false;
        }

        if (($classSchedule->recurrence_type ?? 'weekly') === 'weekly') {
            return true;
        }

        $firstOccurrence = $startBoundary->copy();
        while ($firstOccurrence->format('l') !== $scheduleDay->hari) {
            $firstOccurrence->addDay();
        }

        if ($date->lt($firstOccurrence)) {
            return false;
        }

        $targetOrdinal = (int) ceil($firstOccurrence->day / 7);
        $dateOrdinal = (int) ceil($date->day / 7);

        return $targetOrdinal === $dateOrdinal;
    }

    private function getTimeSlot($hour)
    {
        if ($hour >= 6 && $hour < 12) {
            return 'Morning';
        } elseif ($hour >= 12 && $hour < 18) {
            return 'Afternoon';
        } else {
            return 'Night';
        }
    }

}

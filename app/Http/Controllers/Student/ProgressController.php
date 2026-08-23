<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentInvoice;
use App\Models\TeacherAttendance;
use App\Models\StudentAttendance;
use App\Models\ClassSchedule;
use App\Models\Course;
use App\Models\MonthlyStudentReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProgressController extends Controller
{
    public function index(Request $request)
    {
        $student = Auth::user()->students;
        // dd($student);
        $selectedMonth = $request->get('month', now()->month);
        $selectedYear = $request->get('year', now()->year);
        $monthlyReport = MonthlyStudentReport::where('student_id', $student->id)
            ->where('report_month', $selectedMonth)
            ->where('report_year', $selectedYear)
            ->first();

        $progressData = $this->getStudentProgress($student->id, $selectedMonth, $selectedYear);

        return view('student.progress.index', compact('progressData', 'monthlyReport', 'selectedMonth', 'selectedYear'));
    }
    
    private function getStudentProgress($studentId, $month, $year)
    {
        // Ambil invoice student yang sudah dibayar dan berlaku di bulan/tahun tersebut
        $invoices = StudentInvoice::where('student_id', $studentId)
            ->where('status_bayaran', 'paid')
            ->whereNotNull('tarikh_terbit')
            ->whereNotNull('tarikh_tamat')
            ->where(function($query) use ($month, $year) {
                $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
                $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();
                
                // Cek apakah periode invoice overlap dengan bulan yang dipilih
                $query->where(function($q) use ($startOfMonth, $endOfMonth) {
                    $q->where('tarikh_terbit', '<=', $endOfMonth)
                      ->where('tarikh_tamat', '>=', $startOfMonth);
                });
            })
            ->with(['course', 'student'])
            ->get();
            
        $progressReport = [];
        
        foreach ($invoices as $invoice) {
            $course = $invoice->course;
            
            // Ambil kelas yang diikuti student untuk course ini
            $classSchedules = ClassSchedule::where('course_id', $course->id)
                ->whereHas('students', function($query) use ($studentId) {
                    $query->where('student_id', $studentId);
                })
                ->with(['course', 'teacher', 'classScheduleDays'])
                ->get();
                
            foreach ($classSchedules as $class) {
                // Ambil teacher attendance untuk kelas ini di bulan yang dipilih
                $teacherAttendances = TeacherAttendance::where('class_schedule_id', $class->id)
                    ->whereYear('check_in', $year)
                    ->whereMonth('check_in', $month)
                    ->whereNotNull('check_in')
                    ->with(['teacher'])
                    ->orderBy('check_in', 'asc')
                    ->get();
                    
                $attendanceDetails = [];
                $totalSessions = $teacherAttendances->count();
                $attendedSessions = 0;
                
                foreach ($teacherAttendances as $teacherAttendance) {
                    // Cek apakah student hadir di sesi ini
                    $studentAttendance = StudentAttendance::where('class_schedule_id', $class->id)
                        ->where('user_id', Auth::user()->id)
                        ->whereDate('attendance_date', $teacherAttendance->check_in->format('Y-m-d'))
                        ->first();
                        
                    $isPresent = in_array($studentAttendance?->status, ['present', 'late', 'hadir'], true);
                    
                    if ($isPresent) {
                        $attendedSessions++;
                    }
                    
                    $attendanceDetails[] = [
                        'date' => $teacherAttendance->check_in->format('Y-m-d'),
                        'time' => $teacherAttendance->check_in->format('H:i'),
                        'check_in' => $teacherAttendance->check_in,
                        'check_out' => $teacherAttendance->check_out,
                        'class_photo' => $teacherAttendance->class_photo,
                        'note' => $teacherAttendance->note,
                        'student_present' => $isPresent,
                        'student_status' => $studentAttendance?->status,
                        'student_note' => $studentAttendance ? $studentAttendance->note : null,
                        'teacher_name' => $teacherAttendance->teacher->name ?? 'N/A',
                    ];
                }
                
                // Hitung target minimum sessions
                $minimumSessions = $course->minimum_sessions ?? 0;
                $minimumUnit = $course->minimum_sessions_unit ?? 'sessions';
                
                // Hitung persentase kehadiran
                $attendancePercentage = $totalSessions > 0 ? ($attendedSessions / $totalSessions) * 100 : 0;
                
                $progressReport[] = [
                    'invoice_id' => $invoice->id,
                    'course_name' => $course->nama_kos,
                    'class_name' => $class->nama_kelas,
                    'class_id' => $class->id,
                    'invoice_period' => [
                        'start' => $invoice->tarikh_terbit,
                        'end' => $invoice->tarikh_tamat
                    ],
                    'target' => [
                        'minimum_sessions' => $minimumSessions,
                        'minimum_unit' => $minimumUnit,
                        'sessions_duration' => $course->sessions_duration,
                        'duration_unit' => $course->sessions_duration_unit
                    ],
                    'progress' => [
                        'total_sessions' => $totalSessions,
                        'attended_sessions' => $attendedSessions,
                        'attendance_percentage' => round($attendancePercentage, 2)
                    ],
                    'attendance_details' => $attendanceDetails,
                    'class_schedule_days' => $class->classScheduleDays->map(function($day) {
                        return [
                            'hari' => $day->hari,
                            'jam_mulai' => $day->jam_mulai,
                            'durasi_menit' => $day->durasi_menit
                        ];
                    })
                ];
            }
        }
        
        return $progressReport;
    }
}

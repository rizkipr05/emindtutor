<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\Student;
use App\Models\StudentInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index()
    {
        // Get current student
        $student = Student::where('user_id', Auth::id())->first();
        
        if (!$student) {
            return redirect()->back()->with('error', 'Student profile not found');
        }

        $today = Carbon::today('Asia/Kuala_Lumpur');

        // Get all class schedules for this student
        $classSchedules = ClassSchedule::with([
            'course',
            'classScheduleDays',
            'classScheduleStudent.student.user'
        ])
        ->whereHas('classScheduleStudent', function ($q) use ($student) {
            $q->where('student_id', $student->id);
        })
        ->get();

        // Filter schedules: hanya jadwal yg student ini masih aktif
        $classSchedules = $classSchedules->filter(function ($schedule) use ($student, $today) {
            $activeInvoice = StudentInvoice::where('student_id', $student->id)
                ->where('course_id', $schedule->course_id)
                ->where('tarikh_terbit', '<=', $today)
                ->where('tarikh_tamat', '>=', $today)
                ->where('status_bayaran', 'paid')
                ->first();

            // Simpan flag aktif / tidak untuk student ini
            $schedule->is_active = $activeInvoice ? true : false;

            return $activeInvoice !== null;
        });

        // Organize schedules by day and time
        $weeklySchedule = $this->organizeScheduleByWeek($classSchedules);

        return view('student.schedule.index', compact('weeklySchedule'));
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
                $endTime = $startTime->copy()->addMinutes($scheduleDay->durasi_menit);
                
                // Determine time slot based on start time
                $hour = $startTime->hour;
                $timeSlot = $this->getTimeSlot($hour);
                
                // Get semua student dalam kelas (optional, kalau mau tampilkan teman sekelas)
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
                    'status' => $schedule->status,
                    'is_active' => $schedule->is_active,
                    'teacher_name' => $schedule->teacher->user->name ?? 'Unknown Teacher',
                ];

                $weeklySchedule[$timeSlot][$scheduleDay->hari][] = $scheduleItem;
            }
        }

        return $weeklySchedule;
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

<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassSchedule;
use App\Models\ClassScheduleStudent;
use App\Models\Teacher;
use App\Models\StudentAttendance;
use App\Models\TeacherUnavailability;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // public function index()
    // {
    //     return view('teacher.dashboard');
    // }

    public function index()
    {
        $teacher = Teacher::where('user_id', Auth::id())->first();

        if (!$teacher) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
        }

        // 2. Bilangan kelas minggu ini
        $today = Carbon::today('Asia/Kuala_Lumpur');

        // Get all class schedules for this teacher
        $classSchedules = ClassSchedule::with([
            'course',
            'classScheduleDays',
            'classScheduleStudent.student.user',
            'classScheduleStudent.student.invoices'
        ])
        ->where('teacher_id', $teacher->id)
        ->get();

        // Filter schedules: hanya yang punya student aktif
        $classSchedules = $classSchedules->filter(function ($schedule) use ($today) {
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

            // return true hanya kalau ada minimal 1 student aktif
            return count($activeStudents) > 0;
        });
        // dd($classSchedules);

        // Jumlah kelas aktif
        $classesThisWeek = $classSchedules->count();

        // Jumlah total siswa aktif (gabungan semua kelas)
        $studentsCount = $classSchedules->sum(function ($schedule) {
            return count($schedule->active_students);
        });
        // dd($studentsCount);

        // 3. Kadar kehadiran pelajar (%)
        // $totalAttendances = ClassScheduleStudent::whereHas('classSchedule', function ($q) use ($teacher) {
        //     $q->where('teacher_id', $teacher->id);
        // })->count();

        // $presentAttendances = ClassScheduleStudent::whereHas('classSchedule', function ($q) use ($teacher) {
        //     $q->where('teacher_id', $teacher->id);
        // })->where('status', 'present')->count();

        // $attendanceRate = $totalAttendances > 0 ? round(($presentAttendances / $totalAttendances) * 100, 2) : 0;

        $classScheduleIds = $classSchedules->pluck('id'); 

        $totalAttendances = StudentAttendance::whereIn('class_schedule_id', $classScheduleIds)->count();
        $presentAttendances = StudentAttendance::whereIn('class_schedule_id', $classScheduleIds)
            ->where('status', 'present')
            ->count();

        $attendanceRate = $totalAttendances > 0 
            ? round(($presentAttendances / $totalAttendances) * 100, 2) 
            : 0;

        // 4. Waktu guru boleh terima kelas (unavailability)
        $unavailabilities = TeacherUnavailability::where('teacher_id', $teacher->id)->get();

        $recentAnnouncements = Announcement::where('created_by', Auth::id())
            ->latest()
            ->take(5)
            ->get();
        $overview = [
            'announcements' => $recentAnnouncements->count(),
            'unavailabilities' => $unavailabilities->count(),
            'activeClasses' => $classesThisWeek,
            'students' => $studentsCount,
        ];

        return view('teacher.dashboard', compact(
            'teacher',
            'studentsCount',
            'classesThisWeek',
            'attendanceRate',
            'unavailabilities',
            'recentAnnouncements',
            'overview'
        ));
    }

}

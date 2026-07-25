<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassSchedule;
use App\Models\ClassScheduleDay;
use App\Models\ClassScheduleException;
use App\Models\ClassScheduleSession;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use App\Models\StudentAttendance;
use App\Models\Student;
use App\Models\Sallary;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    public function index()
    {
        $teacher = Teacher::where('user_id', Auth::id())->first();
        
        if (!$teacher) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
        }

        $today = Carbon::today('Asia/Kuala_Lumpur');

        // Get class schedules for this teacher
        $classSchedules = ClassSchedule::where('teacher_id', $teacher->id)
            ->with(['course', 'classScheduleDays', 'students.user', 'students.invoices'])
            ->get();

        // Debug: Check data
        foreach ($classSchedules as $schedule) {
            if (!$schedule->course) {
                \Log::error('Course null for ClassSchedule ID: ' . $schedule->id . ', course_id: ' . $schedule->course_id);
            }
        }

        // Filter class schedules: hanya kelas yg masih ada student aktif
        $classSchedules = $classSchedules->filter(function ($schedule) use ($today, $teacher) {
            $activeStudents = [];

            foreach ($schedule->students as $student) {
                if ($student->hasActiveCourseAccess($schedule->course_id, $today)) {
                    $activeStudents[] = $student;
                }
            }

            // simpan daftar student aktif ke dalam jadwal
            $schedule->active_students = $activeStudents;

            // return true hanya kalau ada minimal 1 student aktif
            return count($activeStudents) > 0;
        });

        // Add status information for each class schedule
        $classSchedules->map(function ($schedule) use ($teacher) {
            $schedule->current_status = $this->getClassStatus($schedule);
            $schedule->teacher_checked_in = $this->isTeacherCheckedIn($schedule, $teacher);
            return $schedule;
        });
        // dd($classSchedules);
        return view('teacher.attendance.index', compact('classSchedules'));
    }

    public function takeAttendance($classScheduleId)
    {
        $teacher = Teacher::where('user_id', Auth::id())->first();
        
        if (!$teacher) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
        }

        $classSchedule = ClassSchedule::with(['course', 'classScheduleDays'])
            ->findOrFail($classScheduleId);

        if ($this->isClassCancelledToday($classSchedule->id)) {
            return redirect()->route('teacher.attendance.index')
                ->with('error', 'Kelas ini dibatalkan untuk hari ini.');
        }

        // Check if class is currently ongoing
        $status = $this->getClassStatus($classSchedule);

        // Get or create teacher attendance for today
        $today = Carbon::today('Asia/Kuala_Lumpur');
        $teacherAttendance = TeacherAttendance::where('teacher_id', $teacher->id)
            ->where('class_schedule_id', $classScheduleId)
            ->whereDate('check_in', $today)
            ->first();

        // Get active students for this class
        $activeStudents = $this->getActiveStudents($classSchedule);

        // Get existing student attendances for today
        $existingAttendances = [];
        if ($teacherAttendance) {
            $existingAttendances = StudentAttendance::where('class_schedule_id', $classSchedule->id)
                ->whereDate('attendance_date', $today)
                ->get()
                ->keyBy('user_id');
        }

        return view('teacher.attendance.take', compact(
            'classSchedule', 
            'activeStudents', 
            'teacherAttendance',
            'existingAttendances'
        ));
    }

    public function teacherCheckIn(Request $request, $classScheduleId)
    {
        $teacher = Teacher::where('user_id', Auth::id())->first();
        
        if (!$teacher) {
            return response()->json(['error' => 'Data guru tidak ditemukan.'], 400);
        }

        $today = Carbon::today('Asia/Kuala_Lumpur');

        if ($this->isClassCancelledToday($classScheduleId)) {
            return response()->json(['error' => 'Kelas ini dibatalkan untuk hari ini.'], 400);
        }
        
        // Check if already checked in today
        $existingAttendance = TeacherAttendance::where('teacher_id', $teacher->id)
            ->where('class_schedule_id', $classScheduleId)
            ->whereDate('check_in', $today)
            ->first();

        if ($existingAttendance && $existingAttendance->check_in) {
            return response()->json(['error' => 'Anda sudah check-in untuk kelas ini hari ini.'], 400);
        }

        // Create or update teacher attendance
        $teacherAttendance = TeacherAttendance::create([
            'teacher_id' => $teacher->id,
            'class_schedule_id' => $classScheduleId,
            'check_in' => Carbon::now('Asia/Kuala_Lumpur'),
        ]);
        // dd($teacherAttendance);

        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil!',
            'check_in_time' => $teacherAttendance->check_in->format('H:i')
        ]);
    }

    public function teacherCheckOut(Request $request, $classScheduleId)
    {
        $months = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember',
        ];

        $currentMonth = $months[Carbon::now()->format('F')]; // misal: "September" → "September"

        $teacher = Teacher::where('user_id', Auth::id())->first();
        
        if (!$teacher) {
            return response()->json(['error' => 'Data guru tidak ditemukan.'], 400);
        }

        $today = Carbon::today();
        // dd($classScheduleId);
        
        $teacherAttendance = TeacherAttendance::where('teacher_id', $teacher->id)
            ->where('class_schedule_id', $classScheduleId)
            ->whereDate('created_at', $today)
            ->first();

        if (!$teacherAttendance || !$teacherAttendance->check_in) {
            return response()->json(['error' => 'Anda belum check-in untuk kelas ini.'], 400);
        }

        if ($teacherAttendance->check_out) {
            return response()->json(['error' => 'Anda sudah check-out untuk kelas ini.'], 400);
        }

        // Handle file upload
        $classPhotoPath = null;
        if ($request->hasFile('class_photo')) {
            $classPhotoPath = $request->file('class_photo')->store('class_photos', 'public');
        }

        // Update teacher attendance
        $teacherAttendance->update([
            'check_out' => Carbon::now('Asia/Kuala_Lumpur'),
            'class_photo' => $classPhotoPath,
            'note' => $request->teacher_note
        ]);

        ClassScheduleSession::where('class_schedule_id', $classScheduleId)
            ->whereDate('session_date', Carbon::today('Asia/Kuala_Lumpur'))
            ->where('status', 'scheduled')
            ->update(['status' => 'completed']);

        // =============================
        // Hitung gaji pending
        // =============================
        $classSchedule = ClassSchedule::findOrFail($classScheduleId);
        $amount = 0;
        $allowanceType = $classSchedule->allowance_type ?: $teacher->allowance_type;
        $scheduleAllowance = (float) ($classSchedule->allowance ?? 0);
        $allowance = $scheduleAllowance > 0 ? $scheduleAllowance : (float) ($teacher->allowance ?? 0);

        switch ($allowanceType) {
            case 'per_session':
                $amount = $allowance;
                // dd($amount);
                break;

            case 'per_hour':
                // Hitung durasi dari checkin - checkout (dalam menit)
                // $minutes = $teacherAttendance->check_in->diffInMinutes($teacherAttendance->check_out);
                // $hourlyRate = $classSchedule->allowance / 60; // allowance dianggap per jam → dibagi 60
                // $amount = $minutes * $hourlyRate;

                $scheduleDay = ClassScheduleDay::where('class_schedule_id', $classScheduleId)->first();
                if ($scheduleDay) {
                    $minutes = $scheduleDay->durasi_menit;
                    $hourlyRate = $allowance / 60; // allowance dianggap per jam → dibagi 60
                    $amount = $minutes * $hourlyRate;
                }
                break;

            case 'per_month':
                // Simpan hanya sekali per bulan
                // $exists = Sallary::where('teacher_id', $teacher->id)
                //     ->where('class_schedule_id', $classScheduleId)
                //     ->whereMonth('month', Carbon::now()->month)
                //     ->whereYear('year', Carbon::now()->year)
                //     ->exists();
                $exists = Sallary::where('teacher_id', $teacher->id)
                    ->where('class_schedule_id', $classScheduleId)
                    ->where('month', $currentMonth)
                    ->where('year', Carbon::now()->year)
                    ->exists();

                if (!$exists) {
                    $amount = $allowance;
                }
                break;
        }

        // // dd($amount);

        try {
            if ($amount > 0) {
                Sallary::create([
                    'teacher_id'        => $teacher->id,
                    'class_schedule_id' => $classScheduleId,
                    'attendance_id'     => $teacherAttendance->id,
                    'amount'            => $amount,
                    'status'            => 'pending',
                    'month'             => $currentMonth,
                    'year'              => now()->year,
                    'payment_date'      => now(),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Check-out berhasil & gaji pending tercatat!',
                'amount' => $amount,
                'check_out_time' => $teacherAttendance->check_out->format('H:i'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function storeAttendance(Request $request, $classScheduleId)
    {
        $teacher = Teacher::where('user_id', Auth::id())->first();
        
        if (!$teacher) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
        }

        // Check if teacher has checked in
        $nowTime = Carbon::now('Asia/Kuala_Lumpur')->format('H:i:s');
        $today = Carbon::today('Asia/Kuala_Lumpur');
        // dd($today);
        $teacherAttendance = TeacherAttendance::where('teacher_id', $teacher->id)
            ->where('class_schedule_id', $classScheduleId)
            ->whereDate('check_in', $today)
            ->first();
        // dd($teacherAttendance->check_in);
        if (!$teacherAttendance || !$teacherAttendance->check_in) {
            return redirect()->back()->with('error', 'Anda harus check-in terlebih dahulu sebelum mengambil kehadiran siswa.');
        }

        $request->validate([
            'attendances' => 'required|array',
            'attendances.*' => 'required|in:present,absent,late',
            'attendance_photo' => 'nullable|image|max:5120',
        ]);

        $existingPhotoPath = StudentAttendance::where('class_schedule_id', $classScheduleId)
            ->whereDate('attendance_date', $today)
            ->value('attendance_photo');

        $attendancePhotoPath = $existingPhotoPath;
        if ($request->hasFile('attendance_photo')) {
            $attendancePhotoPath = $request->file('attendance_photo')->store('student_attendances', 'public');
        }

        // Process student attendances
        // foreach ($request->attendances as $userId => $status) {
        //     StudentAttendance::updateOrCreate(
        //         [
        //             'user_id' => $userId,
        //             'class_schedule_id' => $classScheduleId,
        //             'created_at' => $today
        //         ],
        //         [
        //             'status' => $status
        //         ]
        //     );
        // }
        foreach ($request->attendances as $userId => $status) {
            StudentAttendance::updateOrCreate(
                [
                    'user_id' => $userId,
                    'class_schedule_id' => $classScheduleId,
                    'attendance_date' => $today, // lebih aman daripada created_at
                ],
                [
                    'status' => $status,
                    'attendance_time' => $nowTime, // jam bisa diupdate
                    'attendance_photo' => $attendancePhotoPath,
                ]
            );
        }

        return redirect()->route('teacher.attendance.index')
            ->with('success', __('teacher/attendance.attendance_saved_success'));
    }

    private function getClassStatus($classSchedule)
    {
        // Set timezone ke Kuala Lumpur
        $now = Carbon::now('Asia/Kuala_Lumpur');
        $currentDay = $now->format('l'); // Monday, Tuesday, etc.
        $currentTime = $now->format('H:i:s');

        // Check if today is a class day - menggunakan field 'hari' dari database
        $todaySchedule = $classSchedule->classScheduleDays->where('hari', $currentDay)->first();
        
        if (!$todaySchedule) {
            return 'not_today';
        }

        if ($this->isClassCancelledToday($classSchedule->id, $now)) {
            return 'not_today';
        }

        
        // Calculate end time dari jam_mulai + durasi_menit
        $startTime = $todaySchedule->jam_mulai;

        $endTime = \Carbon\Carbon::parse($todaySchedule->jam_mulai)
            ->addMinutes( (int) $todaySchedule->durasi_menit ) // <--- Kuncinya di sini (tambah int)
            ->format('H:i:s');
        // dd($currentTime);

        // 🔹 Cek apakah guru sudah checkout hari ini
        $teacherAttendance = TeacherAttendance::where('class_schedule_id', $classSchedule->id)
            ->whereDate('check_in', $now->toDateString())
            ->first();

        // Check time
        if ($currentTime < $startTime) {
            return 'upcoming';
        } elseif ($currentTime >= $startTime && $currentTime <= $endTime) {
            // Jika guru sudah checkout → anggap selesai
            if ($teacherAttendance && $teacherAttendance->check_out) {
                return 'finished';
            }
            return 'ongoing';
        } else {
            return 'finished';
        }
    }

    private function isTeacherCheckedIn($classSchedule, $teacher)
    {
        $today = Carbon::today('Asia/Kuala_Lumpur');
        
        $attendance = TeacherAttendance::where('teacher_id', $teacher->id)
            ->where('class_schedule_id', $classSchedule->id)
            ->whereDate('check_in', $today)
            ->first();

        return $attendance && $attendance->check_in;
    }

    private function isClassCancelledToday($classScheduleId, ?Carbon $date = null): bool
    {
        $date = $date ?: Carbon::today('Asia/Kuala_Lumpur');
        $dateString = $date->toDateString();

        $cancelledSession = ClassScheduleSession::where('class_schedule_id', $classScheduleId)
            ->whereDate('session_date', $dateString)
            ->where('status', 'cancelled')
            ->exists();

        if ($cancelledSession) {
            return true;
        }

        return ClassScheduleException::where('class_schedule_id', $classScheduleId)
            ->whereDate('exception_date', $dateString)
            ->exists();
    }

    private function getActiveStudents($classSchedule)
    {
        $today = Carbon::today('Asia/Kuala_Lumpur');
        
        // Get students enrolled in this class
        $enrolledStudents = $classSchedule->students()->with(['user', 'invoices'])->get();
        // dd($enrolledStudents);
        
        $activeStudents = [];
        foreach ($enrolledStudents as $student) {
            // Check if student has active invoice for this course
            // dd($student->id);
            if ($student->hasActiveCourseAccess($classSchedule->course_id, $today)) {
                $activeStudents[] = $student;
            }
        }
        
        return collect($activeStudents);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Company;
use App\Models\CompanyInvoice;
use App\Models\TeacherUnavailability;
use App\Models\Review;
use App\Models\User;
use App\Models\TeacherRule;
use App\Models\ClassSchedule;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index()
    {
        $companyId = Auth::user()->company->id;

        $teachers = Teacher::with(['user', 'company'])
            ->where('company_id', $companyId)
            ->get();
        return view('admin.teachers.index', compact('teachers'));
    }

    public function show(Teacher $teacher)
    {
        // Load teacher with all necessary relationships
        $teacher = Teacher::with([
            'user',
            'classSchedules.course',
            'classSchedules.classScheduleStudent.student.user',
            'classSchedules.classScheduleDays'
        ])->findOrFail($teacher->id);

        // Get classes data
        // Format data class schedules
        $classes = $teacher->classSchedules->map(function ($schedule) {
            $course = $schedule->course;

            // ambil murid
            $students = $schedule->classScheduleStudent->map(function ($css) {
                return $css->student?->user?->name ?? '-';
            });
            // ambil id murid
            $studentsId = $schedule->classScheduleStudent->map(function ($css) {
                return $css->student?->id ?? '-';
            });

            // attendance
            $totalSessions = $schedule->teacherAttendances->count();
            $studentAttendances = $schedule->studentAttendances->groupBy('attendance_date');

            $attendanceRate = 0;
            if ($totalSessions > 0) {
                $totalStudents = $students->count();
                $presentCount = $schedule->studentAttendances
                                        ->whereIn('status', ['present', 'late'])
                                        ->count();
                $totalPossibleAttendances = $totalSessions * $totalStudents;
                $attendanceRate = $totalPossibleAttendances > 0
                    ? ($presentCount / $totalPossibleAttendances) * 100
                    : 0;
            }

            return [
                'id' => $schedule->id,
                'course_name' => $course->nama_kos ?? '-',
                'course_type' => $course->jenis ?? 'unknown', // individu / kumpulan
                'students' => $students,
                'studentsId' => $studentsId,
                'student_count' => $students->count(),
                'schedule_days' => $schedule->classScheduleDays->map(function ($day) {
                    return [
                        'hari' => $day->hari,
                        'jam_mulai' => $day->jam_mulai,
                        'durasi_menit' => $day->durasi_menit,
                    ];
                }),
                'total_sessions' => $totalSessions,
                'attendance_rate' => round($attendanceRate, 2),
                'session_details' => $studentAttendances->map(function ($dayAttendances, $date) {
                    return [
                        'date' => $date,
                        'present' => $dayAttendances->where('status', 'present')->count(),
                        'late' => $dayAttendances->where('status', 'late')->count(),
                        'absent' => $dayAttendances->where('status', 'absent')->count(),
                    ];
                })->values()
            ];
        });


        // Get timetable data
        $unavail = TeacherUnavailability::where('teacher_id', $teacher->id)->get();
        $unavailabilities = collect();

        // Add class schedules to timetable
        foreach ($teacher->classSchedules as $schedule) {
            foreach ($schedule->classScheduleDays as $class) {
                $start_time = strtotime($class->jam_mulai);
                $end_time = date("H:i", $start_time + ($class->durasi_menit * 60));
                $unavailabilities->push([
                    'id' => $schedule->id,
                    'type' => 'class',
                    'day' => $class->hari ?? null,
                    'start' => $class->jam_mulai ?? null,
                    'end' => $end_time ?? null,
                    'course' => $schedule->course->nama_kos ?? null,
                ]);
            }
        }

        // Add unavailabilities
        foreach ($unavail as $ua) {
            $unavailabilities->push([
                'type' => 'unavailable',
                'day' => $ua->day ?? null,
                'start' => $ua->start_time ?? null,
                'end' => $ua->end_time ?? null,
                'reason' => $ua->reason ?? null,
            ]);
        }

        // Get reviews data
        $reviews = Review::with(['student.user', 'course'])
            ->where('teacher_id', $teacher->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate average rating
        $averageRating = $reviews->avg('rating') ?? 0;
        $totalReviews = $reviews->count();

        return view('admin.teachers.show.teacher', compact(
            'teacher', 
            'classes', 
            'unavailabilities', 
            'reviews', 
            'averageRating', 
            'totalReviews'
        ));
    }

    // public function classes(Teacher $teacher)
    // {
    //     $teacherId = $teacher->id;
    //     // Ambil teacher + semua jadwalnya + relasi ke murid dan course
    //     $teacher = Teacher::with([
    //         'classSchedules.course',
    //         'classSchedules.classScheduleStudent.student.user'
    //     ])->findOrFail($teacherId);

    //     // Format data class schedules
    //     $classes = $teacher->classSchedules->map(function ($schedule) {
    //         $course = $schedule->course;

    //         // Ambil murid dari relasi
    //         $students = $schedule->classScheduleStudent->map(function ($css) {
    //             return $css->student?->user?->name ?? '-';
    //         });

    //         return [
    //             'id' => $schedule->id,
    //             'course_name' => $course->nama_kos ?? '-',
    //             'course_type' => $course->jenis ?? 'unknown', // individu / kumpulan
    //             'students' => $students,
    //             'student_count' => $students->count(),
    //         ];
    //     });
    //     // dd($classes);

    //     return view('admin.teachers.show.classes', compact('teacher', 'classes'));
    // }

// public function classes(Teacher $teacher, Request $request)
// {
//     $teacherId = $teacher->id;

//     $month = $request->get('month', now()->month);
//     $year = $request->get('year', now()->year);

//     // Ambil teacher + classSchedules lengkap
//     $teacher = Teacher::with([
//         'classSchedules.course',
//         'classSchedules.classScheduleStudent.student.user',
//         'classSchedules.classScheduleDays',
//         'classSchedules.teacherAttendances' => function ($query) use ($month, $year) {
//             $query->whereMonth('check_in', $month)
//                   ->whereYear('check_in', $year);
//         },
//         'classSchedules.studentAttendances' => function ($query) use ($month, $year) {
//             $query->whereMonth('attendance_date', $month)
//                   ->whereYear('attendance_date', $year);
//         }
//     ])->findOrFail($teacherId);

//     // Format data class schedules
//     $classes = $teacher->classSchedules->map(function ($schedule) {
//         $course = $schedule->course;

//         // ambil murid
//         $students = $schedule->classScheduleStudent->map(function ($css) {
//             return $css->student?->user?->name ?? '-';
//         });

//         // attendance
//         $totalSessions = $schedule->teacherAttendances->count();
//         $studentAttendances = $schedule->studentAttendances->groupBy('attendance_date');

//         $attendanceRate = 0;
//         if ($totalSessions > 0) {
//             $totalStudents = $students->count();
//             $presentCount = $schedule->studentAttendances
//                                      ->whereIn('status', ['present', 'late'])
//                                      ->count();
//             $totalPossibleAttendances = $totalSessions * $totalStudents;
//             $attendanceRate = $totalPossibleAttendances > 0
//                 ? ($presentCount / $totalPossibleAttendances) * 100
//                 : 0;
//         }

//         return [
//             'id' => $schedule->id,
//             'course_name' => $course->nama_kos ?? '-',
//             'course_type' => $course->jenis ?? 'unknown', // individu / kumpulan
//             'students' => $students,
//             'student_count' => $students->count(),
//             'schedule_days' => $schedule->classScheduleDays->map(function ($day) {
//                 return [
//                     'hari' => $day->hari,
//                     'jam_mulai' => $day->jam_mulai,
//                     'durasi_menit' => $day->durasi_menit,
//                 ];
//             }),
//             'total_sessions' => $totalSessions,
//             'attendance_rate' => round($attendanceRate, 2),
//             'session_details' => $studentAttendances->map(function ($dayAttendances, $date) {
//                 return [
//                     'date' => $date,
//                     'present' => $dayAttendances->where('status', 'present')->count(),
//                     'late' => $dayAttendances->where('status', 'late')->count(),
//                     'absent' => $dayAttendances->where('status', 'absent')->count(),
//                 ];
//             })->values()
//         ];
//     });

//     return view('admin.teachers.show.classes', compact('teacher', 'classes', 'month', 'year'));
// }


    public function create()
    {
        $user = Auth::user();

        // Ambil company milik user
        $company = Company::where('user_id', $user->id)->firstOrFail();

        $today = now()->toDateString();

        // Ambil subscription aktif (hanya yang status active dan paid)
        $activeSubscription = CompanyInvoice::where('company_id', $company->id)
            ->where('status', 'active')
            ->where('status_bayaran', 'paid')
            ->whereDate('tarikh_terbit', '<=', $today)
            ->whereDate('tarikh_tamat', '>=', $today)
            ->latest()
            ->with('package')
            ->first();

        if (!$activeSubscription || !$activeSubscription->package) {
            return redirect()->route('a.dashboard.index')
                ->with('error', 'Anda tidak mempunyai langganan aktif untuk menambah kelas.');
        }

        // Hitung jumlah teacher milik company
        $currentTeachers = Teacher::where('company_id', $company->id)->count();

        // Ambil batas maksimum kelas dari package
        $maxTeacher = $activeSubscription->package->maks_kelas;

        if ($currentTeachers >= $maxTeacher) {
            return redirect()->route('admin.teachers.index')
                ->with('error', 'Jumlah Teacher sudah mencapai maksimum (' . $maxTeacher . ').');
        }

        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'ic_number' => 'required|string|max:255|unique:teachers,ic_number',
            'phone' => 'nullable|string|max:20',
            'allowance' => 'required|numeric',
            'allowance_type' => 'required|in:per_hour,per_session,per_month',
            'bank_name' => 'required|string|max:255',
            'bank_account_number' => 'required|string|max:255',
            'bank_account_holder' => 'required|string|max:255',
            'account_holders_ic_number' => 'nullable|string|max:255',
        ]);

        // Buat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->ic_number), // default password = IC Number
            'role' => 'teacher', // pastikan role ini sesuai dengan sistemmu
        ]);

        // Buat teacher baru
        Teacher::create([
            'user_id' => $user->id,
            'company_id' => Auth::user()->company->id,
            'phone' => $request->phone,
            'ic_number' => $request->ic_number,
            'allowance' => $request->allowance,
            'allowance_type' => $request->allowance_type,
            'bank_name' => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_account_holder' => $request->bank_account_holder,
            'account_holders_ic_number' => $request->account_holders_ic_number,
            // status otomatis jadi "active"
        ]);

        return redirect()->route('admin.teachers.index')->with('success', 'Guru berhasil ditambahkan.');
    }

    public function edit(Teacher $teacher)
    {
        $users = User::all();
        $companies = Company::all();
        return view('admin.teachers.edit', compact('teacher', 'users', 'companies'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->user_id,
            'phone' => 'required|string|max:20',
            'ic_number' => 'required|string|max:20',
            'bank_name' => 'required|string|max:100',
            'bank_account_number' => 'required|string|max:100',
            'bank_account_holder' => 'required|string|max:100',
            'account_holders_ic_number' => 'nullable|string|max:20',
            'allowance' => 'required|string|max:100',
            'allowance_type' => 'required|in:per_hour,per_session,per_month',
        ]);

        // Update User (name & email)
        $user = $teacher->user;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        // Update Teacher
        $teacher->update([
            'phone' => $request->phone,
            'ic_number' => $request->ic_number,
            'allowance' => $request->allowance,
            'allowance_type' => $request->allowance_type,
            'bank_name' => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_account_holder' => $request->bank_account_holder,
            'account_holders_ic_number' => $request->account_holders_ic_number,
        ]);

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher updated successfully.');
    }

    //destroy user juga janlup
    public function destroy(Teacher $teacher)
    {
        if ($teacher->user) {
            $teacher->user->delete();
        }

        // Hapus guru
        $teacher->delete();
        return redirect()->route('admin.teachers.index')->with('success', 'Guru berhasil dihapus.');
    }

    public function bulkActivate(Request $request)
    {
        // dd($request->all());
        $ids = $request->input('teacher_ids', []);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Sila pilih guru yang ingin diaktifkan.');
        }

        Teacher::whereIn('id', $ids)->update(['status' => 'active']);

        return redirect()->back()->with('success', 'Guru berjaya diaktifkan.');
    }

    public function bulkDeactivate(Request $request)
    {
        // dd($request->all());

        $ids = $request->input('teacher_ids', []);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Sila pilih guru yang ingin dinyahaktifkan.');
        }

        Teacher::whereIn('id', $ids)->update(['status' => 'inactive']);

        return redirect()->back()->with('success', 'Guru berjaya dinyahaktifkan.');
    }

    public function rules()
    {
        $companyId = Auth::user()->company->id;
        $teacherRule = TeacherRule::where('company_id', $companyId)->latest()->first();

        return view('admin.teachers.rules.index', compact('teacherRule'));
    }

    public function performance()
    {
        $companyId = Auth::user()->company->id;

        $teacherCount = Teacher::where('company_id', $companyId)->count();
        $classCount = ClassSchedule::where('company_id', $companyId)->count();
        $studentCount = Student::where('company_id', $companyId)->count();

        return view('admin.teachers.performance', compact('teacherCount', 'classCount', 'studentCount'));
    }
}

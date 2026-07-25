<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassSchedule;
use App\Models\ClassScheduleDay;
use App\Models\ClassScheduleStudent;
use App\Models\TeacherAttendance;
use App\Models\StudentAttendance;
use App\Models\Student;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClassScheduleController extends Controller
{
    public function index()
    {
        $company_id = Auth::user()->company->id;
        $schedule = ClassSchedule::with([
                'teacher.user',
                'students.user',
                'course',
                'teacherAttendances',
                'studentAttendances'
            ])
            ->where('company_id', $company_id)
            ->latest()
            ->first();

        if ($schedule) {
            $class = $this->getClassDetail($schedule->id);
            $cancelledClasses = $this->getCancelledClassesForClass($schedule->id);
        } else {
            $class = [
                'id' => null,
                'class_name' => '-',
                'course_name' => '-',
                'minimum_sessions' => 0,
                'minimum_sessions_unit' => 'session',
                'teacher_name' => '-',
                'total_students' => 0,
                'total_sessions' => 0,
                'final_total_sessions' => 0,
                'attendance_rate' => 0,
                'notes' => collect(),
                'class_photos' => collect(),
                'session_details' => collect(),
            ];
            $cancelledClasses = collect();
        }

        return view('admin.class.index', compact('class', 'cancelledClasses'));
    }

    public function create()
    {
        $company_id = Auth::user()->company->id;
        $teachers = Teacher::where('company_id', $company_id)->get();
        $courses = Course::where('company_id', $company_id)->get();

        return view('class_schedules.create', compact('teachers', 'courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required',
            'teacher_id' => 'required',
            'nama_kelas' => 'required|string',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after:waktu_mulai',
        ]);

        ClassSchedule::create([
            'company_id' => Auth::user()->company->id,
            'course_id' => $request->course_id,
            'teacher_id' => $request->teacher_id,
            'nama_kelas' => $request->nama_kelas,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'status' => 'akan_datang'
        ]);

        return redirect()->route('admin.class.index')->with('success', 'Jadwal kelas dibuat.');
    }

    // public function edit(ClassSchedule $classSchedule)
    // {
    //     $teachers = Teacher::where('company_id', Auth::user()->company_id)->get();
    //     $courses = Course::where('company_id', Auth::user()->company_id)->get();

    //     return view('class_schedules.edit', compact('classSchedule', 'teachers', 'courses'));
    // }

    // public function update(Request $request, ClassSchedule $classSchedule)
    // {
    //     $request->validate([
    //         'course_id' => 'required',
    //         'teacher_id' => 'required',
    //         'nama_kelas' => 'required|string',
    //         'waktu_mulai' => 'required|date',
    //         'waktu_selesai' => 'required|date|after:waktu_mulai',
    //         'status' => 'required|in:selesai,akan_datang,batal'
    //     ]);

    //     $classSchedule->update($request->all());

    //     return redirect()->route('class_schedules.index')->with('success', 'Jadwal diperbarui.');
    // }

    public function edit($id)
    {
        $companyId = Auth::user()->company->id;

        $classSchedule = ClassSchedule::with(['classScheduleDays', 'course', 'teacher.user', 'students'])
            ->where('company_id', $companyId)
            ->findOrFail($id);

        $students = Student::with(['user', 'invoices'])
            ->where('company_id', $companyId)
            ->where('is_approved', 'yes')
            ->orderBy(
                User::select('name')
                    ->whereColumn('users.id', 'students.user_id')
                    ->limit(1)
            )
            ->get()
            ->filter(fn ($student) => $student->hasActiveCourseAccess($classSchedule->course_id))
            ->values();

        $selectedStudentIds = $classSchedule->students->pluck('id')->all();

        return view('admin.class.edit', compact('classSchedule', 'students', 'selectedStudentIds'));
    }

    public function update(Request $request, $id)
    {
// Ambil data schedule_days dari request
$scheduleDays = $request->input('schedule_days', []);

// Normalisasi jam supaya selalu HH:MM:SS
foreach ($scheduleDays as &$day) {
    if (!empty($day['jam_mulai'])) {
        try {
            // Coba parse "H:i:s"
            $time = Carbon::createFromFormat('H:i:s', $day['jam_mulai']);
        } catch (\Exception $e) {
            // Kalau gagal, berarti formatnya "H:i"
            $time = Carbon::createFromFormat('H:i', $day['jam_mulai']);
        }

        // Simpan ulang ke format konsisten H:i:s
        $day['jam_mulai'] = $time->format('H:i:s');
    }
}

// Gabungkan lagi ke request
$request->merge([
    'schedule_days' => $scheduleDays
]);

        // dd($request->all());
        $companyId = Auth::user()->company->id;

        $classSchedule = ClassSchedule::where('company_id', $companyId)->findOrFail($id);

        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'link_group' => 'nullable|url|max:500',
            'notes' => 'nullable|string|max:1000',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'integer',

            // schedule_days wajib array
            'schedule_days' => 'required|array|min:1',

            // setiap item di dalam schedule_days harus punya format ini
            'schedule_days.*.hari' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'schedule_days.*.jam_mulai' => 'required|date_format:H:i:s', // kalau formatmu "01:00:00"
            'schedule_days.*.durasi_menit' => 'required|integer|min:15|max:480',
        ]);

        $studentIds = collect($validated['student_ids'] ?? [])
            ->map(fn ($studentId) => (int) $studentId)
            ->unique()
            ->values()
            ->all();

        $validStudentIds = Student::with('invoices')
            ->where('company_id', $companyId)
            ->where('is_approved', 'yes')
            ->whereIn('id', $studentIds)
            ->get()
            ->filter(fn ($student) => $student->hasActiveCourseAccess($classSchedule->course_id))
            ->pluck('id')
            ->all();

        if (count($validStudentIds) !== count($studentIds)) {
            return back()
                ->withErrors(['student_ids' => 'Ada pelajar yang tidak valid untuk syarikat ini.'])
                ->withInput();
        }

        DB::transaction(function () use ($request, $classSchedule, $id, $studentIds, $companyId) {
            // Update class schedule
            $classSchedule->update([
                'nama_kelas' => $request->nama_kelas,
                'link_group' => $request->link_group,
                'notes' => $request->notes,
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

            if (!empty($studentIds)) {
                ClassScheduleStudent::whereIn('student_id', $studentIds)
                    ->where('class_schedule_id', '!=', $id)
                    ->whereHas('classSchedule', function ($query) use ($classSchedule, $companyId) {
                        $query->where('company_id', $companyId)
                            ->where('course_id', $classSchedule->course_id);
                    })
                    ->delete();
            }

            ClassScheduleStudent::where('class_schedule_id', $id)->delete();

            foreach ($studentIds as $studentId) {
                ClassScheduleStudent::create([
                    'student_id' => $studentId,
                    'class_schedule_id' => $id,
                ]);
            }
        });

        return redirect()->route('admin.class.show', $id)
            ->with('success', 'Class schedule berhasil diperbarui.');
    }

    public function destroy(ClassSchedule $classSchedule)
    {
        $classSchedule->delete();
        return redirect()->route('admin.class.index')->with('success', 'Jadwal dihapus.');
    }

    public function show($id)
    {
        $class = $this->getClassDetail($id);
        $cancelledClasses = $this->getCancelledClassesForClass($id);
        
        if (!$class) {
            return redirect()->back()->with('error', 'Kelas tidak ditemukan');
        }
        
        return view('admin.class.index', compact('class', 'cancelledClasses'));
    }
    
    private function getClassDetail($classId)
    {
        $class = ClassSchedule::with([
            'teacher.user',
            'students.user',
            'course',
            'teacherAttendances',
            'studentAttendances'
        ])
        ->find($classId);
        
        if (!$class) {
            return null;
        }
        
        $totalSessions = $class->teacherAttendances->count();
        $studentAttendances = $class->studentAttendances->groupBy('attendance_date');
        
        $attendanceRate = 0;
        $finalTotalSesi = 0;
        if ($totalSessions > 0) {
            $totalStudents = $class->students->count();
            $presentCount = $class->studentAttendances->whereIn('status', ['present', 'late'])->count();
            $totalPossibleAttendances = $totalSessions * $totalStudents;
            $attendanceRate = $totalPossibleAttendances > 0 ? ($presentCount / $totalPossibleAttendances) * 100 : 0;
            if($presentCount > 0 && $presentCount!=null){
                $finalTotalSesi = ($totalSessions - $presentCount);
            }else{
                $finalTotalSesi = $totalSessions;
            }
        }
        // dd($finalTotalSesi);
        
        return [
            'id' => $class->id,
            'class_name' => $class->nama_kelas,
            'course_name' => $class->course->nama_kos,
            'minimum_sessions' => $class->course->minimum_sessions,
            'minimum_sessions_unit' => $class->course->minimum_sessions_unit,
            'teacher_name' => $class->teacher->user->name ?? 'Tidak ada guru',
            'total_students' => $class->students->count(),
            'total_sessions' => $totalSessions,
            'final_total_sessions' => $finalTotalSesi,
            'attendance_rate' => round($attendanceRate, 2),
            'notes' => $class->teacherAttendances->pluck('note')->filter()->values(),
            'class_photos' => $class->teacherAttendances->pluck('class_photo')->filter()->values(),
            'session_details' => $studentAttendances->map(function ($dayAttendances, $date) use ($class) {
                // CARI teacher attendance yang tanggalnya SAMA dengan $date hasil group by
                $teacherAttendance = $class->teacherAttendances
                    ->filter(function($attendance) use ($date) {
                        // Pastikan format tanggalnya sama (Y-m-d)
                        return $attendance->check_in->format('Y-m-d') === $date;
                    })
                    ->first();

                return [
                    'date' => $date, // Gunakan $date dari groupBy agar konsisten
                    'time' => $teacherAttendance 
                        ? $teacherAttendance->check_in->format('H:i') 
                        : null,
                    'present' => $dayAttendances->where('status', 'present')->count(),
                    'late'    => $dayAttendances->where('status', 'late')->count(),
                    'absent'  => $dayAttendances->where('status', 'absent')->count(),
                ];
            })->values()
        ];
    }

    private function getCancelledClassesForClass($classId)
    {
        return TeacherAttendance::with(['classSchedule', 'teacher.user'])
            ->where('class_schedule_id', $classId)
            ->get()
            ->filter(function ($teacherAttendance) {
                $studentsPresent = StudentAttendance::where('class_schedule_id', $teacherAttendance->class_schedule_id)
                    ->whereDate('attendance_date', $teacherAttendance->check_in->format('Y-m-d'))
                    ->whereIn('status', ['present', 'late'])
                    ->count();
                
                return $studentsPresent == 0;
            })
            ->map(function ($teacherAttendance) {
                return [
                    'class_name' => $teacherAttendance->classSchedule->nama_kelas,
                    'teacher_name' => $teacherAttendance->teacher->user->name,
                    'date' => $teacherAttendance->check_in->format('Y-m-d'),
                    'time' => $teacherAttendance->check_in->format('H:i'),
                    'note' => $teacherAttendance->note,
                ];
            })
            ->values();
    }
}

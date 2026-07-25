<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassScheduleDay;
use App\Models\ClassSchedule;
use App\Models\StudentAttendance;
use App\Models\TeacherAttendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // public function index()
    // {
    //     $now = now();
    //     $hariIni = strtolower($now->format('l')); // misalnya "monday"
    //     $jamSekarang = $now->format('H:i:s');

    //     // 1) Cari kelas yg seharusnya jalan sekarang
    //     $kelasBerlangsung = ClassScheduleDay::with('classSchedule.students.user', 'classSchedule.teacher')
    //         ->where('hari', $hariIni)
    //         ->whereTime('jam_mulai', '<=', $jamSekarang)
    //         ->get()
    //         ->filter(function ($jadwal) use ($jamSekarang) {
    //             $end = \Carbon\Carbon::parse($jadwal->jam_mulai)->addMinutes($jadwal->durasi_menit);
    //             return $end->format('H:i:s') >= $jamSekarang;
    //         });

    //     // 2) Dari kelas yg sedang berlangsung, ambil yg tidak ada teacherAttendance
    //     $kelasTanpaGuru = $kelasBerlangsung->filter(function ($jadwal) {
    //         return $jadwal->classSchedule->teacherAttendances()->whereDate('check_in', today())->doesntExist();
    //     });

    //     // 3) Ambil murid-murid di kelas tsb
    //     $muridMerah = $kelasTanpaGuru->flatMap(function ($jadwal) {
    //         return $jadwal->classSchedule->students;
    //     });

    //     // 4) Ringkasan kehadiran guru & murid
    //     $rekapMurid = StudentAttendance::selectRaw('status, count(*) as total')
    //         ->whereMonth('attendance_date', now()->month)
    //         ->groupBy('status')
    //         ->get();

    //     $rekapGuru = TeacherAttendance::selectRaw('teacher_id, count(*) as total')
    //         ->whereMonth('check_in', now()->month)
    //         ->groupBy('teacher_id')
    //         ->get();

    //     return view('admin.reports.index', [
    //         'muridMerah' => $muridMerah,
    //         'kelasTanpaGuru' => $kelasTanpaGuru,
    //         'rekapMurid' => $rekapMurid,
    //         'rekapGuru' => $rekapGuru,
    //     ]);
    // }

    public function index(Request $request)
    {
        // dd($request->all());
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        // Ambil filter bulan & tahun dari query string
        $month = $request->get('month', now()->month); // default bulan sekarang
        $year = $request->get('year', now()->year); // default tahun sekarang
        // dd(now()->month);
        // 1. Ringkasan kehadiran pelajar & guru
        $attendanceSummary = $this->getAttendanceSummary($month, $year);
        
        // 2. Laporan kehadiran per kelas
        $classAttendanceReports = $this->getClassAttendanceReports($month, $year);
        
        // 3. Data untuk chart perbandingan prestasi
        $classPerformanceChart = $this->getClassPerformanceData($month, $year);
        
        // 4. Laporan kelas yang tercicir/batal
        $cancelledClasses = $this->getCancelledClassesReport($month, $year);

        return view('admin.reports.index', [
            'attendanceSummary' => $attendanceSummary,
            'classAttendanceReports' => $classAttendanceReports,
            'classPerformanceChart' => $classPerformanceChart,
            'cancelledClasses' => $cancelledClasses,
            'months' => $months,
            'month' => $month,
            'year' => $year,
        ]);
    }

    private function getAttendanceSummary($month, $year)
    {
        // dd(now()->month);
        // Ringkasan kehadiran pelajar bulan ini
        $studentSummary = StudentAttendance::selectRaw('status, count(*) as total')
            ->whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year)
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status');

        // Ringkasan kehadiran guru bulan ini
        $teacherSummary = TeacherAttendance::selectRaw('count(distinct teacher_id) as active_teachers, count(*) as total_sessions')
            ->whereMonth('check_in', $month)
            ->whereYear('check_in', $year)
            ->first();

        return [
            'students' => [
                'present' => $studentSummary->get('present', 0),
                'late' => $studentSummary->get('late', 0),
                'absent' => $studentSummary->get('absent', 0),
            ],
            'teachers' => [
                'active_teachers' => $teacherSummary->active_teachers ?? 0,
                'total_sessions' => $teacherSummary->total_sessions ?? 0,
            ]
        ];
    }

    private function getClassAttendanceReports($month, $year)
    {
        return ClassSchedule::with([
            'teacher.user',
            'students.user',
            'course',
            'teacherAttendances' => function($query) use ($month, $year) {
                $query->whereMonth('check_in', $month)
                      ->whereYear('check_in', $year);
            },
            'studentAttendances' => function($query) use ($month, $year) {
                $query->whereMonth('attendance_date', $month)
                      ->whereYear('attendance_date', $year);
            }
        ])
        ->get()
        ->map(function ($class) {
            $totalSessions = $class->teacherAttendances->count();
            $studentAttendances = $class->studentAttendances->groupBy('attendance_date');
            
            $attendanceRate = 0;
            if ($totalSessions > 0) {
                $totalStudents = $class->students->count();
                $presentCount = $class->studentAttendances->whereIn('status', ['present', 'late'])->count();
                $totalPossibleAttendances = $totalSessions * $totalStudents;
                $attendanceRate = $totalPossibleAttendances > 0 ? ($presentCount / $totalPossibleAttendances) * 100 : 0;
            }

            return [
                'id' => $class->id,
                'class_name' => $class->nama_kelas,
                'course_name' => $class->course->nama_kos,
                'minimum_sessions' => $class->course->minimum_sessions,
                'minimum_sessions_unit' => $class->course->minimum_sessions_unit,
                'teacher_name' => $class->teacher->user->name ?? 'Tidak ada guru',
                'total_students' => $class->students->count(),
                'total_sessions' => $totalSessions,
                'attendance_rate' => round($attendanceRate, 2),
                'notes' => $class->teacherAttendances->pluck('note')->filter()->values(),
                'class_photos' => $class->teacherAttendances->pluck('class_photo')->filter()->values(),
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
    }

    private function getClassPerformanceData($month, $year)
    {
        $classData = $this->getClassAttendanceReports($month, $year);
        
        // dd($classData->first());
        return [
            'labels' => $classData->map(fn($class) => $class['class_name'])->toArray(),
            'data'   => $classData->map(fn($class) => $class['attendance_rate'])->toArray(),
            'colors' => $classData->map(function ($class) {
                if ($class['attendance_rate'] >= 80) return '#10B981'; // green
                if ($class['attendance_rate'] >= 60) return '#F59E0B'; // yellow
                return '#EF4444'; // red
            })->toArray()
        ];

    }

    private function getCancelledClassesReport($month, $year)
    {
        // Cari kelas yang dibatalkan (guru check-in tapi tidak ada siswa hadir)
        return TeacherAttendance::with(['classSchedule', 'teacher.user'])
            ->whereMonth('check_in', $month)
            ->whereYear('check_in', $year)
            ->get()
            ->filter(function ($teacherAttendance) {
                // Cek apakah ada siswa yang hadir di tanggal yang sama
                $studentsPresent = StudentAttendance::where('class_schedule_id', $teacherAttendance->class_schedule_id)
                    ->whereDate('attendance_date', $teacherAttendance->check_in->format('Y-m-d'))
                    ->whereIn('status', ['present', 'late'])
                    ->count();
                
                return $studentsPresent == 0; // Kelas batal jika tidak ada siswa hadir
            })
            ->map(function ($teacherAttendance) {
                return [
                    'class_name' => $teacherAttendance->classSchedule->class_name,
                    'teacher_name' => $teacherAttendance->teacher->user->name,
                    'date' => $teacherAttendance->check_in->format('Y-m-d'),
                    'time' => $teacherAttendance->check_in->format('H:i'),
                    'note' => $teacherAttendance->note,
                ];
            })
            ->values();
    }

    public function downloadClassReport($classId, $type, $month, $year)
    {
        $class = ClassSchedule::with(['teacher.user', 'teacherAttendances'])
            ->findOrFail($classId);

        if ($type === 'notes') {
            return $this->downloadNotesReport($class, $month, $year);
        } elseif ($type === 'attendance') {
            return $this->downloadAttendanceReport($class, $month, $year);
        }

        abort(404);
    }

    private function downloadNotesReport($class, $month, $year)
    {
        $notes = $class->teacherAttendances()
            ->whereMonth('check_in', $month)
            ->whereYear('check_in', $year)
            ->whereNotNull('note')
            ->get();

        $content = "LAPORAN CATATAN KELAS\n";
        $content .= "Kelas: {$class->class_name}\n";
        $content .= "Guru: {$class->teacher->user->name}\n";
        $content .= "Bulan: " . now()->format('F Y') . "\n\n";

        foreach ($notes as $note) {
            $content .= "Tanggal: " . $note->check_in->format('d/m/Y H:i') . "\n";
            $content .= "Catatan: " . $note->note . "\n";
            $content .= str_repeat('-', 50) . "\n\n";
        }

        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="laporan_catatan_' . $class->class_name . '_' . now()->format('Y_m') . '.txt"');
    }

    private function downloadAttendanceReport($class, $month, $year)
    {
        $attendances = $class->studentAttendances()
            ->with(['user'])
            ->whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year)
            ->get()
            ->groupBy('attendance_date');

        $monthName = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][$month];

        $content = "LAPORAN KEHADIRAN KELAS\n";
        $content .= "Kelas: {$class->class_name}\n";
        $content .= "Guru: {$class->teacher->user->name}\n";
        $content .= "Periode: {$monthName} {$year}\n\n";

        foreach ($attendances as $date => $dayAttendances) {
            $content .= "Tanggal: " . Carbon::parse($date)->format('d/m/Y') . "\n";
            $content .= "Hadir: " . $dayAttendances->where('status', 'present')->count() . "\n";
            $content .= "Terlambat: " . $dayAttendances->where('status', 'late')->count() . "\n";
            $content .= "Tidak Hadir: " . $dayAttendances->where('status', 'absent')->count() . "\n";
            
            $content .= "\nDetail Siswa:\n";
            foreach ($dayAttendances as $attendance) {
                $status = match($attendance->status) {
                    'present' => 'Hadir',
                    'late' => 'Terlambat', 
                    'absent' => 'Tidak Hadir',
                    default => $attendance->status
                };
                $content .= "- {$attendance->user->name}: {$status}\n";
            }
            $content .= str_repeat('=', 50) . "\n\n";
        }

        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="laporan_kehadiran_' . $class->class_name . '_' . $year . '_' . $month . '.txt"');
    }
}

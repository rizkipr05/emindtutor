<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sallary;
use App\Models\Teacher;
use App\Models\Staff;
use App\Models\TeacherAttendance;
use App\Services\WhatsAppNotificationService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SallaryController extends Controller
{
    public function index(Request $request)
    {
        $companyId = auth()->user()->company?->id;

        if (!$companyId) {
            return redirect()
                ->route('admin.teachers.sallaries.index', $request->only(['month', 'year']))
                ->with('error', 'Data syarikat admin tidak ditemukan.');
        }

        // dd($request->all());
        $months = [
            '1' => 'Januari',
            '2' => 'Februari',
            '3' => 'Maret',
            '4' => 'April',
            '5' => 'Mei',
            '6' => 'Juni',
            '7' => 'Juli',
            '8' => 'Agustus',
            '9' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];

        // pakai format lama biar kompatibel dgn view
        // 1. Ambil input sebagai angka. Jika kosong, default ke bulan sekarang (angka)
        $selectedMonth = $request->input('month', Carbon::now()->format('n'));

        $selectedYear  = (int) $request->input('year', Carbon::now()->year);

        // 2. Pastikan $selectedMonth adalah integer jika datang dari input string
        $monthNumber = Carbon::createFromDate($selectedYear, (int)$selectedMonth, 1)->month;
        // $monthNumber   = Carbon::parse("1 $selectedMonth $selectedYear")->month;
        // dd($monthNumber);

        // Cek salary records yang sudah ada
        $existingSalaries = $this->checkExistingSalaries($companyId, $selectedMonth, $selectedYear);

        // ===== Guru: hitung dari class schedule allowance & type + attendance bulan terpilih =====
        $teachers = Teacher::with(['user'])
            ->where('company_id', $companyId)
            ->get();

        $teacherRows = collect();

        foreach ($teachers as $teacher) {
            // Ambil semua attendance guru di bulan & tahun terpilih beserta classSchedule
            $attendances = TeacherAttendance::with(['classSchedule']) // pastikan relasi ada
                ->where('teacher_id', $teacher->id)
                ->whereYear('check_in', $selectedYear)
                ->whereMonth('check_in', $monthNumber)
                ->get();

            // Kelompokkan kontribusi per schedule agar bisa hitung per_session/per_hour/per_month dengan benar
            $bySchedule = [];

            foreach ($attendances as $att) {
                if (!$att->classSchedule) {
                    continue;
                }

                $sid       = $att->class_schedule_id;
                $schedule  = $att->classSchedule;
                $scheduleAllowance = (float) ($schedule->allowance ?? 0);
                $allowance = $scheduleAllowance > 0 ? $scheduleAllowance : (float) ($teacher->allowance ?? 0);
                $type      = $schedule->allowance_type ?: $teacher->allowance_type; // 'per_session' | 'per_hour' | 'per_month'

                if (!isset($bySchedule[$sid])) {
                    $bySchedule[$sid] = [
                        'type'          => $type,
                        'allowance'     => $allowance,
                        'sessions'      => 0,
                        'minutes'       => 0,
                        'has_attd'      => false, // untuk per_month, dibayar kalau ada attendance di bulan tsb
                    ];
                }

                // tandai ada attendance (untuk per_month)
                $bySchedule[$sid]['has_attd'] = true;

                if ($type === 'per_session') {
                    $bySchedule[$sid]['sessions'] += 1;
                } elseif ($type === 'per_hour') {
                    $bySchedule[$sid]['minutes'] += $this->scheduledMinutesForAttendance($att);
                }
                // per_month tidak akumulasi sesi/menit — cukup ditandai 'has_attd'
            }

            // Hitung total gaji guru dari seluruh schedule-nya
            $totalMinutesAllSchedules = 0;
            $totalSessionsAllSchedules = 0;
            $totalSalary = 0;

            foreach ($bySchedule as $row) {
                if ($row['type'] === 'per_session') {
                    $totalSalary += $row['sessions'] * $row['allowance'];
                    $totalSessionsAllSchedules += $row['sessions'];
                } elseif ($row['type'] === 'per_hour') {
                    $hours = $row['minutes'] / 60; // boleh pecahan
                    $totalSalary += $hours * $row['allowance'];
                    $totalMinutesAllSchedules += $row['minutes'];
                } elseif ($row['type'] === 'per_month') {
                    if ($row['has_attd']) {
                        // dibayar fixed jika ada kegiatan di bulan tsb
                        $totalSalary += $row['allowance'];
                    }
                }
            }

            // Cek apakah sudah ada salary record
            $salaryKey = 'teacher_' . $teacher->id;
            $existingSalary = $existingSalaries->get($salaryKey);
            $status = $existingSalary && $existingSalary->status === 'paid' ? 'paid' : 'pending';

            $teacherRows->push([
                'type'              => 'Teacher',
                'name'              => $teacher->user->name,
                'teacher_id'        => $teacher->id,
                'staff_id'          => null,
                'attendance_count'  => $attendances->count(), // total sesi hadir bulan ini
                'total_minutes'     => $totalMinutesAllSchedules, // utk info per_hour
                'salary'            => round($totalSalary, 2),
                'status'            => $status,
            ]);
        }

        // ===== Staff: fixed monthly =====
        $staffs = Staff::with('user')
            ->where('company_id', $companyId)
            ->get();

        $staffRows = $staffs->map(function ($staff) use ($existingSalaries) {
            // Cek apakah sudah ada salary record
            $salaryKey = 'staff_' . $staff->id;
            $status = $existingSalaries->has($salaryKey) ? 'paid' : 'pending';

            return [
                'type'             => 'Staff',
                'name'             => $staff->user->name,
                'teacher_id'       => null,
                'staff_id'         => $staff->id,
                'attendance_count' => '-',        // tidak relevan
                'total_minutes'    => '-',        // tidak relevan
                'salary'           => (float) ($staff->monthly_salary ?? 0),
                'status'           => $status,
            ];
        });

        // Gabungkan ke variabel yang namanya tetap 'sallaries' (biar kompatibel dgn view lama)
        $sallaries = $teacherRows->merge($staffRows);

        // Summary (disesuaikan dengan struktur baru)
        $totalTeachers = $teacherRows->count();              // jumlah baris guru
        $totalAmount   = $sallaries->sum('salary');          // total gaji (guru+staff)
        $paidTeachers  = $sallaries->where('status', 'paid')->count(); // yang sudah dibayar
        $paidAmount    = $sallaries->where('status', 'paid')->sum('salary'); // jumlah yang sudah dibayar

        return view('admin.teachers.sallaries.index', compact(
            'months',
            'selectedMonth',
            'selectedYear',
            'sallaries',
            'totalTeachers',
            'totalAmount',
            'paidTeachers',
            'paidAmount'
        ));
    }

    public function syncAttendance(Request $request)
    {
        $request->validate([
            'month' => 'required|string',
            'year' => 'required|integer',
        ]);

        $companyId = auth()->user()->company?->id;

        if (!$companyId) {
            return redirect()
                ->route('admin.teachers.sallaries.index', $request->only(['month', 'year']))
                ->with('error', 'Data syarikat admin tidak ditemukan.');
        }
        $month = $this->resolveMonthName($request->input('month'));
        $monthNumber = $this->monthNumber($month);
        $year = (int) $request->input('year');

        if (!$monthNumber) {
            return redirect()
                ->route('admin.teachers.sallaries.index', $request->only(['month', 'year']))
                ->with('error', 'Bulan tidak valid.');
        }

        $createdCount = 0;
        $skippedCount = 0;

        DB::beginTransaction();

        try {
            $attendances = TeacherAttendance::with([
                    'teacher',
                    'classSchedule.classScheduleDays',
                    'classSchedule.course',
                ])
                ->whereHas('teacher', fn ($query) => $query->where('company_id', $companyId))
                ->whereYear('check_in', $year)
                ->whereMonth('check_in', $monthNumber)
                ->whereNotNull('check_out')
                ->orderBy('teacher_id')
                ->orderBy('class_schedule_id')
                ->orderBy('check_in')
                ->get()
                ->filter(fn ($attendance) => $attendance->classSchedule);

            foreach ($attendances->groupBy(fn ($attendance) => $attendance->teacher_id . ':' . $attendance->class_schedule_id) as $group) {
                $firstAttendance = $group->first();
                $schedule = $firstAttendance->classSchedule;
                $existingCount = Sallary::where('teacher_id', $firstAttendance->teacher_id)
                    ->where('class_schedule_id', $firstAttendance->class_schedule_id)
                    ->whereIn('month', $this->monthAliases($month))
                    ->where('year', $year)
                    ->count();

                foreach ($group as $attendance) {
                    if ($existingCount > 0 || Sallary::where('attendance_id', $attendance->id)->exists()) {
                        $existingCount = max(0, $existingCount - 1);
                        $skippedCount++;
                        continue;
                    }

                    $amount = $this->attendanceSalaryAmount($attendance);

                    if ($amount <= 0) {
                        $skippedCount++;
                        continue;
                    }

                    Sallary::create([
                        'teacher_id' => $attendance->teacher_id,
                        'class_schedule_id' => $attendance->class_schedule_id,
                        'attendance_id' => $attendance->id,
                        'amount' => $amount,
                        'description' => $this->attendanceSalaryDescription($attendance),
                        'month' => $month,
                        'year' => $year,
                        'payment_date' => optional($attendance->check_out)->toDateString() ?: now()->toDateString(),
                        'status' => 'pending',
                    ]);

                    $createdCount++;

                    if ($schedule->allowance_type === 'per_month') {
                        $existingCount++;
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->route('admin.teachers.sallaries.index', $request->only(['month', 'year']))
                ->with('error', 'Gagal memasukkan gaji dari attendance: ' . $e->getMessage());
        }

        return redirect()
            ->route('admin.teachers.sallaries.index', $request->only(['month', 'year']))
            ->with('success', "Berhasil memasukkan {$createdCount} record gaji dari attendance. {$skippedCount} record dilewati karena sudah ada atau amount 0.");
    }

    /**
     * Create salary records with paid status for selected items
     */
    public function createPaid(Request $request)
    {
        $request->validate([
            'selected_items' => 'required|array',
            'selected_items.*' => 'required|string',
            'month' => 'required|string',
            'year' => 'required|integer',
        ]);

        $month = $this->resolveMonthName($request->input('month'));
        $year = $request->input('year');
        
        $createdCount = 0;
        $errors = [];

        DB::beginTransaction();
        
        try {
            foreach ($request->selected_items as $itemJson) {
                $item = json_decode($itemJson, true);
                
                if (!$item) {
                    $errors[] = "Invalid data format";
                    continue;
                }

                // Hanya proses Teacher karena tabel Sallary hanya untuk teacher_id
                if ($item['type'] !== 'Teacher' || !isset($item['teacher_id'])) {
                    $errors[] = "Invalid item type or missing teacher ID for {$item['name']}";
                    continue;
                }

                // Cek apakah sudah ada record untuk teacher di bulan/tahun ini
                $existingSalaries = Sallary::where('teacher_id', $item['teacher_id'])
                    ->whereIn('month', $this->monthAliases($month))
                    ->where('year', $year)
                    ->get();

                if ($existingSalaries->isNotEmpty()) {
                    Sallary::whereIn('id', $existingSalaries->pluck('id'))->update([
                        'status' => 'paid',
                        'payment_date' => now()->toDateString(),
                    ]);
                    $this->notifyApprovedPayroll($item['teacher_id'], $month, (int) $year);
                    $createdCount++;
                    continue;
                }

                // Create description berdasarkan attendance
                $description = "Gaji guru bulan {$month} {$year} - {$item['attendance_count']} sesi";
                if (isset($item['total_minutes']) && $item['total_minutes'] > 0) {
                    $hours = round($item['total_minutes'] / 60, 1);
                    $description .= " ({$hours} jam)";
                }

                // Create salary record sesuai dengan fillable fields
                $salaryData = [
                    'teacher_id' => $item['teacher_id'],
                    'amount' => $item['salary'],
                    'description' => $description,
                    'month' => $month,
                    'year' => $year,
                    'payment_date' => now()->toDateString(), // format date untuk payment_date
                    'status' => 'paid',
                ];

                Sallary::create($salaryData);
                $this->notifyApprovedPayroll($item['teacher_id'], $month, (int) $year);
                $createdCount++;
            }

            // Untuk Staff, buat tabel terpisah atau handle berbeda jika diperlukan
            // Karena structure saat ini hanya support teacher_id

            DB::commit();

            if ($createdCount > 0) {
                $message = "Berhasil menandai {$createdCount} guru sebagai sudah dibayar.";
            } else {
                $message = "Tidak ada data yang berhasil diproses.";
            }

            if (!empty($errors)) {
                $errorCount = count($errors);
                $message .= " {$errorCount} item dilewati";
                if ($errorCount <= 3) {
                    $message .= ": " . implode(', ', $errors);
                } else {
                    $message .= ": " . implode(', ', array_slice($errors, 0, 2)) . " dan " . ($errorCount - 2) . " lainnya.";
                }
            }

            return redirect()->route('admin.teachers.sallaries.index')->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->route('admin.teachers.sallaries.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

/**
 * Check existing salary records for the given month/year
 * This will be used to update the status in index method
 */
private function checkExistingSalaries($companyId, $month, $year)
{
    // Karena tabel Sallary tidak punya company_id, join dengan teachers untuk filter company
    return Sallary::join('teachers', 'sallaries.teacher_id', '=', 'teachers.id')
        ->where('teachers.company_id', $companyId)
        ->whereIn('sallaries.month', $this->monthAliases((string) $month))
        ->where('sallaries.year', $year)
        ->select('sallaries.*')
        ->get()
        ->keyBy(function($salary) {
            return 'teacher_' . $salary->teacher_id;
        });
}

private function attendanceSalaryAmount(TeacherAttendance $attendance): float
{
    $schedule = $attendance->classSchedule;
    $teacher = $attendance->teacher;

    if (!$schedule) {
        return 0;
    }

    $allowanceType = $schedule->allowance_type ?: $teacher?->allowance_type;
    $scheduleAllowance = (float) ($schedule->allowance ?? 0);
    $allowance = $scheduleAllowance > 0 ? $scheduleAllowance : (float) ($teacher?->allowance ?? 0);

    return match ($allowanceType) {
        'per_session' => $allowance,
        'per_hour' => round(($this->scheduledMinutesForAttendance($attendance) / 60) * $allowance, 2),
        'per_month' => $allowance,
        default => 0,
    };
}

private function attendanceSalaryDescription(TeacherAttendance $attendance): string
{
    $schedule = $attendance->classSchedule;
    $className = $schedule?->nama_kelas ?? $schedule?->course?->nama_kos ?? 'Kelas';

    return 'Gaji mengajar - ' . $className . ' - ' . optional($attendance->check_in)->format('d/m/Y');
}

private function scheduledMinutesForAttendance(TeacherAttendance $attendance): int
{
    $scheduleDay = $attendance->classSchedule?->classScheduleDays?->first();

    if ($scheduleDay && (int) $scheduleDay->durasi_menit > 0) {
        return (int) $scheduleDay->durasi_menit;
    }

    if ($attendance->check_in && $attendance->check_out) {
        return Carbon::parse($attendance->check_in)->diffInMinutes(Carbon::parse($attendance->check_out));
    }

    return 0;
}

private function resolveMonthName(?string $month): string
{
    $monthAliases = [
        '1' => 'Januari',
        '01' => 'Januari',
        'january' => 'Januari',
        'januari' => 'Januari',
        '2' => 'Februari',
        '02' => 'Februari',
        'february' => 'Februari',
        'februari' => 'Februari',
        '3' => 'Maret',
        '03' => 'Maret',
        'march' => 'Maret',
        'maret' => 'Maret',
        'mac' => 'Maret',
        '4' => 'April',
        '04' => 'April',
        'april' => 'April',
        '5' => 'Mei',
        '05' => 'Mei',
        'may' => 'Mei',
        'mei' => 'Mei',
        '6' => 'Juni',
        '06' => 'Juni',
        'june' => 'Juni',
        'juni' => 'Juni',
        'jun' => 'Juni',
        '7' => 'Juli',
        '07' => 'Juli',
        'july' => 'Juli',
        'juli' => 'Juli',
        'julai' => 'Juli',
        '8' => 'Agustus',
        '08' => 'Agustus',
        'august' => 'Agustus',
        'agustus' => 'Agustus',
        'ogos' => 'Agustus',
        '9' => 'September',
        '09' => 'September',
        'september' => 'September',
        '10' => 'Oktober',
        'october' => 'Oktober',
        'oktober' => 'Oktober',
        '11' => 'November',
        'november' => 'November',
        '12' => 'Desember',
        'december' => 'Desember',
        'desember' => 'Desember',
        'disember' => 'Desember',
    ];

    $normalized = strtolower(trim((string) $month));

    return $monthAliases[$normalized] ?? ($month ?: 'Januari');
}

private function monthAliases(string $month): array
{
    $monthName = $this->resolveMonthName($month);
    $monthNumbers = [
        'Januari' => 1,
        'Februari' => 2,
        'Maret' => 3,
        'April' => 4,
        'Mei' => 5,
        'Juni' => 6,
        'Juli' => 7,
        'Agustus' => 8,
        'September' => 9,
        'Oktober' => 10,
        'November' => 11,
        'Desember' => 12,
    ];
    $englishMonths = [
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December',
    ];

    $number = $monthNumbers[$monthName] ?? null;
    $aliases = [$month, $monthName];

    if ($number) {
        $aliases[] = (string) $number;
        $aliases[] = str_pad((string) $number, 2, '0', STR_PAD_LEFT);
        $aliases[] = $englishMonths[$number];
        $aliases[] = strtolower($englishMonths[$number]);
    }

    return array_values(array_unique(array_filter($aliases)));
}

private function monthNumber(string $month): ?int
{
    $monthName = $this->resolveMonthName($month);
    $monthNumbers = [
        'Januari' => 1,
        'Februari' => 2,
        'Maret' => 3,
        'April' => 4,
        'Mei' => 5,
        'Juni' => 6,
        'Juli' => 7,
        'Agustus' => 8,
        'September' => 9,
        'Oktober' => 10,
        'November' => 11,
        'Desember' => 12,
    ];

    return $monthNumbers[$monthName] ?? null;
}



    public function show($id)
    {
        $teacher = Teacher::findOrFail($id);

        $sallaries = Sallary::where('teacher_id', $id)
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();

        return view('admin.teachers.sallaries.show', compact('teacher', 'sallaries'));
    }

    public function markPaid(Request $request)
    {
        $ids = $request->input('sallary_ids', []);
        $salaries = Sallary::whereIn('id', $ids)->get();
        Sallary::whereIn('id', $salaries->pluck('id'))->update([
            'status' => 'paid',
            'payment_date' => now()
        ]);

        $salaries->groupBy(fn (Sallary $salary) => $salary->teacher_id . ':' . $salary->month . ':' . $salary->year)
            ->each(function ($rows) {
                $salary = $rows->first();
                $this->notifyApprovedPayroll($salary->teacher_id, $salary->month, (int) $salary->year);
            });

        return redirect()->back()->with('success', 'Gaji guru berhasil ditandai sebagai dibayar.');
    }

    private function notifyApprovedPayroll(int $teacherId, string $month, int $year): void
    {
        $teacher = Teacher::with('user')->find($teacherId);

        if (! $teacher) {
            return;
        }

        app(WhatsAppNotificationService::class)->sendOnce(
            $teacher,
            'payroll_approved_' . strtolower($month) . '_' . $year,
            $teacher->phone,
            "Gaji anda bagi {$month} {$year} telah diluluskan. Anda boleh memuat turun slip gaji di eMindTutor: "
                . route('teacher.sallaries.download', [$this->monthNumber($month), $year])
        );
    }


    public function addBonus(Request $request, $teacherId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
        ]);

        Sallary::create([
            'teacher_id' => $teacherId,
            'amount' => $request->amount,
            'description' => $request->description,
            'month' => Carbon::now()->format('F'),
            'year' => Carbon::now()->year,
            'status' => 'paid',
            'payment_date' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Bonus added successfully.');
    }
}

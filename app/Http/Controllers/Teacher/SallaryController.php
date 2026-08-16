<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sallary;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SallaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('teacher.payroll.index', $this->buildPageData($request));
    }

    private function buildPageData(Request $request): array
    {
        $teacher = Teacher::with(['user', 'company.user'])->where('user_id', Auth::id())->firstOrFail();
        $months = __('teacher/payroll.months');
        $latestSalary = Sallary::where('teacher_id', $teacher->id)
            ->orderByDesc('year')
            ->orderByDesc('created_at')
            ->first();

        $latestMonthKey = $latestSalary
            ? array_search($this->resolveMonthName($latestSalary->month), $this->normalizedMonthMap(), true)
            : false;

        $defaultMonth = $latestMonthKey !== false
            ? strtolower((string) $latestMonthKey)
            : strtolower(now()->englishMonth);

        $defaultYear = $latestSalary ? (int) $latestSalary->year : (int) now()->year;

        $selectedMonth = $request->get('month', $defaultMonth);
        $selectedYear = (int) $request->get('year', $defaultYear);
        $resolvedMonth = $this->resolveMonthName($selectedMonth);

        $payrollData = $this->buildPayrollData($teacher, $resolvedMonth, $selectedYear);
        $pdfFields = $this->buildPdfFields($request, $teacher, $resolvedMonth, $selectedYear, $payrollData);

        return compact(
            'teacher',
            'months',
            'selectedMonth',
            'selectedYear',
            'pdfFields'
        ) + $payrollData + [
            'month' => $resolvedMonth,
            'year' => $selectedYear,
        ];
    }

    public function preview(Request $request, $month, $year)
    {
        $teacher = Teacher::with(['user', 'company.user'])->where('user_id', Auth::id())->firstOrFail();
        $resolvedMonth = $this->resolveMonthName($month);
        $data = $this->buildPayrollData($teacher, $resolvedMonth, (int) $year);

        $pdf = Pdf::loadView('teacher.payroll.slip', $data + [
            'teacher' => $teacher,
            'month' => $resolvedMonth,
            'year' => (int) $year,
            'printedAt' => now('Asia/Kuala_Lumpur'),
            'pdfFields' => $this->buildPdfFields($request, $teacher, $resolvedMonth, (int) $year, $data),
        ]);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream("slip-gaji-{$teacher->user->name}-{$resolvedMonth}-{$year}.pdf");
    }

    /**
     * Download slip gaji bulanan (semua data dalam 1 bulan)
     */
    public function download(Request $request, $month, $year)
    {
        $teacher = Teacher::with(['user', 'company.user'])->where('user_id', Auth::id())->firstOrFail();
        $resolvedMonth = $this->resolveMonthName($month);
        $data = $this->buildPayrollData($teacher, $resolvedMonth, (int) $year);

        $pdf = Pdf::loadView('teacher.payroll.slip', $data + [
            'teacher' => $teacher,
            'month' => $resolvedMonth,
            'year' => (int) $year,
            'printedAt' => now('Asia/Kuala_Lumpur'),
            'pdfFields' => $this->buildPdfFields($request, $teacher, $resolvedMonth, (int) $year, $data),
        ]);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download("slip-gaji-{$teacher->user->name}-{$resolvedMonth}-{$year}.pdf");
    }

    private function buildPayrollData(Teacher $teacher, string $month, int $year): array
    {
        $monthAliases = $this->monthAliases($month);

        $salaries = Sallary::where('teacher_id', $teacher->id)
            ->with(['classSchedule.course', 'classSchedule.classScheduleDays', 'classSchedule.students'])
            ->whereIn('month', $monthAliases)
            ->where('year', $year)
            ->orderBy('payment_date')
            ->get();

        $teachingSalaries = $salaries->whereNotNull('class_schedule_id');
        $payrollSalaries = $salaries
            ->whereNull('class_schedule_id')
            ->where('amount', '>', 0)
            ->filter(fn ($salary) => $this->isPayrollAggregateSalary($salary));
        $bonusSalaries = $salaries
            ->whereNull('class_schedule_id')
            ->where('amount', '>', 0)
            ->reject(fn ($salary) => $this->isPayrollAggregateSalary($salary));
        $deductionSalaries = $salaries->where('amount', '<', 0);
        $groupedTeachingSalaries = $teachingSalaries->groupBy('class_schedule_id');

        $teachingItems = $this->buildAttendanceTeachingItems($teacher, $month, $year, $salaries);

        if ($teachingItems->isEmpty()) {
            $teachingItems = $this->buildRecordedTeachingItems($groupedTeachingSalaries);
        }

        if ($teachingItems->isEmpty() && $payrollSalaries->isNotEmpty()) {
            $teachingItems = collect([[
                'label' => __('teacher/payroll.learning_detail_title'),
                'subject' => __('teacher/payroll.learning_detail_title'),
                'days' => collect(),
                'session_count' => 0,
                'student_count' => 0,
                'amount' => (float) $payrollSalaries->sum('amount'),
                'status' => $payrollSalaries->contains(fn ($salary) => $salary->status === 'paid') ? 'paid' : 'pending',
            ]]);
        }

        $deductionItems = $deductionSalaries->map(function ($deduction) {
            return [
                'label' => $deduction->description ?: __('teacher/payroll.detail.deduction_title'),
                'amount' => abs((float) $deduction->amount),
            ];
        })->values();

        $bonusItems = $bonusSalaries->map(function ($bonus) {
            return [
                'label' => $bonus->description ?: 'Bonus',
                'amount' => (float) $bonus->amount,
            ];
        })->values();

        $totalTeachingSalary = (float) $teachingItems->sum('amount');
        $totalBonus = (float) $bonusSalaries->sum('amount');
        $totalDeductions = (float) $deductionSalaries->sum('amount');
        $grandTotal = $totalTeachingSalary + $totalBonus + $totalDeductions;

        return [
            'salaries' => $salaries,
            'teachingSalaries' => $teachingSalaries,
            'payrollSalaries' => $payrollSalaries,
            'bonusSalaries' => $bonusSalaries,
            'deductionSalaries' => $deductionSalaries,
            'groupedTeachingSalaries' => $groupedTeachingSalaries,
            'teachingItems' => $teachingItems,
            'bonusItems' => $bonusItems,
            'deductionItems' => $deductionItems,
            'totalClasses' => $teachingItems->sum('session_count'),
            'totalTeachingSalary' => $totalTeachingSalary,
            'totalBonus' => $totalBonus,
            'totalDeductions' => $totalDeductions,
            'grandTotal' => $grandTotal,
        ];
    }

    private function buildAttendanceTeachingItems(Teacher $teacher, string $month, int $year, $salaries)
    {
        $monthNumber = $this->monthNumber($month);

        if (!$monthNumber) {
            return collect();
        }

        $attendances = TeacherAttendance::with([
                'classSchedule.course',
                'classSchedule.classScheduleDays',
                'classSchedule.students',
            ])
            ->where('teacher_id', $teacher->id)
            ->whereYear('check_in', $year)
            ->whereMonth('check_in', $monthNumber)
            ->whereNotNull('check_out')
            ->get()
            ->filter(fn ($attendance) => $attendance->classSchedule);

        $payrollMarkedPaid = $salaries->contains(function ($salary) {
            return $this->isPayrollAggregateSalary($salary) && $salary->status === 'paid';
        });

        return $attendances
            ->groupBy('class_schedule_id')
            ->map(function ($classAttendances, $classScheduleId) use ($salaries, $payrollMarkedPaid, $teacher) {
                $schedule = $classAttendances->first()->classSchedule;
                $amount = 0;
                $totalMinutes = 0;

                $allowanceType = $this->effectiveAllowanceType($schedule, $teacher);
                $allowance = $this->effectiveAllowance($schedule, $teacher);

                if ($allowanceType === 'per_month') {
                    $amount = $allowance;
                } else {
                    foreach ($classAttendances as $attendance) {
                        if ($allowanceType === 'per_session') {
                            $amount += $allowance;
                        } elseif ($allowanceType === 'per_hour') {
                            $minutes = $this->scheduledMinutesForAttendance($attendance);
                            $totalMinutes += $minutes;
                            $amount += ($minutes / 60) * $allowance;
                        }
                    }
                }

                $classSalaryRecords = $salaries->where('class_schedule_id', $classScheduleId);
                $status = $payrollMarkedPaid || (
                    $classSalaryRecords->isNotEmpty()
                    && $classSalaryRecords->every(fn ($salary) => $salary->status === 'paid')
                ) ? 'paid' : 'pending';

                return [
                    'label' => $schedule?->nama_kelas
                        ?? $schedule?->course?->nama_kos
                        ?? __('teacher/payroll.not_available'),
                    'subject' => $schedule?->course?->nama_kos ?? __('teacher/payroll.not_available'),
                    'days' => $this->formatScheduleDays($schedule),
                    'session_count' => $classAttendances->count(),
                    'student_count' => $schedule?->students?->count() ?? 0,
                    'amount' => round($amount, 2),
                    'status' => $status,
                    'total_minutes' => $totalMinutes,
                ];
            })
            ->values();
    }

    private function buildRecordedTeachingItems($groupedTeachingSalaries)
    {
        return $groupedTeachingSalaries->map(function ($classSalaries) {
            $firstSalary = $classSalaries->first();
            $schedule = $firstSalary->classSchedule;

            return [
                'label' => $schedule?->nama_kelas
                    ?? $schedule?->course?->nama_kos
                    ?? __('teacher/payroll.not_available'),
                'subject' => $schedule?->course?->nama_kos ?? __('teacher/payroll.not_available'),
                'days' => $this->formatScheduleDays($schedule),
                'session_count' => $classSalaries->count(),
                'student_count' => $schedule?->students?->count() ?? 0,
                'amount' => (float) $classSalaries->sum('amount'),
                'status' => $classSalaries->every(fn ($salary) => $salary->status === 'paid') ? 'paid' : 'pending',
            ];
        })->values();
    }

    private function formatScheduleDays($schedule)
    {
        return ($schedule?->classScheduleDays ?? collect())->map(function ($day) {
            $start = Carbon::parse($day->jam_mulai);
            $end = (clone $start)->addMinutes((int) $day->durasi_menit);

            return [
                'day' => $day->hari,
                'time' => $start->format('H:i') . ' - ' . $end->format('H:i'),
            ];
        });
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

    private function isPayrollAggregateSalary(Sallary $salary): bool
    {
        return str_starts_with(strtolower((string) $salary->description), 'gaji guru bulan');
    }

    private function effectiveAllowanceType($schedule, ?Teacher $teacher): ?string
    {
        return $schedule?->allowance_type ?: $teacher?->allowance_type;
    }

    private function effectiveAllowance($schedule, ?Teacher $teacher): float
    {
        $scheduleAllowance = (float) ($schedule?->allowance ?? 0);

        return $scheduleAllowance > 0 ? $scheduleAllowance : (float) ($teacher?->allowance ?? 0);
    }

    private function buildPdfFields(Request $request, Teacher $teacher, string $month, int $year, array $payrollData): array
    {
        $company = $teacher->company;
        $teachingItems = $payrollData['teachingItems'] ?? collect();
        $totalTeachingSalary = $payrollData['totalTeachingSalary'] ?? 0;
        $companyName = $company?->user?->name ?: ($company?->nama_syarikat ?? '-');

        $defaults = [
            'company_name' => $companyName,
            'company_registration' => $company?->nombor_pendaftaran_syarikat ?? '-',
            'company_address' => $company?->alamat ?? '-',
            'employee_name' => strtoupper($teacher->user->name),
            'employee_nric' => $teacher->ic_number ?? '-',
            'bank_name' => strtoupper($teacher->bank_name ?? '-'),
            'bank_account_number' => $teacher->bank_account_number ?? '-',
            'outlet' => $teachingItems->pluck('label')->take(2)->implode(', ') ?: $companyName,
            'payslip_date' => now('Asia/Kuala_Lumpur')->format('d/m/Y'),
            'payroll_period' => $month . ' ' . $year,
            'department' => $companyName,
            'position' => 'Teacher',
            'employee_ref' => 'T-' . str_pad((string) $teacher->id, 5, '0', STR_PAD_LEFT),
            'annual_leave_entitlement' => '0.00',
            'annual_leave_earned' => '0.00',
            'annual_leave_cf' => '0.00',
            'annual_leave_taken' => '0.00',
            'annual_leave_balance' => '0.00',
            'medical_leave_entitlement' => '14.00',
            'medical_leave_taken' => '0.00',
            'medical_leave_balance' => '14.00',
            'employer_epf' => number_format(round($totalTeachingSalary * 0.11, 2), 2, '.', ''),
            'employer_socso' => number_format(round($totalTeachingSalary * 0.0175, 2), 2, '.', ''),
            'employer_eis' => number_format(round($totalTeachingSalary * 0.002, 2), 2, '.', ''),
            'employer_pcb' => '0.00',
            'employer_hrd' => '0.00',
        ];

        return $defaults;
    }

    private function resolveMonthName(?string $month): string
    {
        $monthMap = $this->normalizedMonthMap();

        if (!$month) {
            return 'Januari';
        }

        $normalized = strtolower(trim($month));

        if (isset($monthMap[$normalized])) {
            return $monthMap[$normalized];
        }

        if (ctype_digit($normalized)) {
            $numericMonthMap = [
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

            if (isset($numericMonthMap[$normalized])) {
                return $numericMonthMap[$normalized];
            }
        }

        foreach ($monthMap as $value) {
            if (strtolower($value) === $normalized) {
                return $value;
            }
        }

        return $month;
    }

    private function monthAliases(string $month): array
    {
        $monthNumber = $this->monthNumber($month);
        $aliases = [$month];

        if ($monthNumber) {
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

            $aliases[] = (string) $monthNumber;
            $aliases[] = str_pad((string) $monthNumber, 2, '0', STR_PAD_LEFT);
            $aliases[] = $this->resolveMonthName((string) $monthNumber);
            $aliases[] = $englishMonths[$monthNumber];
            $aliases[] = strtolower($englishMonths[$monthNumber]);
        }

        return array_values(array_unique(array_filter($aliases, fn ($alias) => $alias !== null && $alias !== '')));
    }

    private function monthNumber(string $month): ?int
    {
        $normalized = strtolower(trim($month));

        if (ctype_digit($normalized)) {
            $number = (int) $normalized;
            return $number >= 1 && $number <= 12 ? $number : null;
        }

        $monthAliases = [
            'januari' => 1,
            'februari' => 2,
            'maret' => 3,
            'mac' => 3,
            'april' => 4,
            'mei' => 5,
            'juni' => 6,
            'jun' => 6,
            'juli' => 7,
            'julai' => 7,
            'agustus' => 8,
            'ogos' => 8,
            'september' => 9,
            'oktober' => 10,
            'november' => 11,
            'desember' => 12,
            'disember' => 12,
        ];

        if (isset($monthAliases[$normalized])) {
            return $monthAliases[$normalized];
        }

        $englishMonths = [
            'january' => 1,
            'february' => 2,
            'march' => 3,
            'april' => 4,
            'may' => 5,
            'june' => 6,
            'july' => 7,
            'august' => 8,
            'september' => 9,
            'october' => 10,
            'november' => 11,
            'december' => 12,
        ];

        if (isset($englishMonths[$normalized])) {
            return $englishMonths[$normalized];
        }

        foreach ($this->normalizedMonthMap() as $key => $value) {
            if ($normalized === $key || $normalized === strtolower($value)) {
                return $englishMonths[$key] ?? null;
            }
        }

        return null;
    }

    private function normalizedMonthMap(): array
    {
        return [
            'january' => 'Januari',
            'february' => 'Februari',
            'march' => 'Maret',
            'april' => 'April',
            'may' => 'Mei',
            'june' => 'Juni',
            'july' => 'Juli',
            'august' => 'Agustus',
            'september' => 'September',
            'october' => 'Oktober',
            'november' => 'November',
            'december' => 'Desember',
            'mac' => 'Maret',
            'jun' => 'Juni',
            'julai' => 'Juli',
            'ogos' => 'Agustus',
            'disember' => 'Desember',
        ];
    }
}

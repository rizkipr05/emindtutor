@extends('layouts.teacher')

@section('title', __('teacher/payroll.page_title'))

@section('content')
<div class="container mx-auto px-4 py-6">
    @php
        $company = $teacher->company;
        $companyLogo = !empty($company?->logo_syarikat) ? asset('uploads/' . $company->logo_syarikat) : null;
    @endphp

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-2xl font-bold mb-4 text-center">{{ __('teacher/payroll.company_payroll_record') }}</h2>
        <p class="text-center text-gray-600">
            {{ __('teacher/payroll.teacher_info', ['name' => $teacher->user->name, 'ic' => $teacher->ic_number ?? __('teacher/payroll.not_available')]) }}
        </p>
        <div class="mt-5 flex justify-center gap-3">
            <a href="{{ route('teacher.sallaries.customize', ['month' => $selectedMonth, 'year' => $selectedYear]) }}"
                class="inline-flex min-w-[220px] items-center justify-center rounded-lg px-6 py-3 text-sm font-semibold shadow-md transition"
                style="color:#ffffff !important; background-color:#2563eb !important; border:1px solid #1d4ed8 !important;">
                <span style="color:#ffffff !important; display:inline !important; visibility:visible !important; opacity:1 !important;">
                    Kustom PDF Slip Gaji
                </span>
            </a>
            <a href="{{ route('teacher.sallaries.download', ['month' => $selectedMonth, 'year' => $selectedYear]) }}"
                class="inline-flex min-w-[220px] items-center justify-center rounded-lg bg-red-600 px-6 py-3 text-sm font-semibold text-white shadow-md transition hover:bg-red-700">
                Print PDF Payslip
            </a>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start">
            <div class="flex h-24 w-24 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50 overflow-hidden">
                @if($companyLogo)
                    <img src="{{ $companyLogo }}" alt="{{ $company->user->name ?? __('teacher/payroll.not_available') }}" class="h-full w-full object-contain">
                @else
                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ __('teacher/payroll.letter_head_title') }}</span>
                @endif
            </div>
            <div class="flex-1">
                <h3 class="text-xl font-bold text-slate-900">{{ $company->user->name ?? __('teacher/payroll.not_available') }}</h3>
                <div class="mt-3 grid grid-cols-1 gap-3 text-sm text-gray-700 md:grid-cols-2">
                    <p><span class="font-semibold">{{ __('teacher/payroll.company_registration') }}:</span> {{ $company->nombor_pendaftaran_syarikat ?? __('teacher/payroll.not_available') }}</p>
                    <p><span class="font-semibold">{{ __('teacher/payroll.payslip_date') }}:</span> {{ now('Asia/Kuala_Lumpur')->format('d/m/Y') }}</p>
                    <p class="md:col-span-2"><span class="font-semibold">{{ __('teacher/payroll.company_address') }}:</span> {{ $company->alamat ?? __('teacher/payroll.not_available') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-bold mb-4 text-slate-800">{{ __('teacher/payroll.self_detail_title') }}</h3>
            <div class="space-y-2 text-sm text-gray-700">
                <p><span class="font-semibold">{{ __('teacher/payroll.name') }}:</span> {{ $teacher->user->name }}</p>
                <p><span class="font-semibold">{{ __('teacher/payroll.ic_number') }}:</span> {{ $teacher->ic_number ?? __('teacher/payroll.not_available') }}</p>
                <p><span class="font-semibold">{{ __('teacher/payroll.bank.name') }}:</span> {{ $teacher->bank_name ?? '-' }}</p>
                <p><span class="font-semibold">{{ __('teacher/payroll.bank.account_number') }}:</span> {{ $teacher->bank_account_number ?? '-' }}</p>
                <p><span class="font-semibold">{{ __('teacher/payroll.payslip_date') }}:</span> {{ now('Asia/Kuala_Lumpur')->format('d/m/Y') }}</p>
                <p><span class="font-semibold">{{ __('teacher/payroll.payroll_period') }}:</span> {{ $month }} {{ $year }}</p>
            </div>
        </div>
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-bold mb-4 text-slate-800">{{ __('teacher/payroll.learning_detail_title') }}</h3>
            <div class="space-y-2 text-sm text-gray-700">
                <p><span class="font-semibold">{{ __('teacher/payroll.summary.total_classes') }}:</span> {{ $totalClasses }}</p>
                <p><span class="font-semibold">{{ __('teacher/payroll.totals.total_teaching_salary') }}:</span> RM {{ number_format($totalTeachingSalary, 0, ',', '.') }}</p>
                <p><span class="font-semibold">{{ __('teacher/payroll.totals.total_bonus') }}:</span> RM {{ number_format($totalBonus, 0, ',', '.') }}</p>
                <p><span class="font-semibold">{{ __('teacher/payroll.totals.total_deductions') }}:</span> -RM {{ number_format(abs($totalDeductions), 0, ',', '.') }}</p>
                <p><span class="font-semibold">{{ __('teacher/payroll.net_salary') }}:</span> RM {{ number_format($totalTeachingSalary + $totalBonus + $totalDeductions, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <form method="GET" class="mb-6 bg-white shadow-md rounded-lg p-4">
        <div class="flex gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('teacher/payroll.filter.month') }}</label>
                @php
                    // Use months defined in language file so labels are translated
                    $translatedMonths = __('teacher/payroll.months');
                    // $translatedMonths is an associative array: key => label
                @endphp
                <select name="month" class="w-full border-gray-300 rounded-md shadow-sm">
                    @foreach($translatedMonths as $key => $label)
                        @php
                            // Support various possible formats for currently selected $month
                            $isSelected = (
                                (string)$month === (string)$key ||
                                (string)$month === (string)$label ||
                                strtolower((string)$month) === strtolower((string)$label) ||
                                strtolower((string)$month) === strtolower((string)$key)
                            );
                        @endphp
                        <option value="{{ $key }}" {{ $isSelected ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('teacher/payroll.filter.year') }}</label>
                <input type="number" name="year" class="w-full border-gray-300 rounded-md shadow-sm" value="{{ $year }}">
            </div>
            <div>
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition">{{ __('teacher/payroll.filter.button') }}</button>
            </div>
        </div>
    </form>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <h3 class="text-green-700 font-semibold">💼 {{ __('teacher/payroll.summary.total_classes') }}</h3>
            <p class="text-2xl font-bold text-green-800">{{ $totalClasses }}</p>
        </div>
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h3 class="text-blue-700 font-semibold">💰 {{ __('teacher/payroll.summary.total_teaching_salary') }}</h3>
            <p class="text-xl font-bold text-blue-800">RM {{ number_format($totalTeachingSalary, 0, ',', '.') }}</p>
        </div>
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <h3 class="text-yellow-700 font-semibold">🎁 {{ __('teacher/payroll.summary.total_bonus') }}</h3>
            <p class="text-xl font-bold text-yellow-800">RM {{ number_format($totalBonus, 0, ',', '.') }}</p>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <h3 class="text-red-700 font-semibold">⚠️ {{ __('teacher/payroll.summary.total_deductions') }}</h3>
            <p class="text-xl font-bold text-red-800">RM {{ number_format(abs($totalDeductions), 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Detail Sections -->
    <div class="space-y-6">
        
        <!-- 1. Gaji Mengajar -->
        @if($teachingItems->isNotEmpty())
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-xl font-bold mb-4 text-green-700">💼 {{ __('teacher/payroll.learning_detail_title') }}</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-green-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-green-700">{{ __('teacher/payroll.table.subject') }}</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-green-700">{{ __('teacher/payroll.table.class') }}</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-green-700">{{ __('teacher/payroll.table.day_time') }}</th>
                            <th class="px-4 py-2 text-center text-sm font-semibold text-green-700">{{ __('teacher/payroll.table.sessions') }}</th>
                            <th class="px-4 py-2 text-right text-sm font-semibold text-green-700">{{ __('teacher/payroll.table.salary') }}</th>
                            <th class="px-4 py-2 text-center text-sm font-semibold text-green-700">{{ __('teacher/payroll.table.status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($teachingItems as $item)
                            @php
                                $dayTime = $item['days']->map(function ($day) {
                                    return $day['day'] . ' ' . $day['time'];
                                })->implode(', ') ?: '-';
                                $status = $item['status'] ?? 'pending';
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    {{ $item['subject'] ?? __('teacher/payroll.table.subject') }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $item['label'] ?? __('teacher/payroll.table.class') }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $dayTime }}</td>
                                <td class="px-4 py-3 text-center">{{ $item['session_count'] ?? 0 }}x</td>
                                <td class="px-4 py-3 text-right font-semibold">
                                    RM {{ number_format($item['amount'] ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium
                                        {{ $status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $status == 'paid' ? __('teacher/payroll.paid') : __('teacher/payroll.pending') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-green-100">
                        <tr>
                            <td colspan="5" class="px-4 py-3 font-bold text-green-800">{{ __('teacher/payroll.totals.total_teaching_salary') }}</td>
                            <td class="px-4 py-3 text-right font-bold text-green-800">
                                RM {{ number_format($totalTeachingSalary, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @endif

        <!-- 2. Elaun/Bonus -->
        @if($bonusSalaries->isNotEmpty())
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-xl font-bold mb-4 text-yellow-700">🎁 {{ __('teacher/payroll.detail.bonus_title') }}</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-yellow-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-yellow-700">{{ __('teacher/payroll.table.description') }}</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-yellow-700">{{ __('teacher/payroll.table.month') }}</th>
                            <th class="px-4 py-2 text-right text-sm font-semibold text-yellow-700">{{ __('teacher/payroll.table.amount') }}</th>
                            <th class="px-4 py-2 text-center text-sm font-semibold text-yellow-700">{{ __('teacher/payroll.table.status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($bonusSalaries as $bonus)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $bonus->description ?? 'Bonus/Elaun' }}</td>
                                <td class="px-4 py-3">{{ $bonus->month }} {{ $bonus->year }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-yellow-600">
                                    RM {{ number_format($bonus->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        {{ $bonus->status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $bonus->status == 'paid' ? __('teacher/payroll.paid') : __('teacher/payroll.pending') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-yellow-100">
                        <tr>
                            <td colspan="2" class="px-4 py-3 font-bold text-yellow-800">Total Bonus</td>
                            <td class="px-4 py-3 text-right font-bold text-yellow-800">
                                RM {{ number_format($totalBonus, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @endif

        <!-- 3. Potongan -->
        @if($deductionSalaries->isNotEmpty())
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-xl font-bold mb-4 text-red-700">⚠️ {{ __('teacher/payroll.detail.deduction_title') }}</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-red-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-red-700">{{ __('teacher/payroll.table.type') }}</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-red-700">{{ __('teacher/payroll.table.month') }}</th>
                            <th class="px-4 py-2 text-right text-sm font-semibold text-red-700">{{ __('teacher/payroll.table.amount') }}</th>
                            <th class="px-4 py-2 text-center text-sm font-semibold text-red-700">{{ __('teacher/payroll.table.status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($deductionSalaries as $deduction)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $deduction->description ?? 'Potongan' }}</td>
                                @php
                                    // try to resolve the month via translation keys (e.g. 'january')
                                    $monthKey = is_string($deduction->month) ? strtolower($deduction->month) : $deduction->month;
                                    $monthLabel = \Illuminate\Support\Facades\Lang::has('teacher/payroll.months.' . $monthKey)
                                        ? __('teacher/payroll.months.' . $monthKey)
                                        : $deduction->month;
                                @endphp
                                <td class="px-4 py-3">{{ $monthLabel }} {{ $deduction->year }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-red-600">
                                    -RM {{ number_format(abs($deduction->amount), 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        {{ $deduction->status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        @php
                                            $statusKey = $deduction->status ?? 'pending';
                                            $statusLabel = \Illuminate\Support\Facades\Lang::has('teacher/payroll.status_deductions.' . $statusKey)
                                                ? __('teacher/payroll.status_deductions.' . $statusKey)
                                                : ($statusKey == 'paid' ? __('teacher/payroll.paid') : __('teacher/payroll.pending'));
                                        @endphp
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-red-100">
                        <tr>
                            <td colspan="2" class="px-4 py-3 font-bold text-red-800">{{ __('teacher/payroll.deductions') }}</td>
                            <td class="px-4 py-3 text-right font-bold text-red-800">
                                -RM {{ number_format(abs($totalDeductions), 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @endif

        <!-- 4. Ringkasan Total & Download -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-md rounded-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                <div>
                    <h3 class="text-xl font-bold mb-4">💼 {{ __('teacher/payroll.payment_summary_title') }} {{ $month }} {{ $year }}</h3>
                    @php
                        $grandTotal = $totalTeachingSalary + $totalBonus + $totalDeductions;
                    @endphp
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>{{ __('teacher/payroll.totals.total_teaching_salary') }}:</span>
                            <span class="font-semibold">RM {{ number_format($totalTeachingSalary, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>{{ __('teacher/payroll.totals.total_bonus') }}:</span>
                            <span class="font-semibold">RM {{ number_format($totalBonus, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>{{ __('teacher/payroll.totals.total_deductions') }}:</span>
                            <span class="font-semibold">-RM {{ number_format(abs($totalDeductions), 0, ',', '.') }}</span>
                        </div>
                        <hr class="border-white/30">
                        <div class="flex justify-between text-lg font-bold">
                            <span>{{ __('teacher/payroll.totals.net_total') }}:</span>
                            <span>RM {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <a href="{{ route('teacher.sallaries.download', ['month' => $selectedMonth, 'year' => $selectedYear]) }}"
                        class="inline-flex items-center justify-center rounded-lg bg-white px-6 py-3 font-semibold text-blue-600 transition hover:bg-gray-100">
                        Print PDF Payslip
                    </a>
                        <p class="text-sm mt-2 opacity-90">
                            {{ __('teacher/payroll.filter_status_label', [], null) ?: 'Status' }}:
                            @if($salaries->where('status', 'pending')->count() > 0 || $teachingItems->contains(fn ($item) => ($item['status'] ?? 'pending') === 'pending'))
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">{{ __('teacher/payroll.status_partial') }}</span>
                            @else
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">{{ __('teacher/payroll.status_all_paid') }}</span>
                            @endif
                        </p>

                     <div class="mt-4 text-white">
                        <p><span class="font-semibold">{{ __('teacher/payroll.bank.name') }}:</span> {{ $teacher->bank_name ?? '-' }}</p>
                        <p><span class="font-semibold">{{ __('teacher/payroll.bank.account_number') }}:</span> {{ $teacher->bank_account_number ?? '-' }}</p>
                        <p><span class="font-semibold">{{ __('teacher/payroll.bank.account_holder') }}:</span> {{ $teacher->bank_account_holder ?? '-' }}</p>
                        <p><span class="font-semibold">{{ __('teacher/payroll.bank.account_holder_ic') }}:</span> {{ $teacher->account_holders_ic_number ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

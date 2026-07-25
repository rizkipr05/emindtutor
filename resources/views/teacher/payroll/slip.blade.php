<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('teacher/payroll.payslip_title') }} - {{ $teacher->user->name }}</title>
    <style>
        @page {
            margin: 18mm 16mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #111;
            margin: 0;
        }

        .sheet {
            width: 100%;
        }

        .header {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .header td {
            vertical-align: top;
        }

        .logo-cell {
            width: 88px;
        }

        .logo-box {
            width: 76px;
            height: 76px;
            border: 1px solid #d4d4d4;
            text-align: center;
            vertical-align: middle;
        }

        .logo-box img {
            width: 76px;
            height: 76px;
            object-fit: contain;
        }

        .company-name {
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .company-meta {
            font-size: 11px;
            line-height: 1.4;
        }

        .info-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .info-grid td {
            width: 50%;
            vertical-align: top;
            padding-right: 24px;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .meta-label {
            width: 1%;
            white-space: nowrap;
            padding-right: 2px;
        }

        .meta-sep {
            width: 1%;
            text-align: center;
            white-space: nowrap;
            padding: 0 3px 0 0 !important;
        }

        .split-head {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0 4px;
        }

        .split-head td {
            font-weight: 700;
            font-size: 12px;
            vertical-align: middle;
        }

        .line {
            border-top: 1px solid #111;
            height: 1px;
        }

        .section-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .section-table td {
            vertical-align: top;
            width: 50%;
        }

        .inner-table {
            width: 100%;
            border-collapse: collapse;
        }

        .inner-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .amount {
            text-align: right;
            white-space: nowrap;
        }

        .spacer {
            height: 68px;
        }

        .total-box {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .total-box td {
            padding: 4px 0;
            vertical-align: middle;
        }

        .total-label {
            text-align: right;
            padding-right: 10px;
            font-weight: 700;
        }

        .total-value {
            width: 120px;
            text-align: right;
            border-top: 1px solid #111;
            border-bottom: 1px solid #111;
        }

        .net-row .total-label,
        .net-row .total-value {
            font-size: 18px;
            font-weight: 700;
        }

        .bottom-grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
        }

        .bottom-grid td {
            width: 25%;
            vertical-align: top;
            padding-right: 14px;
        }

        .bottom-title {
            font-weight: 700;
            margin-bottom: 2px;
        }

        .bottom-table {
            width: 100%;
            border-collapse: collapse;
        }

        .bottom-table td {
            padding: 1px 0;
            vertical-align: top;
        }

        .bottom-table td:first-child {
            white-space: nowrap;
            padding-right: 2px;
        }

        .bottom-table td:nth-child(2) {
            width: 1%;
            white-space: nowrap;
            text-align: center;
            padding: 0 3px 0 0;
        }

        .muted {
            color: #555;
        }
    </style>
</head>
<body>
    @php
        $company = $teacher->company;
        $companyName = $pdfFields['company_name'] ?? ($company?->user?->name ?? '-');
        $printedAt = $printedAt ?? now('Asia/Kuala_Lumpur');
        $logoPath = null;

        if (!empty($company?->logo_syarikat)) {
            $candidate = public_path('uploads/' . $company->logo_syarikat);
            if (file_exists($candidate)) {
                $logoPath = $candidate;
            }
        }

        $outletName = $pdfFields['outlet'] ?? $companyName;
        $employeeRef = $pdfFields['employee_ref'] ?? ('T-' . str_pad((string) $teacher->id, 5, '0', STR_PAD_LEFT));
        $earningRows = $teachingItems->map(function ($item) {
            $scheduleText = $item['days']->map(function ($day) {
                return $day['day'] . ' ' . $day['time'];
            })->implode(', ');

            return [
                'label' => $item['label'],
                'meta' => trim($item['session_count'] . ' sesi' . ($scheduleText ? ' | ' . $scheduleText : '')),
                'amount' => $item['amount'],
            ];
        })->values();

        foreach ($bonusItems as $bonus) {
            $earningRows->push([
                'label' => $bonus['label'],
                'meta' => 'Bonus',
                'amount' => $bonus['amount'],
            ]);
        }

        $employerEpf = $pdfFields['employer_epf'] ?? number_format(round($totalTeachingSalary * 0.11, 2), 2, '.', '');
        $employerSocso = $pdfFields['employer_socso'] ?? number_format(round($totalTeachingSalary * 0.0175, 2), 2, '.', '');
        $employerEis = $pdfFields['employer_eis'] ?? number_format(round($totalTeachingSalary * 0.002, 2), 2, '.', '');
    @endphp

    <div class="sheet">
        <table class="header">
            <tr>
                <td class="logo-cell">
                    <div class="logo-box">
                        @if($logoPath)
                            <img src="{{ $logoPath }}" alt="{{ $companyName }}">
                        @endif
                    </div>
                </td>
                <td>
                    <div class="company-name">{{ $companyName }}</div>
                    <div class="company-meta">
                        <div>COM. {{ $pdfFields['company_registration'] ?? ($company?->nombor_pendaftaran_syarikat ?? '-') }}</div>
                        <div>{{ $pdfFields['company_address'] ?? ($company?->alamat ?? '-') }}</div>
                    </div>
                </td>
            </tr>
        </table>

        <table class="info-grid">
            <tr>
                <td>
                    <table class="meta-table">
                        <tr><td class="meta-label">Name</td><td class="meta-sep">:</td><td>{{ $pdfFields['employee_name'] ?? strtoupper($teacher->user->name) }}</td></tr>
                        <tr><td class="meta-label">NRIC</td><td class="meta-sep">:</td><td>{{ $pdfFields['employee_nric'] ?? ($teacher->ic_number ?? '-') }}</td></tr>
                        <tr><td class="meta-label">Bank</td><td class="meta-sep">:</td><td>{{ $pdfFields['bank_name'] ?? strtoupper($teacher->bank_name ?? '-') }}</td></tr>
                        <tr><td class="meta-label">Bank #</td><td class="meta-sep">:</td><td>{{ $pdfFields['bank_account_number'] ?? ($teacher->bank_account_number ?? '-') }}</td></tr>
                        <tr><td class="meta-label">Outlet</td><td class="meta-sep">:</td><td>{{ $outletName ?: '-' }}</td></tr>
                    </table>
                </td>
                <td>
                    <table class="meta-table">
                        <tr><td class="meta-label">Payslip Date</td><td class="meta-sep">:</td><td>{{ $pdfFields['payslip_date'] ?? $printedAt->format('d/m/Y') }}</td></tr>
                        <tr><td class="meta-label">Payroll Period</td><td class="meta-sep">:</td><td>{{ $pdfFields['payroll_period'] ?? ($month . ' ' . $year) }}</td></tr>
                        <tr><td class="meta-label">Department</td><td class="meta-sep">:</td><td>{{ $pdfFields['department'] ?? $companyName }}</td></tr>
                        <tr><td class="meta-label">Position</td><td class="meta-sep">:</td><td>{{ $pdfFields['position'] ?? 'Teacher' }}</td></tr>
                    </table>
                </td>
            </tr>
        </table>

        <table class="split-head">
            <tr>
                <td style="width: 18%;" class="line"></td>
                <td style="width: 10%; text-align: center;">Earning</td>
                <td style="width: 18%;" class="line"></td>
                <td style="width: 8%; text-align: center;">&lt;</td>
                <td style="width: 18%;" class="line"></td>
                <td style="width: 10%; text-align: center;">Deduction</td>
                <td style="width: 18%;" class="line"></td>
            </tr>
        </table>

        <table class="section-table">
            <tr>
                <td style="padding-right: 18px;">
                    <table class="inner-table">
                        @forelse($earningRows as $row)
                            <tr>
                                <td>
                                    <div>{{ $row['label'] }}</div>
                                    <div class="muted">{{ $row['meta'] }}</div>
                                </td>
                                <td class="amount">{{ number_format($row['amount'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td>No earning data</td>
                                <td class="amount">0.00</td>
                            </tr>
                        @endforelse
                    </table>
                </td>
                <td style="padding-left: 18px;">
                    <table class="inner-table">
                        @forelse($deductionItems as $row)
                            <tr>
                                <td>{{ $row['label'] }}</td>
                                <td class="amount">{{ number_format($row['amount'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td>No deduction</td>
                                <td class="amount">0.00</td>
                            </tr>
                        @endforelse
                    </table>
                </td>
            </tr>
        </table>

        <div class="spacer"></div>

        <table class="section-table">
            <tr>
                <td style="padding-right: 18px;">
                    <table class="total-box">
                        <tr>
                            <td class="total-label">Total:</td>
                            <td class="total-value">{{ number_format($totalTeachingSalary + $totalBonus, 2) }}</td>
                        </tr>
                        <tr class="net-row">
                            <td class="total-label">Net Salary:</td>
                            <td class="total-value">{{ number_format($grandTotal, 2) }}</td>
                        </tr>
                    </table>
                </td>
                <td style="padding-left: 18px;">
                    <table class="total-box">
                        <tr>
                            <td class="total-label">Total:</td>
                            <td class="total-value">{{ number_format(abs($totalDeductions), 2) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table class="bottom-grid">
            <tr>
                <td>
                    <div class="bottom-title">Employee's Ref. No.</div>
                    <table class="bottom-table">
                        <tr><td>EPF</td><td>:</td><td>{{ $employeeRef }}</td></tr>
                        <tr><td>SOCSO</td><td>:</td><td>{{ $pdfFields['employee_nric'] ?? ($teacher->ic_number ?? '-') }}</td></tr>
                        <tr><td>PCB</td><td>:</td><td>-</td></tr>
                    </table>
                </td>
                <td>
                    <div class="bottom-title">Annual Leave</div>
                    <table class="bottom-table">
                        <tr><td>Entitlement</td><td>:</td><td>{{ $pdfFields['annual_leave_entitlement'] ?? '0.00' }}</td></tr>
                        <tr><td>Earned Leave</td><td>:</td><td>{{ $pdfFields['annual_leave_earned'] ?? '0.00' }}</td></tr>
                        <tr><td>C/F</td><td>:</td><td>{{ $pdfFields['annual_leave_cf'] ?? '0.00' }}</td></tr>
                        <tr><td>Taken</td><td>:</td><td>{{ $pdfFields['annual_leave_taken'] ?? '0.00' }}</td></tr>
                        <tr><td>Balance</td><td>:</td><td>{{ $pdfFields['annual_leave_balance'] ?? '0.00' }}</td></tr>
                    </table>
                </td>
                <td>
                    <div class="bottom-title">Medical Leave</div>
                    <table class="bottom-table">
                        <tr><td>Entitlement</td><td>:</td><td>{{ $pdfFields['medical_leave_entitlement'] ?? '14.00' }}</td></tr>
                        <tr><td>Taken</td><td>:</td><td>{{ $pdfFields['medical_leave_taken'] ?? '0.00' }}</td></tr>
                        <tr><td>Balance</td><td>:</td><td>{{ $pdfFields['medical_leave_balance'] ?? '14.00' }}</td></tr>
                    </table>
                </td>
                <td>
                    <div class="bottom-title">Employer Contribution</div>
                    <table class="bottom-table">
                        <tr><td>EPF</td><td>:</td><td>{{ $employerEpf }}</td></tr>
                        <tr><td>SOCSO</td><td>:</td><td>{{ $employerSocso }}</td></tr>
                        <tr><td>EIS</td><td>:</td><td>{{ $employerEis }}</td></tr>
                        <tr><td>PCB</td><td>:</td><td>{{ $pdfFields['employer_pcb'] ?? '0.00' }}</td></tr>
                        <tr><td>HRD</td><td>:</td><td>{{ $pdfFields['employer_hrd'] ?? '0.00' }}</td></tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>

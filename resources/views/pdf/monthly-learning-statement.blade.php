<!doctype html>
<html lang="ms">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1e293b; font-size: 10px; }
        h1 { margin: 0; font-size: 19px; color: #0f172a; }
        h2 { font-size: 14px; margin: 20px 0 8px; color: #0f172a; }
        .muted { color: #64748b; } .header { width: 100%; border-bottom: 2px solid #14b8a6; padding-bottom: 12px; }
        .header-logo { width: 76px; padding-right: 12px; vertical-align: top; }
        .header-logo-box { width: 66px; height: 66px; border: 1px solid #e2e8f0; border-radius: 4px; text-align: center; }
        .header-logo img { width: 64px; height: 64px; object-fit: contain; }
        .company-meta { margin: 3px 0 0; color: #475569; font-size: 9px; line-height: 1.5; }
        .report-title { margin: 7px 0 0; color: #0f766e; font-size: 10px; font-weight: bold; }
        .student-card { width: 100%; margin-top: 18px; border: 1px solid #cbd5e1; border-left: 4px solid #0f766e; background: #f8fafc; }
        .student-card td { border: 0; padding: 10px 12px; vertical-align: top; }
        .info-label { color: #64748b; font-size: 8px; font-weight: bold; text-transform: uppercase; }
        .info-value { margin-top: 3px; color: #0f172a; font-size: 11px; font-weight: bold; }
        .summary { width: 100%; margin-top: 14px; border-collapse: separate; border-spacing: 6px; }
        .summary td { border: 1px solid #bae6e1; background: #f0fdfa; padding: 10px; border-radius: 5px; width: 33%; }
        .summary-label { color: #0f766e; font-size: 8px; font-weight: bold; text-transform: uppercase; }
        .summary-value { color: #0f172a; font-size: 16px; font-weight: bold; margin-top: 4px; }
        .class-card { margin: 12px 0 18px; border: 1px solid #cbd5e1; }
        .class-heading { background: #f0fdfa; border-bottom: 1px solid #99f6e4; padding: 9px 10px; }
        .class-name { color: #0f172a; font-size: 11px; font-weight: bold; }
        .class-meta { color: #0f766e; font-size: 9px; font-weight: bold; margin-top: 3px; }
        table { width: 100%; border-collapse: collapse; } th { background: #0f766e; color: #fff; text-align: left; }
        th, td { border: 1px solid #cbd5e1; padding: 7px; vertical-align: top; } th { font-size: 9px; } .present { color: #047857; font-weight: bold; } .absent { color: #b91c1c; font-weight: bold; }
        .footer { position: fixed; bottom: -20px; left: 0; right: 0; font-size: 8px; color: #64748b; text-align: center; }
    </style>
</head>
<body>
@php
    $summary = $report->summary ?? [];
    $company = $report->company;
    $companyName = $company->user?->name ?: ($company->nama_syarikat ?: 'EMindTutor');
    $logoDataUri = null;

    if (!empty($company->logo_syarikat)) {
        $logoPath = base_path('../public_html/uploads/' . $company->logo_syarikat);
        if (!file_exists($logoPath)) {
            $logoPath = base_path('uploads/' . $company->logo_syarikat);
        }

        if (is_file($logoPath) && is_readable($logoPath)) {
            $mimeType = mime_content_type($logoPath) ?: 'image/png';
            $logoDataUri = 'data:' . $mimeType . ';base64,' . base64_encode(file_get_contents($logoPath));
        }
    }
@endphp
<table class="header">
    <tr>
        <td class="header-logo">
            @if($logoDataUri)
                <div class="header-logo-box"><img src="{{ $logoDataUri }}" alt="{{ $companyName }}"></div>
            @endif
        </td>
        <td>
            <h1>{{ strtoupper($companyName) }}</h1>
            <p class="company-meta">
                @if($company->alamat){{ $company->alamat }}<br>@endif
                @if($company->user?->email){{ $company->user->email }}@endif
                @if($company->user?->email && $company->no_telefon) · @endif
                {{ $company->no_telefon }}
            </p>
            <p class="report-title">{{ $company->monthly_report_pdf_title ?: __('reports.pdf.statement') }}</p>
        </td>
    </tr>
</table>
<table class="student-card">
    <tr>
        <td style="width:44%;">
            <div class="info-label">Nama Pelajar</div>
            <div class="info-value">{{ $summary['student_name'] ?? $report->student->user->name }}</div>
        </td>
        <td style="width:30%;">
            <div class="info-label">Tempoh Laporan</div>
            <div class="info-value">{{ $report->period_start->translatedFormat('F Y') }}</div>
        </td>
        <td style="width:26%;">
            <div class="info-label">Tarikh Dijana</div>
            <div class="info-value">{{ $report->collected_at->translatedFormat('d M Y') }}</div>
        </td>
    </tr>
</table>
<table class="summary"><tr>
    <td><div class="summary-label">{{ __('reports.pdf.total_classes') }}</div><div class="summary-value">{{ $summary['total_sessions'] ?? 0 }}</div></td>
    <td><div class="summary-label">{{ __('reports.pdf.attendance') }}</div><div class="summary-value">{{ $summary['attended_sessions'] ?? 0 }}</div></td>
    <td><div class="summary-label">{{ __('reports.pdf.performance') }}</div><div class="summary-value">{{ $summary['attendance_percentage'] ?? 0 }}%</div></td>
</tr></table>
<h2>{{ __('reports.pdf.learning_record') }}</h2>
@forelse($summary['classes'] ?? [] as $class)
    <div class="class-card">
    <div class="class-heading">
        <div class="class-name">{{ $class['class_name'] ?? '-' }}</div>
        <div class="class-meta">Kursus: {{ $class['course_name'] ?? '-' }} · Kehadiran: {{ $class['attendance_percentage'] ?? 0 }}%</div>
    </div>
    <table>
        <thead><tr><th>{{ __('reports.pdf.date') }}</th><th>{{ __('reports.pdf.topic') }}</th><th>{{ __('reports.pdf.attendance') }}</th><th>{{ __('reports.pdf.teacher_note') }}</th></tr></thead>
        <tbody>
        @forelse($class['sessions'] ?? [] as $session)
            <tr>
                <td>{{ \Carbon\Carbon::parse($session['date'])->format('d/m/Y') }}</td>
                <td>{{ $session['learning_topic'] ?? '-' }}</td>
                <td class="{{ ($session['status'] ?? '') === 'hadir' ? 'present' : 'absent' }}">{{ ($session['status'] ?? '') === 'hadir' ? __('reports.teaching.present') : __('reports.teaching.absent') }}</td>
                <td>{{ $session['teacher_note'] ?? '-' }}</td>
            </tr>
        @empty
            <tr><td colspan="4">{{ __('reports.pdf.no_sessions') }}</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
@empty
    <p>{{ __('reports.pdf.no_classes') }}</p>
@endforelse
<div class="footer">{{ $report->company->monthly_report_pdf_footer ?: __('reports.pdf.footer') }}</div>
</body>
</html>

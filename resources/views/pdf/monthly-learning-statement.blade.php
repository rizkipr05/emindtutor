<!doctype html>
<html lang="ms">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1e293b; font-size: 10px; }
        h1 { margin: 0; font-size: 22px; color: #0f766e; }
        h2 { font-size: 14px; margin: 20px 0 8px; color: #0f172a; }
        .muted { color: #64748b; } .header { border-bottom: 2px solid #14b8a6; padding-bottom: 15px; }
        .summary { width: 100%; margin-top: 14px; border-collapse: separate; border-spacing: 8px; }
        .summary td { background: #f0fdfa; padding: 10px; border-radius: 5px; width: 33%; }
        table { width: 100%; border-collapse: collapse; } th { background: #0f766e; color: #fff; text-align: left; }
        th, td { border: 1px solid #cbd5e1; padding: 7px; vertical-align: top; } .present { color: #047857; font-weight: bold; } .absent { color: #b91c1c; font-weight: bold; }
        .footer { position: fixed; bottom: -20px; left: 0; right: 0; font-size: 8px; color: #64748b; text-align: center; }
    </style>
</head>
<body>
@php($summary = $report->summary ?? [])
<div class="header">
    <h1>{{ $report->company->nama_syarikat ?: 'EMindTutor' }}</h1>
    <p class="muted">{{ $report->company->monthly_report_pdf_title ?: __('reports.pdf.statement') }}</p>
</div>
<h2>{{ $summary['student_name'] ?? $report->student->user->name }}</h2>
<p class="muted">{{ __('reports.pdf.period') }} {{ $report->period_start->translatedFormat('F Y') }} · {{ __('reports.pdf.generated') }} {{ $report->collected_at->translatedFormat('d M Y, H:i') }}</p>
<table class="summary"><tr>
    <td><strong>{{ __('reports.pdf.total_classes') }}</strong><br>{{ $summary['total_sessions'] ?? 0 }}</td>
    <td><strong>{{ __('reports.pdf.attendance') }}</strong><br>{{ $summary['attended_sessions'] ?? 0 }}</td>
    <td><strong>{{ __('reports.pdf.performance') }}</strong><br>{{ $summary['attendance_percentage'] ?? 0 }}%</td>
</tr></table>
<h2>{{ __('reports.pdf.learning_record') }}</h2>
@forelse($summary['classes'] ?? [] as $class)
    <p><strong>{{ $class['course_name'] ?? '-' }}</strong> — {{ $class['class_name'] ?? '-' }} ({{ $class['attendance_percentage'] ?? 0 }}% kehadiran)</p>
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
@empty
    <p>{{ __('reports.pdf.no_classes') }}</p>
@endforelse
<div class="footer">{{ $report->company->monthly_report_pdf_footer ?: __('reports.pdf.footer') }}</div>
</body>
</html>

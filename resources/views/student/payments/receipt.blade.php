<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Resit {{ $invoice->nomor_invoice }}</title>
    <style>
        @page { margin: 34px 42px; }
        body { color: #1f2937; font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        .header { border-bottom: 2px solid #0f766e; padding-bottom: 16px; width: 100%; }
        .logo { height: 58px; max-width: 90px; object-fit: contain; }
        .company-name { color: #0f172a; font-size: 18px; font-weight: bold; }
        .muted { color: #64748b; line-height: 1.6; }
        .receipt-title { color: #0f766e; font-size: 22px; font-weight: bold; margin: 30px 0 5px; }
        .meta { color: #64748b; margin-bottom: 25px; }
        table { border-collapse: collapse; width: 100%; }
        .details td { border-bottom: 1px solid #e2e8f0; padding: 10px 0; }
        .details td:first-child { color: #64748b; width: 42%; }
        .total { background: #f0fdfa; color: #115e59; font-size: 15px; font-weight: bold; margin-top: 22px; padding: 14px; }
        .status { background: #dcfce7; border-radius: 10px; color: #166534; display: inline-block; font-size: 10px; font-weight: bold; margin-top: 22px; padding: 6px 12px; }
        .footer { border-top: 1px solid #e2e8f0; color: #94a3b8; font-size: 9px; margin-top: 55px; padding-top: 14px; text-align: center; }
    </style>
</head>
<body>
    @php
        $company = $invoice->student->company;
        $companyName = $company?->nama_syarikat ?: ($company?->user?->name ?? '-');
        $logoPath = $company?->logo_syarikat ? public_path('uploads/' . $company->logo_syarikat) : null;
        $logoDataUri = null;

        if ($logoPath && file_exists($logoPath)) {
            $mimeType = mime_content_type($logoPath) ?: 'image/png';
            $logoDataUri = 'data:' . $mimeType . ';base64,' . base64_encode(file_get_contents($logoPath));
        }
    @endphp

    <table class="header">
        <tr>
            <td style="width:105px; vertical-align:top;">
                @if($logoDataUri)
                    <img class="logo" src="{{ $logoDataUri }}" alt="{{ $companyName }}">
                @endif
            </td>
            <td>
                <div class="company-name">{{ $companyName }}</div>
                <div class="muted">
                    @if($company?->nombor_pendaftaran_syarikat)No. Pendaftaran: {{ $company->nombor_pendaftaran_syarikat }}<br>@endif
                    @if($company?->alamat){{ $company->alamat }}<br>@endif
                    {{ collect([$company?->email_syarikat, $company?->no_telefon, $company?->link])->filter()->implode(' | ') }}
                </div>
            </td>
        </tr>
    </table>

    <div class="receipt-title">RESIT PEMBAYARAN</div>
    <div class="meta">No. Resit / Invois: {{ $invoice->nomor_invoice }}</div>

    <table class="details">
        <tr><td>Nama Pelajar</td><td>{{ $invoice->student->user->name ?? '-' }}</td></tr>
        <tr><td>Kursus</td><td>{{ $invoice->course?->localized_name ?? $invoice->course?->nama_kos ?? '-' }}</td></tr>
        <tr><td>Tarikh Bayaran</td><td>{{ $invoice->payment?->dibayar_pada ? \Carbon\Carbon::parse($invoice->payment->dibayar_pada)->format('d/m/Y') : \Carbon\Carbon::parse($invoice->updated_at)->format('d/m/Y') }}</td></tr>
        <tr><td>Status</td><td>Telah Dibayar</td></tr>
    </table>

    <div class="total">Jumlah Dibayar: RM {{ number_format((float) $invoice->jumlah, 2) }}</div>
    <div class="status">PEMBAYARAN BERJAYA</div>

    <div class="footer">Ini ialah resit yang dijana secara elektronik oleh {{ $companyName }}.</div>
</body>
</html>
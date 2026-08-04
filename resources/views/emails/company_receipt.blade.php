<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #f0f0f0; border-radius: 8px; }
        .header { text-align: center; margin-bottom: 30px; }
        .receipt-box { background: #f9f9f9; padding: 20px; border-radius: 8px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        .footer { text-align: center; font-size: 12px; color: #999; margin-top: 30px; }
        .status-paid { color: #2dce89; font-weight: bold; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color: #444;">{{ config('app.name', 'eMindTutor') }}</h1>
            <p>Payment Confirmation</p>
        </div>

        <div>
            {!! app(\App\Services\EmailTemplateRenderer::class)->render($templateBody, $templateVariables) !!}
        </div>

        <div class="receipt-box">
            <div class="row">
                <span>ID Transaksi:</span>
                <strong>{{ $invoice->external_id ?? '-' }}</strong>
            </div>
            <div class="row">
                <span>Paket:</span>
                <strong>{{ $invoice->package?->nama_pakej ?? 'Langganan' }}</strong>
            </div>
            <div class="row">
                <span>Jumlah Bayar:</span>
                <strong>RM {{ number_format((float) ($invoice->jumlah ?? 0), 2) }}</strong>
            </div>
            <div class="row">
                <span>Status:</span>
                <span class="status-paid">LUNAS</span>
            </div>
            <div class="row">
                <span>Berlaku Sampai:</span>
                <strong>{{ \Carbon\Carbon::parse($invoice->tarikh_tamat)->format('d M Y') }}</strong>
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} eMindTutor SaaS System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <style>
        .card { font-family: sans-serif; border: 1px solid #ddd; padding: 20px; max-width: 600px; margin: auto; }
        .btn { background: #e3342f; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 15px; }
        .footer { font-size: 12px; color: #777; margin-top: 20px; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Peringatan Langganan Tamat</h2>
        <p>Halo <strong>{{ $inv->company->name }}</strong>,</p>
        
        <p>Kami ingin memberitahukan bahwa masa langganan paket <strong>{{ $inv->package->name ?? 'SaaS Plan' }}</strong> Anda telah berakhir pada hari ini, {{ date('d M Y') }}.</p>

        <p>Demi menjaga kelancaran operasional syarikat Anda, silakan lakukan pembaharuan langganan segera. Saat ini, akses ke fitur sistem telah dinonaktifkan sementara.</p>

        <a href="{{ url('/login') }}" class="btn">Perbaharui Langganan Sekarang</a>

        <div class="footer">
            <p>Terima kasih telah menggunakan eMindTutor.</p>
            <p>Ini adalah email otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <style>
        .container { font-family: sans-serif; padding: 20px; color: #333; }
        .header { border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        .details { margin: 20px 0; border: 1px solid #eee; padding: 15px; border-radius: 8px; }
        .btn { background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; }
        .footer { font-size: 12px; color: #777; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Halo, {{ $invoice->student->user->name }}!</h2>
        </div>
        
        <p>Masa aktif kursus Anda telah berakhir. Berikut adalah detail tagihan pembaruan untuk tetap dapat mengakses kelas:</p>

        <div class="details">
            <p><strong>Nomor Invoice:</strong> {{ $invoice->nomor_invoice }}</p>
            <p><strong>Kursus:</strong> {{ $invoice->course->name }}</p>
            <p><strong>Jumlah:</strong> Rp {{ number_format($invoice->jumlah, 0, ',', '.') }}</p>
            <p><strong>Jatuh Tempo:</strong> {{ \Carbon\Carbon::parse($invoice->tarikh_tamat)->format('d M Y') }}</p>
        </div>

        <p>Silakan lakukan pembayaran sebelum tanggal jatuh tempo. Jika sudah membayar, silakan upload bukti pembayaran di dashboard Anda.</p>
        
        <a href="{{ url('/dashboard') }}" class="btn">Buka Dashboard</a>

        <div class="footer">
            <p>Pesan ini dihasilkan secara otomatis oleh sistem. Jangan membalas email ini.</p>
        </div>
    </div>
</body>
</html>
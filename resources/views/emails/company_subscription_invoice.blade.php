<p>Assalamualaikum {{ $invoice->company?->nama_wakil_syarikat ?? $invoice->company?->user?->name }},</p>

<p>Invoice langganan eMindTutor telah dijana.</p>

<ul>
    <li><strong>No. Invoice:</strong> {{ $invoice->nomor_invoice }}</li>
    <li><strong>Pakej:</strong> {{ $invoice->package?->nama_pakej ?? 'Langganan' }}</li>
    <li><strong>Jumlah:</strong> RM {{ number_format((float) $invoice->jumlah, 2) }}</li>
    <li><strong>Status:</strong> {{ ucfirst($invoice->status_bayaran) }}</li>
</ul>

@if($invoice->payment_url)
    <p><a href="{{ $invoice->payment_url }}">Bayar invoice langganan</a></p>
@endif

<p>Terima kasih,<br>{{ config('app.name', 'eMindTutor') }}</p>
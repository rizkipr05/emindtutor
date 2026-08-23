<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8"><title>{{ app()->getLocale() === 'my' ? 'Invois' : 'Invoice' }} {{ $invoice->nomor_invoice }}</title>
    <style>
        @page { margin: 14mm; } body { font-family: DejaVu Sans, sans-serif; color:#17213d; font-size:9px; line-height:1.48; }
        table { border-collapse:collapse; width:100%; } .top td { vertical-align:top; } .brand-row { height:92px; border-bottom:2px solid #d4b2e7; }
        .tagline { margin-top:4px; color:#5d6575; font-size:6px; font-weight:bold; letter-spacing:.4px; }
        .org { margin-top:15px; font-size:10px; font-weight:bold; } .contact { color:#4c5569; line-height:1.7; }
        .invoice-title { color:#2d1162; font-size:33px; font-weight:bold; letter-spacing:1px; text-align:right; } .invoice-number { float:right; margin-top:6px; border-radius:3px; background:#30116d; color:#fff; padding:7px 15px; font-weight:bold; }
        .grid { margin-top:15px; border-spacing:12px 0; margin-left:-12px; width:calc(100% + 24px); } .grid td { width:50%; vertical-align:top; }
        .info-box { border:1px dashed #99bbb7; border-radius:6px; padding:12px; min-height:91px; } .recipient { border-color:#d9a1d5; } .box-heading { color:#8a1194; font-size:10px; font-weight:bold; margin-bottom:6px; }
        .key { color:#667085; display:inline-block; width:82px; } .badge { background:{{ $invoice->status_bayaran === 'paid' ? '#d8f6e5' : '#fff1c8' }}; color:{{ $invoice->status_bayaran === 'paid' ? '#087b4e' : '#8f6400' }}; border-radius:10px; padding:3px 9px; font-size:8px; font-weight:bold; }
        .items { margin-top:16px; } .items th { background:#c80070; color:#fff; padding:9px 8px; font-size:8px; text-align:left; } .items td { padding:11px 8px; border:1px solid #eee3ea; vertical-align:top; } .right { text-align:right !important; }
        .below { margin-top:16px; border-spacing:12px 0; margin-left:-12px; width:calc(100% + 24px); } .below td { width:50%; vertical-align:top; } .payment { border:1px solid #e5d588; background:#fffdf1; border-radius:6px; padding:11px; } .notes { margin-top:10px; border:1px solid #d8c8ed; background:#fbf8ff; border-radius:6px; padding:11px; }
        .summary { border:1px dashed #ce9ac8; border-radius:6px; padding:12px; } .summary div { padding:4px 0; } .summary .grand { border-top:1px solid #d3b2ca; margin-top:6px; padding-top:10px; font-size:15px; font-weight:bold; }
        .balance { margin-top:10px; padding:12px; border-radius:6px; background:#dff8ef; color:#087861; font-size:15px; font-weight:bold; } .balance strong { float:right; padding:5px 12px; margin-top:-5px; border-radius:3px; background:#087d82; color:#fff; }
        .footer { margin-top:16px; text-align:center; border-top:1px solid #d9dce5; padding-top:9px; color:#697386; font-size:7px; }
    </style>
</head>
<body>
@php
    $my = app()->getLocale() === 'my';
    $company = $invoice->company;
    $brand = $invoiceSettings;
    $title = $my ? 'INVOIS' : 'INVOICE';
    $desc = $invoice->description ?: ($invoice->package ? $invoice->package->nama_pakej : ($my ? 'Caj langganan' : 'Subscription charge'));
    $status = $invoice->status_bayaran === 'paid' ? ($my ? 'DIBAYAR' : 'PAID') : ($my ? 'BELUM DIBAYAR' : 'UNPAID');
    $issueDate = $invoice->tarikh_terbit ?? $invoice->created_at;
    $logoPath = null;
    if (filled($brand['logo'])) {
        $logoPath = str_starts_with($brand['logo'], 'storage/')
            ? storage_path('app/public/' . str($brand['logo'])->after('storage/'))
            : public_path($brand['logo']);
    }
    $logoSource = is_string($logoPath) && is_file($logoPath) ? 'data:' . (mime_content_type($logoPath) ?: 'image/png') . ';base64,' . base64_encode(file_get_contents($logoPath)) : null;
@endphp
<table class="top brand-row"><tr><td>
    @if($logoSource)<img src="{{ $logoSource }}" style="height:52px;max-width:220px;object-fit:contain;vertical-align:middle">@endif
    @if(filled($brand['tagline']))<div class="tagline">{{ $brand['tagline'] }}</div>@endif
    <div class="org">{{ $brand['company_name'] }}</div><div class="contact">{!! nl2br(e($brand['address'])) !!}<br>☎ {{ $brand['phone'] }} &nbsp;•&nbsp; ✉ {{ $brand['email'] }} &nbsp;•&nbsp; {{ $brand['website'] }}</div>
</td><td><div class="invoice-title">{{ $title }}</div><div class="invoice-number">{{ $invoice->nomor_invoice }}</div></td></tr></table>

<table class="grid"><tr><td><div class="info-box recipient"><div class="box-heading">{{ $my ? 'INVOIS KEPADA' : 'INVOICE TO' }}</div><strong>{{ $company?->user?->name }}</strong><br>{{ $company?->alamat ?: '-' }}<br><br>☎ {{ $company?->no_telefon ?: '-' }}<br>✉ {{ $company?->user?->email ?: $company?->email_syarikat }}</div></td><td><div class="info-box"><div><span class="key">{{ $my ? 'No. Invois' : 'Invoice No.' }}</span>: {{ $invoice->nomor_invoice }}</div><div><span class="key">{{ $my ? 'Tarikh' : 'Date' }}</span>: {{ \Carbon\Carbon::parse($issueDate)->format('d M Y') }}</div><div><span class="key">{{ $my ? 'Tarikh Akhir' : 'Due Date' }}</span>: {{ $invoice->tarikh_tamat ? \Carbon\Carbon::parse($invoice->tarikh_tamat)->format('d M Y') : '-' }}</div><div><span class="key">{{ $my ? 'Status' : 'Status' }}</span>: <span class="badge">{{ $status }}</span></div></div></td></tr></table>

<table class="items"><thead><tr><th>{{ $my ? 'PERINCIAN' : 'DESCRIPTION' }}</th><th class="right">QTY</th><th class="right">{{ $my ? 'HARGA UNIT (RM)' : 'UNIT PRICE (RM)' }}</th><th class="right">{{ $my ? 'JUMLAH (RM)' : 'AMOUNT (RM)' }}</th></tr></thead><tbody><tr><td><strong>{{ $desc }}</strong><br><span style="color:#697386">{{ $invoice->invoice_type === 'manual' ? ($my ? 'Caj tambahan manual.' : 'Additional manual charge.') : ($my ? 'Caj langganan platform.' : 'Platform subscription charge.') }}</span></td><td class="right">1</td><td class="right">{{ number_format($invoice->jumlah,2) }}</td><td class="right">{{ number_format($invoice->jumlah,2) }}</td></tr></tbody></table>

<table class="below"><tr><td><div class="payment"><strong>{{ $my ? 'ARAHAN PEMBAYARAN' : 'PAYMENT INSTRUCTIONS' }}</strong><br>{!! nl2br(e($brand['payment_note'])) !!}<br><strong>{{ $brand['company_name'] }}</strong><br>{{ $brand['bank_name'] }}<br>{{ $brand['bank_account'] }}</div><div class="notes"><strong>{{ $my ? 'NOTA PENTING' : 'IMPORTANT NOTES' }}</strong><br>{!! nl2br(e($brand['important_note'])) !!}</div></td><td><div class="summary"><div>{{ $my ? 'SUBJUMLAH' : 'SUBTOTAL' }} <span style="float:right">RM {{ number_format($invoice->jumlah,2) }}</span></div><div>{{ $my ? 'BAYARAN DITERIMA' : 'PAYMENT RECEIVED' }} <span style="float:right">RM {{ number_format($paidAmount,2) }}</span></div><div class="grand">{{ $my ? 'JUMLAH' : 'TOTAL' }} <span style="float:right">RM {{ number_format($invoice->jumlah,2) }}</span></div></div><div class="balance">{{ $my ? 'BAKI PERLU DIBAYAR' : 'BALANCE DUE' }} <strong>RM {{ number_format($balanceDue,2) }}</strong></div></td></tr></table>
<div class="footer">{{ $my ? 'Ini adalah dokumen janaan komputer. Tiada tandatangan diperlukan.' : 'This is a computer-generated document. No signature is required.' }}<br>{{ $brand['company_name'] }} · {{ $brand['email'] }} · {{ $brand['website'] }}</div>
</body></html>

@extends('layouts.admin')

@section('title', 'eMindTutor | Rekod Akaun Pelajar')

@section('content')
@php($credit = $accountBalance < 0)
<div class="mx-auto max-w-6xl px-4 py-7">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <a href="{{ route('admin.students.show', $student->id) }}" class="text-sm text-slate-500 hover:text-slate-800">← Kembali ke Maklumat Pelajar</a>
            <h1 class="mt-2 text-2xl font-bold text-slate-900">Rekod Akaun</h1>
            <p class="text-sm text-slate-500">{{ $student->user?->name }} · Ringkasan invoice dan pembayaran pelajar.</p>
        </div>
        <div class="rounded-xl bg-emerald-50 px-7 py-3 text-center">
            <p class="text-xs font-medium text-slate-600">Baki Semasa</p>
            <p class="text-2xl font-bold {{ $credit ? 'text-emerald-600' : 'text-red-600' }}">{{ $accountBalance < 0 ? '-' : '' }}RM {{ number_format(abs($accountBalance), 2) }}</p>
            <p class="text-xs {{ $credit ? 'text-emerald-700' : 'text-slate-600' }}">{{ $credit ? 'Kredit pelajar' : 'Baki perlu dibayar' }}</p>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-xl border border-blue-100 bg-blue-50 p-5"><p class="text-sm text-slate-500">Jumlah Invoice</p><p class="mt-1 text-xl font-bold">RM {{ number_format($totalInvoiceAmount, 2) }}</p></div>
        <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-5"><p class="text-sm text-slate-500">Jumlah Pembayaran</p><p class="mt-1 text-xl font-bold">RM {{ number_format($totalPaymentAmount, 2) }}</p></div>
        <div class="rounded-xl border border-purple-100 bg-purple-50 p-5"><p class="text-sm text-slate-500">Baki Akaun</p><p class="mt-1 text-xl font-bold {{ $credit ? 'text-emerald-600' : 'text-red-600' }}">{{ $accountBalance < 0 ? '-' : '' }}RM {{ number_format(abs($accountBalance), 2) }}</p></div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <section class="overflow-hidden rounded-xl border bg-white">
                <div class="flex items-center justify-between border-b p-4"><h2 class="font-bold">Invoice Dikeluarkan</h2><a href="{{ route('admin.students.show', $student->id) }}#reviews-content" class="rounded border border-blue-300 px-3 py-2 text-sm text-blue-700 hover:bg-blue-50">+ Buat Invoice Manual</a></div>
                <div class="overflow-x-auto"><table class="w-full text-sm"><thead class="bg-slate-50 text-left text-xs text-slate-500"><tr><th class="p-3">Tarikh</th><th class="p-3">No. Invoice</th><th class="p-3">Kursus</th><th class="p-3 text-right">Jumlah</th><th class="p-3">Status</th></tr></thead><tbody>
                    @forelse($accountInvoices as $invoice)
                    <tr class="border-t"><td class="p-3">{{ optional($invoice->tarikh_terbit)->format('d M Y') ?? $invoice->created_at->format('d M Y') }}</td><td class="p-3 font-medium">{{ $invoice->nomor_invoice }} <a href="{{ route('admin.students.invoices.receipt', [$student->id, $invoice->id]) }}" class="ml-1 text-xs text-blue-600">PDF</a></td><td class="p-3">{{ $invoice->course?->nama_kos ?? '-' }}</td><td class="p-3 text-right">RM {{ number_format($invoice->jumlah, 2) }}</td><td class="p-3"><span class="rounded-full px-2 py-1 text-xs {{ $invoice->status_bayaran === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ $invoice->status_bayaran === 'paid' ? 'Paid' : ucfirst($invoice->status_bayaran) }}</span></td></tr>
                    @empty<tr><td colspan="5" class="p-5 text-center text-slate-500">Tiada invoice direkodkan.</td></tr>@endforelse
                </tbody></table></div>
            </section>
            <section class="overflow-hidden rounded-xl border bg-white">
                <div class="border-b p-4"><h2 class="font-bold">Sejarah Pembayaran</h2></div>
                <div class="overflow-x-auto"><table class="w-full text-sm"><thead class="bg-slate-50 text-left text-xs text-slate-500"><tr><th class="p-3">Tarikh</th><th class="p-3">Rujukan</th><th class="p-3">Metode / Bukti</th><th class="p-3 text-right">Jumlah</th></tr></thead><tbody>
                    @forelse($accountInvoices->where('status_bayaran', 'paid') as $invoice)
                    <tr class="border-t"><td class="p-3">{{ optional($invoice->payment?->dibayar_pada)->format('d M Y') ?? optional($invoice->tarikh_terbit)->format('d M Y') }}</td><td class="p-3 font-medium">{{ $invoice->nomor_invoice }}</td><td class="p-3">@if($invoice->payment?->bukti_bayar)<a href="{{ asset('storage/' . $invoice->payment->bukti_bayar) }}" target="_blank" class="text-blue-600 hover:underline">Bukti pembayaran</a>@else<span class="text-slate-500">Catatan manual</span>@endif</td><td class="p-3 text-right font-medium text-emerald-700">RM {{ number_format($invoice->jumlah, 2) }}</td></tr>
                    @empty<tr><td colspan="4" class="p-5 text-center text-slate-500">Tiada pembayaran berjaya direkodkan.</td></tr>@endforelse
                </tbody></table></div>
            </section>
        </div>
        <aside class="rounded-xl bg-slate-50 p-5"><h2 class="font-bold">Aliran Akaun</h2><div class="mt-4 space-y-4 border-l-2 border-slate-200 pl-4">
            @forelse($accountInvoices->take(10) as $invoice)
            <div><p class="text-xs text-slate-500">{{ optional($invoice->tarikh_terbit)->format('d M Y') }} · Invoice</p><p class="font-medium">{{ $invoice->nomor_invoice }}</p><p>+ RM {{ number_format($invoice->jumlah, 2) }}</p>@if($invoice->status_bayaran === 'paid')<p class="mt-1 text-sm text-emerald-700">− RM {{ number_format($invoice->jumlah, 2) }} · Dibayar</p>@endif</div>
            @empty<p class="text-sm text-slate-500">Belum ada rekod transaksi.</p>@endforelse
        </div></aside>
    </div>
</div>
@endsection
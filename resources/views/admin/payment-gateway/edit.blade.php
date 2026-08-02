@extends('layouts.admin')

@section('title', 'Payment Gateway')

@section('content')
<div class="mx-auto max-w-3xl rounded-2xl bg-white p-8 shadow">
    <h1 class="text-2xl font-bold text-slate-900">Payment Gateway Perusahaan</h1>
    <p class="mt-2 text-slate-600">Pembayaran pelajar untuk {{ $company->nama_wakil_syarikat }} akan dibuat melalui akun ToyyibPay ini dan dana masuk langsung ke rekening perusahaan.</p>
    @if(session('success'))
        <div class="mt-5 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">{{ session('success') }}</div>
    @endif
    @if($gateway && $gateway->is_active)
        <div class="mt-5 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
            <strong>✓ Konfigurasi tersimpan dan aktif.</strong> Secret Key tersimpan terenkripsi. Terakhir diperbarui: {{ $gateway->updated_at->format('d M Y, H:i') }}.
        </div>
    @else
        <div class="mt-5 rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">Payment Gateway belum dikonfigurasi atau belum aktif.</div>
    @endif
    <form method="POST" action="{{ route('admin.payment-gateway.update') }}" class="mt-7">
        @csrf
        @method('PUT')
        <x-payment-gateway-form :gateway="$gateway" />
        <button class="mt-6 rounded-lg bg-teal-600 px-6 py-2 font-semibold text-white hover:bg-teal-700">Simpan Payment Gateway</button>
    </form>
</div>
@endsection

@extends('layouts.auth')

@section('content')
<div class="flex min-h-[60vh] items-center justify-center p-8">
  <div class="w-full max-w-lg rounded-2xl bg-white p-8 text-center shadow-lg">
    @if($registration->status === 'paid')
      <h1 class="text-2xl font-bold text-emerald-700">Pembayaran Berhasil</h1>
      <p class="mt-3 text-slate-600">Akun perusahaan telah dibuat. Silakan login menggunakan email yang didaftarkan.</p>
      <a class="relative z-10 mt-6 inline-flex items-center justify-center rounded-lg px-5 py-3 font-semibold shadow-sm transition hover:opacity-90" style="background-color:#1d4ed8;color:#ffffff" href="{{ route('login') }}">Login</a>
    @elseif($returnStatus === '3')
      <h1 class="text-2xl font-bold text-rose-700">Pembayaran Belum Berhasil</h1>
      <p class="mt-3 text-slate-600">Transaksi dibatalkan atau gagal. Akun perusahaan belum dibuat dan Anda dapat mencoba membayar kembali.</p>
      @if($registration->payment_url)
        <a class="relative z-10 mt-6 inline-flex items-center justify-center rounded-lg px-5 py-3 font-semibold shadow-sm transition hover:opacity-90" style="background-color:#1d4ed8;color:#ffffff" href="{{ $registration->payment_url }}">Coba Bayar Lagi</a>
      @endif
    @else
      <h1 class="text-2xl font-bold text-amber-700">Menunggu Konfirmasi Pembayaran</h1>
      <p class="mt-3 text-slate-600">Akun akan dibuat otomatis setelah pembayaran ToyyibPay terkonfirmasi.</p>
    @endif
  </div>
</div>
@endsection

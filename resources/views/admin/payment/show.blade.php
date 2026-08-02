@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl shadow-lg p-8 mt-10">
    <h2 class="text-2xl font-bold text-blue-900 mb-6">
        {{ __('admin/payment/show.header.title') }}
    </h2>

    <div class="mb-6">
        <p><strong>{{ __('admin/payment/show.package.label') }}:</strong> {{ $package->nama_pakej }}</p>
        <p><strong>{{ __('admin/payment/show.package.price') }}:</strong> RM {{ number_format($package->harga, 2) }}</p>
    </div>

    <div class="border-t pt-4">
        <p><strong>{{ __('admin/payment/show.invoice.no') }}:</strong> {{ $invoice->nomor_invoice }}</p>
        <p><strong>{{ __('admin/payment/show.invoice.amount') }}:</strong> RM {{ number_format($invoice->jumlah, 2) }}</p>
        <p><strong>{{ __('admin/payment/show.invoice.status') }}:</strong> 
            @if (strtolower($invoice->status_bayaran) == 'paid')
                <span class="text-green-600 font-semibold">PAID</span>
            @elseif (strtolower($invoice->status_bayaran) == 'expired')
                <span class="text-red-600 font-semibold">EXPIRED</span>
            @else
                <span class="text-yellow-600 font-semibold">PENDING</span>
            @endif
        </p>
    </div>

    <div class="mt-6 flex justify-end space-x-3">
        @if(strtolower($invoice->status_bayaran) != 'paid')
            <a href="{{ $invoice->payment_url }}" target="_blank" rel="noopener"
               class="bg-blue-900 text-white px-6 py-2 rounded-lg hover:bg-blue-800 transition">
              {{ __('admin/payment/show.buttons.pay_now') }}
            </a>
        @endif

        <a href="{{ route('a.dashboard.index') }}"
           class="bg-gray-200 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
           {{ __('admin/payment/show.buttons.back') }}
        </a>
    </div>
</div>
@endsection

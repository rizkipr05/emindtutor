@extends('layouts.admin')

@section('title', __('admin/payment/index.title'))

@section('content')
<div class="container mx-auto px-4 py-10">
    <div class="max-w-lg mx-auto bg-white rounded-2xl shadow-lg p-8">
    <h2 class="text-2xl font-bold mb-6 text-center">{{ __('admin/payment/index.header.title') }}</h2>

        <!-- Paket yang dipilih -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">{{ __('admin/payment/index.header.selected_package') }}</h3>
            <div class="border rounded-xl p-4 bg-gray-50 space-y-2">
                <p class="font-bold text-gray-800 text-xl">
                    {{ $package->nama_pakej }}
                    @if($package->is_trial)
                        <span class="ml-2 px-2 py-1 text-xs rounded-full bg-green-500 text-white">{{ __('admin/payment/index.package.trial_badge') }}</span>
                    @endif
                </p>
                
                <p class="text-gray-600">
                    {{ __('admin/payment/index.package.price') }}: 
                    <span class="font-semibold text-gray-900">
                        RM {{ number_format($package->harga, 2) }}
                    </span>
                </p>
                
                <p class="text-gray-600">
                    {{ __('admin/payment/index.package.duration') }}: 
                    <span class="font-semibold text-gray-900">{{ $package->duration_days }} {{ __('admin/payment/index.package.duration_unit') }}</span>
                </p>

                <p class="text-gray-600">
                    {{ __('admin/payment/index.package.max_students') }}: <span class="font-semibold">{{ $package->maks_pelajar }}</span>
                </p>
                
                <p class="text-gray-600">
                    {{ __('admin/payment/index.package.max_teachers') }}: <span class="font-semibold">{{ $package->maks_guru }}</span>
                </p>
                
                <p class="text-gray-600">
                    {{ __('admin/payment/index.package.max_classes') }}: <span class="font-semibold">{{ $package->maks_kelas }}</span>
                </p>

                @if($package->deskripsi)
                    <div class="mt-3">
                        <h4 class="text-sm font-semibold text-gray-700 mb-1">{{ __('admin/payment/index.header.description') }}</h4>
                        <ul class="list-disc list-inside text-gray-600 text-sm space-y-1">
                            @foreach(explode("\n", $package->deskripsi) as $line)
                                @if(!empty(trim($line)))
                                    <li>{{ $line }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>


        <!-- Form pembayaran -->
        <form action="{{ route('payment.show', $package->id) }}" method="POST">
            @csrf

            <!-- Hidden package_id -->
            <input type="hidden" name="package_id" value="{{ $package->id }}">

            <!-- Diskon -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2">{{ __('admin/payment/index.discount.title') }}</h3>

                <!-- Input manual kode -->
                <div class="mb-3">
                    <label for="kode_diskon" class="block text-sm font-medium text-gray-700">{{ __('admin/payment/index.discount.enter_code') }}</label>
                    <input type="text" name="kode_diskon" id="kode_diskon" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="e.g. SAVE20">
                </div>

                <!-- Pilih dari daftar -->
                @if(isset($discounts) && $discounts->count() > 0)
                    <div class="mt-3">
                        <label for="select_diskon" class="block text-sm font-medium text-gray-700">{{ __('admin/payment/index.discount.select_label') }}</label>
                        <select name="kode_diskon" id="select_diskon" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">{{ __('admin/payment/index.discount.choose_placeholder') }}</option>
                            @foreach($discounts as $discount)
                                <option value="{{ $discount->kode_diskon }}">
                                    {{ $discount->nama_diskon }}
                                    @if($discount->percent)
                                        - {{ $discount->percent }}%
                                    @elseif($discount->potongan)
                                        - RM {{ number_format($discount->potongan, 2) }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">{{ __('admin/payment/index.discount.valid_until', ['date' => $discount->end_date]) }}</p>
                    </div>
                @endif
            </div>

            <!-- Tombol -->
            <div class="flex justify-between items-center">
                <a href="{{ route('a.dashboard.index') }}" class="px-4 py-2 rounded-xl border text-gray-600 hover:bg-gray-100">
                    {{ __('admin/payment/index.buttons.cancel') }}
                </a>
                <button type="submit" class="px-6 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
                    {{ __('admin/payment/index.buttons.pay') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
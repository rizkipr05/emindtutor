@extends('layouts.auth')

@section('content')
  @php
    $basePath = rtrim(request()->getBaseUrl(), '/');
    $loginUrl = ($basePath ?: '') . '/login';
  @endphp
  <!-- Container -->
  <div class="flex items-center justify-center p-8 w-full">
    <div class="w-full max-w-3xl bg-white shadow-lg rounded-2xl p-12">

      <!-- Header -->
  <h2 class="text-3xl font-bold text-center text-blue-900">{{ __('compro/auth.signup_title') }}</h2>
  <p class="text-center text-gray-500 mt-2">{{ __('compro/auth.signup_subtext') }}</p>

      <!-- Form -->
      <form action="{{ route('register_post') }}" method="POST" class="mt-8 space-y-6">
        @csrf

        <!-- Tampilkan error global -->
        @if ($errors->any())
          <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
            <ul class="list-disc pl-5">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <fieldset>
          <legend class="text-lg font-bold text-blue-900">Pilih Paket Langganan</legend>
          <p class="mt-1 text-sm text-gray-500">Pilih paket terlebih dahulu. Setelah data diisi, paket berbayar akan diarahkan ke pembayaran ToyyibPay.</p>

          @if($packages->isEmpty())
            <div class="mt-3 rounded-lg bg-red-50 p-3 text-sm text-red-700">
              Belum ada paket langganan aktif. Silakan hubungi Super Admin.
            </div>
          @else
            <div class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-{{ min(3, max(1, $packages->count())) }}">
              @foreach($packages as $package)
                @php($isSelected = (string) $selectedPackageId === (string) $package->id)
                <label data-package-card class="relative block cursor-pointer rounded-xl border-2 p-4 transition hover:border-blue-500 {{ $isSelected ? 'border-blue-700 bg-blue-50' : 'border-gray-200 bg-white' }}">
                  <input
                    type="radio"
                    name="package_id"
                    value="{{ $package->id }}"
                    class="sr-only"
                    required
                    {{ $isSelected ? 'checked' : '' }}
                    onchange="document.querySelectorAll('[data-package-card]').forEach(card => { card.classList.remove('border-blue-700', 'bg-blue-50'); card.classList.add('border-gray-200', 'bg-white'); }); this.closest('[data-package-card]').classList.remove('border-gray-200', 'bg-white'); this.closest('[data-package-card]').classList.add('border-blue-700', 'bg-blue-50');"
                  >
                  <span class="contents">
                    @if($package->is_trial)
                      <span class="mb-2 inline-block rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700">Percubaan</span>
                    @endif
                    <span class="block font-bold text-gray-900">{{ $package->nama_pakej }}</span>
                    <span class="mt-1 block text-xl font-bold text-blue-800">RM {{ number_format($package->harga, 2) }}</span>
                    <span class="mt-1 block text-sm text-gray-500">
                      @if($package->pricing_per_student)
                        {{ $package->duration_days }} hari · Harga per student aktif
                      @else
                        {{ $package->duration_days }} hari · Maks. {{ $package->maks_pelajar }} pelajar
                      @endif
                    </span>
                  </span>
                </label>
              @endforeach
            </div>
          @endif
          @error('package_id')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </fieldset>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Name -->
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">{{ __('compro/auth.company_name') }}</label>
            <input type="text" name="name" id="name" required
              class="mt-2 w-full rounded-xl border-gray-300 focus:ring-blue-900 focus:border-blue-900 px-4 py-3 shadow-sm">
            @error('name')
              <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Email -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">{{ __('compro/auth.login_email') }}</label>
            <input type="email" name="email" id="email" required
              class="mt-2 w-full rounded-xl border-gray-300 focus:ring-blue-900 focus:border-blue-900 px-4 py-3 shadow-sm">
            @error('email')
              <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Password -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">
              {{ __('compro/auth.login_password') }}
            </label>
            <input type="password" name="password" id="password" required
              class="mt-2 w-full rounded-xl border-gray-300 focus:ring-blue-900 focus:border-blue-900 px-4 py-3 shadow-sm @error('password') border-red-500 @enderror">
            @error('password')
              <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Confirm Password -->
          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
              Confirm Password
            </label>
            <input type="password" name="password_confirmation" id="password_confirmation" required
              class="mt-2 w-full rounded-xl border-gray-300 focus:ring-blue-900 focus:border-blue-900 px-4 py-3 shadow-sm @error('password_confirmation') border-red-500 @enderror">
            @error('password_confirmation')
              <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
          </div>




          <!-- Nama Wakil Syarikat -->
          <div>
            <label for="nama_wakil_syarikat" class="block text-sm font-medium text-gray-700">{{ __('compro/auth.company_rep_name') }}</label>
            <input type="text" name="nama_wakil_syarikat" id="nama_wakil_syarikat" required
              class="mt-2 w-full rounded-xl border-gray-300 focus:ring-blue-900 focus:border-blue-900 px-4 py-3 shadow-sm">
            @error('nama_wakil_syarikat')
              <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Nomor telefon Wakil Syarikat -->
          <div>
            <label for="pic_telefon" class="block text-sm font-medium text-gray-700">Nomor Telefon Wakil Syarikat</label>
            <input type="tel" name="pic_telefon" id="pic_telefon" required value="{{ old('pic_telefon', '60') }}" inputmode="numeric" pattern="60[0-9]{7,11}" title="Gunakan format nomor Malaysia, contoh 60123456789"
              class="mt-2 w-full rounded-xl border-gray-300 focus:ring-blue-900 focus:border-blue-900 px-4 py-3 shadow-sm">
            <p class="text-xs text-gray-500 mt-1">Gunakan awalan 60, contoh: 60123456789.</p>
            @error('pic_telefon')
              <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Alamat (biar full width) -->
          <div class="md:col-span-2">
            <label for="alamat" class="block text-sm font-medium text-gray-700">{{ __('compro/auth.address') }}</label>
            <textarea name="alamat" id="alamat" rows="3" required
              class="mt-2 w-full rounded-xl border-gray-300 focus:ring-blue-900 focus:border-blue-900 px-4 py-3 shadow-sm"></textarea>
            @error('alamat')
              <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <!-- Submit -->
        <div class="pt-6">
            <button type="submit"
            class="w-full bg-blue-900 text-white py-3 px-6 rounded-full font-semibold hover:bg-blue-800 transition">
            {{ __('compro/auth.signup_submit') }}
          </button>
        </div>

        <!-- Login Link -->
        <p class="text-center text-sm text-gray-500 mt-4">
          Already have an account?
          <a href="{{ $loginUrl }}" class="text-blue-900 font-semibold hover:underline">{{ __('compro/auth.login_header') }}</a>
        </p>
      </form>
    </div>
  </div>
@endsection

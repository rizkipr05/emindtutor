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
            <input type="text" name="pic_telefon" id="pic_telefon" required
              class="mt-2 w-full rounded-xl border-gray-300 focus:ring-blue-900 focus:border-blue-900 px-4 py-3 shadow-sm">
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

          <!-- No Telefon -->
          <div>
            <label for="no_telefon" class="block text-sm font-medium text-gray-700">{{ __('compro/auth.phone') }}</label>
            <input type="text" name="no_telefon" id="no_telefon" required
              class="mt-2 w-full rounded-xl border-gray-300 focus:ring-blue-900 focus:border-blue-900 px-4 py-3 shadow-sm">
            @error('no_telefon')
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

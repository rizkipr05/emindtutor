@extends('layouts.superadmin')

@section('content')
  <div class="flex items-center justify-center p-8 w-full">
    <div class="w-full max-w-3xl bg-white shadow-lg rounded-2xl p-12">

      <!-- Header -->
      <h2 class="text-3xl font-bold text-center text-blue-900">Tambah Company Baru</h2>
      <p class="text-center text-gray-500 mt-2">Isi informasi untuk menambah company</p>

      <!-- Form -->
      <form action="{{ route('companies.store') }}" method="POST" class="mt-8 space-y-6">
        @csrf

        <!-- Error -->
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

          <!-- Nama Syarikat -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Nama Syarikat</label>
            <input type="text" name="nama" required
              class="mt-2 w-full rounded-xl border-gray-300 px-4 py-3 shadow-sm focus:ring-blue-900 focus:border-blue-900">
          </div>

          <!-- Email -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" required
              class="mt-2 w-full rounded-xl border-gray-300 px-4 py-3 shadow-sm focus:ring-blue-900 focus:border-blue-900">
          </div>

          <!-- Password -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" required
              class="mt-2 w-full rounded-xl border-gray-300 px-4 py-3 shadow-sm focus:ring-blue-900 focus:border-blue-900">
          </div>

          <!-- Confirm -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input type="password" name="password_confirmation" required
              class="mt-2 w-full rounded-xl border-gray-300 px-4 py-3 shadow-sm focus:ring-blue-900 focus:border-blue-900">
          </div>

          <!-- Wakil -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Nama Wakil Syarikat</label>
            <input type="text" name="nama_wakil_syarikat" required
              class="mt-2 w-full rounded-xl border-gray-300 px-4 py-3 shadow-sm focus:ring-blue-900 focus:border-blue-900">
          </div>

          <!-- Telefon Wakil -->
          <div>
            <label class="block text-sm font-medium text-gray-700">No Telefon Wakil</label>
            <input type="text" name="pic_telefon" required
              class="mt-2 w-full rounded-xl border-gray-300 px-4 py-3 shadow-sm focus:ring-blue-900 focus:border-blue-900">
          </div>

          <!-- Alamat -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Alamat</label>
            <textarea name="alamat" rows="3" required
              class="mt-2 w-full rounded-xl border-gray-300 px-4 py-3 shadow-sm focus:ring-blue-900 focus:border-blue-900"></textarea>
          </div>

          <!-- Telefon Syarikat -->
          <div>
            <label class="block text-sm font-medium text-gray-700">No Telefon Syarikat</label>
            <input type="text" name="no_telefon" required
              class="mt-2 w-full rounded-xl border-gray-300 px-4 py-3 shadow-sm focus:ring-blue-900 focus:border-blue-900">
          </div>

        </div>

        <!-- Submit -->
        <div class="pt-6">
          <button type="submit"
            class="w-full bg-blue-900 text-white py-3 px-6 rounded-full font-bold hover:bg-blue-800 transition">
            Simpan Company
          </button>
        </div>

      </form>
    </div>
  </div>
@endsection
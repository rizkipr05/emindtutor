@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto mt-10">
    <div class="bg-white shadow rounded-xl p-8">

        <h2 class="text-2xl font-bold mb-6">Edit Company Information</h2>

        <form action="{{ route('admin.company.update', $company->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Nama Wakil Syarikat --}}
                <div>
                    <label class="block font-semibold">Nama Wakil Syarikat</label>
                    <input type="text" name="nama_wakil_syarikat"
                        value="{{ old('nama_wakil_syarikat', $company->nama_wakil_syarikat) }}"
                        class="w-full border rounded-lg px-3 py-2 mt-1" required>
                </div>

                {{-- PIC Telefon --}}
                <div>
                    <label class="block font-semibold">PIC Telefon</label>
                    <input type="text" name="pic_telefon"
                        value="{{ old('pic_telefon', $company->pic_telefon) }}"
                        class="w-full border rounded-lg px-3 py-2 mt-1">
                </div>

                {{-- PIC Email --}}
                <div>
                    <label class="block font-semibold">PIC Email</label>
                    <input type="email" name="pic_email"
                        value="{{ old('pic_email', $company->pic_email) }}"
                        class="w-full border rounded-lg px-3 py-2 mt-1">
                </div>

                {{-- No Telefon Syarikat --}}
                <div>
                    <label class="block font-semibold">No Telefon Syarikat</label>
                    <input type="text" name="no_telefon"
                        value="{{ old('no_telefon', $company->no_telefon) }}"
                        class="w-full border rounded-lg px-3 py-2 mt-1">
                </div>

                {{-- Link --}}
                <div>
                    <label class="block font-semibold">Link Website / Sosmed</label>
                    <input type="text" name="link"
                        value="{{ old('link', $company->link) }}"
                        class="w-full border rounded-lg px-3 py-2 mt-1">
                </div>

                {{-- Nomor Pendaftaran Syarikat --}}
                <div>
                    <label class="block font-semibold">Nomor Pendaftaran Syarikat</label>
                    <input type="text" name="nomor_pendaftaran_syarikat"
                        value="{{ old('nomor_pendaftaran_syarikat', $company->nomor_pendaftaran_syarikat) }}"
                        class="w-full border rounded-lg px-3 py-2 mt-1">
                </div>

            </div>

            {{-- Alamat --}}
            <div class="mt-6">
                <label class="block font-semibold">Alamat</label>
                <textarea name="alamat"
                    class="w-full border rounded-lg px-3 py-2 mt-1 h-24">{{ old('alamat', $company->alamat) }}</textarea>
            </div>

            {{-- Logo Syarikat --}}
            <div class="mt-6">
                <label class="block font-semibold">Logo Syarikat</label>
                <input type="file" name="logo_syarikat" class="mt-1">

                @if ($company->logo_syarikat)
                    <div class="mt-3">
                        <p class="text-sm text-gray-600 mb-1">Current Logo:</p>
                        <img src="{{ asset('uploads/' . $company->logo_syarikat) }}"
                             class="h-20 rounded border">
                    </div>
                @endif
            </div>

            {{-- Submit --}}
            <div class="mt-8 flex justify-end">
                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-700 transition">
                    Update
                </button>
            </div>
            <a href="{{ route('a.dashboard.index') }}"
                class="inline-block bg-gradient-to-r from-teal-500 to-cyan-500 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg sm:rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 font-semibold text-sm sm:text-base">
                Kembali
            </a>

        </form>
    </div>
</div>
@endsection
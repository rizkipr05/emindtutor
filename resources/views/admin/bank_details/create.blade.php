@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto mt-10">
    <div class="bg-white shadow rounded-xl p-8">

        <h2 class="text-2xl font-bold mb-6">Tambah Maklumat Bank Syarikat</h2>

        <form action="{{ route('admin.bank_details.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Nama Bank --}}
                <div>
                    <label class="block font-semibold">Nama Bank</label>
                    <input type="text" name="nama_bank" 
                        value="{{ old('nama_bank') }}"
                        placeholder="Contoh: Maybank / CIMB / RHB"
                        class="w-full border rounded-lg px-3 py-2 mt-1 @error('nama_bank') border-red-500 @enderror" required>
                    @error('nama_bank')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nombor Akaun Bank --}}
                <div>
                    <label class="block font-semibold">Nombor Akaun Bank</label>
                    <input type="text" name="nombor_akaun_bank" 
                        value="{{ old('nombor_akaun_bank') }}"
                        placeholder="Masukkan nombor akaun tanpa sengkang (-)"
                        class="w-full border rounded-lg px-3 py-2 mt-1 @error('nombor_akaun_bank') border-red-500 @enderror" required>
                    @error('nombor_akaun_bank')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Info Tambahan (Optional - Jika anda mahu tambah nota) --}}
            <div class="mt-6">
                <p class="text-sm text-gray-500 italic">
                    * Sila pastikan maklumat perbankan adalah tepat bagi mengelakkan sebarang kesulitan dalam urusan transaksi.
                </p>
            </div>

            {{-- Submit & Back Buttons --}}
            <div class="mt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
                {{-- Butang Kembali --}}
                <a href="{{ route('admin.bank_details.index') }}"
                    class="w-full sm:w-auto text-center bg-gradient-to-r from-teal-500 to-cyan-500 text-white px-6 py-2 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 font-semibold">
                    Kembali
                </a>

                {{-- Butang Simpan --}}
                <button type="submit"
                    class="w-full sm:w-auto bg-blue-600 text-white px-10 py-2 rounded-lg shadow hover:bg-blue-700 transition font-bold">
                    Simpan Maklumat
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
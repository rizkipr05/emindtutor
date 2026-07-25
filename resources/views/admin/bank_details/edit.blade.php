@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto mt-10">
    <div class="bg-white shadow rounded-xl p-8">
        
        <h2 class="text-2xl font-bold mb-6">
            {{ isset($bankDetail) ? 'Kemaskini Maklumat Bank' : 'Tambah Maklumat Bank' }}
        </h2>

        <form action="{{ isset($bankDetail) ? route('admin.bank_details.update', $bankDetail->id) : route('admin.bank_details.store') }}" method="POST">
            @csrf
            @if(isset($bankDetail))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nama Bank --}}
                <div>
                    <label class="block font-semibold">Nama Bank</label>
                    <input type="text" name="nama_bank" 
                           placeholder="Contoh: Maybank, CIMB"
                           value="{{ old('nama_bank', $bankDetail->nama_bank ?? '') }}" 
                           class="w-full border rounded-lg px-3 py-2 mt-1 @error('nama_bank') border-red-500 @enderror" required>
                    @error('nama_bank')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nombor Akaun Bank --}}
                <div>
                    <label class="block font-semibold">Nombor Akaun Bank</label>
                    <input type="text" name="nombor_akaun_bank" 
                           value="{{ old('nombor_akaun_bank', $bankDetail->nombor_akaun_bank ?? '') }}" 
                           class="w-full border rounded-lg px-3 py-2 mt-1 @error('nombor_akaun_bank') border-red-500 @enderror" required>
                    @error('nombor_akaun_bank')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Butang Tindakan --}}
            <div class="mt-8 flex items-center justify-between">
                <a href="{{ route('admin.bank_details.index') }}" 
                   class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-200 transition">
                    Batal
                </a>

                <button type="submit" 
                        class="bg-blue-600 text-white px-8 py-2 rounded-lg shadow hover:bg-blue-700 transition">
                    {{ isset($bankDetail) ? 'Update Bank' : 'Simpan Bank' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
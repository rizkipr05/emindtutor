@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto mt-10">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Maklumat Bank Syarikat</h2>
        <a href="{{ route('admin.bank_details.create') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition">
            + Tambah Bank
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-xl overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-4 font-semibold">Nama Bank</th>
                    <th class="px-6 py-4 font-semibold">Nombor Akaun</th>
                    <th class="px-6 py-4 text-right">Tindakan</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($bankDetails as $bank)
                <tr>
                    <td class="px-6 py-4">{{ $bank->nama_bank }}</td>
                    <td class="px-6 py-4 font-mono">{{ $bank->nombor_akaun_bank }}</td>
                    <td class="px-6 py-4 text-right flex justify-end space-x-2">
                        <a href="{{ route('admin.bank_details.edit', $bank->id) }}" class="text-blue-600 hover:underline">Edit</a>
                        
                        <form action="{{ route('admin.bank_details.destroy', $bank->id) }}" method="POST" onsubmit="return confirm('Adakah anda pasti?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Padam</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-8 text-center text-gray-500">Tiada maklumat bank didaftarkan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        <a href="{{ route('a.dashboard.index') }}" class="text-gray-600 hover:text-gray-900 font-semibold">
            ← Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <div class="bg-white shadow-lg rounded-2xl p-6 font-sans">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Tambah Bonus / Potongan Gaji</h2>

        {{-- Error Alert --}}
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.bonus.store', $user->id) }}" method="POST" class="space-y-5">
            @csrf

            {{-- Info User --}}
            <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                <p class="text-sm text-gray-600">Bonus/ Potongan untuk:</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ $user->name }}
                </p>
                <p class="text-sm text-blue-600 font-medium">
                    Role: {{ ucfirst($user->role) }}
                </p>
            </div>

            {{-- Jumlah Bonus / Potongan --}}
            <div>
                <label for="amount" class="block text-sm font-semibold text-gray-700 mb-2">
                    Jumlah <span class="text-gray-500">(isi negatif untuk potongan)</span>
                </label>
                <input type="number" id="amount" name="amount" step="0.01" required
                    class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm px-3 py-2"
                    placeholder="cth: 50 atau -20">
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                <textarea id="description" name="description" rows="3"
                    class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm px-3 py-2"
                    placeholder="Tuliskan keterangan bonus atau potongan..."></textarea>
            </div>

            {{-- Bulan --}}
            <div>
                <label for="month" class="block text-sm font-semibold text-gray-700 mb-2">Bulan</label>
                <select id="month" name="month" required
                    class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm px-3 py-2">
                    <option value="">-- Select Month --</option>
                    <option value="January">January</option>
                    <option value="February">February</option>
                    <option value="March">March</option>
                    <option value="April">April</option>
                    <option value="May">May</option>
                    <option value="June">June</option>
                    <option value="July">July</option>
                    <option value="August">August</option>
                    <option value="September">September</option>
                    <option value="October">October</option>
                    <option value="November">November</option>
                    <option value="December">December</option>
                </select>
            </div>


            {{-- Tahun --}}
            <div>
                <label for="year" class="block text-sm font-semibold text-gray-700 mb-2">Tahun</label>
                <input type="number" id="year" name="year" min="2000" required
                    class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm px-3 py-2"
                    placeholder="2025">
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('admin.teachers.sallaries.index') }}"
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm font-medium">
                   Batal
                </a>
                <button type="submit"
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                   Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
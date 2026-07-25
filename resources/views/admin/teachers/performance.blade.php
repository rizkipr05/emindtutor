@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow rounded-2xl p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Teacher Performance</h1>
                <p class="text-sm text-gray-500">Ringkasan prestasi guru untuk syarikat ini.</p>
            </div>
            <a href="{{ route('a.dashboard.index') }}" class="text-sm text-blue-600 hover:underline">Kembali</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="rounded-xl bg-slate-50 border p-4">
                <p class="text-sm text-gray-500">Jumlah Guru</p>
                <p class="text-3xl font-bold text-gray-900">{{ $teacherCount }}</p>
            </div>
            <div class="rounded-xl bg-slate-50 border p-4">
                <p class="text-sm text-gray-500">Jumlah Kelas</p>
                <p class="text-3xl font-bold text-gray-900">{{ $classCount }}</p>
            </div>
            <div class="rounded-xl bg-slate-50 border p-4">
                <p class="text-sm text-gray-500">Jumlah Pelajar</p>
                <p class="text-3xl font-bold text-gray-900">{{ $studentCount }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

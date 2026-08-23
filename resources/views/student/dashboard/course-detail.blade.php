@extends('layouts.student')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-6">
    <a href="{{ route('s.dashboard.index') }}" class="mb-5 inline-flex items-center text-sm font-medium text-teal-700 hover:text-teal-900">← Kembali ke Dashboard</a>

    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-sm font-medium text-teal-600">Butiran Kursus</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-900">{{ $course->localized_name }}</h1>
                @if($course->description)
                    <p class="mt-3 whitespace-pre-line text-slate-600">{{ $course->description }}</p>
                @endif
            </div>
            @if($course->fee)
                <div class="rounded-xl bg-teal-50 px-4 py-3 text-right">
                    <p class="text-xs text-slate-500">Yuran Kursus</p>
                    <p class="text-lg font-bold text-teal-700">RM {{ number_format($course->fee, 2) }}</p>
                </div>
            @endif
        </div>

        <div class="mt-6 grid grid-cols-1 gap-3 border-t border-slate-100 pt-5 sm:grid-cols-3">
            <div><p class="text-xs text-slate-500">Jenis</p><p class="font-medium text-slate-800">{{ $course->jenis ?: '-' }}</p></div>
            <div><p class="text-xs text-slate-500">Mod Pengajaran</p><p class="font-medium text-slate-800">{{ $course->teaching_mode ?: '-' }}</p></div>
            <div><p class="text-xs text-slate-500">Tempoh Sesi</p><p class="font-medium text-slate-800">{{ $course->sessions_duration ? $course->sessions_duration . ' ' . $course->sessions_duration_unit : '-' }}</p></div>
        </div>
    </div>

    <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="text-xl font-bold text-slate-900">Jadual Kelas</h2>
        <div class="mt-4 space-y-4">
            @forelse($schedules as $schedule)
                <div class="rounded-xl border border-slate-200 p-4">
                    <p class="font-semibold text-slate-900">{{ $schedule->nama_kelas ?: 'Kelas' }}</p>
                    <p class="mt-1 text-sm text-slate-600">Guru: {{ $schedule->teacher?->user?->name ?: '-' }}</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @forelse($schedule->classScheduleDays as $day)
                            <span class="rounded-full bg-teal-50 px-3 py-1 text-sm text-teal-800">{{ $day->hari }} · {{ \Carbon\Carbon::parse($day->jam_mulai)->format('H:i') }}</span>
                        @empty
                            <span class="text-sm text-slate-500">Jadual belum ditetapkan.</span>
                        @endforelse
                    </div>
                </div>
            @empty
                <p class="rounded-xl bg-slate-50 p-4 text-slate-500">Jadual kelas belum tersedia.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

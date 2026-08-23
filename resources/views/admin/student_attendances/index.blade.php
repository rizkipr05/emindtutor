@extends('layouts.admin')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Student Attendance</h1>
            <p class="mt-1 text-sm text-slate-500">Rekod semua clocking pelajar. Admin boleh kemas kini tarikh, masa dan status.</p>
        </div>
        <form method="GET" class="flex items-end gap-2">
            <label class="text-sm font-medium text-slate-700">Tarikh<input type="date" name="date" value="{{ $date }}" class="mt-1 block rounded-lg border-slate-300"></label>
            <button class="rounded-lg bg-teal-600 px-4 py-2 text-sm font-medium text-white hover:bg-teal-700">Tapis</button>
        </form>
    </div>

    @if(session('success'))<div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">{{ session('success') }}</div>@endif

    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto"><table class="min-w-full text-sm"><thead class="bg-slate-50 text-left text-slate-600"><tr><th class="px-4 py-3">Nama Pelajar</th><th class="px-4 py-3">Kursus / Kelas</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Tarikh</th><th class="px-4 py-3">Clock In</th><th class="px-4 py-3">Clock Out</th><th class="px-4 py-3">Bil. Sesi</th><th class="px-4 py-3 text-right">Tindakan</th></tr></thead><tbody class="divide-y divide-slate-100">@forelse($attendances as $attendance)<tr><td class="px-4 py-3 font-medium text-slate-900">{{ $attendance->user?->name ?: '-' }}</td><td class="px-4 py-3 text-slate-600"><div>{{ $attendance->classSchedule?->course?->localized_name ?: '-' }}</div><div class="text-xs text-slate-400">{{ $attendance->classSchedule?->nama_kelas }}</div></td><td class="px-4 py-3"><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $attendance->status === 'present' ? 'bg-emerald-100 text-emerald-700' : ($attendance->status === 'late' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">{{ ucfirst($attendance->status) }}</span></td><td class="px-4 py-3 text-slate-600">{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d/m/Y') }}</td><td class="px-4 py-3 font-medium text-slate-800">{{ \Carbon\Carbon::parse($attendance->attendance_time)->format('H:i') }}</td><td class="px-4 py-3 font-medium text-slate-800">{{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') : '-' }}</td><td class="px-4 py-3 text-slate-600">{{ $attendance->classSchedule?->studentAttendances()->where('user_id', $attendance->user_id)->count() ?? 0 }}</td><td class="px-4 py-3 text-right"><a href="{{ route('admin.student-attendances.edit', $attendance) }}" class="font-medium text-blue-600 hover:text-blue-800">Edit</a></td></tr>@empty<tr><td colspan="8" class="px-4 py-10 text-center text-slate-500">Tiada rekod kehadiran untuk tarikh ini.</td></tr>@endforelse</tbody></table></div>
        @if($attendances->hasPages())<div class="border-t border-slate-100 px-4 py-3">{{ $attendances->links() }}</div>@endif
    </div>
</div>
@endsection

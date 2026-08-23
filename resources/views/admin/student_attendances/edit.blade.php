@extends('layouts.admin')

@section('content')
<div class="mx-auto max-w-xl px-4 py-6">
    <a href="{{ route('admin.student-attendances.index', ['date' => $attendance->attendance_date]) }}" class="text-sm font-medium text-teal-700 hover:text-teal-900">← Kembali</a>
    <div class="mt-4 rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h1 class="text-xl font-bold text-slate-900">Edit Student Attendance</h1>
        <p class="mt-1 text-sm text-slate-500">{{ $attendance->user?->name }} · {{ $attendance->classSchedule?->course?->localized_name }}</p>
        <form method="POST" action="{{ route('admin.student-attendances.update', $attendance) }}" class="mt-6 space-y-4">@csrf @method('PUT')
            <label class="block text-sm font-medium text-slate-700">Tarikh<input required type="date" name="attendance_date" value="{{ old('attendance_date', $attendance->attendance_date) }}" class="mt-1 block w-full rounded-lg border-slate-300"></label>
            <label class="block text-sm font-medium text-slate-700">Jam clocking<input required type="time" name="attendance_time" value="{{ old('attendance_time', \Carbon\Carbon::parse($attendance->attendance_time)->format('H:i')) }}" class="mt-1 block w-full rounded-lg border-slate-300"></label>
            <label class="block text-sm font-medium text-slate-700">Jam clock out<input type="time" name="check_out_time" value="{{ old('check_out_time', $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') : '') }}" class="mt-1 block w-full rounded-lg border-slate-300"></label>
            <label class="block text-sm font-medium text-slate-700">Status<select name="status" class="mt-1 block w-full rounded-lg border-slate-300"><option value="present" @selected(old('status', $attendance->status) === 'present')>Present</option><option value="late" @selected(old('status', $attendance->status) === 'late')>Late</option><option value="absent" @selected(old('status', $attendance->status) === 'absent')>Absent</option></select></label>
            <label class="block text-sm font-medium text-slate-700">Catatan<textarea name="note" rows="3" class="mt-1 block w-full rounded-lg border-slate-300">{{ old('note', $attendance->note) }}</textarea></label>
            @if($errors->any())<p class="text-sm text-red-600">{{ $errors->first() }}</p>@endif
            <button class="rounded-lg bg-teal-600 px-4 py-2 font-medium text-white hover:bg-teal-700">Simpan perubahan</button>
        </form>
    </div>
</div>
@endsection

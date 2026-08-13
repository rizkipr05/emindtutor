@extends('layouts.teacher')

@section('title', __('reports.teaching.title'))

@section('content')
<div class="mx-auto max-w-5xl">
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ __('reports.teaching.title') }}</h1>
            <p class="mt-1 text-sm text-slate-600">{{ __('reports.teaching.subtitle') }} {{ $classSchedule->nama_kelas }} · {{ $classSchedule->course?->localized_name }}</p>
        </div>
        <a href="{{ route('teacher.attendance.index') }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">{{ __('reports.teaching.back') }}</a>
    </div>

    @if(session('success'))<div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">{{ session('success') }}</div>@endif

    <form method="GET" class="mb-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <label class="mb-1 block text-sm font-semibold text-slate-800">{{ __('reports.teaching.class_date') }}</label>
        <div class="flex gap-3">
            <input type="date" name="date" value="{{ $classDate->toDateString() }}" class="rounded-xl border border-slate-300 px-3 py-2">
            <button class="rounded-xl bg-slate-700 px-4 py-2 text-sm font-semibold text-white">{{ __('reports.teaching.show') }}</button>
        </div>
    </form>

    <form method="POST" action="{{ route('teacher.teaching-reports.store', $classSchedule) }}" class="space-y-5">
        @csrf
        <input type="hidden" name="class_date" value="{{ $classDate->toDateString() }}">
        @foreach($students as $student)
            @php
                $saved = $reports->get($student->id);
                $status = $attendance->get($student->user_id)?->status;
                $statusLabel = ['present' => __('reports.teaching.present'), 'late' => __('reports.teaching.late'), 'absent' => __('reports.teaching.absent')][$status] ?? __('reports.teaching.not_recorded');
            @endphp
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between gap-3">
                    <div>
                        <h2 class="font-bold text-slate-900">{{ $student->user->name }}</h2>
                        <p class="text-sm text-slate-500">{{ __('reports.teaching.attendance') }}: <span class="font-semibold">{{ $statusLabel }}</span></p>
                    </div>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">{{ __('reports.teaching.learning_topic') }}</label>
                        <input required name="reports[{{ $student->id }}][learning_topic]" value="{{ old("reports.{$student->id}.learning_topic", $saved?->learning_topic) }}" class="w-full rounded-xl border border-slate-300 px-3 py-2.5" placeholder="{{ __('reports.teaching.learning_topic_placeholder') }}">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">{{ __('reports.teaching.teacher_note') }}</label>
                        <textarea name="reports[{{ $student->id }}][teacher_note]" rows="3" class="w-full rounded-xl border border-slate-300 px-3 py-2.5" placeholder="{{ __('reports.teaching.teacher_note_placeholder') }}">{{ old("reports.{$student->id}.teacher_note", $saved?->teacher_note) }}</textarea>
                    </div>
                </div>
            </section>
        @endforeach
        <button type="submit" class="rounded-xl bg-teal-600 px-5 py-3 font-semibold text-white shadow-sm hover:bg-teal-700">{{ __('reports.teaching.save') }}</button>
    </form>
</div>
@endsection

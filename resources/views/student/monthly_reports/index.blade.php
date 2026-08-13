@extends('layouts.student')

@section('title', __('reports.monthly.history_title'))

@section('content')
<div class="mx-auto max-w-5xl">
    <div class="mb-6 rounded-2xl bg-white p-6 shadow-sm">
        <h1 class="text-2xl font-bold text-slate-900">{{ __('reports.monthly.history_title') }}</h1>
        <p class="mt-1 text-sm text-slate-600">{{ __('reports.monthly.history_subtitle') }}</p>
    </div>
    <div class="space-y-4">
    @forelse($reports as $report)
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-bold text-slate-900">{{ $report->period_start->translatedFormat('F Y') }}</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ __('reports.monthly.generated', ['date' => $report->collected_at->translatedFormat('d M Y, H:i')]) }} · {{ __('reports.monthly.attendance', ['percentage' => data_get($report->summary, 'attendance_percentage', 0)]) }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    @if($report->pdf_path)
                        <a href="{{ route('student.monthly-reports.download', $report) }}" class="rounded-xl bg-teal-600 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-700">{{ __('reports.monthly.download_pdf') }}</a>
                        <button type="button" onclick="navigator.clipboard.writeText('{{ route('reports.public.download', $report->public_token) }}'); this.textContent='{{ __('reports.monthly.link_copied') }}'" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">{{ __('reports.monthly.copy_link') }}</button>
                    @endif
                </div>
            </div>
        </article>
    @empty
        <div class="rounded-2xl bg-white p-10 text-center text-slate-500 shadow-sm">{{ __('reports.monthly.none') }}</div>
    @endforelse
    </div>
</div>
@endsection

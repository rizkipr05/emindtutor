@extends('layouts.admin')

@section('title', __('reports.monthly.admin_title') . ' | EMindTutor')

@section('content')
<div class="mx-auto max-w-7xl space-y-6">
    <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-teal-600">{{ __('reports.monthly.menu') }}</p>
            <h1 class="mt-1 text-2xl font-bold text-slate-900">{{ __('reports.monthly.admin_title') }}</h1>
            <p class="mt-1 text-sm text-slate-500">{{ __('reports.monthly.admin_subtitle') }}</p>
        </div>
        <a href="{{ route('admin.settings.index', ['section' => 'monthly_reports']) }}" class="inline-flex items-center justify-center rounded-xl border border-teal-200 bg-teal-50 px-4 py-2.5 text-sm font-semibold text-teal-700 hover:bg-teal-100">{{ __('reports.monthly.open_settings') }}</a>
    </div>

    <form method="GET" class="flex flex-wrap items-end gap-3 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div>
            <label for="month" class="mb-1 block text-sm font-semibold text-slate-700">{{ __('student/progress/index.select_month') }}</label>
            <select id="month" name="month" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm">
                <option value="">{{ __('reports.monthly.all_months') }}</option>
                @for($month = 1; $month <= 12; $month++)
                    <option value="{{ $month }}" @selected(request('month') == $month)>{{ __('student/progress/index.months.' . $month) }}</option>
                @endfor
            </select>
        </div>
        <div>
            <label for="year" class="mb-1 block text-sm font-semibold text-slate-700">{{ __('student/progress/index.year') }}</label>
            <select id="year" name="year" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm">
                <option value="">{{ __('reports.monthly.all_years') }}</option>
                @for($year = now()->year - 2; $year <= now()->year + 1; $year++)
                    <option value="{{ $year }}" @selected(request('year') == $year)>{{ $year }}</option>
                @endfor
            </select>
        </div>
        <button class="rounded-xl bg-teal-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-teal-700">{{ __('reports.monthly.filter') }}</button>
    </form>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                    <tr><th class="px-5 py-3">{{ __('reports.monthly.student') }}</th><th class="px-5 py-3">{{ __('reports.monthly.period') }}</th><th class="px-5 py-3">{{ __('reports.monthly.attendance', ['percentage' => '']) }}</th><th class="px-5 py-3">{{ __('reports.monthly.generated_at') }}</th><th class="px-5 py-3 text-right">{{ __('reports.monthly.actions') }}</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                @forelse($reports as $report)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-4 font-semibold text-slate-900">{{ $report->student->user->name }}</td>
                        <td class="px-5 py-4 text-slate-600">{{ $report->period_start->translatedFormat('F Y') }}</td>
                        <td class="px-5 py-4 text-slate-600">{{ data_get($report->summary, 'attendance_percentage', 0) }}%</td>
                        <td class="px-5 py-4 text-slate-600">{{ $report->collected_at->translatedFormat('d M Y, H:i') }}</td>
                        <td class="px-5 py-4 text-right">
                            @if($report->pdf_path)
                                <a href="{{ route('admin.monthly-reports.download', $report) }}" class="font-semibold text-teal-700 hover:underline">{{ __('reports.monthly.download_pdf') }}</a>
                                <button type="button" onclick="navigator.clipboard.writeText('{{ route('reports.public.download', $report->public_token) }}'); this.textContent='{{ __('reports.monthly.link_copied') }}'" class="ml-3 font-semibold text-slate-600 hover:underline">{{ __('reports.monthly.copy_link') }}</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-12 text-center text-slate-500">{{ __('reports.monthly.no_reports') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($reports->hasPages())<div class="border-t border-slate-100 px-5 py-4">{{ $reports->links() }}</div>@endif
    </div>
</div>
@endsection

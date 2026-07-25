@extends('layouts.superadmin')

@section('title', __('super_admin/settings.title'))

@section('content')
@php
    $navSections = [
        'overview' => [
            'label' => __('super_admin/settings.sections.overview.title'),
            'caption' => __('super_admin/settings.sections.overview.subtitle'),
            'icon' => 'M4 7.25A3.25 3.25 0 017.25 4h9.5A3.25 3.25 0 0120 7.25v9.5A3.25 3.25 0 0116.75 20h-9.5A3.25 3.25 0 014 16.75v-9.5zM8.5 8.5h7v1.5h-7zm0 3h7V13h-7zm0 3h4V16h-4z',
            'tone' => 'from-cyan-500 to-teal-500',
        ],
        'companies' => [
            'label' => __('super_admin/settings.sections.companies.title'),
            'caption' => __('super_admin/settings.sections.companies.subtitle'),
            'icon' => 'M5 20h14a1 1 0 001-1v-8a1 1 0 00-1-1h-2V6.5A2.5 2.5 0 0014.5 4h-5A2.5 2.5 0 007 6.5V10H5a1 1 0 00-1 1v8a1 1 0 001 1zm5-13.5V6.5a1.5 1.5 0 011.5-1.5h5a1.5 1.5 0 011.5 1.5V10z',
            'tone' => 'from-indigo-500 to-blue-500',
        ],
        'packages' => [
            'label' => __('super_admin/settings.sections.packages.title'),
            'caption' => __('super_admin/settings.sections.packages.subtitle'),
            'icon' => 'M12 2l7 4v6c0 5-3.5 9.5-7 10-3.5-.5-7-5-7-10V6l7-4zm0 5.2a2.8 2.8 0 100 5.6 2.8 2.8 0 000-5.6z',
            'tone' => 'from-violet-500 to-fuchsia-500',
        ],
        'billing' => [
            'label' => __('super_admin/settings.sections.billing.title'),
            'caption' => __('super_admin/settings.sections.billing.subtitle'),
            'icon' => 'M7 4h10a1 1 0 011 1v14l-4-2-4 2-4-2-4 2V5a1 1 0 011-1h4zm1.5 4.5h7v1.5h-7zm0 3h7V13h-7z',
            'tone' => 'from-amber-500 to-orange-500',
        ],
        'staff' => [
            'label' => __('super_admin/settings.sections.staff.title'),
            'caption' => __('super_admin/settings.sections.staff.subtitle'),
            'icon' => 'M12 12a4 4 0 100-8 4 4 0 000 8zm-7 8a7 7 0 0114 0H5z',
            'tone' => 'from-rose-500 to-pink-500',
        ],
        'reports' => [
            'label' => __('super_admin/settings.sections.reports.title'),
            'caption' => __('super_admin/settings.sections.reports.subtitle'),
            'icon' => 'M4 19.5h16M6 16V9m4 7V6m4 10v-5m4 5V8',
            'tone' => 'from-sky-500 to-cyan-500',
        ],
        'communications' => [
            'label' => __('super_admin/settings.sections.communications.title'),
            'caption' => __('super_admin/settings.sections.communications.subtitle'),
            'icon' => 'M3 6.75A2.75 2.75 0 015.75 4h12.5A2.75 2.75 0 0121 6.75v7.5A2.75 2.75 0 0118.25 17H9l-5 3v-3H5.75A2.75 2.75 0 013 14.25z',
            'tone' => 'from-emerald-500 to-teal-500',
        ],
    ];

    $sectionRows = $currentSection['details'] ?? [];
    $featureItems = $currentSection['features'] ?? [];
@endphp

<div class="px-4 py-6 sm:px-6 lg:px-8">
    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard.index') }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-cyan-200 bg-white text-cyan-600 shadow-sm transition hover:bg-cyan-50">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-cyan-600">{{ __('super_admin/settings.breadcrumb.superadmin') }}</p>
                <h1 class="text-3xl font-bold text-slate-900">{{ __('super_admin/settings.heading') }}</h1>
            </div>
        </div>

        <div class="flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('dashboard.index') }}" class="text-cyan-600 hover:underline">{{ __('super_admin/settings.breadcrumb.home') }}</a>
            <span>/</span>
            <a href="{{ route('superadmin.settings.index') }}" class="text-cyan-600 hover:underline">{{ __('super_admin/settings.breadcrumb.settings') }}</a>
            <span>/</span>
            <span class="font-semibold text-slate-800">{{ $currentSection['title'] }}</span>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[320px_minmax(0,1fr)]">
        <aside class="rounded-[1.5rem] border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-lg font-bold text-slate-900">{{ __('super_admin/settings.sidebar.title') }}</h2>
                <p class="mt-1 text-sm text-slate-500">{{ __('super_admin/settings.sidebar.subtitle') }}</p>
            </div>

            <nav class="space-y-2 p-3">
                @foreach($navSections as $key => $section)
                    <a href="{{ route('superadmin.settings.index', ['section' => $key]) }}"
                       class="group flex items-center gap-3 rounded-xl px-3 py-3 transition {{ $currentSectionKey === $key ? 'bg-slate-200/90 text-slate-900 shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $currentSectionKey === $key ? 'bg-cyan-100 text-cyan-700' : 'bg-slate-100 text-slate-400' }}">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="{{ $section['icon'] }}"></path>
                            </svg>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block truncate text-sm font-semibold">{{ $section['label'] }}</span>
                            <span class="block truncate text-xs {{ $currentSectionKey === $key ? 'text-slate-600' : 'text-slate-400' }}">{{ $section['caption'] }}</span>
                        </span>
                        @if($currentSectionKey === $key)
                            <span class="rounded-full bg-cyan-100 px-2 py-1 text-[10px] font-bold uppercase tracking-wide text-cyan-700">{{ __('super_admin/settings.sidebar.active') }}</span>
                        @endif
                    </a>
                @endforeach
            </nav>
        </aside>

        <section class="space-y-6">
            <div class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-5">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900">{{ $currentSection['title'] }}</h2>
                            <p class="mt-1 max-w-3xl text-sm leading-6 text-slate-500">{{ $currentSection['subtitle'] }}</p>
                        </div>
                        <span class="inline-flex w-fit items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold uppercase tracking-wide text-emerald-700">
                            {{ __('super_admin/settings.sidebar.active') }}
                        </span>
                    </div>
                </div>

                <div class="grid gap-0 lg:grid-cols-[1.05fr_0.95fr]">
                    <div class="p-6">
                        <div class="grid gap-4 sm:grid-cols-2">
                            @foreach($sectionRows as $row)
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                                    <p class="text-sm font-semibold text-slate-500">{{ $row['label'] }}</p>
                                    <p class="mt-2 text-xl font-bold text-slate-900">{{ $row['value'] }}</p>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 rounded-2xl border border-cyan-100 bg-gradient-to-br from-cyan-50 to-sky-50 p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900">{{ __('super_admin/settings.features.title') }}</h3>
                                    <p class="mt-1 text-sm text-slate-500">{{ __('super_admin/settings.features.subtitle') }}</p>
                                </div>
                                <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-cyan-700 shadow-sm">
                                    {{ __('super_admin/settings.quick_links.open') }}
                                </span>
                            </div>

                            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                                @foreach($featureItems as $feature)
                                    <a href="{{ $feature['url'] }}" class="group flex items-start gap-3 rounded-2xl border border-cyan-100 bg-white px-4 py-3 transition hover:border-cyan-200 hover:shadow-sm">
                                        <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-cyan-100 text-cyan-700">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </span>
                                        <span class="min-w-0">
                                            <span class="block text-sm font-semibold text-slate-900 group-hover:text-cyan-700">{{ $feature['label'] }}</span>
                                            <span class="mt-1 block text-sm text-slate-500">{{ $feature['desc'] }}</span>
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-slate-200 bg-slate-50/70 p-6 lg:border-l lg:border-t-0">
                        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900">{{ __('super_admin/settings.quick_links.title') }}</h3>
                                    <p class="mt-1 text-sm text-slate-500">{{ __('super_admin/settings.bottom.desc') }}</p>
                                </div>
                            </div>

                            <div class="mt-4 space-y-3">
                                <a href="{{ route('companies.index') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 transition hover:border-cyan-200 hover:bg-cyan-50">
                                    <span>
                                        <span class="block text-sm font-semibold text-slate-900">{{ __('super_admin/settings.quick_links.companies') }}</span>
                                        <span class="block text-xs text-slate-500">{{ __('super_admin/settings.quick_links.companies_desc') }}</span>
                                    </span>
                                    <span class="text-sm font-semibold text-cyan-700">{{ __('super_admin/settings.quick_links.open') }}</span>
                                </a>
                                <a href="{{ route('invoices.index') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 transition hover:border-amber-200 hover:bg-amber-50">
                                    <span>
                                        <span class="block text-sm font-semibold text-slate-900">{{ __('super_admin/settings.quick_links.billing') }}</span>
                                        <span class="block text-xs text-slate-500">{{ __('super_admin/settings.quick_links.billing_desc') }}</span>
                                    </span>
                                    <span class="text-sm font-semibold text-cyan-700">{{ __('super_admin/settings.quick_links.open') }}</span>
                                </a>
                                <a href="{{ route('superadmin.stats.index') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 transition hover:border-indigo-200 hover:bg-indigo-50">
                                    <span>
                                        <span class="block text-sm font-semibold text-slate-900">{{ __('super_admin/settings.quick_links.reports') }}</span>
                                        <span class="block text-xs text-slate-500">{{ __('super_admin/settings.quick_links.reports_desc') }}</span>
                                    </span>
                                    <span class="text-sm font-semibold text-cyan-700">{{ __('super_admin/settings.quick_links.open') }}</span>
                                </a>
                            </div>
                        </div>

                        <div class="mt-4 rounded-2xl border border-slate-900 bg-slate-950 p-5 text-white shadow-sm">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold">{{ __('super_admin/settings.bottom.title') }}</h3>
                                <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-white/80">{{ $currentSection['badge'] }}</span>
                            </div>
                            <div class="mt-4 grid grid-cols-2 gap-3">
                                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                    <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('super_admin/settings.bottom.active_companies') }}</p>
                                    <p class="mt-2 text-2xl font-black">{{ $stats['activeCompanies'] }}</p>
                                </div>
                                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                    <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('super_admin/settings.bottom.paid_invoices') }}</p>
                                    <p class="mt-2 text-2xl font-black">{{ $stats['paidInvoices'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

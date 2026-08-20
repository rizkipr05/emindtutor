
@extends('layouts.admin')

@section('title', __('admin/settings.title') . ' | EMindTutor')

@section('content')
<style>
    .admin-settings-layout { display: block; }
    @media (min-width: 1024px) {
        .admin-settings-layout {
            display: grid;
            grid-template-columns: 280px minmax(0, 1fr);
            gap: 1.5rem;
            align-items: start;
        }
        .admin-settings-sidebar { position: sticky; top: 1.5rem; }
    }
</style>

<div class="px-4 py-6 sm:px-6 lg:px-8">

    @if(session('success'))
        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 shadow-sm">
            ✅ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700 shadow-sm">
            ❌ {{ session('error') }}
        </div>
    @endif

    <div class="admin-settings-layout">

        {{-- ─── SIDEBAR NAVIGATION ─── --}}
        <aside class="admin-settings-sidebar w-full rounded-[1.5rem] border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-lg font-bold text-slate-900">{{ __('admin/settings.title') }}</h2>
                <p class="mt-1 text-sm text-slate-500">{{ __('admin/settings.manage_company') }}</p>
            </div>
            <nav class="space-y-1.5 p-3">
                @foreach($navSections as $key => $section)
                    <a href="{{ route('admin.settings.index', ['section' => $key]) }}"
                       class="group flex items-center gap-3 rounded-xl px-3 py-3 transition
                              {{ $currentSectionKey === $key ? 'bg-slate-200/90 text-slate-900 shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl
                                     {{ $currentSectionKey === $key ? 'bg-teal-100 text-teal-700' : 'bg-slate-100 text-slate-400 group-hover:text-slate-600' }}">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $section['icon'] }}"/>
                            </svg>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block truncate text-sm font-semibold">{{ $section['label'] }}</span>
                            <span class="block truncate text-xs {{ $currentSectionKey === $key ? 'text-slate-600' : 'text-slate-400' }}">{{ $section['caption'] }}</span>
                        </span>
                        @if($currentSectionKey === $key)
                            <span class="rounded-full bg-teal-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-teal-700">{{ __('admin/settings.active') }}</span>
                        @endif
                    </a>
                @endforeach
            </nav>
        </aside>

        {{-- ─── MAIN CONTENT ─── --}}
        <section class="min-w-0 space-y-6">

            {{-- Page Header --}}
            <div class="flex flex-col gap-4 rounded-[1.5rem] border border-slate-200 bg-white px-6 py-5 shadow-sm lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-teal-600">{{ __('admin/settings.admin_company') }}</p>
                    <h1 class="text-2xl font-bold text-slate-900">{{ __('admin/settings.title') }}</h1>
                </div>
                <div class="flex flex-wrap gap-2 text-sm text-slate-500">
                    <a href="{{ route('a.dashboard.index') }}" class="text-teal-600 hover:underline">{{ __('admin/settings.dashboard') }}</a>
                    <span>/</span>
                    <a href="{{ route('admin.settings.index') }}" class="text-teal-600 hover:underline">{{ __('admin/settings.settings') }}</a>
                    <span>/</span>
                    <span class="font-semibold text-slate-800">{{ $navSections[$currentSectionKey]['label'] ?? $currentSectionKey }}</span>
                </div>
            </div>

            {{-- ════════════════════════════════ TABS ════════════════════════════════ --}}

            @if($currentSectionKey === 'overview')
            {{-- ─── OVERVIEW: Edit Company Profile ─── --}}
            <div class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h2 class="text-xl font-bold text-slate-900">{{ __('admin/settings.overview.title') }}</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ __('admin/settings.overview.subtitle') }}</p>
                </div>
                <form method="POST" action="{{ route('admin.settings.update', ['section' => 'overview']) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="p-6 grid gap-4 sm:grid-cols-2">

                        <div class="sm:col-span-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                            <label class="block text-sm font-semibold text-slate-900 mb-1">Nama Syarikat</label>
                            <input type="text" value="{{ $company->user?->name ?: $company->nama_syarikat }}"
                                class="block w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-100 px-3 py-2.5 text-sm text-slate-700 shadow-sm"
                                readonly>
                            <p class="mt-1.5 text-xs text-slate-500">Nama ini menggunakan data semasa pendaftaran syarikat dan turut dipaparkan pada Monthly Report.</p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                            <label class="block text-sm font-semibold text-slate-900 mb-1">E-mel Login Syarikat</label>
                            <input type="email" value="{{ $company->user?->email ?: $user->email }}"
                                class="block w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-100 px-3 py-2.5 text-sm text-slate-700 shadow-sm"
                                readonly>
                            <p class="mt-1.5 text-xs text-slate-500">Menggunakan e-mel yang didaftarkan untuk login syarikat.</p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                            <label class="block text-sm font-semibold text-slate-900 mb-1">Website Syarikat</label>
                            <input type="url" name="link" value="{{ old('link', $company->link) }}" placeholder="https://example.com"
                                   class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm focus:border-teal-400 focus:ring-2 focus:ring-teal-100 focus:outline-none">
                            @error('link')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <div class="sm:col-span-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                            <label class="block text-sm font-semibold text-slate-900 mb-1">{{ __('admin/settings.representative_name') }}</label>
                            <input type="text" name="nama_wakil_syarikat" value="{{ old('nama_wakil_syarikat', $company->nama_wakil_syarikat) }}"
                                   class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm focus:border-teal-400 focus:ring-2 focus:ring-teal-100 focus:outline-none" required>
                            @error('nama_wakil_syarikat')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                            <label class="block text-sm font-semibold text-slate-900 mb-1">{{ __('admin/settings.representative_email') }}</label>
                            <input type="email" value="{{ $company->user?->email ?: $user->email }}"
                                class="block w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-100 px-3 py-2.5 text-sm text-slate-700 shadow-sm"
                                readonly>
                            <p class="mt-1.5 text-xs text-slate-500">Diselaraskan dengan e-mel login syarikat.</p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                            <label class="block text-sm font-semibold text-slate-900 mb-1">{{ __('admin/settings.representative_phone') }}</label>
                            <input type="text" name="pic_telefon" value="{{ old('pic_telefon', $company->pic_telefon) }}"
                                   placeholder="011-XXXXXXXX"
                                   class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm focus:border-teal-400 focus:ring-2 focus:ring-teal-100 focus:outline-none">
                            @error('pic_telefon')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                            <label class="block text-sm font-semibold text-slate-900 mb-1">{{ __('admin/settings.company_phone') }}</label>
                            <input type="text" name="no_telefon" value="{{ old('no_telefon', $company->no_telefon) }}"
                                   class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm focus:border-teal-400 focus:ring-2 focus:ring-teal-100 focus:outline-none">
                            @error('no_telefon')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                            <label class="block text-sm font-semibold text-slate-900 mb-1">{{ __('admin/settings.registration_number') }}</label>
                            <input type="text" name="nombor_pendaftaran_syarikat" value="{{ old('nombor_pendaftaran_syarikat', $company->nombor_pendaftaran_syarikat) }}"
                                   class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm focus:border-teal-400 focus:ring-2 focus:ring-teal-100 focus:outline-none">
                            @error('nombor_pendaftaran_syarikat')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <div class="sm:col-span-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                            <label class="block text-sm font-semibold text-slate-900 mb-1">{{ __('admin/settings.address') }}</label>
                            <textarea name="alamat" rows="3"
                                      class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm focus:border-teal-400 focus:ring-2 focus:ring-teal-100 focus:outline-none">{{ old('alamat', $company->alamat) }}</textarea>
                            @error('alamat')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <div class="sm:col-span-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                            <label class="block text-sm font-semibold text-slate-900 mb-1">Logo Syarikat</label>
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                                @if($company->logo_syarikat)
                                    <img src="{{ asset('uploads/' . $company->logo_syarikat) }}" alt="Logo syarikat" class="h-16 w-16 rounded-xl border border-slate-200 bg-white object-contain p-1">
                                @endif
                                <div class="flex flex-wrap items-center gap-3">
                                    <input id="logo_syarikat" type="file" name="logo_syarikat" accept="image/png,image/jpeg,image/webp" class="sr-only"
                                           onchange="document.getElementById('logo_syarikat_name').textContent = this.files.length ? this.files[0].name : 'Tiada fail dipilih'">
                                    <label for="logo_syarikat" class="inline-flex cursor-pointer items-center rounded-xl bg-teal-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-700">
                                        Pilih Logo
                                    </label>
                                    <span id="logo_syarikat_name" class="text-sm text-slate-500">Tiada fail dipilih</span>
                                </div>
                            </div>
                            <p class="mt-1.5 text-xs text-slate-500">Format PNG, JPG, JPEG atau WEBP. Maksimum 2 MB.</p>
                            @error('logo_syarikat')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="flex items-center justify-between gap-4 border-t border-slate-200 px-6 py-4">
                        <p class="text-sm text-slate-500">{{ __('admin/settings.changes_immediate') }}</p>
                        <button type="submit" class="inline-flex items-center rounded-xl px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:opacity-90" style="background:#0d9488">
                            {{ __('admin/settings.save_changes') }}
                        </button>
                    </div>
                </form>
            </div>

            @elseif($currentSectionKey === 'subscription')
            {{-- ─── SUBSCRIPTION ─── --}}
            <div class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-5 flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">{{ __('admin/settings.subscription.title') }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ __('admin/settings.subscription.subtitle') }}</p>
                    </div>
                </div>
                <div class="p-6 space-y-6">
                    @if(!$company->status)
                        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-4 text-center">
                            <p class="text-red-700 font-semibold">{{ __('admin/settings.account_frozen') }}</p>
                        </div>
                    @elseif($activeSubscription && $activeSubscription->package)
                        @php
                            $progressPct = $totalDays > 0 ? round(($remainingDays / $totalDays) * 100) : 0;
                            $progressPct = min(100, max(0, $progressPct));
                            $barColor    = $remainingDays < 7  ? 'bg-red-500'
                                         : ($remainingDays < 30 ? 'bg-yellow-400' : 'bg-emerald-500');
                            $badgeClass  = $remainingDays < 7  ? 'bg-red-100 text-red-700 border-red-300'
                                         : ($remainingDays < 30 ? 'bg-yellow-100 text-yellow-700 border-yellow-300' : 'bg-emerald-100 text-emerald-700 border-emerald-300');
                        @endphp

                        @if($remainingDays < 30)
                        <div class="flex items-start gap-3 rounded-xl border {{ $remainingDays < 7 ? 'border-red-200 bg-red-50' : 'border-yellow-200 bg-yellow-50' }} px-4 py-3">
                            <svg class="w-5 h-5 mt-0.5 shrink-0 {{ $remainingDays < 7 ? 'text-red-500' : 'text-yellow-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                            </svg>
                            <p class="text-sm font-medium {{ $remainingDays < 7 ? 'text-red-700' : 'text-yellow-700' }}">
                                {{ $remainingDays < 7 ? __('admin/settings.critical_expiry') : __('admin/settings.renew_soon') }}
                            </p>
                        </div>
                        @endif

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <div class="rounded-xl bg-slate-50 border border-slate-100 px-4 py-3">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1">{{ __('admin/settings.active_plan') }}</p>
                                <p class="text-sm font-bold text-slate-900">{{ $activeSubscription->package->nama_pakej }}</p>
                            </div>
                            <div class="rounded-xl bg-slate-50 border border-slate-100 px-4 py-3">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1">{{ __('admin/settings.status') }}</p>
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-bold {{ $activeSubscription->status == 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $activeSubscription->status == 'active' ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                    {{ $activeSubscription->status == 'active' ? __('admin/settings.enabled') : __('admin/settings.disabled') }}
                                </span>
                            </div>
                            <div class="rounded-xl bg-slate-50 border border-slate-100 px-4 py-3">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1">{{ __('admin/settings.start_date') }}</p>
                                <p class="text-sm font-semibold text-slate-900">{{ $startDate ? $startDate->translatedFormat('d M Y') : '-' }}</p>
                            </div>
                            <div class="rounded-xl bg-slate-50 border border-slate-100 px-4 py-3">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1">{{ __('admin/settings.end_date') }}</p>
                                <p class="text-sm font-bold {{ $remainingDays < 7 ? 'text-red-700' : ($remainingDays < 30 ? 'text-yellow-700' : 'text-slate-900') }}">
                                    {{ $expiryDate ? $expiryDate->translatedFormat('d M Y') : '-' }}
                                </p>
                            </div>
                        </div>

                        {{-- Progress Bar --}}
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-semibold text-slate-700">{{ __('admin/settings.remaining_time') }}</span>
                                <span class="inline-block px-2.5 py-0.5 text-xs font-bold rounded-full border {{ $badgeClass }}">
                                    {{ __('admin/settings.days_remaining', ['count' => $remainingDays]) }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                                <div class="{{ $barColor }} h-3 rounded-full transition-all duration-700" style="width: {{ $progressPct }}%"></div>
                            </div>
                            <p class="mt-1.5 text-xs text-slate-400">{{ __('admin/settings.period_active', ['percent' => $progressPct]) }}</p>
                        </div>

                        @if($remainingDays < 30)
                        <a href="{{ route('admin.subscribe.index') }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 text-white rounded-xl shadow transform hover:scale-105 transition-all duration-300 font-semibold text-sm"
                           style="background:linear-gradient(135deg,#f97316,#f59e0b)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            {{ __('admin/settings.renew_subscription') }}
                        </a>
                        @else
                        <a href="{{ route('admin.subscribe.index') }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 transition-all duration-200 font-semibold text-sm">
                            {{ __('admin/settings.view_other_packages') }}
                        </a>
                        @endif

                    @else
                        <div class="text-center py-10">
                            <svg class="w-14 h-14 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                            <p class="text-gray-500 mb-4">{{ __('admin/settings.no_active_subscription') }}</p>
                            <a href="{{ route('admin.subscribe.index') }}"
                               class="inline-block text-white px-6 py-2.5 rounded-xl shadow font-semibold text-sm"
                               style="background:linear-gradient(135deg,#0d9488,#0891b2)">
                                {{ __('admin/settings.subscribe_now') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Subscription History --}}
            @if($subscriptionHistory->count() > 0)
            <div class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-indigo-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 002 2v12a2 2 0 002 2h10a2 2 0 00-2-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900">{{ __('admin/settings.subscription_history') }}</h2>
                            <p class="text-xs text-slate-500">{{ __('admin/settings.latest_records') }}</p>
                        </div>
                    </div>
                    @if($activeSubscription && $remainingDays < 30)
                        <a href="{{ route('admin.subscribe.index') }}"
                           class="inline-flex shrink-0 items-center gap-1.5 rounded-lg bg-teal-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-teal-700">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            {{ __('admin/settings.renew_subscription') }}
                        </a>
                    @endif
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($subscriptionHistory as $inv)
                    <div class="px-6 py-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div>
                            <p class="text-sm font-semibold text-slate-800">{{ $inv->package?->nama_pakej ?? '-' }}</p>
                            <p class="text-xs text-slate-500">{{ $inv->nomor_invoice }} &bull;
                                {{ \Carbon\Carbon::parse($inv->tarikh_terbit)->format('d/m/Y') }}
                                –
                                {{ \Carbon\Carbon::parse($inv->tarikh_tamat)->format('d/m/Y') }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.settings.subscription.receipt', $inv) }}"
                               target="_blank"
                               rel="noopener"
                               class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-3 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-indigo-700">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m0 0l-3-3m3 3l3-3M3 16.5v2A2.5 2.5 0 005.5 21h13a2.5 2.5 0 002.5-2.5v-2M7 3h10v5H7z" />
                                </svg>
                                {{ __('admin/settings.paid_invoice') }}
                            </a>
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full border
                                {{ $inv->status_bayaran === 'paid' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200' }}">
                                {{ ucfirst($inv->status_bayaran) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @elseif($currentSectionKey === 'bank_details')
            {{-- ─── BANK DETAILS ─── --}}
            <div class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-5 flex items-center justify-between flex-wrap gap-3">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">{{ __('admin/settings.bank_details.title') }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ __('admin/settings.bank_details.subtitle') }}</p>
                    </div>
                    <a href="{{ route('admin.bank_details.create') }}"
                       class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:opacity-90"
                       style="background:#0d9488">
                        {{ __('admin/settings.add_bank_account') }}
                    </a>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($bankDetails as $bank)
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-6 py-4">
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-slate-900">{{ $bank->nama_bank }}</p>
                            <p class="mt-0.5 text-sm text-slate-500">{{ $bank->nombor_akaun_bank }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.bank_details.edit', $bank) }}"
                               class="rounded-lg px-3 py-2 text-xs font-bold text-teal-700 hover:bg-teal-50 transition">{{ __('admin/settings.edit') }}</a>
                            <form method="POST" action="{{ route('admin.bank_details.destroy', $bank) }}"
                                  onsubmit="return confirm(@js(__('admin/settings.delete_bank_confirm'))) ">
                                @csrf @method('DELETE')
                                <button type="submit" class="rounded-lg px-3 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 transition">{{ __('admin/settings.delete') }}</button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-10 text-center text-sm text-slate-500">
                        {{ __('admin/settings.no_bank_accounts') }}
                        <a href="{{ route('admin.bank_details.create') }}" class="ml-1 font-semibold text-teal-600 hover:underline">{{ __('admin/settings.add_now') }}</a>
                    </div>
                    @endforelse
                </div>
            </div>

            @elseif($currentSectionKey === 'payment_gateway')
            {{-- ─── PAYMENT GATEWAY ─── --}}
            <div class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">{{ __('admin/settings.payment_gateway.title') }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ __('admin/settings.payment_gateway.subtitle') }}</p>
                    </div>
                    <div>
                        @if($gateway && $gateway->is_active)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3.5 py-1.5 text-xs font-bold text-emerald-800 border border-emerald-300">
                                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                {{ __('admin/settings.status_active') }}
                            </span>
                        @elseif($gateway && !$gateway->is_active)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3.5 py-1.5 text-xs font-bold text-amber-800 border border-amber-300">
                                <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                                {{ __('admin/settings.status_inactive') }}
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3.5 py-1.5 text-xs font-bold text-slate-700 border border-slate-300">
                                <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                {{ __('admin/settings.status_unconfigured') }}
                            </span>
                        @endif
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.settings.update', ['section' => 'payment_gateway']) }}">
                    @csrf
                    <div class="p-6 grid gap-4 sm:grid-cols-2">

                        {{-- Alert Status Banner --}}
                        <div class="sm:col-span-2">
                            @if($gateway && $gateway->is_active)
                                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-200 text-emerald-800 font-bold text-lg">
                                            ✓
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-emerald-900">{{ __('admin/settings.banner_active_title') }}</h4>
                                            <p class="text-xs text-emerald-700 mt-0.5">{{ __('admin/settings.banner_active_desc', ['time' => $gateway->updated_at?->timezone(config('app.timezone'))->format('d M Y, H:i') ?? '-']) }}</p>
                                        </div>
                                    </div>
                                    <span class="rounded-full bg-emerald-600 px-3 py-1 text-xs font-bold text-white uppercase tracking-wider">{{ __('admin/settings.enabled') }} ({{ __('admin/settings.admin_company') }})</span>
                                </div>
                            @elseif($gateway && !$gateway->is_active)
                                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-200 text-amber-800 font-bold text-lg">
                                            !
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-amber-900">{{ __('admin/settings.banner_inactive_title') }}</h4>
                                            <p class="text-xs text-amber-700 mt-0.5">{{ __('admin/settings.banner_inactive_desc') }}</p>
                                        </div>
                                    </div>
                                    <span class="rounded-full bg-amber-600 px-3 py-1 text-xs font-bold text-white uppercase tracking-wider">{{ __('admin/settings.disabled') }}</span>
                                </div>
                            @else
                                <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-rose-200 text-rose-800 font-bold text-lg">
                                            ✕
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-rose-900">{{ __('admin/settings.banner_unconfigured_title') }}</h4>
                                            <p class="text-xs text-rose-700 mt-0.5">{{ __('admin/settings.banner_unconfigured_desc') }}</p>
                                        </div>
                                    </div>
                                    <span class="rounded-full bg-rose-600 px-3 py-1 text-xs font-bold text-white uppercase tracking-wider">{{ __('admin/settings.status_unconfigured') }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="sm:col-span-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                            <label class="block text-sm font-semibold text-slate-900 mb-1">User Secret Key (ToyyibPay)</label>
                            <input type="password" name="user_secret_key"
                                   value=""
                                   placeholder="{{ $gateway ? __('admin/settings.secret_key_saved_placeholder') : 'xxxxx-xxxxx-xxxxx' }}"
                                   class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm focus:border-teal-400 focus:ring-2 focus:ring-teal-100 focus:outline-none" {{ $gateway ? '' : 'required' }}>
                            <p class="mt-1.5 text-xs text-slate-500">{{ __('admin/settings.secret_key_help') }}</p>
                            @error('user_secret_key')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                            <label class="block text-sm font-semibold text-slate-900 mb-1">Category Code</label>
                            <input type="text" name="category_code"
                                   value="{{ old('category_code', $gateway?->category_code) }}"
                                   placeholder="xxxxxxxx"
                                   class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm focus:border-teal-400 focus:ring-2 focus:ring-teal-100 focus:outline-none" required>
                            <p class="mt-1.5 text-xs text-slate-500">{{ __('admin/settings.category_code_help') }}</p>
                            @error('category_code')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                            <label class="block text-sm font-semibold text-slate-900 mb-1">{{ __('admin/settings.payment_channel') }}</label>
                            <select name="payment_channel"
                                    class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm focus:border-teal-400 focus:ring-2 focus:ring-teal-100 focus:outline-none">
                                <option value="0" {{ old('payment_channel', $gateway?->payment_channel ?? 0) == 0 ? 'selected' : '' }}>{{ __('admin/settings.all_fpx_card') }}</option>
                                <option value="1" {{ old('payment_channel', $gateway?->payment_channel) == 1 ? 'selected' : '' }}>{{ __('admin/settings.fpx_only') }}</option>
                                <option value="2" {{ old('payment_channel', $gateway?->payment_channel) == 2 ? 'selected' : '' }}>{{ __('admin/settings.credit_card_only') }}</option>
                            </select>
                            @error('payment_channel')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                            <label class="block text-sm font-semibold text-slate-900 mb-1">Base URL</label>
                            <input type="url" name="base_url"
                                   value="{{ old('base_url', $gateway?->base_url ?? 'https://toyyibpay.com') }}"
                                   class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm focus:border-teal-400 focus:ring-2 focus:ring-teal-100 focus:outline-none" required>
                            @error('base_url')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                            <label class="block text-sm font-semibold text-slate-900 mb-1">API URL</label>
                            <input type="url" name="api_url"
                                   value="{{ old('api_url', $gateway?->api_url ?? 'https://toyyibpay.com') }}"
                                   class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm focus:border-teal-400 focus:ring-2 focus:ring-teal-100 focus:outline-none" required>
                            @error('api_url')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                            <label class="block text-sm font-semibold text-slate-900 mb-1">{{ __('admin/settings.invoice_expiry_days') }}</label>
                            <input type="number" name="expiry_days" min="1" max="100"
                                   value="{{ old('expiry_days', $gateway?->expiry_days ?? 3) }}"
                                   class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm focus:border-teal-400 focus:ring-2 focus:ring-teal-100 focus:outline-none" required>
                            <p class="mt-1.5 text-xs text-slate-500">{{ __('admin/settings.invoice_expiry_help') }}</p>
                            @error('expiry_days')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <div class="sm:col-span-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                            <input type="hidden" name="is_active" value="0">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-900">{{ __('admin/settings.enable_gateway') }}</label>
                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ __('admin/settings.current_status') }} 
                                        @if($gateway?->is_active ?? true)
                                            <span class="font-bold text-emerald-600">{{ __('admin/settings.status_active') }}</span>
                                        @else
                                            <span class="font-bold text-amber-600">{{ __('admin/settings.status_inactive') }}</span>
                                        @endif
                                    </p>
                                </div>
                                <label class="relative inline-flex cursor-pointer items-center">
                                    <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                                           @checked(old('is_active', $gateway?->is_active ?? true))>
                                    <div class="h-6 w-11 rounded-full bg-slate-300 transition peer-checked:bg-teal-500"></div>
                                    <span class="absolute left-1 top-1 h-4 w-4 rounded-full bg-white transition peer-checked:translate-x-5"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between gap-4 border-t border-slate-200 px-6 py-4">
                        <p class="text-sm text-slate-500">{{ __('admin/settings.gateway_settings_help') }}</p>
                        <button type="submit" class="inline-flex items-center rounded-xl px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:opacity-90" style="background:#0d9488">
                            {{ __('admin/settings.save_configuration') }}
                        </button>
                    </div>
                </form>
            </div>

            @elseif($currentSectionKey === 'monthly_reports')
            <div class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h2 class="text-xl font-bold text-slate-900">{{ __('reports.monthly.settings_title') }}</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ __('reports.monthly.settings_subtitle') }}</p>
                </div>
                <form method="POST" action="{{ route('admin.settings.update', ['section' => 'monthly_reports']) }}">
                    @csrf
                    <div class="space-y-4 p-6">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <label for="monthly_report_schedule" class="mb-1 block text-sm font-semibold text-slate-900">{{ __('reports.monthly.collection_date') }}</label>
                            <select id="monthly_report_schedule" name="monthly_report_schedule" class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm focus:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-100">
                                <option value="first_day" {{ old('monthly_report_schedule', $company->monthly_report_schedule) === 'first_day' ? 'selected' : '' }}>{{ __('reports.monthly.first_day') }}</option>
                                <option value="last_day" {{ old('monthly_report_schedule', $company->monthly_report_schedule) === 'last_day' ? 'selected' : '' }}>{{ __('reports.monthly.last_day') }}</option>
                                <option value="custom_day" {{ old('monthly_report_schedule', $company->monthly_report_schedule) === 'custom_day' ? 'selected' : '' }}>{{ __('reports.monthly.custom_day') }}</option>
                            </select>
                            @error('monthly_report_schedule')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div id="custom-report-day" class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <label for="monthly_report_custom_day" class="mb-1 block text-sm font-semibold text-slate-900">{{ __('reports.monthly.custom_day_label') }}</label>
                            <input id="monthly_report_custom_day" type="number" name="monthly_report_custom_day" min="1" max="31" value="{{ old('monthly_report_custom_day', $company->monthly_report_custom_day) }}" class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm focus:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-100">
                            <p class="mt-1.5 text-xs text-slate-500">{{ __('reports.monthly.custom_day_help') }}</p>
                            @error('monthly_report_custom_day')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <label for="monthly_report_pdf_title" class="mb-1 block text-sm font-semibold text-slate-900">{{ __('reports.monthly.pdf_title') }}</label>
                            <input id="monthly_report_pdf_title" name="monthly_report_pdf_title" value="{{ old('monthly_report_pdf_title', $company->monthly_report_pdf_title) }}" placeholder="Monthly Learning Statement" class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900">
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <label for="monthly_report_pdf_footer" class="mb-1 block text-sm font-semibold text-slate-900">{{ __('reports.monthly.pdf_footer') }}</label>
                            <textarea id="monthly_report_pdf_footer" name="monthly_report_pdf_footer" rows="3" class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900" placeholder="Contoh: Dokumen ini dijana secara automatik.">{{ old('monthly_report_pdf_footer', $company->monthly_report_pdf_footer) }}</textarea>
                        </div>
                    </div>
                    <div class="flex items-center justify-between gap-4 border-t border-slate-200 px-6 py-4">
                        <p class="text-sm text-slate-500">{{ __('reports.monthly.student_visibility') }}</p>
                        <button type="submit" class="inline-flex items-center rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-700">{{ __('reports.monthly.save') }}</button>
                    </div>
                </form>
            </div>

            <script>
                const scheduleInput = document.getElementById('monthly_report_schedule');
                const customDayInput = document.getElementById('custom-report-day');
                const toggleCustomDay = () => customDayInput.classList.toggle('hidden', scheduleInput.value !== 'custom_day');
                scheduleInput.addEventListener('change', toggleCustomDay);
                toggleCustomDay();
            </script>

            @elseif($currentSectionKey === 'security')
            {{-- ─── SECURITY: Change Password ─── --}}
            <div class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h2 class="text-xl font-bold text-slate-900">{{ __('admin/settings.security.title') }}</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ __('admin/settings.security.subtitle') }}</p>
                </div>
                <form method="POST" action="{{ route('admin.settings.update', ['section' => 'security']) }}">
                    @csrf
                    <div class="p-6 space-y-4 max-w-xl">

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                            <label class="block text-sm font-semibold text-slate-900 mb-1">{{ __('admin/settings.account_email') }}</label>
                            <p class="text-sm text-slate-700 bg-white border border-slate-200 rounded-xl px-3 py-2.5">{{ $user->email }}</p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                            <label for="current_password" class="block text-sm font-semibold text-slate-900 mb-1">{{ __('admin/settings.current_password') }}</label>
                            <input id="current_password" type="password" name="current_password" autocomplete="current-password"
                                   class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm focus:border-teal-400 focus:ring-2 focus:ring-teal-100 focus:outline-none" required>
                            @error('current_password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                            <label for="password" class="block text-sm font-semibold text-slate-900 mb-1">{{ __('admin/settings.new_password') }}</label>
                            <input id="password" type="password" name="password" autocomplete="new-password"
                                   class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm focus:border-teal-400 focus:ring-2 focus:ring-teal-100 focus:outline-none" required>
                            <p class="mt-1.5 text-xs text-slate-500">{{ __('admin/settings.password_help') }}</p>
                            @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                            <label for="password_confirmation" class="block text-sm font-semibold text-slate-900 mb-1">{{ __('admin/settings.confirm_new_password') }}</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password"
                                   class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm focus:border-teal-400 focus:ring-2 focus:ring-teal-100 focus:outline-none" required>
                        </div>
                    </div>
                    <div class="flex items-center justify-between gap-4 border-t border-slate-200 px-6 py-4">
                        <p class="text-sm text-slate-500">{{ __('admin/settings.stay_signed_in') }}</p>
                        <button type="submit" class="inline-flex items-center rounded-xl px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:opacity-90" style="background:#0d9488">
                            {{ __('admin/settings.update_password') }}
                        </button>
                    </div>
                </form>
            </div>
            @endif

            {{-- ─── QUICK LINKS PANEL (always visible at bottom) ─── --}}
            <div class="overflow-hidden rounded-[1.5rem] border border-teal-100 bg-gradient-to-br from-teal-50 to-cyan-50 shadow-sm">
                <div class="border-b border-teal-100 px-6 py-5">
                    <h3 class="text-lg font-bold text-slate-900">{{ __('admin/settings.quick_links') }}</h3>
                    <p class="mt-1 text-sm text-slate-500">{{ __('admin/settings.quick_links_subtitle') }}</p>
                </div>
                <div class="p-5 grid gap-3 sm:grid-cols-2">
                    <a href="{{ route('admin.settings.index', ['section' => 'overview']) }}"
                       class="group flex items-start gap-3 rounded-2xl border border-teal-100 bg-white px-4 py-3 transition hover:border-teal-200 hover:shadow-sm">
                        <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-teal-100 text-teal-700">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span>
                            <span class="block text-sm font-semibold text-slate-900 group-hover:text-teal-700">{{ __('admin/settings.company_profile') }}</span>
                            <span class="mt-0.5 block text-xs text-slate-500">{{ __('admin/settings.company_profile_desc') }}</span>
                        </span>
                    </a>
                    <a href="{{ route('admin.bank_details.index') }}"
                       class="group flex items-start gap-3 rounded-2xl border border-teal-100 bg-white px-4 py-3 transition hover:border-teal-200 hover:shadow-sm">
                        <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-teal-100 text-teal-700">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span>
                            <span class="block text-sm font-semibold text-slate-900 group-hover:text-teal-700">{{ __('admin/settings.bank_account') }}</span>
                            <span class="mt-0.5 block text-xs text-slate-500">{{ __('admin/settings.bank_account_desc') }}</span>
                        </span>
                    </a>


                    <a href="{{ route('admin.subscribe.index') }}"
                       class="group flex items-start gap-3 rounded-2xl border border-teal-100 bg-white px-4 py-3 transition hover:border-teal-200 hover:shadow-sm">
                        <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-teal-100 text-teal-700">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span>
                            <span class="block text-sm font-semibold text-slate-900 group-hover:text-teal-700">{{ __('admin/settings.packages_subscription') }}</span>
                            <span class="mt-0.5 block text-xs text-slate-500">{{ __('admin/settings.packages_subscription_desc') }}</span>
                        </span>
                    </a>
                    <a href="{{ route('admin.bayaran.index') }}"
                       class="group flex items-start gap-3 rounded-2xl border border-teal-100 bg-white px-4 py-3 transition hover:border-teal-200 hover:shadow-sm">
                        <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-indigo-700">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </span>
                        <span>
                            <span class="block text-sm font-semibold text-slate-900 group-hover:text-indigo-700">Senarai Invois Pelajar</span>
                            <span class="mt-0.5 block text-xs text-slate-500">Lihat & urus status invois bayaran pelajar</span>
                        </span>
                    </a>
                </div>
            </div>

        </section>
    </div>
</div>
@endsection

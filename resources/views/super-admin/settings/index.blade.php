@extends('layouts.superadmin')

@section('title', __('super_admin/settings.title'))

@section('content')
@php
    $sectionFields = $currentSection['fields'] ?? [];
    $featureItems = $currentSection['features'] ?? [];
@endphp

<style>
    .superadmin-settings-layout {
        display: block;
    }

    @media (min-width: 1024px) {
        .superadmin-settings-layout {
            display: grid;
            grid-template-columns: 320px minmax(0, 1fr);
            gap: 1.5rem;
            align-items: start;
        }

        .superadmin-settings-sidebar {
            position: sticky;
            top: 1.5rem;
        }
    }
</style>

<div class="px-4 py-6 sm:px-6 lg:px-8">
    @if(session('success'))
        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="superadmin-settings-layout">
        <aside class="superadmin-settings-sidebar w-full rounded-[1.5rem] border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-lg font-bold text-slate-900">{{ __('super_admin/settings.sidebar.title') }}</h2>
                <p class="mt-1 text-sm text-slate-500">{{ __('super_admin/settings.sidebar.subtitle') }}</p>
            </div>

            <nav class="space-y-2 p-3">
                @foreach($navSections as $key => $section)
                    <a href="{{ route('superadmin.settings.index', ['section' => $key]) }}"
                       class="group flex items-center gap-3 rounded-xl px-3 py-3 transition {{ $currentSectionKey === $key ? 'bg-slate-200/90 text-slate-900 shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $currentSectionKey === $key ? 'bg-cyan-100 text-cyan-700' : 'bg-slate-100 text-slate-400' }}">
                            <svg class="h-5 w-5" {{ !empty($section['outline']) ? 'fill=none stroke=currentColor' : 'fill=currentColor' }} viewBox="0 0 24 24">
                                <path @if(!empty($section['outline'])) stroke-linecap="round" stroke-linejoin="round" stroke-width="2" @endif d="{{ $section['icon'] }}"></path>
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

        <section class="min-w-0 space-y-6">
            <div class="flex flex-col gap-4 rounded-[1.5rem] border border-slate-200 bg-white px-6 py-5 shadow-sm lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-cyan-600">{{ __('super_admin/settings.breadcrumb.superadmin') }}</p>
                        <h1 class="text-3xl font-bold text-slate-900">{{ __('super_admin/settings.heading') }}</h1>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2 text-sm text-slate-500">
                    <a href="{{ route('dashboard.index') }}" class="text-cyan-600 hover:underline">{{ __('super_admin/settings.breadcrumb.home') }}</a>
                    <span>/</span>
                    <a href="{{ route('superadmin.settings.index') }}" class="text-cyan-600 hover:underline">{{ __('super_admin/settings.breadcrumb.settings') }}</a>
                    <span>/</span>
                    <span class="font-semibold text-slate-800">{{ $currentSection['title'] }}</span>
                </div>
            </div>

            <form method="POST" enctype="multipart/form-data" action="{{ route('superadmin.settings.update', ['section' => $currentSectionKey]) }}" class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-sm">
                @csrf

                <div class="border-b border-slate-200 px-6 py-5">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900">{{ $currentSection['title'] }}</h2>
                            <p class="mt-1 max-w-3xl text-sm leading-6 text-slate-500">{{ $currentSection['subtitle'] }}</p>
                        </div>
                        @unless($currentSectionKey === 'payment_gateway')
                            <span class="inline-flex w-fit items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold uppercase tracking-wide text-emerald-700">
                                {{ __('super_admin/settings.sidebar.active') }}
                            </span>
                        @endunless
                    </div>
                </div>

                <div class="p-6">
                    @if($currentSectionKey === 'payment_gateway' && $gateway?->is_active && filled($gateway?->user_secret_key))
                        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
                            <h4 class="text-sm font-bold text-emerald-900">✓ Konfigurasi tersimpan dan aktif</h4>
                            <p class="mt-0.5 text-xs text-emerald-700">Secret Key tersimpan terenkripsi. ToyyibPay Global sedia digunakan. Terakhir dikemas kini: {{ $gateway->updated_at?->timezone(config('app.timezone'))->format('d M Y, H:i') ?? '-' }}.</p>
                        </div>
                    @endif
                    <div class="grid gap-4 lg:grid-cols-2">
                        @foreach($sectionFields as $field)
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 {{ !empty($field['full_width']) ? 'lg:col-span-2' : '' }}">
                                @if(($field['type'] ?? 'text') === 'toggle')
                                    <input type="hidden" name="{{ $field['name'] }}" value="0">
                                    <div class="flex items-center justify-between gap-4">
                                        <div class="min-w-0">
                                            <label for="{{ $field['name'] }}" class="block text-sm font-semibold text-slate-900">{{ $field['label'] }}</label>
                                            @if(!empty($field['help']))
                                                <p class="mt-1 text-sm text-slate-500">{{ $field['help'] }}</p>
                                            @endif
                                        </div>
                                        <label class="relative inline-flex cursor-pointer items-center">
                                            <input
                                                id="{{ $field['name'] }}"
                                                type="checkbox"
                                                name="{{ $field['name'] }}"
                                                value="1"
                                                class="sr-only peer"
                                                {{ old($field['name'], $field['value'] ?? ($field['default'] ?? '0')) === '1' ? 'checked' : '' }}
                                            >
                                            <div class="h-6 w-11 rounded-full bg-slate-300 transition peer-checked:bg-cyan-500"></div>
                                            <span class="absolute left-1 top-1 h-4 w-4 rounded-full bg-white transition peer-checked:translate-x-5"></span>
                                        </label>
                                    </div>
                                @else
                                    <label for="{{ $field['name'] }}" class="block text-sm font-semibold text-slate-900">{{ $field['label'] }}</label>

                                    @if(($field['type'] ?? 'text') === 'file')
                                        <input id="{{ $field['name'] }}" type="file" name="{{ $field['name'] }}" accept="image/png,image/jpeg,image/webp" class="mt-3 block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm">
                                        @if(filled($field['value'] ?? ''))
                                            <img src="{{ asset($field['value']) }}" alt="Current invoice logo" class="mt-3 h-16 max-w-52 rounded-lg border border-slate-200 bg-white object-contain p-2">
                                        @endif
                                    @elseif(($field['type'] ?? 'text') === 'select')
                                        <select
                                            id="{{ $field['name'] }}"
                                            name="{{ $field['name'] }}"
                                            class="mt-3 block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm focus:border-cyan-400 focus:outline-none focus:ring-2 focus:ring-cyan-100"
                                        >
                                            @foreach(($field['options'] ?? []) as $optionValue => $optionLabel)
                                                <option value="{{ $optionValue }}" {{ old($field['name'], $field['value'] ?? ($field['default'] ?? '')) == $optionValue ? 'selected' : '' }}>
                                                    {{ $optionLabel }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @elseif(($field['type'] ?? 'text') === 'textarea')
                                        <textarea
                                            id="{{ $field['name'] }}"
                                            name="{{ $field['name'] }}"
                                            rows="{{ $field['rows'] ?? 4 }}"
                                            class="mt-3 block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm focus:border-cyan-400 focus:outline-none focus:ring-2 focus:ring-cyan-100"
                                        >{{ old($field['name'], $field['value'] ?? ($field['default'] ?? '')) }}</textarea>
                                    @else
                                        <input
                                            id="{{ $field['name'] }}"
                                            type="{{ $field['type'] ?? 'text' }}"
                                            name="{{ $field['name'] }}"
                                            value="{{ old($field['name'], $field['value'] ?? ($field['default'] ?? '')) }}"
                                            placeholder="{{ $field['placeholder'] ?? '' }}"
                                            class="mt-3 block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm focus:border-cyan-400 focus:outline-none focus:ring-2 focus:ring-cyan-100"
                                            @if(($field['type'] ?? 'text') === 'number') min="0" @endif
                                        >
                                    @endif

                                    @if(!empty($field['help']))
                                        <p class="mt-2 text-sm text-slate-500">{{ $field['help'] }}</p>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 flex flex-col items-start justify-between gap-4 border-t border-slate-200 pt-5 sm:flex-row sm:items-center">
                        <p class="text-sm text-slate-500">{{ __('super_admin/settings.forms.save_note') }}</p>
                        <button type="submit" class="inline-flex items-center rounded-xl px-5 py-3 text-sm font-semibold shadow-sm transition hover:opacity-90" style="background-color: #0891b2 !important; color: #ffffff !important; border: 1px solid #0e7490;">
                            {{ $currentSectionKey === 'email_templates' ? __('super_admin/settings.forms.save_template') : __('super_admin/settings.forms.save') }}
                        </button>
                    </div>
                </div>
            </form>

            @if($currentSectionKey === 'email_templates')
                <form method="POST" action="{{ route('superadmin.settings.email-templates.send') }}" class="overflow-hidden rounded-[1.5rem] border border-cyan-200 bg-white shadow-sm">
                    @csrf

                    <div class="border-b border-cyan-100 bg-cyan-50 px-6 py-5">
                        <h3 class="text-xl font-bold text-slate-900">Send Welcome Email Manually</h3>
                        <p class="mt-1 text-sm leading-6 text-slate-600">Select one or more company accounts. The saved Welcome Email template will be sent to each selected account.</p>
                    </div>

                    <div class="p-6">
                        @error('company_ids')
                            <p class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">{{ $message }}</p>
                        @enderror

                        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                            <p class="text-sm font-semibold text-slate-700">Company accounts</p>
                            <button type="button" id="toggle-all-email-companies" class="rounded-lg border border-cyan-200 bg-white px-3 py-2 text-xs font-bold text-cyan-700 hover:bg-cyan-50">Select all</button>
                        </div>

                        <div class="max-h-80 divide-y divide-slate-100 overflow-y-auto rounded-xl border border-slate-200">
                            @forelse($emailCompanies as $company)
                                @php
                                    $email = $company->user?->email ?: $company->pic_email ?: $company->email_syarikat;
                                    $companyName = $company->nama_syarikat ?: $company->nama_wakil_syarikat ?: 'Company #' . $company->id;
                                @endphp
                                <label class="flex cursor-pointer items-center gap-3 px-4 py-3 hover:bg-slate-50">
                                    <input type="checkbox" name="company_ids[]" value="{{ $company->id }}" class="email-company-checkbox h-4 w-4 rounded border-slate-300 text-cyan-600 focus:ring-cyan-500" {{ in_array($company->id, old('company_ids', [])) ? 'checked' : '' }}>
                                    <span class="min-w-0 flex-1">
                                        <span class="block truncate text-sm font-bold text-slate-900">{{ $companyName }}</span>
                                        <span class="block truncate text-sm text-slate-500">{{ $email ?: 'No email address available' }}</span>
                                    </span>
                                    @if(!$company->user || blank($email))
                                        <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">Will be skipped</span>
                                    @endif
                                </label>
                            @empty
                                <p class="px-4 py-8 text-center text-sm text-slate-500">No company accounts are available.</p>
                            @endforelse
                        </div>

                        <div class="mt-5 flex flex-col gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-sm text-slate-500">Emails are sent immediately using the current Welcome Email subject and body.</p>
                            <button type="submit" class="inline-flex items-center justify-center rounded-xl px-5 py-3 text-sm font-semibold shadow-sm transition hover:opacity-90" style="background-color: #0891b2 !important; color: #ffffff !important; border: 1px solid #0e7490;">Send Welcome Email</button>
                        </div>
                    </div>
                </form>

                <script>
                    document.getElementById('toggle-all-email-companies')?.addEventListener('click', function () {
                        const checkboxes = [...document.querySelectorAll('.email-company-checkbox')];
                        const selectAll = checkboxes.some((checkbox) => !checkbox.checked);
                        checkboxes.forEach((checkbox) => checkbox.checked = selectAll);
                        this.textContent = selectAll ? 'Clear selection' : 'Select all';
                    });
                </script>
            @endif

            @if($managementPanel)
                <div class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-sm">
                    <div class="flex flex-col gap-4 border-b border-slate-200 px-6 py-5 lg:flex-row lg:items-start lg:justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900">{{ $managementPanel['title'] }}</h3>
                            <p class="mt-1 text-sm leading-6 text-slate-500">{{ $managementPanel['subtitle'] }}</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @if(!empty($managementPanel['create_url']))
                                <a href="{{ $managementPanel['create_url'] }}" class="inline-flex items-center rounded-xl bg-cyan-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-cyan-700">
                                    + {{ __('super_admin/settings.management.add') }}
                                </a>
                            @endif
                            <a href="{{ $managementPanel['manage_url'] }}" class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                {{ __('super_admin/settings.management.manage_all') }}
                            </a>
                        </div>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse($managementPanel['records'] as $record)
                            <div class="flex flex-col gap-3 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-bold text-slate-900">{{ $record['title'] }}</p>
                                    <p class="mt-1 truncate text-sm text-slate-500">{{ $record['meta'] }}</p>
                                </div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">{{ $record['status'] }}</span>
                                    @if(!empty($record['view_url']))
                                        <a href="{{ $record['view_url'] }}" class="rounded-lg px-3 py-2 text-xs font-bold text-cyan-700 hover:bg-cyan-50">{{ __('super_admin/settings.management.view') }}</a>
                                    @endif
                                    @if(!empty($record['edit_url']))
                                        <a href="{{ $record['edit_url'] }}" class="rounded-lg px-3 py-2 text-xs font-bold text-cyan-700 hover:bg-cyan-50">{{ __('super_admin/settings.management.edit') }}</a>
                                    @endif
                                    @if(!empty($record['delete_url']))
                                        <form method="POST" action="{{ $record['delete_url'] }}" onsubmit="return confirm('{{ __('super_admin/settings.management.delete_confirm') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg px-3 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50">{{ __('super_admin/settings.management.delete') }}</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-10 text-center text-sm text-slate-500">{{ __('super_admin/settings.management.empty') }}</div>
                        @endforelse
                    </div>
                </div>
            @endif

            <div class="overflow-hidden rounded-[1.5rem] border border-cyan-100 bg-gradient-to-br from-cyan-50 to-sky-50 shadow-sm">
                <div class="border-b border-cyan-100 px-6 py-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900">{{ __('super_admin/settings.features.title') }}</h3>
                            <p class="mt-1 text-sm text-slate-500">{{ __('super_admin/settings.features.subtitle') }}</p>
                        </div>
                        <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-cyan-700 shadow-sm">
                            {{ $currentSection['badge'] }}
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid gap-3 sm:grid-cols-2">
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
        </section>
    </div>
</div>
@endsection

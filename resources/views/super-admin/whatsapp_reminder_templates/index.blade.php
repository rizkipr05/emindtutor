@extends('layouts.superadmin')

@section('content')
@php($labels = ['teachers' => __('super_admin/reminder_templates.teachers'), 'students' => __('super_admin/reminder_templates.students'), 'company' => __('super_admin/reminder_templates.company')])
<div class="max-w-6xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div><h1 class="text-2xl font-bold">{{ __('super_admin/reminder_templates.title') }}</h1><p class="text-gray-500">{{ __('super_admin/reminder_templates.subtitle') }}</p></div>
        <a href="{{ route('superadmin.whatsapp-reminder-templates.create', ['audience' => $audience]) }}" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700">+ {{ __('super_admin/reminder_templates.create') }}</a>
    </div>
    <div class="mb-5 rounded-xl border {{ $hasFonnteToken ? 'border-emerald-200 bg-emerald-50' : 'border-amber-200 bg-amber-50' }} p-4">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="font-semibold text-gray-900">Token Fonnte untuk WhatsApp Reminder</h2>
                <p class="mt-1 text-sm {{ $hasFonnteToken ? 'text-emerald-800' : 'text-amber-800' }}">{{ $hasFonnteToken ? 'Token telah dikonfigurasi dan disimpan secara terenkripsi.' : 'Token belum dikonfigurasi. Reminder WhatsApp tidak dapat dihantar.' }}</p>
            </div>
            <form method="POST" action="{{ route('superadmin.whatsapp-reminder-templates.fonnte-token.update', ['audience' => $audience]) }}" class="flex w-full flex-col gap-2 sm:flex-row lg:w-auto">
                @csrf
                <input type="password" name="fonnte_token" required autocomplete="new-password" placeholder="{{ $hasFonnteToken ? 'Masukkan token baharu untuk ganti' : 'Masukkan token Fonnte' }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm lg:w-80">
                <button type="submit" class="rounded-lg bg-teal-600 px-4 py-2 text-sm font-medium text-white hover:bg-teal-700">{{ $hasFonnteToken ? 'Tukar Token' : 'Simpan Token' }}</button>
            </form>
        </div>
        @error('fonnte_token')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div class="flex gap-2 mb-5">@foreach($labels as $key => $label)<a href="{{ route('superadmin.whatsapp-reminder-templates.index', ['audience' => $key]) }}" class="px-4 py-2 rounded-lg {{ $audience === $key ? 'bg-teal-600 text-white' : 'bg-white border text-gray-600' }}">{{ __('super_admin/reminder_templates.tab', ['audience' => $label]) }}</a>@endforeach</div>
    @if(session('success'))<div class="mb-4 px-4 py-3 rounded border border-green-300 bg-green-100 text-green-800">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="mb-4 px-4 py-3 rounded border border-red-300 bg-red-100 text-red-800">{{ session('error') }}</div>@endif
    <div class="bg-white shadow rounded-xl overflow-x-auto"><table class="w-full text-sm text-left"><thead class="bg-gray-50 border-b"><tr><th class="px-5 py-3">{{ __('super_admin/reminder_templates.name') }}</th><th class="px-5 py-3">{{ __('super_admin/reminder_templates.message') }}</th><th class="px-5 py-3">{{ __('super_admin/reminder_templates.status') }}</th><th class="px-5 py-3 text-right">{{ __('super_admin/reminder_templates.actions') }}</th></tr></thead><tbody class="divide-y">@forelse($templates as $template)<tr><td class="px-5 py-4 font-semibold">{{ $template->title }}</td><td class="px-5 py-4 text-gray-600 max-w-xl">{{ $template->message }}</td><td class="px-5 py-4">{{ $template->is_active ? __('super_admin/reminder_templates.active') : __('super_admin/reminder_templates.inactive') }}</td><td class="px-5 py-4 text-right whitespace-nowrap"><form class="inline-flex items-center gap-2 mr-3" method="POST" action="{{ route('superadmin.whatsapp-reminder-templates.send', $template) }}" onsubmit="return confirm(@json(__('super_admin/reminder_templates.confirm_send')))">@csrf<select name="company_id" required class="border rounded px-2 py-1 max-w-40"><option value="">{{ __('super_admin/reminder_templates.select_company') }}</option>@foreach($companies as $company)<option value="{{ $company->id }}">{{ $company->nama_wakil_syarikat }}</option>@endforeach</select><button {{ $template->is_active ? '' : 'disabled' }} class="bg-green-600 disabled:bg-gray-300 text-white px-2 py-1 rounded">{{ __('super_admin/reminder_templates.send_now') }}</button></form><a href="{{ route('superadmin.whatsapp-reminder-templates.edit', $template) }}" class="text-blue-600 mr-3">{{ __('super_admin/reminder_templates.edit') }}</a><form class="inline" method="POST" action="{{ route('superadmin.whatsapp-reminder-templates.destroy', $template) }}" onsubmit="return confirm(@json(__('super_admin/reminder_templates.confirm_delete')))">@csrf @method('DELETE')<button class="text-red-600">{{ __('super_admin/reminder_templates.delete') }}</button></form></td></tr>@empty<tr><td colspan="4" class="px-5 py-10 text-center text-gray-500">{{ __('super_admin/reminder_templates.empty', ['audience' => strtolower($labels[$audience])]) }}</td></tr>@endforelse</tbody></table></div>
</div>
@endsection

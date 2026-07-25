@extends('layouts.superadmin')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">{{ __('super_admin/staff.edit.title') }}</h1>

    <div class="bg-white rounded-xl shadow p-6">
        <form action="{{ route('superadmin.staff.update', $staff->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Informasi User --}}
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-3">{{ __('super_admin/staff.edit.user_info') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-600 mb-1">{{ __('super_admin/staff.show.name') }}</label>
                        <input type="text" value="{{ $staff->user->name }}" disabled
                               class="w-full px-4 py-2 border rounded-lg bg-gray-100 text-gray-700">
                    </div>
                    <div>
                        <label class="block text-gray-600 mb-1">{{ __('super_admin/staff.create.labels.email') }}</label>
                        <input type="text" value="{{ $staff->user->email }}" disabled
                               class="w-full px-4 py-2 border rounded-lg bg-gray-100 text-gray-700">
                    </div>
                    <div>
                        <label class="block text-gray-600 mb-1">{{ __('super_admin/staff.show.phone') }}</label>
                        <input type="text" name="phone" value="{{ old('phone', $staff->phone) }}"
                               class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200">
                    </div>
                    <div>
                        <label class="block text-gray-600 mb-1">{{ __('super_admin/staff.create.labels.ic_number') }}</label>
                        <input type="text" name="ic_number" value="{{ old('ic_number', $staff->ic_number) }}"
                               class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200">
                    </div>
                </div>
            </div>

            {{-- Informasi Allowance --}}
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-3">{{ __('super_admin/staff.edit.allowance_info') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-600 mb-1">{{ __('super_admin/staff.show.allowance') }}</label>
                        <input type="number" name="allowance" value="{{ old('allowance', $staff->allowance) }}"
                               class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200">
                    </div>
                    <div>
                        <label class="block text-gray-600 mb-1">{{ __('super_admin/staff.show.allowance_type') }}</label>
                        <select name="allowance_type" 
                                class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200">
                            <option value="">{{ __('super_admin/staff.create.labels.allowance_type_options.choose') }}</option>
                            <option value="per_hour" {{ $staff->allowance_type == 'per_hour' ? 'selected' : '' }}>{{ __('super_admin/staff.create.labels.allowance_type_options.per_hour') }}</option>
                            <option value="per_session" {{ $staff->allowance_type == 'per_session' ? 'selected' : '' }}>{{ __('super_admin/staff.create.labels.allowance_type_options.per_session') }}</option>
                            <option value="per_month" {{ $staff->allowance_type == 'per_month' ? 'selected' : '' }}>{{ __('super_admin/staff.create.labels.allowance_type_options.per_month') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Informasi Bank --}}
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-3">{{ __('super_admin/staff.edit.bank_info') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-600 mb-1">{{ __('super_admin/staff.show.bank_name') }}</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name', $staff->bank_name) }}"
                               class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200">
                    </div>
                    <div>
                        <label class="block text-gray-600 mb-1">{{ __('super_admin/staff.show.bank_account_number') }}</label>
                        <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $staff->bank_account_number) }}"
                               class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200">
                    </div>
                    <div>
                        <label class="block text-gray-600 mb-1">{{ __('super_admin/staff.show.bank_account_holder') }}</label>
                        <input type="text" name="bank_account_holder" value="{{ old('bank_account_holder', $staff->bank_account_holder) }}"
                               class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200">
                    </div>
                    <div>
                        <label class="block text-gray-600 mb-1">{{ __('super_admin/staff.show.account_holders_ic_number') }}</label>
                        <input type="text" name="account_holders_ic_number" value="{{ old('account_holders_ic_number', $staff->account_holders_ic_number) }}"
                               class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200">
                    </div>
                </div>
            </div>

            {{-- Status --}}
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-3">{{ __('super_admin/staff.edit.status') }}</h2>
                <select name="status" class="w-full md:w-1/3 px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200">
                    <option value="active" {{ $staff->status == 'active' ? 'selected' : '' }}>{{ __('super_admin/staff.show.status_active.active') }}</option>
                    <option value="inactive" {{ $staff->status == 'inactive' ? 'selected' : '' }}>{{ __('super_admin/staff.show.status_active.inactive') }}</option>
                </select>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex space-x-3">
                     <a href="{{ route('superadmin.staff.index') }}" 
                         class="px-4 py-2 rounded-lg bg-gray-500 text-white hover:bg-gray-600">{{ __('super_admin/staff.edit.buttons.cancel') }}</a>
                     <button type="submit" 
                                class="px-4 py-2 rounded-lg bg-blue-500 text-white hover:bg-blue-600">{{ __('super_admin/staff.edit.buttons.update') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
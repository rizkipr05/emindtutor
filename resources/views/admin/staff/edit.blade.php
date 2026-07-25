{{-- resources/views/admin/staff/edit.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4">
    <div class="mx-auto bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-semibold text-gray-900">{{ __('admin/staff/edit.title') }}</h1>
        </div>

        <form action="{{ route('admin.staff.update', $staff) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Personal Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('admin/staff/edit.sections.personal') }}</h3>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">{{ __('admin/staff/edit.labels.name') }}</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $staff->user->name) }}" 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">{{ __('admin/staff/edit.labels.email') }}</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $staff->user->email) }}" 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                <label for="ic_number" class="block text-sm font-medium text-gray-700">{{ __('admin/staff/edit.labels.ic_number') }}</label>
                <input type="text" name="ic_number" id="ic_number" value="{{ old('ic_number', $staff->ic_number) }}" 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <p class="mt-1 text-sm text-gray-500">{{ __('admin/staff/edit.labels.ic_helper') }}</p>
                            @error('ic_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">{{ __('admin/staff/edit.labels.phone') }}</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $staff->phone) }}" 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Allowance Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('admin/staff/edit.sections.allowance') }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="allowance" class="block text-sm font-medium text-gray-700">{{ __('admin/staff/edit.labels.allowance') }}</label>
                            <input type="text" name="allowance" id="allowance" value="{{ old('allowance', $staff->allowance) }}" 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('allowance')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="allowance_type" class="block text-sm font-medium text-gray-700">{{ __('admin/staff/edit.labels.allowance_type') }}</label>
                            <select name="allowance_type" id="allowance_type" 
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">{{ __('admin/staff/edit.labels.allowance_type_options.choose') }}</option>
                                <option value="per_hour" {{ old('allowance_type', $staff->allowance_type) == 'per_hour' ? 'selected' : '' }}>{{ __('admin/staff/edit.labels.allowance_type_options.per_hour') }}</option>
                                <option value="session" {{ old('allowance_type', $staff->allowance_type) == 'session' ? 'selected' : '' }}>{{ __('admin/staff/edit.labels.allowance_type_options.session') }}</option>
                                <option value="per_month" {{ old('allowance_type', $staff->allowance_type) == 'per_month' ? 'selected' : '' }}>{{ __('admin/staff/edit.labels.allowance_type_options.per_month') }}</option>
                            </select>
                            @error('allowance_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Bank Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('admin/staff/edit.sections.bank') }}</h3>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label for="bank_name" class="block text-sm font-medium text-gray-700">{{ __('admin/staff/edit.labels.bank_name') }}</label>
                            <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', $staff->bank_name) }}" 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('bank_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="bank_account_number" class="block text-sm font-medium text-gray-700">{{ __('admin/staff/edit.labels.bank_account_number') }}</label>
                            <input type="text" name="bank_account_number" id="bank_account_number" value="{{ old('bank_account_number', $staff->bank_account_number) }}" 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('bank_account_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="bank_account_holder" class="block text-sm font-medium text-gray-700">{{ __('admin/staff/edit.labels.bank_account_holder') }}</label>
                            <input type="text" name="bank_account_holder" id="bank_account_holder" value="{{ old('bank_account_holder', $staff->bank_account_holder) }}" 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('bank_account_holder')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="account_holders_ic_number" class="block text-sm font-medium text-gray-700">{{ __('admin/staff/edit.labels.account_holders_ic_number') }}</label>
                            <input type="text" name="account_holders_ic_number" id="account_holders_ic_number" value="{{ old('account_holders_ic_number', $staff->account_holders_ic_number) }}" 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <p class="mt-1 text-sm text-gray-500">{{ __('admin/staff/edit.labels.optional') }}</p>
                            @error('account_holders_ic_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.staff.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-200">
                    {{ __('admin/staff/edit.buttons.cancel') }}
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200">
                    {{ __('admin/staff/edit.buttons.update') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
{{-- resources/views/superadmin/staff/create.blade.php --}}
@extends('layouts.superadmin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-semibold text-gray-900">{{ __('super_admin/staff.create.title') }}</h1>
        </div>

    <form action="{{ route('superadmin.staff.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="space-y-6">
                <!-- Personal Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('super_admin/staff.create.personal_info') }}</h3>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">{{ __('super_admin/staff.create.labels.name') }}</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">{{ __('super_admin/staff.create.labels.email') }}</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ic_number" class="block text-sm font-medium text-gray-700">{{ __('super_admin/staff.create.labels.ic_number') }}</label>
                            <input type="text" name="ic_number" id="ic_number" value="{{ old('ic_number') }}" 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <p class="mt-1 text-sm text-gray-500">{{ __('super_admin/staff.create.labels.ic_helper') }}</p>
                            @error('ic_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">{{ __('super_admin/staff.create.labels.phone') }}</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Allowance Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('super_admin/staff.create.allowance_info') }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="allowance" class="block text-sm font-medium text-gray-700">{{ __('super_admin/staff.create.labels.allowance') }}</label>
                            <input type="number" name="allowance" id="allowance" value="{{ old('allowance') }}" 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('allowance')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="allowance_type" class="block text-sm font-medium text-gray-700">{{ __('super_admin/staff.create.labels.allowance_type') }}</label>
                            <select name="allowance_type" id="allowance_type" 
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">{{ __('super_admin/staff.create.labels.allowance_type_options.choose') }}</option>
                                <option value="per_hour" {{ old('allowance_type') == 'per_hour' ? 'selected' : '' }}>{{ __('super_admin/staff.create.labels.allowance_type_options.per_hour') }}</option>
                                <option value="per_session" {{ old('allowance_type') == 'per_session' ? 'selected' : '' }}>{{ __('super_admin/staff.create.labels.allowance_type_options.per_session') }}</option>
                                <option value="per_month" {{ old('allowance_type') == 'per_month' ? 'selected' : '' }}>{{ __('super_admin/staff.create.labels.allowance_type_options.per_month') }}</option>
                            </select>
                            @error('allowance_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Bank Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('super_admin/staff.create.bank_info') }}</h3>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label for="bank_name" class="block text-sm font-medium text-gray-700">{{ __('super_admin/staff.create.labels.bank_name') }}</label>
                            <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name') }}" 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('bank_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="bank_account_number" class="block text-sm font-medium text-gray-700">{{ __('super_admin/staff.create.labels.bank_account_number') }}</label>
                            <input type="text" name="bank_account_number" id="bank_account_number" value="{{ old('bank_account_number') }}" 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('bank_account_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="bank_account_holder" class="block text-sm font-medium text-gray-700">{{ __('super_admin/staff.create.labels.bank_account_holder') }}</label>
                            <input type="text" name="bank_account_holder" id="bank_account_holder" value="{{ old('bank_account_holder') }}" 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('bank_account_holder')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="account_holders_ic_number" class="block text-sm font-medium text-gray-700">{{ __('super_admin/staff.create.labels.account_holders_ic_number') }}</label>
                            <input type="text" name="account_holders_ic_number" id="account_holders_ic_number" value="{{ old('account_holders_ic_number') }}" 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <p class="mt-1 text-sm text-gray-500">{{ __('super_admin/staff.create.labels.optional') }}</p>
                            @error('account_holders_ic_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('superadmin.staff.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-200">
                    {{ __('super_admin/staff.create.buttons.cancel') }}
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200">
                    {{ __('super_admin/staff.create.buttons.save') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
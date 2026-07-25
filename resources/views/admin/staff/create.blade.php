@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-4">
    <div class="mx-auto">
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                <h1 class="text-lg font-semibold text-gray-900">{{ __('admin/staff/create.title') }}</h1>
            </div>

            <form action="{{ route('admin.staff.store') }}" method="POST" class="p-4">
                @csrf
                
                <!-- Personal Information -->
                <div class="mb-6">
                    <h3 class="text-base font-medium text-gray-900 mb-3">{{ __('admin/staff/create.sections.personal') }}</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin/staff/create.labels.name') }}</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin/staff/create.labels.email') }}</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin/staff/create.labels.phone') }}</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

               <div>
                <label for="ic_number" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin/staff/create.labels.ic_number') }}</label>
                <input type="text" name="ic_number" id="ic_number" value="{{ old('ic_number') }}" 
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                <p class="mt-1 text-xs text-gray-500">{{ __('admin/staff/create.labels.ic_helper') }}</p>
                            @error('ic_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Allowance Information -->
                <div class="mb-6">
                    <h3 class="text-base font-medium text-gray-900 mb-3">{{ __('admin/staff/create.sections.allowance') }}</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="allowance" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin/staff/create.labels.allowance') }}</label>
                            <input type="number" name="allowance" id="allowance" value="{{ old('allowance') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            @error('allowance')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="allowance_type" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin/staff/create.labels.allowance_type') }}</label>
                            <select name="allowance_type" id="allowance_type" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="">{{ __('admin/staff/create.labels.allowance_type_options.choose') }}</option>
                                <option value="per_hour" {{ old('allowance_type') == 'per_hour' ? 'selected' : '' }}>{{ __('admin/staff/create.labels.allowance_type_options.per_hour') }}</option>
                                <option value="session" {{ old('allowance_type') == 'session' ? 'selected' : '' }}>{{ __('admin/staff/create.labels.allowance_type_options.session') }}</option>
                                <option value="per_month" {{ old('allowance_type') == 'per_month' ? 'selected' : '' }}>{{ __('admin/staff/create.labels.allowance_type_options.per_month') }}</option>
                            </select>
                            @error('allowance_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Bank Information -->
                <div class="mb-6">
                    <h3 class="text-base font-medium text-gray-900 mb-3">{{ __('admin/staff/create.sections.bank') }}</h3>
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin/staff/create.labels.bank_name') }}</label>
                                <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                @error('bank_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="bank_account_number" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin/staff/create.labels.bank_account_number') }}</label>
                                <input type="text" name="bank_account_number" id="bank_account_number" value="{{ old('bank_account_number') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                @error('bank_account_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="bank_account_holder" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin/staff/create.labels.bank_account_holder') }}</label>
                            <input type="text" name="bank_account_holder" id="bank_account_holder" value="{{ old('bank_account_holder') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            @error('bank_account_holder')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="account_holders_ic_number" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin/staff/create.labels.account_holders_ic_number') }}</label>
                            <input type="text" name="account_holders_ic_number" id="account_holders_ic_number" value="{{ old('account_holders_ic_number') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <p class="mt-1 text-xs text-gray-500">{{ __('admin/staff/create.labels.optional') }}</p>
                            @error('account_holders_ic_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-3 sm:justify-end pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.staff.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50 text-center text-sm font-medium transition-colors">
                        {{ __('admin/staff/create.buttons.cancel') }}
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm font-medium transition-colors">
                        {{ __('admin/staff/create.buttons.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
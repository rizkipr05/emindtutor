@extends('layouts.admin')

@section('content')
<div class="min-h-screen">
    <div class="mx-auto px-2 sm:px-4 lg:px-6">
        <!-- Header Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <a href="{{ route('admin.teachers.index') }}" 
                           class="mr-4 p-2 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                                <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                                {{ __('admin/teachers/create.header.title') }}
                            </h1>
                            <p class="text-sm text-gray-500 mt-1">{{ __('admin/teachers/create.header.subtitle') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-400 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-red-800 mb-2">Terdapat ralat dalam borang:</h3>
                        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <form action="{{ route('admin.teachers.store') }}" method="POST" class="p-6">
                @csrf
                
                <!-- Personal Information Section -->
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('admin/teachers/create.sections.personal_information') }}</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ __('admin/teachers/create.fields.full_name') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                   placeholder="{{ __('admin/teachers/create.placeholders.full_name') }}"
                                   required>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ __('admin/teachers/create.fields.email') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="email" 
                                       name="email" 
                                       value="{{ old('email') }}"
                                       class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                       placeholder="{{ __('admin/teachers/create.placeholders.email') }}"
                                       required>
                                <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ __('admin/teachers/create.fields.phone') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       name="phone" 
                                       value="{{ old('phone') }}"
                                       class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                       placeholder="{{ __('admin/teachers/create.placeholders.phone') }}"
                                       required>
                                <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ __('admin/teachers/create.fields.ic_number') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="ic_number" 
                                   value="{{ old('ic_number') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                   placeholder="{{ __('admin/teachers/create.placeholders.ic_number') }}"
                                   required>
                            <p class="text-xs text-amber-600 flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ __('admin/teachers/create.notes.ic_used_password') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Payment Information Section -->
                <div class="mb-8 border-t border-gray-200 pt-8">
                    <div class="flex items-center mb-4">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" stroke="none" viewBox="0 0 24 24">
                            <path d="M8 15.5H6c0 2.59 2.42 4.12 5 4.44V22h2v-2.07c2.25-.3 5-1.59 5-4.43s-2.75-4.13-5-4.43V6.1c1.33.24 3 .94 3 2.4h2c0-2.84-2.75-4.13-5-4.43V2h-2v2.07c-2.25.3-5 1.59-5 4.43s2.67 4.11 5 4.43v4.97c-1.45-.25-3-1.02-3-2.4m8 0c0 1.46-1.67 2.16-3 2.4v-4.8c1.33.24 3 .94 3 2.4m-8-7c0-1.46 1.67-2.16 3-2.4v4.8c-1.37-.25-3-1-3-2.4"></path>

                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('admin/teachers/create.sections.allowance_information') }}</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ __('admin/teachers/create.fields.allowance_rate') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       name="allowance" 
                                       value="{{ old('allowance') }}"
                                       step="0.01"
                                       min="0"
                                       class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                       placeholder="{{ __('admin/teachers/create.placeholders.allowance_rate') }}"
                                       required>
                                <span class="absolute left-3 top-2.5 text-gray-500 text-sm">RM</span>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ __('admin/teachers/create.fields.allowance_type') }} <span class="text-red-500">*</span>
                            </label>
                            <select name="allowance_type" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                    required>
                                <option value="">{{ __('admin/teachers/create.selects.choose_allowance_type') }}</option>
                                <option value="per_session" {{ old('allowance_type') == 'per_session' ? 'selected' : '' }}>{{ __('admin/teachers/create.selects.per_session') }}</option>
                                <option value="per_hour" {{ old('allowance_type') == 'per_hour' ? 'selected' : '' }}>{{ __('admin/teachers/create.selects.per_hour') }}</option>
                                <option value="per_month" {{ old('allowance_type') == 'per_month' ? 'selected' : '' }}>{{ __('admin/teachers/create.selects.per_month') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Bank Information Section -->
                <div class="mb-8 border-t border-gray-200 pt-8">
                    <div class="flex items-center mb-4">
                        <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('admin/teachers/create.sections.bank_information') }}</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ __('admin/teachers/create.fields.bank_name') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="bank_name" 
                                   value="{{ old('bank_name') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                   placeholder="{{ __('admin/teachers/create.placeholders.bank_name') }}"
                                   required>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ __('admin/teachers/create.fields.bank_account_number') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="bank_account_number" 
                                   value="{{ old('bank_account_number') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                   placeholder="{{ __('admin/teachers/create.placeholders.bank_account_number') }}"
                                   required>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ __('admin/teachers/create.fields.bank_account_holder') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="bank_account_holder" 
                                   value="{{ old('bank_account_holder') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                   placeholder="{{ __('admin/teachers/create.placeholders.bank_account_holder') }}"
                                   required>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ __('admin/teachers/create.fields.account_holders_ic_number') }}
                            </label>
                            <input type="text" 
                                   name="account_holders_ic_number" 
                                   value="{{ old('account_holders_ic_number') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                   placeholder="XXXXXXXXXXXX (opsional)">
                            <p class="text-xs text-gray-500 mt-1">{{ __('admin/teachers/create.notes.same_as_teacher_ic') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.teachers.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        {{ __('admin/teachers/create.buttons.cancel') }}
                    </a>
                    
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ __('admin/teachers/create.buttons.save_teacher') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
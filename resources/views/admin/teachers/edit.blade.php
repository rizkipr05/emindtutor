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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                {{ __('admin/teachers/edit.title') }}
                            </h1>
                            <p class="text-sm text-gray-500 mt-1">{{ __('admin/teachers/edit.desc', ['name' => $teacher->user->name]) }}</p>
                        </div>
                    </div>
                    <!-- Status Badge -->
                    <div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($teacher->status === 'active') bg-green-100 text-green-800 
                            @else bg-red-100 text-red-800 @endif">
                            <span class="w-1.5 h-1.5 mr-1.5 rounded-full 
                                @if($teacher->status === 'active') bg-green-400 
                                @else bg-red-400 @endif">
                            </span>
                            {{ $teacher->status === 'active' ? __('admin/teachers/edit.status_active') : __('admin/teachers/edit.status_inactive') }}
                        </span>
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
                        <h3 class="text-sm font-medium text-red-800 mb-2">{{ __('admin/teachers/edit.error_title') }}</h3>
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
            <form action="{{ route('admin.teachers.update', $teacher->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <!-- Personal Information Section -->
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('admin/teachers/edit.personal_info') }}</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ __('admin/teachers/edit.name') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name', $teacher->user->name) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                   placeholder="{{ __('admin/teachers/edit.name_placeholder') }}"
                                   required>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ __('admin/teachers/edit.email') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="email" 
                                       name="email" 
                                       value="{{ old('email', $teacher->user->email) }}"
                                       class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                       placeholder="{{ __('admin/teachers/edit.email_placeholder') }}"
                                       required>
                                <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ __('admin/teachers/edit.ic_number') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="ic_number" 
                                   value="{{ old('ic_number', $teacher->ic_number) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                   placeholder="{{ __('admin/teachers/edit.ic_number_placeholder') }}"
                                   required>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ __('admin/teachers/edit.phone') }}
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       name="phone" 
                                       value="{{ old('phone', $teacher->phone) }}"
                                       class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                       placeholder="{{ __('admin/teachers/edit.phone_placeholder') }}">
                                <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information Section -->
                <div class="mb-8 border-t border-gray-200 pt-8">
                    <div class="flex items-center mb-4">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('admin/teachers/edit.allowance_info') }}</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ __('admin/teachers/edit.allowance_amount') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       step="0.01" 
                                       name="allowance" 
                                       value="{{ old('allowance', $teacher->allowance) }}"
                                       class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                       placeholder="{{ __('admin/teachers/edit.allowance_amount_placeholder') }}"
                                       required>
                                <span class="absolute left-3 top-2.5 text-gray-500 text-sm">RM</span>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ __('admin/teachers/edit.allowance_type') }} <span class="text-red-500">*</span>
                            </label>
                            <select name="allowance_type" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                    required>
                                <option value="">{{ __('admin/teachers/edit.allowance_type_placeholder') }}</option>
                                <option value="per_session" {{ old('allowance_type', $teacher->allowance_type) == 'per_session' ? 'selected' : '' }}>{{ __('admin/teachers/edit.allowance_type_per_session') }}</option>
                                <option value="per_hour" {{ old('allowance_type', $teacher->allowance_type) == 'per_hour' ? 'selected' : '' }}>{{ __('admin/teachers/edit.allowance_type_per_hour') }}</option>
                                <option value="per_month" {{ old('allowance_type', $teacher->allowance_type) == 'per_month' ? 'selected' : '' }}>{{ __('admin/teachers/edit.allowance_type_per_month') }}</option>
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
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('admin/teachers/edit.bank_info') }}</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ __('admin/teachers/edit.bank_name') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="bank_name" 
                                   value="{{ old('bank_name', $teacher->bank_name) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                   placeholder="{{ __('admin/teachers/edit.bank_name_placeholder') }}"
                                   required>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ __('admin/teachers/edit.bank_account_number') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="bank_account_number" 
                                   value="{{ old('bank_account_number', $teacher->bank_account_number) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                   placeholder="{{ __('admin/teachers/edit.bank_account_number_placeholder') }}"
                                   required>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ __('admin/teachers/edit.bank_account_holder') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="bank_account_holder" 
                                   value="{{ old('bank_account_holder', $teacher->bank_account_holder) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                   placeholder="{{ __('admin/teachers/edit.bank_account_holder_placeholder') }}"
                                   required>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ __('admin/teachers/edit.bank_ic_number') }}
                            </label>
                            <input type="text" 
                                   name="account_holders_ic_number" 
                                   value="{{ old('account_holders_ic_number', $teacher->account_holders_ic_number) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                    placeholder="{{ __('admin/teachers/edit.bank_ic_number_placeholder') }}">
                <p class="text-xs text-gray-500 mt-1">{{ __('admin/teachers/edit.bank_ic_number_note') }}</p>
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
                        {{ __('admin/teachers/edit.back') }}
                    </a>
                    
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        {{ __('admin/teachers/edit.save') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Danger Zone -->
        <div class="mt-8 bg-white rounded-lg shadow-sm border border-red-200">
            <div class="px-6 py-4 border-b border-red-200 bg-red-50">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-red-900">{{ __('admin/teachers/edit.danger_zone') }}</h3>
                </div>
                <p class="text-sm text-red-700 mt-1">{{ __('admin/teachers/edit.danger_zone_desc') }}</p>
            </div>
            
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">{{ __('admin/teachers/edit.delete_teacher') }}</h4>
                        <p class="text-sm text-gray-500">{{ __('admin/teachers/edit.delete_teacher_desc') }}</p>
                    </div>
                    <form id="deleteForm" 
                          action="{{ route('admin.teachers.destroy', $teacher->id) }}" 
                          method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" 
                                onclick="confirmDelete('{{ $teacher->ic_number }}')" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            {{ __('admin/teachers/edit.delete') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmDelete(correctIC) {
    Swal.fire({
    title: '{{ __('admin/teachers/edit.delete_confirm_title') }}',
    text: "{{ __('admin/teachers/edit.delete_confirm_text') }}",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: '{{ __('admin/teachers/edit.delete_confirm_confirm') }}',
    cancelButtonText: '{{ __('admin/teachers/edit.delete_confirm_cancel') }}',
        reverseButtons: true,
        customClass: {
            confirmButton: 'bg-red-600 hover:bg-red-700',
            cancelButton: 'bg-gray-300 hover:bg-gray-400'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: '{{ __('admin/teachers/edit.delete_confirm_ic_title') }}',
                input: 'text',
                inputLabel: '{{ __('admin/teachers/edit.delete_confirm_ic_label') }}',
                inputPlaceholder: '{{ __('admin/teachers/edit.delete_confirm_ic_placeholder') }}',
                showCancelButton: true,
                confirmButtonText: '{{ __('admin/teachers/edit.delete_confirm_ic_confirm') }}',
                cancelButtonText: '{{ __('admin/teachers/edit.delete_confirm_cancel') }}',
                inputValidator: (value) => {
                    if (!value) {
                        return '{{ __('admin/teachers/edit.delete_confirm_ic_error') }}';
                    }
                },
                customClass: {
                    confirmButton: 'bg-red-600 hover:bg-red-700',
                    cancelButton: 'bg-gray-300 hover:bg-gray-400'
                }
            }).then((icResult) => {
                if (icResult.isConfirmed) {
                    if (icResult.value.trim() === correctIC) {
                        // Show loading
                        Swal.fire({
                            title: '{{ __('admin/teachers/edit.deleting') }}',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        document.getElementById('deleteForm').submit();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '{{ __('admin/teachers/edit.delete_confirm_ic_not_match') }}',
                            text: '{{ __('admin/teachers/edit.delete_confirm_ic_try_again') }}',
                            confirmButtonText: 'OK',
                            customClass: {
                                confirmButton: 'bg-blue-600 hover:bg-blue-700'
                            }
                        });
                    }
                }
            });
        }
    });
}
</script>
@endsection
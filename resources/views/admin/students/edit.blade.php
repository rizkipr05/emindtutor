@extends('layouts.admin')

@section('content')


    <a href="{{ route('admin.students.approved') }}">
        <div class="mx-auto bg-white p-3 rounded-2xl shadow-md mb-4">
            <!-- Back Button -->
            <div class="">
                <div
                   class="inline-flex items-center text-gray-600 hover:text-gray-800 font-medium">
                    <!-- Icon panah kiri -->
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         class="h-5 w-5 mr-1" 
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M15 19l-7-7 7-7" />
                    </svg>
                    {{ __('admin/students/edit.header.title', ['name' => $student->user->name ?? __('admin/students/edit.header.back_default')]) }}
                </div>
            </div>
        </div>
    </a>

<div class="mx-auto bg-white p-8 rounded-2xl shadow-md space-y-6">

    <!-- Update Form -->
    <form action="{{ route('admin.students.update', $student->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
    
        <!-- Personal Information Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-4">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ __('admin/students/edit.sections.personal.title') }}
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">{{ __('admin/students/edit.sections.personal.subtitle') }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                    {{ __('admin/students/edit.form.personal.name') }}
                                </span>
                            </label>
                <input type="text" name="name" value="{{ old('name', $student->user->name) }}" 
                                   class="w-full border @error('name') border-red-300 bg-red-50 @else border-gray-300 @enderror rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                   placeholder="{{ __('admin/students/edit.placeholders.name') }}" required>
                            @error('name')
                                <div class="mt-2 flex items-center text-red-600 text-sm">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                    {{ __('admin/students/edit.form.personal.email') }}
                                </span>
                            </label>
                <input type="email" name="email" value="{{ old('email', $student->user->email) }}" 
                                   class="w-full border @error('email') border-red-300 bg-red-50 @else border-gray-300 @enderror rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                   placeholder="{{ __('admin/students/edit.placeholders.email') }}" required>
                            @error('email')
                                <div class="mt-2 flex items-center text-red-600 text-sm">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                    {{ __('admin/students/edit.form.personal.phone') }}
                                </span>
                            </label>
                <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" 
                                   class="w-full border @error('phone') border-red-300 bg-red-50 @else border-gray-300 @enderror rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                   placeholder="{{ __('admin/students/edit.placeholders.phone') }}" required>
                            @error('phone')
                                <div class="mt-2 flex items-center text-red-600 text-sm">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- IC Number -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                    </svg>
                    {{ __('admin/students/edit.form.personal.ic_number') }}
                                </span>
                            </label>
                <input type="text" name="ic_number" value="{{ old('ic_number', $student->ic_number) }}" 
                                   class="w-full border @error('ic_number') border-red-300 bg-red-50 @else border-gray-300 @enderror rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 font-mono" 
                   placeholder="{{ __('admin/students/edit.placeholders.ic_number') }}" required>
                            <div class="mt-2 flex items-center text-amber-600 text-sm bg-amber-50 p-2 rounded-lg">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                {{ __('admin/students/edit.notes.ic_changed') }}
                            </div>
                            @error('ic_number')
                                <div class="mt-2 flex items-center text-red-600 text-sm">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                    {{ __('admin/students/edit.form.personal.address') }}
                                </span>
                            </label>
                <textarea name="address" rows="3" 
                                      class="w-full border @error('address') border-red-300 bg-red-50 @else border-gray-300 @enderror rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 resize-none" 
                      placeholder="{{ __('admin/students/edit.placeholders.address') }}">{{ old('address', $student->address) }}</textarea>
                            @error('address')
                                <div class="mt-2 flex items-center text-red-600 text-sm">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                    {{ __('admin/students/edit.form.personal.notes') }}
                                </span>
                            </label>
                <textarea name="notes" rows="3" 
                                      class="w-full border @error('notes') border-red-300 bg-red-50 @else border-gray-300 @enderror rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 resize-none" 
                      placeholder="{{ __('admin/students/edit.placeholders.notes') }}">{{ old('notes', $student->notes) }}</textarea>
                            @error('notes')
                                <div class="mt-2 flex items-center text-red-600 text-sm">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Sponsor Information Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-4">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            {{ __('admin/students/edit.sections.sponsor.title') }}
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">{{ __('admin/students/edit.sections.sponsor.subtitle') }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Sponsor Name -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                    {{ __('admin/students/edit.form.penaja.sponsor_name') }}
                                </span>
                            </label>
                <input type="text" name="sponsor_name" value="{{ old('sponsor_name', $student->sponsor_name) }}" 
                                   class="w-full border @error('sponsor_name') border-red-300 bg-red-50 @else border-gray-300 @enderror rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200" 
                   placeholder="{{ __('admin/students/edit.placeholders.sponsor_name') }}">
                            @error('sponsor_name')
                                <div class="mt-2 flex items-center text-red-600 text-sm">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Sponsor Phone -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                {{ __('admin/students/edit.form.penaja.sponsor_phone') }}
                                </span>
                            </label>
                            <input type="text" name="sponsor_phone" value="{{ old('sponsor_phone', $student->sponsor_phone) }}" 
                                   class="w-full border @error('sponsor_phone') border-red-300 bg-red-50 @else border-gray-300 @enderror rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200" 
                                   placeholder="{{ __('admin/students/edit.placeholders.sponsor_phone') }}">
                            @error('sponsor_phone')
                                <div class="mt-2 flex items-center text-red-600 text-sm">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Sponsor Email -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                    {{ __('admin/students/edit.form.penaja.sponsor_email') }}
                                </span>
                            </label>
                <input type="email" name="sponsor_email" value="{{ old('sponsor_email', $student->sponsor_email) }}" 
                   class="w-full border @error('sponsor_email') border-red-300 bg-red-50 @else border-gray-300 @enderror rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200" 
                   placeholder="{{ __('admin/students/edit.placeholders.sponsor_email') }}">
                            @error('sponsor_email')
                                <div class="mt-2 flex items-center text-red-600 text-sm">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <!-- Action Buttons -->
                <div class="border-t border-gray-200 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center space-x-4 w-full sm:w-auto">
                        <!-- Update Button -->
                        <button type="submit" 
                                class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg shadow-sm hover:from-blue-700 hover:to-blue-800 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ __('admin/students/edit.buttons.update') }}
                        </button>
                    </div>
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
                    <h3 class="text-lg font-semibold text-red-900">{{ __('admin/students/edit.danger.title') }}</h3>
                </div>
                <p class="text-sm text-red-700 mt-1">{{ __('admin/students/edit.danger.subtitle') }}</p>
            </div>
            
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">{{ __('admin/students/edit.danger.delete_title') }}</h4>
                        <p class="text-sm text-gray-500">{{ __('admin/students/edit.danger.delete_subtitle') }}</p>
                    </div>
                    <form id="deleteForm" 
                          action="{{ route('admin.students.destroy', $student->id) }}" 
                          method="POST">
                        @csrf
                        @method('DELETE')
            <button type="button" 
                onclick="confirmDelete('{{ $student->ic_number }}')" 
                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            {{ __('admin/students/edit.buttons.delete') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const deleteLocale = {
    confirm_title: @json(__('admin/students/edit.sweetalert.confirm_title')),
    confirm_text: @json(__('admin/students/edit.sweetalert.confirm_text')),
    confirm_button: @json(__('admin/students/edit.sweetalert.confirm_button')),
    cancel_button: @json(__('admin/students/edit.sweetalert.cancel_button')),
    verify_title: @json(__('admin/students/edit.sweetalert.verify_title')),
    verify_label: @json(__('admin/students/edit.sweetalert.verify_label')),
    verify_placeholder: @json(__('admin/students/edit.sweetalert.verify_placeholder')),
    verify_confirm: @json(__('admin/students/edit.sweetalert.verify_confirm')),
    verify_empty: @json(__('admin/students/edit.sweetalert.verify_empty')),
    deleting: @json(__('admin/students/edit.sweetalert.deleting')),
    mismatch_title: @json(__('admin/students/edit.sweetalert.mismatch_title')),
    mismatch_text: @json(__('admin/students/edit.sweetalert.mismatch_text')),
    mismatch_confirm: @json(__('admin/students/edit.sweetalert.mismatch_confirm')),
};

function confirmDelete(correctIC) {
    Swal.fire({
        title: deleteLocale.confirm_title,
        text: deleteLocale.confirm_text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: deleteLocale.confirm_button,
        cancelButtonText: deleteLocale.cancel_button,
        reverseButtons: true,
        customClass: {
            confirmButton: 'bg-red-600 hover:bg-red-700',
            cancelButton: 'bg-gray-300 hover:bg-gray-400'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: deleteLocale.verify_title,
                input: 'text',
                inputLabel: deleteLocale.verify_label,
                inputPlaceholder: deleteLocale.verify_placeholder,
                showCancelButton: true,
                confirmButtonText: deleteLocale.verify_confirm,
                cancelButtonText: deleteLocale.cancel_button,
                inputValidator: (value) => {
                    if (!value) {
                        return deleteLocale.verify_empty;
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
                            title: deleteLocale.deleting,
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        document.getElementById('deleteForm').submit();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: deleteLocale.mismatch_title,
                            text: deleteLocale.mismatch_text,
                            confirmButtonText: deleteLocale.mismatch_confirm,
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
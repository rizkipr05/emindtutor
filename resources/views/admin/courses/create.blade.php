@extends('layouts.admin')

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto px-2">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-3">
                <a href="{{ route('admin.courses.index') }}" class="hover:text-blue-600 transition-colors">
                    {{ __('admin/courses/create.breadcrumb_index') }}
                </a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="font-medium text-gray-900">{{ __('admin/courses/create.breadcrumb_create') }}</span>
            </nav>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('admin/courses/create.title') }}</h1>
            <p class="text-gray-600">{{ __('admin/courses/create.desc') }}</p>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-8 bg-red-50 border border-red-200 rounded-2xl p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-red-900">{{ __('admin/courses/create.error_title') }}</h3>
                </div>
                <ul class="list-disc list-inside space-y-2 text-red-800">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-8 bg-green-50 border border-green-200 rounded-2xl p-6">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="ml-3 text-green-800 font-medium">{{ session('success') ?? __('admin/courses/create.success') }}</p>
                </div>
            </div>
        @endif

        <!-- Form -->
        <div class="mx-auto">
            <form action="{{ route('admin.courses.store') }}" method="POST" class="space-y-8">
                @csrf

                <!-- Basic Information Section -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ __('admin/courses/create.basic_info') }}
                        </h2>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Kursus Mengikut Bahasa -->
                        <div>
                            <label for="nama_kos_en" class="block text-sm font-semibold text-gray-700 mb-2">
                                Course Name (English) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="nama_kos_en" name="nama_kos_en" value="{{ old('nama_kos_en') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('nama_kos_en') ? 'border-red-500 bg-red-50' : '' }}" 
                                   placeholder="Example: Mathematics Class"
                                   required>
                            @error('nama_kos_en')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div>
                            <label for="nama_kos_my" class="block text-sm font-semibold text-gray-700 mb-2">Nama Kelas (Bahasa Melayu) <span class="text-red-500">*</span></label>
                            <input type="text" id="nama_kos_my" name="nama_kos_my" value="{{ old('nama_kos_my') }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('nama_kos_my') ? 'border-red-500 bg-red-50' : '' }}" placeholder="Contoh: Kelas Matematik" required>
                            @error('nama_kos_my')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <!-- Jenis Kursus -->
                        <div>
                            <label for="jenis" class="block text-sm font-semibold text-gray-700 mb-2">
                                {{ __('admin/courses/create.type') }} <span class="text-red-500">*</span>
                            </label>
                            <select id="jenis" 
                                    name="jenis" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('jenis') ? 'border-red-500 bg-red-50' : '' }}" 
                                    required>
                                <option value="">{{ __('admin/courses/create.type_placeholder') }}</option>
                                <option value="individu" {{ old('jenis') == 'individu' ? 'selected' : '' }}>
                                    👤 {{ __('admin/courses/create.type_individu') }}
                                </option>
                                <option value="kumpulan" {{ old('jenis') == 'kumpulan' ? 'selected' : '' }}>
                                    👥 {{ __('admin/courses/create.type_kumpulan') }}
                                </option>
                            </select>
                            @error('jenis')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Mod Pengajaran -->
                        <div>
                            <label for="teaching_mode" class="block text-sm font-semibold text-gray-700 mb-2">
                                {{ __('admin/courses/create.teaching_mode') }} <span class="text-red-500">*</span>
                            </label>
                            <select id="teaching_mode" 
                                    name="teaching_mode" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('teaching_mode') ? 'border-red-500 bg-red-50' : '' }}" 
                                    required>
                                <option value="">{{ __('admin/courses/create.teaching_mode_placeholder') }}</option>
                                <option value="offline" {{ old('teaching_mode') == 'offline' ? 'selected' : '' }}>
                                    🏢 {{ __('admin/courses/create.teaching_mode_offline') }}
                                </option>
                                <option value="online" {{ old('teaching_mode') == 'online' ? 'selected' : '' }}>
                                    💻 {{ __('admin/courses/create.teaching_mode_online') }}
                                </option>
                                <option value="hybrid" {{ old('teaching_mode') == 'hybrid' ? 'selected' : '' }}>
                                    🔄 {{ __('admin/courses/create.teaching_mode_hybrid') }}
                                </option>
                            </select>
                            @error('teaching_mode')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Pricing & Capacity Section -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" stroke="none" viewBox="0 0 24 24">
                                <path d="M8 15.5H6c0 2.59 2.42 4.12 5 4.44V22h2v-2.07c2.25-.3 5-1.59 5-4.43s-2.75-4.13-5-4.43V6.1c1.33.24 3 .94 3 2.4h2c0-2.84-2.75-4.13-5-4.43V2h-2v2.07c-2.25.3-5 1.59-5 4.43s2.67 4.11 5 4.43v4.97c-1.45-.25-3-1.02-3-2.4m8 0c0 1.46-1.67 2.16-3 2.4v-4.8c1.33.24 3 .94 3 2.4m-8-7c0-1.46 1.67-2.16 3-2.4v4.8c-1.37-.25-3-1-3-2.4"></path>
                            </svg>
                            {{ __('admin/courses/create.pricing_capacity') }}
                        </h2>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Yuran -->
                        <div>
                            <label for="fee" class="block text-sm font-semibold text-gray-700 mb-2">
                                {{ __('admin/courses/create.fee') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-lg">RM</span>
                                <input type="number" 
                                       id="fee" 
                                       name="fee" 
                                       value="{{ old('fee') }}"
                                       step="0.01"
                                       min="0"
                                       class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('fee') ? 'border-red-500 bg-red-50' : '' }}" 
                                       placeholder="{{ __('admin/courses/create.fee_placeholder') }}"
                                       required>
                            </div>
                            @error('fee')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Max Student -->
                        <div>
                            <label for="max_student" class="block text-sm font-semibold text-gray-700 mb-2">
                                {{ __('admin/courses/create.max_student') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   id="max_student" 
                                   name="max_student" 
                                   value="{{ old('max_student') }}"
                                   min="1"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('max_student') ? 'border-red-500 bg-red-50' : '' }}" 
                                   placeholder="{{ __('admin/courses/create.max_student_placeholder') }}"
                                   required>
                            @error('max_student')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Payment Options -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-4">{{ __('admin/courses/create.payment_options') }}</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="flex items-center p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" 
                                           id="charge_on_registration" 
                                           name="charge_on_registration" 
                                           class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                           {{ old('charge_on_registration') ? 'checked' : '' }}>
                                    <label for="charge_on_registration" class="ml-3 text-gray-700">
                                        <div class="font-medium">{{ __('admin/courses/create.charge_on_registration') }}</div>
                                        <div class="text-sm text-gray-500">{{ __('admin/courses/create.charge_on_registration_desc') }}</div>
                                    </label>
                                </div>
                                
                                <div class="flex items-center p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" 
                                           id="include_registration_fee" 
                                           name="include_registration_fee" 
                                           class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                           {{ old('include_registration_fee') ? 'checked' : '' }}>
                                    <label for="include_registration_fee" class="ml-3 text-gray-700">
                                        <div class="font-medium">{{ __('admin/courses/create.include_registration_fee') }}</div>
                                        <div class="text-sm text-gray-500">{{ __('admin/courses/create.include_registration_fee_desc') }}</div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Session Settings Section -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ __('admin/courses/create.session_settings') }}
                        </h2>
                        <p class="text-gray-600 text-sm mt-1">{{ __('admin/courses/create.optional_info') }}</p>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Minimum Sessions -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin/courses/create.min_sessions') }}</label>
                            <div class="grid grid-cols-2 gap-3">
                                <input type="number" 
                                       name="minimum_sessions" 
                                       value="{{ old('minimum_sessions') }}"
                                       min="0"
                                       class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('minimum_sessions') ? 'border-red-500 bg-red-50' : '' }}" 
                                       placeholder="Contoh: 4">
                                <select name="minimum_sessions_unit" 
                                        class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('minimum_sessions_unit') ? 'border-red-500 bg-red-50' : '' }}">
                                    <option value="session" {{ old('minimum_sessions_unit') == 'session' ? 'selected' : '' }}>{{ __('admin/courses/create.session_unit_session') }}</option>
                                    <option value="hour" {{ old('minimum_sessions_unit') == 'hour' ? 'selected' : '' }}>{{ __('admin/courses/create.session_unit_hour') }}</option>
                                    <option value="minute" {{ old('minimum_sessions_unit') == 'minute' ? 'selected' : '' }}>{{ __('admin/courses/create.session_unit_minute') }}</option>
                                </select>
                            </div>
                            @if($errors->has('minimum_sessions') || $errors->has('minimum_sessions_unit'))
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $errors->first('minimum_sessions') ?: $errors->first('minimum_sessions_unit') }}
                                </p>
                            @endif
                        </div>

                        <!-- Session Duration -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin/courses/create.session_duration') }}</label>
                            <div class="grid grid-cols-2 gap-3">
                                <input type="number" 
                                       name="sessions_duration" 
                                       value="{{ old('sessions_duration') }}"
                                       min="0"
                                       class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('sessions_duration') ? 'border-red-500 bg-red-50' : '' }}" 
                                       placeholder="Contoh: 60">
                                <select name="sessions_duration_unit" 
                                        class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('sessions_duration_unit') ? 'border-red-500 bg-red-50' : '' }}">
                                    <option value="minute" {{ old('sessions_duration_unit') == 'minute' ? 'selected' : '' }}>{{ __('admin/courses/create.session_unit_minute') }}</option>
                                    <option value="hour" {{ old('sessions_duration_unit') == 'hour' ? 'selected' : '' }}>{{ __('admin/courses/create.session_unit_hour') }}</option>
                                </select>
                            </div>
                            @if($errors->has('sessions_duration') || $errors->has('sessions_duration_unit'))
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $errors->first('sessions_duration') ?: $errors->first('sessions_duration_unit') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Additional Information Section -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            {{ __('admin/courses/create.additional_info') }}
                        </h2>
                        <p class="text-gray-600 text-sm mt-1">{{ __('admin/courses/create.optional_info') }}</p>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin/courses/create.desc_label') }}</label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="4" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none {{ $errors->has('description') ? 'border-red-500 bg-red-50' : '' }}"
                                      placeholder="{{ __('admin/courses/create.desc_label') }}">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Instructions Template -->
                        <div>
                            <label for="instructions_template" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin/courses/create.instructions_template_label') }}</label>
                            <textarea id="instructions_template" 
                                      name="instructions_template" 
                                      rows="8" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none font-mono text-sm {{ $errors->has('instructions_template') ? 'border-red-500 bg-red-50' : '' }}"
                                      placeholder="{{ str_replace('\\n', "\n", __('admin/courses/create.instructions_template_placeholder')) }}">{{ old('instructions_template') }}</textarea>
                            @error('instructions_template')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Session Notes Template -->
                        <div>
                            <label for="session_notes_template" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin/courses/create.session_notes_template_label') }}</label>
                            <textarea id="session_notes_template" 
                                      name="session_notes_template" 
                                      rows="8" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none font-mono text-sm {{ $errors->has('session_notes_template') ? 'border-red-500 bg-red-50' : '' }}"
                                      placeholder="{{ str_replace('\\n', "\n", __('admin/courses/create.session_notes_template_placeholder')) }}">{{ old('session_notes_template') }}</textarea>
                            @error('session_notes_template')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>
                </div>
                

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-end gap-4">
                    <a href="{{ route('admin.courses.index') }}" 
                       class="inline-flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-8 py-3 rounded-xl font-medium transition-all duration-300 border border-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        {{ __('admin/courses/create.cancel') }}
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-8 py-3 rounded-xl font-medium shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ __('admin/courses/create.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-resize textareas
document.addEventListener('DOMContentLoaded', function() {
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(function(textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });

    // Form validation feedback
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(function(field) {
            if (!field.value.trim()) {
                field.classList.add('border-red-500', 'bg-red-50');
                isValid = false;
            } else {
                field.classList.remove('border-red-500', 'bg-red-50');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            // Scroll to first error
            const firstError = form.querySelector('.border-red-500');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        }
    });

    // Real-time validation
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(function(input) {
        input.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.classList.add('border-red-500', 'bg-red-50');
            } else {
                this.classList.remove('border-red-500', 'bg-red-50');
            }
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('border-red-500') && this.value.trim()) {
                this.classList.remove('border-red-500', 'bg-red-50');
            }
        });
    });
});

// Fee formatting
document.getElementById('fee').addEventListener('input', function(e) {
    let value = e.target.value;
    if (value && !isNaN(value)) {
        // Format the display but keep the actual value for form submission
        const formatted = parseFloat(value).toFixed(2);
        if (formatted !== 'NaN') {
            e.target.setAttribute('data-formatted', formatted);
        }
    }
});

// Dynamic content based on course type
document.getElementById('jenis').addEventListener('change', function(e) {
    const maxStudentField = document.getElementById('max_student');
    const maxStudentLabel = maxStudentField.previousElementSibling;
    
    if (e.target.value === 'individu') {
        maxStudentField.value = '1';
        maxStudentField.setAttribute('readonly', true);
        maxStudentLabel.innerHTML = 'Maksimum Pelajar <span class="text-red-500">*</span> <span class="text-sm text-gray-500">(Auto-set untuk individu)</span>';
        maxStudentField.classList.add('bg-gray-100');
    } else {
        maxStudentField.removeAttribute('readonly');
        maxStudentField.value = '';
        maxStudentLabel.innerHTML = 'Maksimum Pelajar <span class="text-red-500">*</span>';
        maxStudentField.classList.remove('bg-gray-100');
        maxStudentField.placeholder = 'Contoh: 20';
    }
});
</script>
@endsection

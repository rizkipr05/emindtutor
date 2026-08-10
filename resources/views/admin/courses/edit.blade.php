@extends('layouts.admin')

@section('content')
<div class="min-h-screen px-2 sm:px-6 lg:px-8">
    <div class="mx-auto">

         <!-- Header -->
        <div class="mb-8">
            <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-3">
                <a href="{{ route('admin.courses.index') }}" class="hover:text-blue-600 transition-colors">
                    {{ __('admin/courses/edit.breadcrumb_index') }}
                </a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="font-medium text-gray-900">{{ __('admin/courses/edit.breadcrumb_edit') }}</span>
            </nav>
        </div>

        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-sky-600 to-cyan-700 px-8 py-6">
                <h2 class="text-3xl font-bold text-white flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    {{ __('admin/courses/edit.title') }}
                </h2>
                <p class="text-blue-100 mt-2">{{ __('admin/courses/edit.desc') }}</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mx-8 mt-6 p-4 bg-red-50 border-l-4 border-red-400 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <h3 class="text-red-800 font-medium">{{ __('admin/courses/edit.error_title') }}</h3>
                    </div>
                    <ul class="mt-2 text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="flex items-start">
                                <span class="w-2 h-2 bg-red-400 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Content -->
            <form method="POST" action="{{ route('admin.courses.update', $course->id) }}" class="p-8 space-y-8">
                @csrf
                @method('PUT')

                <!-- Basic Information Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ __('admin/courses/edit.basic_info') }}
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Course Names -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Course Name (English)</label>
                            <div class="relative">
                                <input type="text" name="nama_kos_en" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm hover:shadow-md"
                                    value="{{ old('nama_kos_en', $course->nama_kos_en ?: $course->nama_kos) }}" placeholder="Example: Mathematics Class">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kelas (Bahasa Melayu)</label>
                            <input type="text" name="nama_kos_my" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm hover:shadow-md" value="{{ old('nama_kos_my', $course->nama_kos_my ?: $course->nama_kos) }}" placeholder="Contoh: Kelas Matematik">
                        </div>

                        <!-- Fee -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin/courses/edit.fee') }}</label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-gray-500 font-medium">RM</span>
                                <input type="number" name="fee" 
                                    class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm hover:shadow-md"
                                    value="{{ old('fee', $course->fee) }}"
                                    placeholder="{{ __('admin/courses/edit.fee_placeholder') }}">
                            </div>
                        </div>

                        <!-- Teaching Mode -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin/courses/edit.teaching_mode') }}</label>
                            <div class="relative">
                                <select name="teaching_mode" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm hover:shadow-md appearance-none">
                                    <option value="offline" {{ old('teaching_mode', $course->teaching_mode) == 'offline' ? 'selected' : '' }}> {{ __('admin/courses/create.teaching_mode_offline') }}</option>
                                    <option value="online" {{ old('teaching_mode', $course->teaching_mode) == 'online' ? 'selected' : '' }}>{{ __('admin/courses/create.teaching_mode_online') }}</option>
                                    <option value="hybrid" {{ old('teaching_mode', $course->teaching_mode) == 'hybrid' ? 'selected' : '' }}>{{ __('admin/courses/create.teaching_mode_hybrid') }}</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Checkboxes -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                            <label class="flex items-start space-x-3 cursor-pointer">
                                <input type="hidden" name="charge_on_registration" value="0">
                                <div class="flex-shrink-0">
                                    <input type="checkbox" name="charge_on_registration" value="1" 
                                        class="w-5 h-5 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                                        {{ old('charge_on_registration', $course->charge_on_registration) ? 'checked' : '' }}>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-900">{{ __('admin/courses/edit.charge_on_registration') }}</span>
                                    <p class="text-xs text-gray-600 mt-1">{{ __('admin/courses/edit.charge_on_registration_desc') }}</p>
                                </div>
                            </label>
                        </div>

                        <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                            <label class="flex items-start space-x-3 cursor-pointer">
                                <input type="hidden" name="include_registration_fee" value="0">
                                <div class="flex-shrink-0">
                                    <input type="checkbox" name="include_registration_fee" value="1" 
                                        class="w-5 h-5 text-green-600 border-2 border-gray-300 rounded focus:ring-green-500 focus:ring-2"
                                        {{ old('include_registration_fee', $course->include_registration_fee) ? 'checked' : '' }}>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-900">{{ __('admin/courses/edit.include_registration_fee') }}</span>
                                    <p class="text-xs text-gray-600 mt-1">{{ __('admin/courses/edit.include_registration_fee_desc') }}</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Session Details Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ __('admin/courses/edit.session_details') }}
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Minimum Sessions -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin/courses/edit.min_sessions') }}</label>
                            <div class="grid grid-cols-2 gap-3">
                                <input type="number" name="minimum_sessions" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white shadow-sm hover:shadow-md"
                                    value="{{ old('minimum_sessions', $course->minimum_sessions) }}"
                                    placeholder="{{ __('admin/courses/edit.min_sessions_placeholder') }}">
                                <select name="minimum_sessions_unit" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white shadow-sm hover:shadow-md">
                                    <option value="session" {{ old('minimum_sessions_unit', $course->minimum_sessions_unit) == 'session' ? 'selected' : '' }}>{{ __('admin/courses/create.session_unit_session') }}</option>
                                    <option value="hour" {{ old('minimum_sessions_unit', $course->minimum_sessions_unit) == 'hour' ? 'selected' : '' }}>{{ __('admin/courses/create.session_unit_hour') }}</option>
                                    <option value="minute" {{ old('minimum_sessions_unit', $course->minimum_sessions_unit) == 'minute' ? 'selected' : '' }}>{{ __('admin/courses/create.session_unit_minute') }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Session Duration -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin/courses/edit.session_duration') }}</label>
                            <div class="grid grid-cols-2 gap-3">
                                <input type="number" name="sessions_duration" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white shadow-sm hover:shadow-md"
                                    value="{{ old('sessions_duration', $course->sessions_duration) }}"
                                    placeholder="{{ __('admin/courses/edit.session_duration_placeholder') }}">
                                <select name="sessions_duration_unit" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white shadow-sm hover:shadow-md">
                                    <option value="minute" {{ old('sessions_duration_unit', $course->sessions_duration_unit) == 'minute' ? 'selected' : '' }}>{{ __('admin/courses/create.session_unit_minute') }}</option>
                                    <option value="hour" {{ old('sessions_duration_unit', $course->sessions_duration_unit) == 'hour' ? 'selected' : '' }}>{{ __('admin/courses/create.session_unit_hour') }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Course Type -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin/courses/edit.course_type') }}</label>
                            <div class="relative">
                                <select name="jenis" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white shadow-sm hover:shadow-md appearance-none">
                                    <option value="individu" {{ old('jenis', $course->jenis) == 'individu' ? 'selected' : '' }}>👤 {{ __('admin/courses/edit.type_individu') }}</option>
                                    <option value="kumpulan" {{ old('jenis', $course->jenis) == 'kumpulan' ? 'selected' : '' }}>👥 {{ __('admin/courses/edit.type_kumpulan') }}</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Content Templates Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            {{ __('admin/courses/edit.content_templates') }}
                        </h3>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin/courses/edit.desc_label') }}</label>
                        <div class="relative">
                            <textarea name="description" rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white shadow-sm hover:shadow-md resize-none"
                                placeholder="{{ __('admin/courses/edit.desc_placeholder') }}">{{ old('description', $course->description) }}</textarea>
                            <div class="absolute bottom-3 right-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Instructions Template -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin/courses/edit.instructions_template_label') }}</label>
                        <div class="relative">
                            <textarea name="instructions_template" rows="5"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white shadow-sm hover:shadow-md resize-none"
                                placeholder="{{ str_replace('\\n', "\n", __('admin/courses/edit.instructions_template_placeholder')) }}">{{ old('instructions_template', $course->instructions_template) }}</textarea>
                            <div class="absolute bottom-3 right-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Session Notes Template -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin/courses/edit.session_notes_template_label') }}</label>
                        <div class="relative">
                            <textarea name="session_notes_template" rows="5"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white shadow-sm hover:shadow-md resize-none"
                                placeholder="{{ str_replace('\\n', "\n", __('admin/courses/edit.session_notes_template_placeholder')) }}">{{ old('session_notes_template', $course->session_notes_template) }}</textarea>
                            <div class="absolute bottom-3 right-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-end pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.courses.index') }}" 
                        class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                        </svg>
                        {{ __('admin/courses/edit.back') }}
                    </a>
                    <button type="submit" 
                        class="inline-flex justify-center items-center px-8 py-3 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ __('admin/courses/edit.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
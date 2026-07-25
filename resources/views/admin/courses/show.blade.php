@extends('layouts.admin')

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto px-2">
        <!-- Breadcrumb & Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-3">
                    <a href="{{ route('admin.courses.index') }}" class="hover:text-blue-600 transition-colors">
                        {{ __('admin/courses/show.breadcrumb_index') }}
                    </a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="font-medium text-gray-900">{{ __('admin/courses/show.breadcrumb_show') }}</span>
                </nav>
                <h1 class="text-3xl font-bold text-gray-900">{{ $course->nama_kos }}</h1>
                <div class="flex items-center gap-4 mt-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                        {{ $course->jenis === 'individu' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                        {{ $course->jenis === 'individu' ? __('admin/courses/show.type_individu') : __('admin/courses/show.type_kumpulan') }}
                    </span>
                    <span class="text-gray-500">•</span>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.courses.edit', $course->id) }}" 
                   class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl shadow-lg transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    {{ __('admin/courses/show.edit') }}
                </a>
                <a href="{{ route('admin.courses.index') }}" 
                   class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl shadow-lg transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('admin/courses/show.back') }}
                </a>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Course Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ __('admin/courses/show.title') }}
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin/courses/show.course_name') }}</label>
                                    <p class="text-gray-900 bg-gray-50 px-4 py-3 rounded-lg border">{{ $course->nama_kos }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin/courses/show.course_type') }}</label>
                                    <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium 
                                        {{ $course->jenis === 'individu' ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-purple-100 text-purple-800 border border-purple-200' }}">
                                        @if($course->jenis === 'individu')
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                        @endif
                                        {{ $course->jenis === 'individu' ? __('admin/courses/show.type_individu') : __('admin/courses/show.type_kumpulan') }}
                                    </span>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin/courses/show.teaching_mode') }}</label>
                                    <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium 
                                        {{ $course->teaching_mode === 'online' ? 'bg-blue-100 text-blue-800 border border-blue-200' : 
                                           ($course->teaching_mode === 'offline' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-yellow-100 text-yellow-800 border border-yellow-200') }}">
                                        @if($course->teaching_mode === 'online')
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        @endif
                                        @if($course->teaching_mode === 'online')
                                            {{ __('admin/courses/show.teaching_mode_online') }}
                                        @elseif($course->teaching_mode === 'offline')
                                            {{ __('admin/courses/show.teaching_mode_offline') }}
                                        @else
                                            {{ __('admin/courses/show.teaching_mode_hybrid') }}
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin/courses/show.fee') }}</label>
                                    <div class="bg-green-50 border border-green-200 px-4 py-3 rounded-lg">
                                        <span class="text-2xl font-bold text-green-800">RM{{ number_format($course->fee, 2) }}</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin/courses/show.max_student') }}</label>
                                    <p class="text-gray-900 bg-gray-50 px-4 py-3 rounded-lg border">
                                        {{ $course->max_student ? __(
                                            'admin/courses/show.max_student_person',
                                            ['count' => $course->max_student]
                                        ) : __('admin/courses/show.max_student_unlimited') }}
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin/courses/show.min_sessions') }}</label>
                                    <p class="text-gray-900 bg-gray-50 px-4 py-3 rounded-lg border">
                                        @if ($course->minimum_sessions)
                                            {{ __('admin/courses/show.min_sessions_unit', ['count' => $course->minimum_sessions, 'unit' => $course->minimum_sessions_unit ?? 'sesi']) }}
                                        @else
                                            {{ __('admin/courses/show.min_sessions_none') }}
                                        @endif
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin/courses/show.session_duration') }}</label>
                                    <p class="text-gray-900 bg-gray-50 px-4 py-3 rounded-lg border">
                                        @if ($course->sessions_duration)
                                            {{ __('admin/courses/show.session_duration_unit', ['count' => $course->sessions_duration, 'unit' => $course->sessions_duration_unit ?? 'sesi']) }}
                                        @else
                                            {{ __('admin/courses/show.session_duration_none') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment & Registration Settings -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" stroke="none" viewBox="0 0 24 24">
                                <path class="b" d="M13 14H18V16H13z"></path><path class="b" d="m4,20h16c1.1,0,2-.9,2-2V6c0-1.1-.9-2-2-2H4c-1.1,0-2,.9-2,2v12c0,1.1.9,2,2,2Zm0-14h16v12s-16,0-16,0V6Z"></path>
                            </svg>
                            {{ __('admin/courses/show.payment_settings') }}
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center p-4 bg-gray-50 rounded-xl border">
                                <div class="w-12 h-12 mx-auto mb-3 flex items-center justify-center rounded-full 
                                    {{ $course->charge_on_registration ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                    @if($course->charge_on_registration)
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    @endif
                                </div>
                                <h3 class="font-semibold text-gray-900 mb-1">{{ __('admin/courses/show.charge_on_registration') }}</h3>
                                <p class="text-sm text-gray-600">
                                    {{ $course->charge_on_registration ? __('admin/courses/show.charge_on_registration_on') : __('admin/courses/show.charge_on_registration_off') }}
                                </p>
                            </div>

                            <div class="text-center p-4 bg-gray-50 rounded-xl border">
                                <div class="w-12 h-12 mx-auto mb-3 flex items-center justify-center rounded-full 
                                    {{ $course->include_registration_fee ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                    @if($course->include_registration_fee)
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    @endif
                                </div>
                                <h3 class="font-semibold text-gray-900 mb-1">{{ __('admin/courses/show.include_registration_fee') }}</h3>
                                <p class="text-sm text-gray-600">
                                    {{ $course->include_registration_fee ? __('admin/courses/show.include_registration_fee_on') : __('admin/courses/show.include_registration_fee_off') }}
                                </p>
                            </div>

                            <div class="text-center p-4 bg-gray-50 rounded-xl border">
                                <div class="w-12 h-12 mx-auto mb-3 flex items-center justify-center rounded-full bg-blue-100 text-blue-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-gray-900 mb-1">{{ __('admin/courses/show.registration_link') }}</h3>
                                <p class="text-sm text-gray-600">
                                    {{ $course->registration_link ? __('admin/courses/show.registration_link_available') : __('admin/courses/show.registration_link_unset') }}
                                </p>
                            </div>
                        </div>

                        @if($course->registration_link)
                            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                                <label class="block text-sm font-semibold text-blue-900 mb-2">{{ __('admin/courses/show.registration_link_label') }}</label>
                                <div class="flex items-center gap-3">
                                    <code class="flex-1 bg-white px-3 py-2 rounded border text-blue-800 text-sm break-all">
                                        {{ $course->registration_link }}
                                    </code>
                                    <a href="{{ $course->registration_link }}" target="_blank" 
                                       class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                        Buka
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                @if($course->description)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-200">
                                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    {{ __('admin/courses/show.description') }}
                                </h2>
                        </div>
                        <div class="p-6">
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                                <pre class="whitespace-pre-wrap text-sm text-gray-700 font-mono">{{ $course->description }}</pre>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- instructions template -->
                @if($course->instructions_template)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-200">
                                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    {{ __('admin/courses/show.instructions_template') }}
                                </h2>
                        </div>
                        <div class="p-6">
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                                <pre class="whitespace-pre-wrap text-sm text-gray-700 font-mono">{{ $course->instructions_template }}</pre>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Session Notes Template -->
                @if($course->session_notes_template)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-200">
                                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    {{ __('admin/courses/show.session_notes_template') }}
                                </h2>
                        </div>
                        <div class="p-6">
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                                <pre class="whitespace-pre-wrap text-sm text-gray-700 font-mono">{{ $course->session_notes_template }}</pre>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // You could add a toast notification here
        alert('Course ID copied to clipboard!');
    });
}
</script>
@endsection
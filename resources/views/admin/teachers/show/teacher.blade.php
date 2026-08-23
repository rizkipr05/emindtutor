@extends('layouts.admin')

@section('title', __('admin/teachers/show/teacher.header'))

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto px-2">
        
        <!-- Back Navigation -->
        <a href="{{ route('admin.teachers.index') }}" class="group block mb-6">
            <div class="bg-white/80 backdrop-blur-sm hover:bg-white p-4 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-white/50">
                <div class="inline-flex items-center text-gray-700 group-hover:text-sky-600 font-medium transition-colors duration-200">
                    <div class="p-2 rounded-full bg-sky-100 group-hover:bg-sky-200 mr-3 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </div>
                    <span class="text-lg">{{ $teacher->user->name ?? __('admin/teachers/show/teacher.header') }}</span>
                </div>
            </div>
        </a>

        <!-- Main Content Card -->
        <div class="bg-white/90 backdrop-blur-sm shadow-2xl rounded-3xl border border-white/50 overflow-hidden">
            
            <!-- Header with gradient -->
            <div class="bg-gradient-to-r from-sky-600 via-cyan-600 to-sky-600 p-6 text-white">
                <h1 class="text-2xl font-bold flex items-center">
                    <div class="p-2 bg-white/20 rounded-lg mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    {{ __('admin/teachers/show/teacher.header') }}
                </h1>
            </div>

            <div class="p-4">
                <!-- Toggle Tab Menu -->
                <div class="mb-4">
                    <div class="bg-gray-100 p-1 rounded-2xl flex flex-col sm:flex-row sm:space-x-1 space-y-1 sm:space-y-0" id="tabToggle">
                        <button class="tab-btn active px-6 py-3 rounded-xl font-medium transition-all duration-300 text-sm" data-tab="teacher">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ __('admin/teachers/show/teacher.tabs.info') }}
                            </span>
                        </button>
                        <button class="tab-btn px-6 py-3 rounded-xl font-medium transition-all duration-300 text-sm" data-tab="classes">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                {{ __('admin/teachers/show/teacher.tabs.classes') }}
                            </span>
                        </button>
                        <button class="tab-btn px-6 py-3 rounded-xl font-medium transition-all duration-300 text-sm" data-tab="timetable">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ __('admin/teachers/show/teacher.tabs.timetable') }}
                            </span>
                        </button>
                        <button class="tab-btn px-6 py-3 rounded-xl font-medium transition-all duration-300 text-sm" data-tab="reviews">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                                {{ __('admin/teachers/show/teacher.tabs.reviews') }}
                            </span>
                        </button>
                    </div>
                </div>


                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- Teacher Info Tab -->
                    <div id="teacher-content" class="tab-pane active">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                
                                <!-- Personal Information -->
                                <div class="space-y-4">
                                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                                        <div class="w-2 h-6 bg-gradient-to-b from-sky-500 to-cyan-500 rounded-full mr-3"></div>
                                        {{ __('admin/teachers/show/teacher.sections.personal') }}
                                    </h3>

                                    <div class="space-y-3">
                                        <!-- Field -->
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 items-start group">
                                            <div class="text-sm font-semibold text-gray-600 pt-1">{{ __('admin/teachers/show/teacher.fields.name') }}</div>
                                            <div class="sm:col-span-2 bg-white rounded-lg p-3 shadow-sm border border-gray-200 group-hover:border-blue-300 transition-colors">
                                                {{ $teacher->user->name ?? '-' }}
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 items-start group">
                                            <div class="text-sm font-semibold text-gray-600 pt-1">{{ __('admin/teachers/show/teacher.fields.email') }}</div>
                                            <div class="sm:col-span-2 bg-white rounded-lg p-3 shadow-sm border border-gray-200 group-hover:border-blue-300 transition-colors">
                                                {{ $teacher->user->email ?? '-' }}
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 items-start group">
                                            <div class="text-sm font-semibold text-gray-600 pt-1">{{ __('admin/teachers/show/teacher.fields.phone') }}</div>
                                            <div class="sm:col-span-2 bg-white rounded-lg p-3 shadow-sm border border-gray-200 group-hover:border-blue-300 transition-colors">
                                                {{ $teacher->phone }}
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 items-start group">
                                            <div class="text-sm font-semibold text-gray-600 pt-1">{{ __('admin/teachers/show/teacher.fields.ic_number') }}</div>
                                            <div class="sm:col-span-2 bg-white rounded-lg p-3 shadow-sm border border-gray-200 group-hover:border-blue-300 transition-colors">
                                                {{ $teacher->ic_number }}
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 items-start group">
                                            <div class="text-sm font-semibold text-gray-600 pt-1">{{ __('admin/teachers/show/teacher.fields.status') }}</div>
                                            <div class="sm:col-span-2">
                                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                                                    {{ $teacher->status === 'active' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                                    <div class="w-2 h-2 rounded-full mr-2 {{ $teacher->status === 'active' ? 'bg-green-400' : 'bg-red-400' }}"></div>
                                                    {{ __('admin/teachers/show/teacher.fields.status_' . $teacher->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Financial Information -->
                                <div class="space-y-4">
                                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                                        <div class="w-2 h-6 bg-gradient-to-b from-green-500 to-emerald-500 rounded-full mr-3"></div>
                                        {{ __('admin/teachers/show/teacher.sections.financial') }}
                                    </h3>

                                    <div class="space-y-3">
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 items-start group">
                                            <div class="text-sm font-semibold text-gray-600 pt-1">{{ __('admin/teachers/show/teacher.fields.bank_name') }}</div>
                                            <div class="sm:col-span-2 bg-white rounded-lg p-3 shadow-sm border border-gray-200 group-hover:border-green-300 transition-colors">
                                                {{ $teacher->bank_name }}
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 items-start group">
                                            <div class="text-sm font-semibold text-gray-600 pt-1">{{ __('admin/teachers/show/teacher.fields.bank_account_number') }}</div>
                                            <div class="sm:col-span-2 bg-white rounded-lg p-3 shadow-sm border border-gray-200 group-hover:border-green-300 transition-colors">
                                                {{ $teacher->bank_account_number }}
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 items-start group">
                                            <div class="text-sm font-semibold text-gray-600 pt-1">{{ __('admin/teachers/show/teacher.fields.bank_account_holder') }}</div>
                                            <div class="sm:col-span-2 bg-white rounded-lg p-3 shadow-sm border border-gray-200 group-hover:border-green-300 transition-colors">
                                                {{ $teacher->bank_account_holder }}
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 items-start group">
                                            <div class="text-sm font-semibold text-gray-600 pt-1">{{ __('admin/teachers/show/teacher.fields.account_holders_ic_number') }}</div>
                                            <div class="sm:col-span-2 bg-white rounded-lg p-3 shadow-sm border border-gray-200 group-hover:border-green-300 transition-colors">
                                                {{ $teacher->account_holders_ic_number }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                    <!-- Classes Tab -->
                    <div id="classes-content" class="tab-pane hidden">
                        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-4 border border-indigo-100 text-center">
                            
                        {{-- INDIVIDUAL --}}
                        <div class="mb-10">
                            <div class="flex items-center mb-6">
                                <div class="bg-gradient-to-r from-emerald-500 to-teal-500 p-3 rounded-xl mr-4 shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">{{ __('admin/teachers/show/teacher.classes.individual') }}</h3>
                            </div>
                            
                            <div class="space-y-4">
                                @forelse($classes->where('course_type', 'individu') as $class)
                                    <div class="bg-gradient-to-r from-white to-emerald-50/50 border border-emerald-200/50 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-3 hover:scale-102">
                                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-1">
                                            <div class="flex-1">
                                                @foreach($class['students'] as $index => $student)
                                                    <a href="{{ route('admin.students.show', $class['studentsId'][$index]) }}" 
                                                    class="block text-lg font-bold text-emerald-700 hover:text-emerald-800 transition-colors duration-200 mb-2 hover:underline decoration-2 underline-offset-4">
                                                        {{ $student }}
                                                    </a>
                                                @endforeach
                                                <a href="{{ route('admin.class.show', $class['id']) }}"
                                                    class="inline-flex items-center 
                                                        bg-gradient-to-r from-emerald-100 to-teal-100 
                                                        text-emerald-700 text-sm font-semibold 
                                                        px-3 py-1 rounded-full border border-emerald-200 
                                                        transition duration-200 ease-in-out
                                                        hover:from-emerald-200 hover:to-teal-200 
                                                        hover:text-emerald-900 hover:border-emerald-300 hover:shadow-md">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                    </svg>
                                                    {{ $class['course_name'] }}
                                                </a>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                                            <div class="bg-white/70 backdrop-blur-sm rounded-xl p-3 border border-gray-200/50">
                                                <span class="text-sm text-gray-600 font-medium">{{ __('admin/teachers/show/teacher.classes.total_sessions') }}:</span>
                                                <span class="ml-2 text-lg font-bold text-blue-600">{{ $class['total_sessions'] }}</span>
                                            </div>
                                            <div class="bg-white/70 backdrop-blur-sm rounded-xl p-3 border border-gray-200/50">
                                                <span class="text-sm text-gray-600 font-medium">{{ __('admin/teachers/show/teacher.classes.attendance') }}:</span>
                                                <span class="ml-2 text-lg font-bold {{ $class['attendance_rate'] >= 80 ? 'text-green-600' : ($class['attendance_rate'] >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                                    {{ $class['attendance_rate'] }}%
                                                </span>
                                            </div>
                                        </div>

                                        <button onclick="toggleDetails('{{ $class['id'] }}')" 
                                            class="mt-4 w-full bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 flex items-center justify-center shadow-md hover:shadow-lg">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            {{ __('admin/teachers/show/teacher.classes.view_details') }}
                                        </button>
                                        
                                        <div id="details-{{ $class['id'] }}" class="hidden mt-4 space-y-4 border-t border-emerald-200/50 pt-4">
                                            {{-- Jadwal --}}
                                            @if(count($class['schedule_days']) > 0)
                                                <div class="bg-white/50 backdrop-blur-sm rounded-xl p-4 border border-gray-200/30">
                                                    <h4 class="text-base font-bold text-gray-800 mb-3 flex items-center">
                                                        <div class="bg-blue-500 p-1.5 rounded-lg mr-2">
                                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                        </div>
                                                        {{ __('admin/teachers/show/teacher.classes.schedule') }}
                                                    </h4>
                                                    <div class="space-y-2">
                                                        @foreach($class['schedule_days'] as $day)
                                                            <div class="flex flex-col sm:flex-row sm:items-center bg-white/70 rounded-lg p-3 border border-blue-200/50">
                                                                <div class="bg-blue-500 text-white font-medium text-xs px-3 py-1 rounded-full mb-2 sm:mb-0 sm:mr-3">
                                                                    {{ __('admin/teachers/show/teacher.timetable.days')[strtolower($day['hari'])] ?? ucfirst($day['hari']) }}
                                                                </div>
                                                                <div class="text-gray-700 font-medium">
                                                                    {{ \Carbon\Carbon::parse($day['jam_mulai'])->format('H:i') }} 
                                                                    <span class="text-blue-600 font-semibold">({{ $day['durasi_menit'] }} {{ __('admin/teachers/show/teacher.classes.minute') }})</span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- Riwayat Sesi --}}
                                            @if(count($class['session_details']) > 0)
                                                <div class="bg-white/50 backdrop-blur-sm rounded-xl p-4 border border-gray-200/30">
                                                    <h4 class="text-base font-bold text-gray-800 mb-3 flex items-center">
                                                        <div class="bg-green-500 p-1.5 rounded-lg mr-2">
                                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                            </svg>
                                                        </div>
                                                        {{ __('admin/teachers/show/teacher.classes.session_history') }}
                                                    </h4>
                                                    <div class="space-y-2 max-h-48 overflow-y-auto">
                                                        @foreach($class['session_details'] as $session)
                                                            <div class="bg-white/70 rounded-lg p-3 border border-gray-200/50">
                                                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                                                                    <div class="font-semibold text-gray-800">
                                                                        {{ \Carbon\Carbon::parse($session['date'])->format('d F Y') }}
                                                                    </div>
                                                                    <div class="flex flex-wrap gap-2">
                                                                        <span class="bg-green-100 text-green-700 font-medium px-2 py-1 rounded-full text-xs border border-green-200">
                                                                            ✓ {{ $session['present'] }}
                                                                        </span>
                                                                        <span class="bg-yellow-100 text-yellow-700 font-medium px-2 py-1 rounded-full text-xs border border-yellow-200">
                                                                            ⏰ {{ $session['late'] }}
                                                                        </span>
                                                                        <span class="bg-red-100 text-red-700 font-medium px-2 py-1 rounded-full text-xs border border-red-200">
                                                                            ✗ {{ $session['absent'] }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-300">
                                        <div class="bg-gray-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-gray-500 font-medium">{{ __('admin/teachers/show/teacher.classes.no_individual') }}</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- GROUP / KUMPULAN --}}
                        <div>
                            <div class="flex items-center mb-6">
                                <div class="bg-gradient-to-r from-rose-500 to-pink-500 p-3 rounded-xl mr-4 shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent">{{ __('admin/teachers/show/teacher.classes.group') }}</h3>
                            </div>
                            
                            <div class="space-y-4">
                                @forelse($classes->where('course_type', 'kumpulan') as $class)
                                    <div class="bg-gradient-to-r from-white to-rose-50/50 border border-rose-200/50 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-3 hover:scale-102">
                                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                                            <div class="flex-1">
                                                <a href="{{ route('admin.class.show', $class['id']) }}" class="block text-lg font-bold text-rose-700 hover:text-rose-800 transition-colors duration-200 mb-2 hover:underline decoration-2 underline-offset-4">
                                                    {{ $class['course_name'] }}
                                                </a>
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <div class="inline-flex items-center bg-gradient-to-r from-rose-100 to-pink-100 text-rose-700 text-sm font-semibold px-3 py-1 rounded-full border border-rose-200">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                        </svg>
                                                        {{ __('admin/teachers/show/teacher.classes.group') }}
                                                    </div>
                                                    <span class="bg-gradient-to-r from-orange-500 to-red-500 text-white text-sm font-bold px-3 py-1 rounded-full shadow-sm">
                                                        {{ $class['student_count'] }} {{ __('admin/teachers/show/teacher.classes.students') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                                            <div class="bg-white/70 backdrop-blur-sm rounded-xl p-3 border border-gray-200/50">
                                                <span class="text-sm text-gray-600 font-medium">{{ __('admin/teachers/show/teacher.classes.total_sessions') }}:</span>
                                                <span class="ml-2 text-lg font-bold text-blue-600">{{ $class['total_sessions'] }}</span>
                                            </div>
                                            <div class="bg-white/70 backdrop-blur-sm rounded-xl p-3 border border-gray-200/50">
                                                <span class="text-sm text-gray-600 font-medium">{{ __('admin/teachers/show/teacher.classes.attendance') }}:</span>
                                                <span class="ml-2 text-lg font-bold {{ $class['attendance_rate'] >= 80 ? 'text-green-600' : ($class['attendance_rate'] >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                                    {{ $class['attendance_rate'] }}%
                                                </span>
                                            </div>
                                        </div>

                                        <button onclick="toggleDetails('{{ $class['id'] }}')" 
                                            class="mt-4 w-full bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 flex items-center justify-center shadow-md hover:shadow-lg">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            {{ __('admin/teachers/show/teacher.classes.view_details') }}
                                        </button>
                                        
                                        <div id="details-{{ $class['id'] }}" class="hidden mt-4 space-y-4 border-t border-rose-200/50 pt-4">
                                            {{-- Daftar Student --}}
                                            @if($class['student_count'] > 0)
                                                <div class="bg-white/50 backdrop-blur-sm rounded-xl p-4 border border-gray-200/30">
                                                    <h4 class="text-base font-bold text-gray-800 mb-3 flex items-center">
                                                        <div class="bg-rose-500 text-white p-1.5 rounded-lg mr-2">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                            </svg>
                                                        </div>
                                                        {{ __('admin/teachers/show/teacher.classes.students') }} ({{ $class['student_count'] }})
                                                    </h4>
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                        @foreach($class['students'] as $index => $student)
                                                            <a href="{{ route('admin.students.show', $class['studentsId'][$index]) }}" class="bg-white/70 rounded-lg p-3 border border-rose-200/50 flex items-center">
                                                                <div class="w-8 h-8 bg-rose-100 rounded-full flex items-center justify-center mr-3">
                                                                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                                    </svg>
                                                                </div>
                                                                <span class="text-gray-700 font-medium">{{ $student }}</span>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- Jadwal --}}
                                            @if(count($class['schedule_days']) > 0)
                                                <div class="bg-white/50 backdrop-blur-sm rounded-xl p-4 border border-gray-200/30">
                                                    <h4 class="text-base font-bold text-gray-800 mb-3 flex items-center">
                                                        <div class="bg-blue-500 p-1.5 rounded-lg mr-2">
                                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                        </div>
                                                        {{ __('admin/teachers/show/teacher.classes.schedule') }}
                                                    </h4>
                                                    <div class="space-y-2">
                                                        @foreach($class['schedule_days'] as $day)
                                                            <div class="flex flex-col sm:flex-row sm:items-center bg-white/70 rounded-lg p-3 border border-blue-200/50">
                                                                <div class="bg-blue-500 text-white font-medium text-xs px-3 py-1 rounded-full mb-2 sm:mb-0 sm:mr-3">
                                                                    {{ __('admin/teachers/show/teacher.timetable.days')[strtolower($day['hari'])] ?? ucfirst($day['hari']) }}
                                                                </div>
                                                                <div class="text-gray-700 font-medium">
                                                                    {{ \Carbon\Carbon::parse($day['jam_mulai'])->format('H:i') }} 
                                                                    <span class="text-blue-600 font-semibold">({{ $day['durasi_menit'] }} {{ __('admin/teachers/show/teacher.classes.minute') }})</span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- Riwayat Sesi --}}
                                            @if(count($class['session_details']) > 0)
                                                <div class="bg-white/50 backdrop-blur-sm rounded-xl p-4 border border-gray-200/30">
                                                    <h4 class="text-base font-bold text-gray-800 mb-3 flex items-center">
                                                        <div class="bg-green-500 p-1.5 rounded-lg mr-2">
                                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                            </svg>
                                                        </div>
                                                        {{ __('admin/teachers/show/teacher.classes.session_history') }}
                                                    </h4>
                                                    <div class="space-y-2 max-h-48 overflow-y-auto">
                                                        @foreach($class['session_details'] as $session)
                                                            <div class="bg-white/70 rounded-lg p-3 border border-gray-200/50">
                                                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                                                                    <div class="font-semibold text-gray-800">
                                                                        {{ \Carbon\Carbon::parse($session['date'])->format('d F Y') }}
                                                                    </div>
                                                                    <div class="flex flex-wrap gap-2">
                                                                        <span class="bg-green-100 text-green-700 font-medium px-2 py-1 rounded-full text-xs border border-green-200">
                                                                            ✓ {{ $session['present'] }}
                                                                        </span>
                                                                        <span class="bg-yellow-100 text-yellow-700 font-medium px-2 py-1 rounded-full text-xs border border-yellow-200">
                                                                            ⏰ {{ $session['late'] }}
                                                                        </span>
                                                                        <span class="bg-red-100 text-red-700 font-medium px-2 py-1 rounded-full text-xs border border-red-200">
                                                                            ✗ {{ $session['absent'] }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-300">
                                        <div class="bg-gray-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-gray-500 font-medium">{{ __('admin/teachers/show/teacher.classes.no_group') }}</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        </div>
                    </div>

                    <!-- Timetable Tab (Empty for now) -->
                    <div id="timetable-content" class="tab-pane hidden">
                        <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-2xl border border-orange-100 text-center">
                            <!-- Timetable -->
                            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                                <div class="p-4 border-b bg-gray-50">
                                    <!-- <h2 class="text-lg font-semibold text-gray-900">Weekly Schedule</h2> -->
                                    <div class="flex items-center space-x-6 text-sm">
                                        <div class="flex items-center">
                                            <div class="w-4 h-4 bg-gradient-to-r from-green-500 to-teal-500 rounded mr-2"></div>
                                            <span class="text-gray-600">{{ __('admin/teachers/show/teacher.timetable.class_schedule') }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-4 h-4 bg-gradient-to-r from-red-500 to-pink-500 rounded mr-2"></div>
                                            <span class="text-gray-600">{{ __('admin/teachers/show/teacher.timetable.unavailable') }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-4 h-4 bg-gray-100 border border-gray-300 rounded mr-2"></div>
                                            <span class="text-gray-600">{{ __('admin/teachers/show/teacher.timetable.available') }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Scrollable Timetable Container -->
                                <div class="overflow-x-auto">
                                    <div class="relative" style="min-width: 900px;">
                                        @php
                                            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                            $dayNames = ['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'];
                                            
                                            // Generate time slots from 6 AM to 9 PM (16 hours total)
                                            $startHour = 0;
                                            $endHour = 24;
                                            $totalHours = $endHour - $startHour;
                                            $cellHeight = 60; // Height per hour in pixels
                                            
                                            // Group unavailabilities by day
                                            $scheduleByDay = [];
                                            foreach ($unavailabilities as $item) {
                                                $day = strtolower($item['day']);
                                                if (!isset($scheduleByDay[$day])) {
                                                    $scheduleByDay[$day] = [];
                                                }
                                                $scheduleByDay[$day][] = $item;
                                            }
                                            
                                            // Helper function to convert time to pixels
                                            function timeToPixels($time, $startHour, $cellHeight) {
                                                $parts = explode(':', $time);
                                                $hour = (int)$parts[0];
                                                $minute = (int)$parts[1];
                                                $totalMinutes = (($hour - $startHour) * 60) + $minute;
                                                return ($totalMinutes / 60) * $cellHeight;
                                            }
                                            
                                            // Helper function to calculate duration in pixels
                                            function durationToPixels($startTime, $endTime, $cellHeight) {
                                                $startParts = explode(':', $startTime);
                                                $endParts = explode(':', $endTime);
                                                
                                                $startMinutes = ((int)$startParts[0] * 60) + (int)$startParts[1];
                                                $endMinutes = ((int)$endParts[0] * 60) + (int)$endParts[1];
                                                
                                                $durationMinutes = $endMinutes - $startMinutes;
                                                return ($durationMinutes / 60) * $cellHeight;
                                            }
                                        @endphp
                                        
                                        <!-- Fixed Header -->
                                        <div class="sticky top-0 z-20 bg-white">
                                            <div class="grid grid-cols-8">
                                                <!-- Time column header -->
                                                <div class="bg-sky-500 text-white p-3 text-center font-semibold border-r-2 border-sky-500">
                                                    {{ __('admin/teachers/show/teacher.timetable.time') }}
                                                </div>
                                                
                                                <!-- Day headers -->
                                                @foreach (__('admin/teachers/show/teacher.timetable.day_names') as $dayName)
                                                    <div class="bg-sky-500 text-white p-3 text-center font-semibold border-r border-sky-500">
                                                        {{ $dayName }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Scrollable Content -->
                                        <div class="overflow-y-auto" style="max-height: 500px;">
                                            <div class="relative">
                                                <!-- Grid Structure -->
                                                <div class="grid grid-cols-8">
                                                    <!-- Time slots and day columns -->
                                                    @for ($hour = $startHour; $hour < $endHour; $hour++)
                                                        <!-- Time label -->
                                                        <div class="bg-gray-50 border-r-2 border-gray-300 border-b border-gray-200 p-2 text-center text-sm font-medium text-gray-700 sticky left-0 z-10"
                                                            style="height: {{ $cellHeight }}px;">
                                                            {{ sprintf('%02d:00', $hour) }}
                                                        </div>
                                                        
                                                        <!-- Day cells -->
                                                        @foreach ($days as $dayIndex => $day)
                                                            <div class="border-r border-b border-gray-200 relative bg-gray-50 hover:bg-gray-100 transition-colors"
                                                                style="height: {{ $cellHeight }}px;">
                                                                <!-- Grid lines for 30-minute intervals -->
                                                                <div class="absolute top-1/2 left-0 right-0 h-px bg-gray-300 opacity-50"></div>
                                                            </div>
                                                        @endforeach
                                                    @endfor
                                                </div>

                                                <!-- Schedule Cards Overlay -->
                                                <div class="absolute inset-0 pointer-events-none">
                                                    @foreach ($scheduleByDay as $day => $schedules)
                                                        @php
                                                            $dayIndex = array_search($day, $days);
                                                            if ($dayIndex === false) continue;
                                                            
                                                            // Calculate column position (skip time column)
                                                            $leftPosition = ((( $dayIndex + 1) / 8) * 100);
                                                            $width = (1/8) * 100;
                                                        @endphp
                                                        
                                                        @foreach ($schedules as $schedule)
                                                            @php
                                                                // Skip if time is invalid
                                                                if (!$schedule['start'] || !$schedule['end']) continue;
                                                                
                                                                // Calculate start hour to check if it's within our range
                                                                $startHour24 = (int) substr($schedule['start'], 0, 2);
                                                                if ($startHour24 < $startHour || $startHour24 >= $endHour) continue;
                                                                
                                                                $topPosition = timeToPixels($schedule['start'], $startHour, $cellHeight);
                                                                $height = durationToPixels($schedule['start'], $schedule['end'], $cellHeight);
                                                            @endphp
                                                            
                                                            @if ($schedule['type'] === 'class')
                                                                <a href="{{ route('admin.class.show', $schedule['id']) }}" 
                                                                class="absolute bg-gradient-to-r from-green-500 to-teal-500 text-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 transform cursor-pointer pointer-events-auto group"
                                                                style="left: {{ $leftPosition }}%; width: {{ $width - 0.5 }}%; top: {{ $topPosition }}px; height: {{ max($height - 2, 30) }}px; margin: 1px;">
                                                                    <div class="p-2 h-full flex flex-col justify-center text-xs">
                                                                       
                                                                        <div class="font-bold mb-1">
                                                                            {{ $schedule['course'] }}
                                                                        </div>
                                                                        <div class="text-green-100 text-xs mb-1">
                                                                            <i class="fas fa-clock mr-1"></i>
                                                                            {{ substr($schedule['start'], 0, 5) }} - {{ substr($schedule['end'], 0, 5) }}
                                                                        </div>
                                                                        
                                                                    </div>
                                                                    <!-- Hover overlay -->
                                                                    <div class="absolute inset-0 bg-white bg-opacity-50 rounded-lg opacity-0 group-hover:opacity-10 transition-opacity duration-200"></div>
                                                                </a>
                                                            @else
                                                                <div class="absolute bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 transform cursor-pointer pointer-events-auto group"
                                                                    style="left: {{ $leftPosition }}%; width: {{ $width - 0.5 }}%; top: {{ $topPosition }}px; height: {{ max($height - 2, 30) }}px; margin: 1px;">
                                                                    <div class="p-2 h-full flex flex-col justify-center text-xs">
                                                                        <div class="font-bold truncate mb-1">
                                                                            <i class="fas fa-times-circle mr-1 text-xs"></i>
                                                                            {{ __('admin/teachers/show/teacher.timetable.unavailable') }}
                                                                        </div>
                                                                        <div class="text-red-100 text-xs mb-1">
                                                                            <i class="fas fa-clock mr-1"></i>
                                                                            {{ substr($schedule['start'], 0, 5) }} - {{ substr($schedule['end'], 0, 5) }}
                                                                        </div>
                                                                                
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <!-- Reviews Tab -->
                    <div id="reviews-content" class="tab-pane hidden">
                        <!-- Rating Summary Card -->
                        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-2xl p-6 border border-yellow-100 mb-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-8">
                                    <!-- Average Rating Display -->
                                    <div class="text-center">
                                        <div class="text-4xl font-bold text-gray-800">
                                            {{ number_format($averageRating, 1) }}
                                        </div>
                                        <div class="flex items-center justify-center mb-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5 {{ $i <= round($averageRating) ? 'text-yellow-400' : 'text-gray-300' }}"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.946a1
                                                    1 0 00.95.69h4.148c.969 0 1.371
                                                    1.24.588 1.81l-3.357 2.44a1 1 0
                                                    00-.364 1.118l1.287 3.946c.3.922-.755
                                                    1.688-1.54 1.118l-3.357-2.44a1 1 0
                                                    00-1.175 0l-3.357 2.44c-.784.57-1.838-.196-1.539-1.118l1.287-3.946a1
                                                    1 0 00-.364-1.118L2.075 9.373c-.783-.57-.38-1.81.588-1.81h4.149a1
                                                    1 0 00.95-.69l1.287-3.946z" />
                                                </svg>
                                            @endfor
                                        </div>
                                        <p class="text-sm text-gray-600">{{ __('admin/teachers/show/teacher.reviews.rating_average') }}</p>
                                    </div>
                                    
                                    <!-- Total Reviews -->
                                    <div class="text-center">
                                        <div class="text-4xl font-bold text-gray-800">
                                            {{ $totalReviews }}
                                        </div>
                                        <p class="text-sm text-gray-600">{{ __('admin/teachers/show/teacher.reviews.total_reviews') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reviews List -->
                        @if($reviews->isEmpty())
                            <div class="bg-white shadow rounded-xl p-8 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.959 8.959 0 01-4.906-1.436L3 21l1.436-5.094A8.959 8.959 0 011 12c0-4.418 3.582-8 8-8s8 3.582 8 8z" />
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('admin/teachers/show/teacher.reviews.empty_title') }}</h3>
                                <p class="text-gray-500">{{ __('admin/teachers/show/teacher.reviews.empty_desc') }}</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($reviews as $review)
                                    <div class="bg-white shadow rounded-xl p-6 hover:shadow-lg transition-shadow duration-200">
                                        <div class="flex items-start justify-between mb-3">
                                            <!-- Student Info -->
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center">
                                                    <span class="text-white font-semibold text-sm">
                                                        {{ strtoupper(substr($review->student->user->name ?? 'A', 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-gray-800">
                                                        {{ $review->student->user->name ?? __('admin/teachers/show/teacher.reviews.anonymous') }}
                                                    </p>
                                                </div>
                                            </div>
                                            
                                            <!-- Date -->
                                            <div class="text-right">
                                                <span class="text-sm text-gray-500">
                                                    {{ $review->created_at->format('d M Y') }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- Rating Stars -->
                                        <div class="flex items-center mb-3">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.946a1
                                                    1 0 00.95.69h4.148c.969 0 1.371
                                                    1.24.588 1.81l-3.357 2.44a1 1 0
                                                    00-.364 1.118l1.287 3.946c.3.922-.755
                                                    1.688-1.54 1.118l-3.357-2.44a1 1 0
                                                    00-1.175 0l-3.357 2.44c-.784.57-1.838-.196-1.539-1.118l1.287-3.946a1
                                                    1 0 00-.364-1.118L2.075 9.373c-.783-.57-.38-1.81.588-1.81h4.149a1
                                                    1 0 00.95-.69l1.287-3.946z" />
                                                </svg>
                                            @endfor
                                            <span class="ml-2 text-sm font-medium text-gray-600">
                                                ({{ $review->rating }}/5)
                                            </span>
                                        </div>
                                        
                                        <!-- Comment -->
                                        @if($review->comment)
                                            <div class="bg-gray-50 rounded-lg p-4">
                                                <p class="text-gray-700 leading-relaxed">{{ $review->comment }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Load More Button (if needed for pagination) -->
                            @if($reviews->count() >= 10)
                                <div class="text-center mt-6">
                                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors duration-200">
                                        {{ __('admin/teachers/show/teacher.reviews.load_more') }}
                                    </button>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-4 flex justify-end">
                    <a href="{{ route('admin.teachers.edit', $teacher->id) }}" 
                       class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-sky-500 to-cyan-600 text-white rounded-xl hover:from-sky-600 hover:to-cyan-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        {{ __('admin/teachers/show/teacher.actions.edit') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.dataset.tab;

            // Remove active class from all buttons
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'bg-white', 'text-blue-600', 'shadow-lg');
                btn.classList.add('text-gray-600', 'hover:text-gray-800');
            });

            // Add active class to clicked button
            this.classList.add('active', 'bg-white', 'text-blue-600', 'shadow-lg');
            this.classList.remove('text-gray-600', 'hover:text-gray-800');

            // Hide all tab panes
            tabPanes.forEach(pane => {
                pane.classList.add('hidden');
                pane.classList.remove('active');
            });

            // Show target tab pane
            const targetPane = document.getElementById(targetTab + '-content');
            if (targetPane) {
                targetPane.classList.remove('hidden');
                targetPane.classList.add('active');
            }
        });
    });
});
function toggleDetails(id) {
    let el = document.getElementById('details-' + id);
    el.classList.toggle('hidden');
}
</script>

<style>
.tab-btn.active {
    background: white;
    color: #2563eb;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.tab-btn:not(.active) {
    color: #6b7280;
}

.tab-btn:not(.active):hover {
    color: #374151;
}
</style>
@endsection

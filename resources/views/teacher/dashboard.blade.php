@extends('layouts.teacher')

@section('title', __('teacher/dashboard.page_title', ['site' => 'EMindTutor']))

@section('content')

<div class="min-h-screen sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-6 sm:space-y-8">
        
        <!-- Header Welcome -->
        <div class="text-center mb-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2">{{ __('teacher/dashboard.teacher_dashboard', ['company' => $teacher->company?->user?->name ?? $teacher->company?->nama_wakil_syarikat ?? 'EMindTutor']) }}</h1>
            <p class="text-gray-600 text-sm sm:text-base">{{ __('teacher/dashboard.welcome_back', ['site' => 'EMindTutor']) }}</p>
        </div>

        <div class="bg-white/80 backdrop-blur-sm border border-white/20 p-4 sm:p-6 lg:p-8 rounded-2xl sm:rounded-3xl shadow-lg">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-slate-100 rounded-xl">
                    <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"></path>
                    </svg>
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ __('teacher/dashboard.overview_heading') }}</h2>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <div class="rounded-xl bg-blue-50 p-4">
                    <p class="text-sm text-gray-500">{{ __('teacher/dashboard.stat_students') }}</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $overview['students'] }}</p>
                </div>
                <div class="rounded-xl bg-green-50 p-4">
                    <p class="text-sm text-gray-500">{{ __('teacher/dashboard.stat_classes_this_week') }}</p>
                    <p class="text-2xl font-bold text-green-600">{{ $overview['activeClasses'] }}</p>
                </div>
                <div class="rounded-xl bg-amber-50 p-4">
                    <p class="text-sm text-gray-500">{{ __('teacher/dashboard.overview_announcements') }}</p>
                    <p class="text-2xl font-bold text-amber-600">{{ $overview['announcements'] }}</p>
                </div>
                <div class="rounded-xl bg-violet-50 p-4">
                    <p class="text-sm text-gray-500">{{ __('teacher/dashboard.overview_unavailabilities') }}</p>
                    <p class="text-2xl font-bold text-violet-600">{{ $overview['unavailabilities'] }}</p>
                </div>
            </div>
        </div>

        <!-- Maklumat Guru -->
        <div class="bg-white/80 backdrop-blur-sm border border-white/20 p-4 sm:p-6 lg:p-8 rounded-2xl sm:rounded-3xl shadow-lg hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-blue-100 rounded-xl">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ __('teacher/dashboard.info_heading') }}</h2>
            </div>
            
            <div class="overflow-hidden">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div class="bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition-colors duration-200">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="font-semibold text-gray-700 text-sm sm:text-base">{{ __('teacher/dashboard.label_name') }}</span>
                            <span class="text-gray-900 font-medium break-words">{{ $teacher->user?->name ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition-colors duration-200">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="font-semibold text-gray-700 text-sm sm:text-base">{{ __('teacher/dashboard.label_email') }}</span>
                            <span class="text-gray-900 font-medium break-all">{{ $teacher->user?->email ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition-colors duration-200">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="font-semibold text-gray-700 text-sm sm:text-base">{{ __('teacher/dashboard.label_phone') }}</span>
                            <span class="text-gray-900 font-medium">{{ $teacher->phone ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition-colors duration-200">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="font-semibold text-gray-700 text-sm sm:text-base">{{ __('teacher/dashboard.label_ic') }}</span>
                            <span class="text-gray-900 font-medium">{{ $teacher->ic_number ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Ringkas -->
        <div class="bg-white/80 backdrop-blur-sm border border-white/20 p-4 sm:p-6 lg:p-8 rounded-2xl sm:rounded-3xl shadow-lg hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-green-100 rounded-xl">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ __('teacher/dashboard.stats_heading') }}</h2>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <div class="group bg-gradient-to-br from-blue-50 to-blue-100 p-6 border border-blue-200 rounded-xl sm:rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 hover:scale-105">
                    <div class="text-center">
                        <div class="mb-3">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-500 rounded-xl text-white mb-3">
                                <svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
                                    fill="currentColor" viewBox="0 0 24 24" >
                                    <!--Boxicons v3.0 https://boxicons.com | License  https://docs.boxicons.com/free-->
                                    <path d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5m0-8c1.65 0 3 1.35 3 3s-1.35 3-3 3-3-1.35-3-3 1.35-3 3-3M4 22h16c.55 0 1-.45 1-1v-1c0-3.86-3.14-7-7-7h-4c-3.86 0-7 3.14-7 7v1c0 .55.45 1 1 1m6-7h4c2.76 0 5 2.24 5 5H5c0-2.76 2.24-5 5-5"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-sm sm:text-base font-semibold text-gray-700 mb-2">{{ __('teacher/dashboard.stat_students') }}</p>
                        <p class="text-2xl sm:text-3xl font-bold text-blue-600">{{ $studentsCount }}</p>
                    </div>
                </div>
                
                <div class="group bg-gradient-to-br from-green-50 to-green-100 p-6 border border-green-200 rounded-xl sm:rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 hover:scale-105">
                    <div class="text-center">
                        <div class="mb-3">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-green-500 rounded-xl text-white mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-sm sm:text-base font-semibold text-gray-700 mb-2">{{ __('teacher/dashboard.stat_classes_this_week') }}</p>
                        <p class="text-2xl sm:text-3xl font-bold text-green-600">{{ $classesThisWeek }}</p>
                    </div>
                </div>
                
                <div class="group bg-gradient-to-br from-purple-50 to-purple-100 p-6 border border-purple-200 rounded-xl sm:rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 hover:scale-105 sm:col-span-2 lg:col-span-1">
                    <div class="text-center">
                        <div class="mb-3">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-purple-500 rounded-xl text-white mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-sm sm:text-base font-semibold text-gray-700 mb-2">{{ __('teacher/dashboard.stat_attendance_rate') }}</p>
                        <p class="text-2xl sm:text-3xl font-bold text-purple-600">{{ number_format($attendanceRate, 2) }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengumuman daripada perusahaan -->
        <div class="bg-white/80 backdrop-blur-sm border border-white/20 p-4 sm:p-6 lg:p-8 rounded-2xl sm:rounded-3xl shadow-lg hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-cyan-100 rounded-xl">
                        <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ __('teacher/dashboard.announcement_recent_title') }}</h2>
                        <p class="text-sm text-gray-500">{{ __('teacher/dashboard.announcement_recent_desc') }}</p>
                    </div>
                </div>
                <a href="{{ route('broadcasts.index') }}" class="text-sm font-semibold text-cyan-700 hover:text-cyan-800">
                    {{ __('teacher/dashboard.view_all') }}
                </a>
            </div>

            @if($recentAnnouncements->isEmpty())
                <div class="rounded-2xl border border-dashed border-gray-200 bg-gray-50 px-6 py-10 text-center text-gray-500">
                    {{ __('teacher/dashboard.announcement_empty') }}
                </div>
            @else
                <div class="space-y-4">
                    @foreach($recentAnnouncements as $announcement)
                        <a href="{{ route('broadcasts.show', $announcement->id) }}" class="block rounded-2xl border border-cyan-100 bg-gradient-to-r from-cyan-50 to-white p-4 transition hover:border-cyan-200 hover:shadow-md">
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">{{ $announcement->title }}</h3>
                                    <p class="mt-1 text-sm text-gray-600 line-clamp-2">{{ $announcement->message }}</p>
                                    <div class="mt-3 flex flex-wrap items-center gap-2 text-xs">
                                        <span class="inline-flex items-center rounded-full bg-cyan-100 px-3 py-1 font-medium text-cyan-700">
                                            {{ __('teacher/dashboard.announcement_target_prefix', ['target' => str_replace('_', ' ', $announcement->target_audience)]) }}
                                        </span>
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 font-medium text-gray-600">
                                            {{ \Carbon\Carbon::parse($announcement->created_at)->locale(app()->getLocale() === 'en' ? 'en' : 'ms')->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                                <svg class="mt-1 h-5 w-5 flex-shrink-0 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Waktu Guru Boleh Terima Kelas -->
        <div class="bg-white/80 backdrop-blur-sm border border-white/20 p-4 sm:p-6 lg:p-8 rounded-2xl sm:rounded-3xl shadow-lg hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-indigo-100 rounded-xl">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ __('teacher/dashboard.unavailability_heading') }}</h2>
            </div>
            
            @if($unavailabilities->isEmpty())
                <div class="text-center py-12">
                    <div class="mb-4">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-500 text-base sm:text-lg">{{ __('teacher/dashboard.no_unavailability') }}</p>
                    <p class="text-gray-400 text-sm mt-2">{{ __('teacher/dashboard.available_all_times') }}</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full align-middle">
                        <table class="min-w-full divide-y divide-gray-200 rounded-xl overflow-hidden shadow-sm">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                <tr>
                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold text-gray-900 uppercase tracking-wider">{{ __('teacher/dashboard.table_day') }}</th>
                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold text-gray-900 uppercase tracking-wider">{{ __('teacher/dashboard.table_start_time') }}</th>
                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold text-gray-900 uppercase tracking-wider">{{ __('teacher/dashboard.table_end_time') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($unavailabilities as $item)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm sm:text-base text-gray-900 font-medium">{{ __('teacher/dashboard.days.' . $item->day) ?? $item->day }}</td>
                                    <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm sm:text-base text-gray-700">{{ $item->start_time }}</td>
                                    <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm sm:text-base text-gray-700">{{ $item->end_time }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <!-- Pengurusan Biometrik (WebAuthn) -->
        <div class="bg-white/80 backdrop-blur-sm border border-white/20 p-4 sm:p-6 lg:p-8 rounded-2xl sm:rounded-3xl shadow-lg hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-emerald-100 rounded-xl">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3zm0 2c-2.67 0-8 1.337-8 4v2h16v-2c0-2.663-5.33-4-8-4z"></path>
                    </svg>
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ __('teacher/dashboard.biometric_title') }}</h2>
            </div>
            <p class="text-gray-600 mb-6 text-sm sm:text-base">{{ __('teacher/dashboard.biometric_desc') }}</p>
            <div class="pt-4">
                <button type="button" id="btn-register-capjari" style="background-color: #10b981 !important; color: white !important;" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 font-semibold px-6 py-3 sm:px-8 sm:py-4 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 text-sm sm:text-base">
                    {{ __('teacher/dashboard.biometric_register') }}
                </button>
            </div>
        </div>

        <!-- Tukar Kata Laluan -->
        <div class="bg-white/80 backdrop-blur-sm border border-white/20 p-4 sm:p-6 lg:p-8 rounded-2xl sm:rounded-3xl shadow-lg hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-red-100 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ __('teacher/dashboard.change_password') }}</h2>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 shadow-sm">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-green-800 text-sm sm:text-base">{{ session('success') ?? __('teacher/dashboard.success_saved') }}</p>
                    </div>
                </div>
            @endif

            <!-- Error Message -->
            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 shadow-sm">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <ul class="list-disc list-inside text-red-800 text-sm space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label class="flex items-center gap-2 text-sm sm:text-base font-semibold text-gray-700 mb-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            {{ __('teacher/dashboard.label_current_password') }}
                        </label>
                        <input type="password" name="current_password"
                            class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-xl p-3 sm:p-4 transition-all duration-200 text-sm sm:text-base @error('current_password') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror"
                            placeholder="{{ __('teacher/dashboard.placeholder_current_password') }}"
                            required>
                        @error('current_password')
                            <p class="text-red-600 text-sm mt-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="flex items-center gap-2 text-sm sm:text-base font-semibold text-gray-700 mb-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            {{ __('teacher/dashboard.label_new_password') }}
                        </label>
                        <input type="password" name="new_password"
                            class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-xl p-3 sm:p-4 transition-all duration-200 text-sm sm:text-base @error('new_password') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror"
                            placeholder="{{ __('teacher/dashboard.placeholder_new_password') }}"
                            required>
                        @error('new_password')
                            <p class="text-red-600 text-sm mt-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="flex items-center gap-2 text-sm sm:text-base font-semibold text-gray-700 mb-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ __('teacher/dashboard.label_confirm_password') }}
                        </label>
                        <input type="password" name="new_password_confirmation"
                            class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-xl p-3 sm:p-4 transition-all duration-200 text-sm sm:text-base"
                            placeholder="{{ __('teacher/dashboard.placeholder_confirm_password') }}"
                            required>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold px-6 py-3 sm:px-8 sm:py-4 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 text-sm sm:text-base">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        {{ __('teacher/dashboard.update_password') }}
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

@webauthnScripts

<script>
    const biometricUnavailableMessage = 'Chrome/OS gagal membuat passkey. Pastikan screen lock/PIN Chrome aktif, login Google Password Manager normal, atau gunakan device yang punya fingerprint/Windows Hello.';

    async function platformAuthenticatorAvailable() {
        if (!window.PublicKeyCredential || typeof PublicKeyCredential.isUserVerifyingPlatformAuthenticatorAvailable !== 'function') {
            return true;
        }

        return PublicKeyCredential.isUserVerifyingPlatformAuthenticatorAvailable();
    }

    function prepareWebAuthnCreationOptions(publicKey, preferPlatform = true) {
        if (!preferPlatform) {
            delete publicKey.authenticatorSelection;
            return publicKey;
        }

        publicKey.authenticatorSelection = publicKey.authenticatorSelection || {};
        if (publicKey.authenticatorSelection.residentKey === 'required') {
            publicKey.authenticatorSelection.residentKey = 'preferred';
        }

        publicKey.authenticatorSelection.authenticatorAttachment = 'platform';
        publicKey.authenticatorSelection.requireResidentKey = false;
        publicKey.authenticatorSelection.userVerification = 'preferred';

        return publicKey;
    }

    function webAuthnRegisterErrorMessage(name, message) {
        if (name === 'UnknownError') {
            return message + '\n\n' + biometricUnavailableMessage;
        }

        return message;
    }

    document.getElementById('btn-register-capjari').addEventListener('click', async function () {
        const button = this;
        const resetButton = () => {
            button.disabled = false;
            button.classList.remove('opacity-70', 'cursor-wait');
        };

        const webauthn = new WebAuthn((name, message) => {
            resetButton();
            alert(@json(__('teacher/dashboard.biometric_device_error_prefix')) + webAuthnRegisterErrorMessage(name, message));
        });
        
        if (!webauthn.supported()) {
            alert(@json(__('teacher/dashboard.biometric_not_supported')));
            return;
        }

        if (window.location.hostname !== 'localhost' && !window.isSecureContext) {
            alert(@json(__('teacher/dashboard.biometric_https_required')));
            return;
        }

        const preferPlatform = await platformAuthenticatorAvailable();

        button.disabled = true;
        button.classList.add('opacity-70', 'cursor-wait');

        fetch('{{ route("webauthn.register.options") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(res => {
            if (!res.ok) {
                return res.json().then(err => { throw err; });
            }
            return res.json();
        })
        .then(publicKey => {
            return webauthn.register(prepareWebAuthnCreationOptions(publicKey, preferPlatform), function (credentials) {
                fetch('{{ route("webauthn.register") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ name: @json(__('teacher/dashboard.biometric_name')), data: credentials })
                })
                .then(res => res.json())
                .then(response => {
                    if (response.success) {
                        alert(@json(__('teacher/dashboard.biometric_success')));
                    } else {
                        alert(response.error || response.message || @json(__('teacher/dashboard.biometric_failed')));
                    }
                    resetButton();
                })
                .catch(e => {
                    resetButton();
                    alert(@json(__('teacher/dashboard.biometric_verification_error')));
                });
            });
        })
        .catch(error => {
            resetButton();
            console.error(error);
            let msg = error.message || error.error || @json(__('teacher/dashboard.biometric_server_error'));
            alert(@json(__('teacher/dashboard.biometric_error_prefix')) + msg);
        });
    });
</script>
@endsection

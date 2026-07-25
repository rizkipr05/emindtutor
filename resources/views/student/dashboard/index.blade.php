@extends('layouts.student')

@section('content')
<div class="min-h-screen sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-6 sm:space-y-8">
        
        <!-- Header Section -->
        <div class="text-center mb-8 sm:mb-12">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold bg-gradient-to-r from-orange-600 to-amber-600 bg-clip-text text-transparent mb-3">
                {{ optional(optional($student->company)->user)->name ?? optional($student->user)->name ?? 'Student Dashboard' }}
            </h1>
            <div class="w-16 sm:w-20 lg:w-24 h-1 bg-gradient-to-r from-orange-500 to-amber-500 mx-auto rounded-full"></div>
        </div>

        @if($profileMissing ?? false)
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 sm:p-6 text-amber-800 shadow-sm">
                Profil student untuk akun ini belum lengkap. Hubungi admin untuk melengkapi data student agar kursus, jadwal, dan pembayaran muncul.
            </div>
        @endif

        <!-- Pengumuman Terbaru -->
        <div class="bg-white/80 backdrop-blur-sm border border-white/20 p-4 sm:p-6 lg:p-8 rounded-2xl sm:rounded-3xl shadow-lg hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-amber-100 rounded-xl">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Pengumuman Terbaru</h2>
                        <p class="text-sm text-gray-500">Maklumat terkini daripada guru anda.</p>
                    </div>
                </div>
                <a href="{{ route('broadcasts.index') }}" class="text-sm font-semibold text-amber-700 hover:text-amber-800">
                    Lihat semua
                </a>
            </div>

            @if($announcements->isEmpty())
                <div class="rounded-2xl border border-dashed border-gray-200 bg-gray-50 px-6 py-10 text-center text-gray-500">
                    Belum ada pengumuman untuk anda.
                </div>
            @else
                <div class="space-y-4">
                    @foreach($announcements as $announcement)
                        <a href="{{ route('broadcasts.show', $announcement->id) }}" class="block rounded-2xl border border-amber-100 bg-gradient-to-r from-amber-50 to-white p-4 transition hover:border-amber-200 hover:shadow-md">
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">{{ $announcement->title }}</h3>
                                    <p class="mt-1 text-sm text-gray-600 line-clamp-2">{{ $announcement->message }}</p>
                                    <div class="mt-3 flex flex-wrap items-center gap-2 text-xs">
                                        <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 font-medium text-amber-700">
                                            {{ $announcement->creator->name ?? 'Guru' }}
                                        </span>
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 font-medium text-gray-600">
                                            {{ \Carbon\Carbon::parse($announcement->created_at)->locale(app()->getLocale() === 'en' ? 'en' : 'ms')->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                                <svg class="mt-1 h-5 w-5 flex-shrink-0 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Maklumat Murid -->
        <div class="bg-white/80 backdrop-blur-sm border border-white/20 p-4 sm:p-6 lg:p-8 rounded-2xl sm:rounded-3xl shadow-lg hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-blue-100 rounded-xl">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ __('student/dashboard/dashboard.student_info') }}</h2>
            </div>
            
            <div class="overflow-hidden">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div class="bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition-colors duration-200">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="font-semibold text-gray-700 text-sm sm:text-base">{{ __('student/dashboard/dashboard.label_name') }}</span>
                            <span class="text-gray-900 font-medium break-words">{{ $student->user->name }}</span>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition-colors duration-200">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="font-semibold text-gray-700 text-sm sm:text-base">{{ __('student/dashboard/dashboard.label_email') }}</span>
                            <span class="text-gray-900 font-medium break-all">{{ $student->user->email }}</span>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition-colors duration-200">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="font-semibold text-gray-700 text-sm sm:text-base">{{ __('student/dashboard/dashboard.label_phone') }}</span>
                            <span class="text-gray-900 font-medium">{{ $student->phone ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition-colors duration-200">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="font-semibold text-gray-700 text-sm sm:text-base">{{ __('student/dashboard/dashboard.label_ic') }}</span>
                            <span class="text-gray-900 font-medium">{{ $student->ic_number ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Course Yang Diambil -->
        <div class="bg-white/80 backdrop-blur-sm border border-white/20 p-4 sm:p-6 lg:p-8 rounded-2xl sm:rounded-3xl shadow-lg hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-green-100 rounded-xl">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ __('student/dashboard/dashboard.courses_taken') }}</h2>
            </div>

            @if($courses && $courses->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    @foreach($courses as $course)
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 sm:p-6 hover:shadow-lg transition-all duration-300 border border-gray-200">
                            <div class="flex items-start justify-between mb-4">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                    </svg>
                                </div>
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                                    {{ __('student/dashboard/dashboard.paid') }}
                                </span>
                            </div>
                            
                            <h3 class="font-bold text-gray-900 mb-2 text-sm sm:text-base line-clamp-2">
                                {{ $course->nama_kos }}
                            </h3>
                            
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                                @if($course->sessions_duration)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $course->sessions_duration }} {{ __('student/dashboard/dashboard.sessions_duration_unit.' . $course->sessions_duration_unit) }}
                                    </span>
                                @endif
                                
                                @if($course->jenis)
                                    <span class="px-2 py-1 bg-gray-200 text-gray-700 text-xs rounded-full">
                                        {{ __('student/dashboard/dashboard.jenis_course.' . $course->jenis) }}
                                    </span>
                                @endif
                            </div>
                            
                            <div class="flex items-center justify-between">
                                @if($course->fee)
                                    <span class="font-bold text-lg text-blue-600">
                                        RM {{ number_format($course->fee, 2) }}
                                    </span>
                                @endif
                                
                                <!-- Link to course detail or learning page -->
                                    <a href="#" 
                                   class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 font-medium text-sm transition-colors duration-200">
                                    {{ __('student/dashboard/dashboard.view_detail') }}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="p-4 bg-gray-100 rounded-full inline-block mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-600 mb-2">{{ __('student/dashboard/dashboard.no_courses') }}</h3>
                    <p class="text-gray-500 text-sm mb-4">{{ __('student/dashboard/dashboard.no_courses_desc') }}</p>
                    <!-- <a href="#" 
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        {{ __('student/dashboard/dashboard.explore_courses') }}
                    </a> -->
                </div>
            @endif
        </div>

        <!-- Tukar Kata Laluan -->
        <div class="bg-white/80 backdrop-blur-sm border border-white/20 p-4 sm:p-6 lg:p-8 rounded-2xl sm:rounded-3xl shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-red-100 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ __('student/dashboard/dashboard.change_password') }}</h2>
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
                        <p class="text-green-800 text-sm sm:text-base">{{ session('success') ?? __('student/dashboard.success_saved') }}</p>
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
                            {{ __('student/dashboard/dashboard.label_current_password') }}
                        </label>
                        <input type="password" name="current_password"
                            class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-xl p-3 sm:p-4 transition-all duration-200 text-sm sm:text-base @error('current_password') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror"
                            placeholder="{{ __('student/dashboard/dashboard.placeholder_current_password') }}"
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
                            {{ __('student/dashboard/dashboard.label_new_password') }}
                        </label>
                        <input type="password" name="new_password"
                            class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-xl p-3 sm:p-4 transition-all duration-200 text-sm sm:text-base @error('new_password') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror"
                            placeholder="{{ __('student/dashboard/dashboard.placeholder_new_password') }}"
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
                            {{ __('student/dashboard/dashboard.label_confirm_password') }}
                        </label>
                        <input type="password" name="new_password_confirmation"
                            class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-xl p-3 sm:p-4 transition-all duration-200 text-sm sm:text-base"
                            placeholder="{{ __('student/dashboard/dashboard.placeholder_confirm_password') }}"
                            required>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold px-6 py-3 sm:px-8 sm:py-4 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 text-sm sm:text-base">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        {{ __('student/dashboard/dashboard.update_password') }}
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection

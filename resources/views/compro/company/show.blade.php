@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800 py-12 px-6">
    <!-- Background decorative elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 -right-4 w-72 h-72 bg-orange-500/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 -left-8 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/3 w-64 h-64 bg-orange-400/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 max-w-6xl mx-auto">
        <!-- Company Header Section -->
        <div class="text-center mb-12" data-aos="fade-up">
            <!-- Company Avatar -->
            <div class="relative inline-block mb-6">
                <div class="w-24 h-24 rounded-3xl flex items-center justify-center bg-gradient-to-br from-blue-500 via-blue-600 to-orange-500 text-white text-4xl font-bold shadow-2xl">
                    <svg class="w-14 h-14 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <!-- Glow effect for avatar -->
                <div class="absolute inset-0 rounded-3xl bg-gradient-to-br from-blue-400 to-orange-400 blur-lg opacity-50 -z-10"></div>
            </div>

            <!-- Company Title -->
            <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-blue-400 via-blue-300 to-orange-400 bg-clip-text text-transparent mb-4">
                {{ $company->user->name }}
            </h1>
            
            <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-orange-500 mx-auto mb-6 rounded-full"></div>

            <!-- Company Info Cards -->
            <div class="grid md:grid-cols-3 gap-4 max-w-4xl mx-auto mb-8">
                <!-- Address Card -->
                <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-2xl p-4">
                    <div class="flex items-center justify-center space-x-3">
                        <div class="w-8 h-8 text-orange-400">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">{{ __('compro/company.card_info.address') }}</p>
                            <p class="text-sm text-slate-300">{{ $company->alamat }}</p>
                        </div>
                    </div>
                </div>

                <!-- Phone Card -->
                <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-2xl p-4">
                    <div class="flex items-center justify-center space-x-3">
                        <div class="w-8 h-8 text-blue-400">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">{{ __('compro/company.card_info.phone') }}</p>
                            <a href="tel:{{ $company->no_telefon }}" 
                               class="text-sm text-slate-300 hover:text-blue-300 transition-colors">
                                {{ $company->no_telefon }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Website Card -->
                @if($company->link)
                <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-2xl p-4">
                    <div class="flex items-center justify-center space-x-3">
                        <div class="w-8 h-8 text-orange-400">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.56-.5-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.56.5.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.498-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">{{ __('compro/company.card_info.website') }}</p>
                            <a href="{{ $company->link }}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="text-sm text-slate-300 hover:text-orange-300 transition-colors inline-flex items-center space-x-1">
                                <span class="truncate max-w-[120px]">
                                    {{ parse_url($company->link, PHP_URL_HOST) ?: $company->link }}
                                </span>
                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-slate-800/30 backdrop-blur-sm border border-slate-700/30 rounded-2xl p-4 opacity-50">
                    <div class="flex items-center justify-center space-x-3">
                        <div class="w-8 h-8 text-slate-500">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.56-.5-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.56.5.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.498-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">{{ __('compro/company.card_info.website') }}</p>
                            <p class="text-sm text-slate-500">{{ __('compro/company.website_not_available') }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Courses Section Title -->
            <div class="flex items-center justify-center space-x-4 mb-8">
                <div class="h-px bg-gradient-to-r from-transparent via-slate-600 to-transparent flex-1"></div>
                <h2 class="text-2xl font-bold text-slate-300">{{ __('compro/company.course_title') }}</h2>
                <div class="h-px bg-gradient-to-r from-transparent via-slate-600 to-transparent flex-1"></div>
            </div>
        </div>

        <!-- Courses Grid -->
        @if($courses->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" data-aos="fade-up" data-aos-delay="200">
                @foreach($courses as $course)
                <div class="group relative" data-aos="fade-up" data-aos-delay="{{ 300 + ($loop->index * 100) }}">
                    <!-- Glow effect -->
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-600 to-orange-600 rounded-3xl blur opacity-20 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                    
                    <!-- Course Card -->
                    <div class="relative bg-gradient-to-br from-slate-800 to-slate-900 border border-slate-700/50 shadow-2xl rounded-3xl backdrop-blur-sm hover:shadow-orange-500/10 hover:shadow-2xl transition-all duration-500 transform group-hover:-translate-y-2 overflow-hidden">
                        <!-- Header -->
                        <div class="p-6 border-b border-slate-700/50 bg-gradient-to-r from-slate-800/50 to-slate-700/30">
                            <h3 class="text-xl font-bold text-white group-hover:text-blue-300 transition-colors duration-300 mb-3">
                                {{ $course->nama_kos }}
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-gradient-to-r from-blue-500/20 to-blue-600/20 text-blue-300 border border-blue-500/30">
                                    {{ __('compro/company.course_category.' . $course->jenis) }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-gradient-to-r from-emerald-500/20 to-emerald-600/20 text-emerald-300 border border-emerald-500/30">
                                    {{ __('compro/company.course_type.' . $course->teaching_mode) }}
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6 space-y-4">
                            <!-- Fee -->
                            <div class="flex items-center space-x-3 p-3 bg-gradient-to-r from-indigo-500/10 to-purple-500/10 rounded-xl border border-indigo-500/20">
                                <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-lg flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 24 24" stroke="none">
                                        <path d="M12 2C6.49 2 2 6.49 2 12s4.49 10 10 10 10-4.49 10-10S17.51 2 12 2m0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8"></path><path d="M12 11c-2 0-2-.63-2-1 0-.48.7-1 2-1 1.18 0 1.39.64 1.4 1.02l1-.02h1c0-1.03-.67-2.47-2.4-2.88V6h-2v1.09C9.03 7.42 8 8.72 8 10c0 1.12.52 3 4 3 2 0 2 .68 2 1 0 .42-.62 1-2 1-1.84 0-1.99-.86-2-1H8c0 .92.66 2.55 3 2.92V18h2v-1.09c1.97-.33 3-1.63 3-2.91 0-1.12-.52-3-4-3"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">{{ __('compro/company.course_fee') }}</p>
                                    <p class="text-lg font-bold text-white">
                                        {{ __('compro/company.currency') }} {{ number_format($course->fee, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Details Grid -->
                            <div class="grid grid-cols-1 gap-3">
                                <!-- Minimum Sessions -->
                                @if($course->minimum_sessions)
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0 w-6 h-6 text-green-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m-6-8h6m-9 8V6a2 2 0 012-2h6a2 2 0 012 2v12" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-green-300 font-medium">{{ __('compro/company.min_sessions') }}</p>
                                        <p class="text-sm text-slate-300">
                                            {{ __('compro/company.minimal_sessions', [
                                                'count' => $course->minimum_sessions,
                                                'unit' => __('compro/company.session_unit.' . $course->minimum_sessions_unit)
                                            ]) }}
                                        </p>
                                    </div>
                                </div>
                                @endif

                                <!-- Duration -->
                                @if($course->sessions_duration)
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0 w-6 h-6 text-yellow-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-yellow-300 font-medium">Duration</p>
                                        <p class="text-sm text-slate-300">
                                            {{ __('compro/company.duration', [
                                                'count' => $course->sessions_duration,
                                                'unit' => __('compro/company.duration_unit.' . $course->sessions_duration_unit)
                                            ]) }}
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- Description -->
                            @if($course->description)
                            <div class="p-4 bg-slate-800/50 rounded-xl border border-slate-700/30">
                                <p class="text-slate-300 text-sm leading-relaxed line-clamp-4">
                                    {!! nl2br(e($course->description)) !!}
                                </p>
                            </div>
                            @endif
                        </div>

                        <!-- Footer -->
                        <div class="p-6 border-t border-slate-700/50 bg-gradient-to-r from-slate-800/30 to-slate-700/10">
                            <a href="{{ $course->registration_link ?? '#' }}" 
                               target="_blank"
                               class="inline-flex items-center justify-center w-full space-x-2 px-4 py-3 bg-gradient-to-r from-blue-500 to-orange-500 hover:from-blue-600 hover:to-orange-600 text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                <span>{{ __('compro/company.register_now') }}</span>
                                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16" data-aos="fade-up" data-aos-delay="300">
                <div class="w-32 h-32 mx-auto mb-8 bg-gradient-to-br from-slate-700/50 to-slate-800/50 rounded-full flex items-center justify-center border border-slate-600/30">
                    <svg class="w-16 h-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-300 mb-4">{{ __('compro/company.empty_state.title') }}</h3>
                <p class="text-slate-400 max-w-md mx-auto">
                    {{ __('compro/company.empty_state.desc') }}
                </p>
            </div>
        @endif
    </div>
</div>

<script>
AOS.init({
    duration: 1000,
    once: true,
    easing: 'ease-out-cubic',
});
</script>

<style>
/* Line clamp utility */
.line-clamp-4 {
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Enhanced backdrop blur */
.backdrop-blur-sm {
    backdrop-filter: blur(12px);
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #1e293b;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #3b82f6, #f97316);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(to bottom, #2563eb, #ea580c);
}

/* Floating animation */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-5px); }
}

.group:hover .animate-float {
    animation: float 3s ease-in-out infinite;
}
</style>
@endsection
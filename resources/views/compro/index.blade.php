@extends('layouts.app')

@section('content')
    @php
        $basePath = rtrim(request()->getBaseUrl(), '/');
        $signupUrl = ($basePath ?: '') . '/daftar';
    @endphp

    {{-- Hero Section --}}
    <section class="text-center py-24 px-4 relative overflow-hidden">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-6" data-aos="fade-up">
                    {{ __('compro/home.hero_title') }}
        </h1>
        <p class="text-sm md:text-base text-gray-300 max-w-xl mx-auto mb-10" data-aos="fade-up" data-aos-delay="200">
                    {{ __('compro/home.hero_subtext') }}
        </p>

        <div class="flex justify-center space-x-4" data-aos="zoom-in" data-aos-delay="400">
            <a href="{{ route('compro.about.index') }}" class="bg-white text-black font-semibold px-6 py-3 rounded-full hover:bg-gray-200 transition">
                    {{ __('compro/home.hero_cta_explore') }}
            </a>
            <a href="{{ $signupUrl }}" class="bg-teal-400 text-white font-semibold px-6 py-3 rounded-full hover:bg-teal-500 transition">
                    {{ __('compro/home.hero_cta_signup') }}
            </a>
        </div>

        {{-- Background decoration --}}
        <div class="absolute bottom-0 right-0 w-1/2 opacity-10 pointer-events-none">
            <svg viewBox="0 0 600 600" fill="none">
                <circle cx="300" cy="300" r="300" fill="#00C2CB"/>
            </svg>
        </div>
    </section>

    {{-- Showcase Section --}}
    <section class="py-20 relative overflow-hidden">
        <!-- Background decorative elements -->
        <div class="absolute inset-0 opacity-30">
            <div class="absolute top-20 right-10 w-40 h-40 bg-gradient-to-r from-cyan-400 to-blue-500 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 left-10 w-32 h-32 bg-gradient-to-r from-orange-400 to-red-500 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            
            {{-- Section Header --}}
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-white mb-4">
                    {{ __('compro/home.showcase_title') }}
                </h2>
                <p class="text-lg text-white max-w-2xl mx-auto leading-relaxed">
                    {{ __('compro/home.showcase_subtext') }}
                </p>
            </div>

            {{-- Main Gallery Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
                
                {{-- Featured Large Image --}}
                <div class="lg:col-span-2">
                    <div class="group relative bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-200/50 hover:shadow-2xl transition-all duration-500">
                        <div class="aspect-[16/10] bg-gradient-to-br from-cyan-100 to-blue-100 relative overflow-hidden">
                            {{-- Placeholder content for dashboard view --}}
                            <!-- <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-full h-full bg-gradient-to-br from-slate-800 to-slate-600 flex items-center justify-center">
                                    <div class="text-center text-white">
                                        <div class="w-16 h-16 bg-gradient-to-r from-cyan-400 to-orange-500 rounded-full mx-auto mb-4 flex items-center justify-center">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                        </div>
                                        <h3 class="font-semibold text-lg">Dashboard Utama</h3>
                                        <p class="text-sm opacity-80">Interface pembelajaran yang intuitif</p>
                                    </div>
                                </div>
                            </div> -->
                            <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center">
                                    <div class="w-12 h-12 bg-gradient-to-br from-slate-800 to-slate-600 rounded-full mx-auto mb-3 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" stroke="none" viewBox="0 0 24 24">
                                        <path d="M11 6h2v2h-2zM15 6h2v2h-2zM15 10.03h2V12h-2zM15 14h2v2h-2zM7 15h2v2H7z"></path><path d="M3 16v5c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-1.1-.9-2-2-2H9c-1.1 0-2 .9-2 2v5.59L2.29 14.3c-.29.29-.37.72-.22 1.09s.52.62.92.62Zm8 4H5v-5.59l3-3 3 3zm8-16v16h-6v-4a1.002 1.002 0 0 0 .7-1.71L8.99 9.58V3.99h10Z"></path>
                                        </svg>
                                    </div>
                                    <h4 class="font-medium text-slate-700">{{ __('compro/home.showcase_feature_dashboard_title') }}</h4>
                                </div>
                            </div>
                            {{-- Replace with actual image: --}}
                            <img src="{{ asset('images/dashboard-utama.png') }}" alt="{{ __('compro/home.showcase_feature_dashboard_alt') }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="p-6 bg-white">
                            <h3 class="font-semibold text-lg text-slate-800 mb-2">{{ __('compro/home.showcase_feature_dashboard_title') }}</h3>
                            <p class="text-slate-600 text-sm">{{ __('compro/home.showcase_feature_dashboard_desc') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Side Images --}}
                <div class="space-y-8">
                    {{-- Interactive Lessons --}}
                    <div class="group bg-white rounded-2xl shadow-lg overflow-hidden border border-slate-200/50 hover:shadow-xl transition-all duration-300">
                        <div class="aspect-[4/3] bg-gradient-to-br from-orange-100 to-red-100 relative overflow-hidden">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-500 rounded-full mx-auto mb-3 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                    </div>
                                    <h4 class="font-medium text-slate-700">{{ __('compro/home.showcase_feature_payroll_title') }}</h4>
                                </div>
                            </div>
                            <img src="{{ asset('images/gaji.png') }}" alt="{{ __('compro/home.showcase_feature_payroll_alt') }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="p-4">
                            <h4 class="font-medium text-slate-800 mb-1">{{ __('compro/home.showcase_feature_payroll_title') }}</h4>
                            <p class="text-slate-600 text-xs">{{ __('compro/home.showcase_feature_payroll_desc') }}</p>
                        </div>
                    </div>

                    {{-- Progress Tracking --}}
                    <div class="group bg-white rounded-2xl shadow-lg overflow-hidden border border-slate-200/50 hover:shadow-xl transition-all duration-300">
                        <div class="aspect-[4/3] bg-gradient-to-br from-cyan-100 to-teal-100 relative overflow-hidden">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-gradient-to-r from-cyan-500 to-teal-500 rounded-full mx-auto mb-3 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                    </div>
                                    <h4 class="font-medium text-slate-700">{{ __('compro/home.showcase_feature_progress_title') }}</h4>
                                </div>
                            </div>
                            <img src="{{ asset('images/progress.png') }}" alt="{{ __('compro/home.showcase_feature_progress_alt') }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="p-4">
                            <h4 class="font-medium text-slate-800 mb-1">{{ __('compro/home.showcase_feature_progress_cta') }}</h4>
                            <p class="text-slate-600 text-xs">{{ __('compro/home.showcase_feature_progress_desc') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bottom Row - Three Equal Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- Mobile App --}}
                <div class="group bg-white rounded-xl shadow-lg overflow-hidden border border-slate-200/50 hover:shadow-xl transition-all duration-300">
                    <div class="aspect-[16/10] bg-gradient-to-br from-purple-100 to-pink-100 relative overflow-hidden">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg mx-auto mb-2 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" stroke="none" viewBox="0 0 24 24">
                                    <path d="m19,4h-2v-2h-2v2h-6v-2h-2v2h-2c-1.1,0-2,.9-2,2v14c0,1.1.9,2,2,2h14c1.1,0,2-.9,2-2V6c0-1.1-.9-2-2-2ZM5,20v-12h14v-2,14s-14,0-14,0Z"></path><path d="M7 11H9V13H7z"></path><path d="M11 11H13V13H11z"></path><path d="M15 11H17V13H15z"></path><path d="M7 15H9V17H7z"></path><path d="M11 15H13V17H11z"></path><path d="M15 15H17V17H15z"></path>
                                    </svg>
                                </div>
                                <h4 class="font-medium text-slate-700 text-sm">{{ __('compro/home.card_schedule_label') }}</h4>
                            </div>
                        </div>
                        <img src="{{ asset('images/jadual.png') }}" alt="{{ __('compro/home.card_schedule_alt') }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="p-4">
                        <h4 class="font-medium text-slate-800 mb-1">{{ __('compro/home.card_schedule_title') }}</h4>
                        <p class="text-slate-600 text-xs">{{ __('compro/home.card_schedule_desc') }}</p>
                    </div>
                </div>

                {{-- Assessment Tools --}}
                <div class="group bg-white rounded-xl shadow-lg overflow-hidden border border-slate-200/50 hover:shadow-xl transition-all duration-300">
                    <div class="aspect-[16/10] bg-gradient-to-br from-green-100 to-emerald-100 relative overflow-hidden">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg mx-auto mb-2 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h4 class="font-medium text-slate-700 text-sm">{{ __('compro/home.dashboard_student_title') }}</h4>
                            </div>
                        </div>
                        <img src="{{ asset('images/student.png') }}" alt="Pelacakan Progress" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="p-4">
                        <h4 class="font-medium text-slate-800 mb-1">{{ __('compro/home.dashboard_student_title') }}</h4>
                        <p class="text-slate-600 text-xs">{{ __('compro/home.dashboard_student_desc') }}</p>
                    </div>
                </div>

                {{-- Community Features --}}
                <div class="group bg-white rounded-xl shadow-lg overflow-hidden border border-slate-200/50 hover:shadow-xl transition-all duration-300">
                    <div class="aspect-[16/10] bg-gradient-to-br from-blue-100 to-indigo-100 relative overflow-hidden">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg mx-auto mb-2 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <h4 class="font-medium text-slate-700 text-sm">{{ __('compro/home.dashboard_teacher_title') }}</h4>
                            </div>
                        </div>
                        <img src="{{ asset('images/teacher.png') }}" alt="Pelacakan Progress" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="p-4">
                        <h4 class="font-medium text-slate-800 mb-1">{{ __('compro/home.dashboard_teacher_title') }}</h4>
                        <p class="text-slate-600 text-xs">{{ __('compro/home.dashboard_teacher_desc') }}</p>
                    </div>
                </div>
            </div>

            {{-- CTA Button --}}
            <div class="text-center mt-12">
                    <a href="{{ $signupUrl }}" 
                class="inline-flex items-center bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-8 py-4 rounded-full font-semibold transition-all duration-200 shadow-lg hover:shadow-orange-500/25 hover:scale-105 transform group">
                    <span>{{ __('compro/home.cta_join_now') }}</span>
                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>

        </div>
    </section>


    {{-- Features Section --}}
    <!-- <section class="py-20 px-6 bg-gray-900 text-white">
        <div class="max-w-6xl mx-auto grid md:grid-cols-3 gap-10 text-center">
            <div class="p-6 rounded-xl bg-gray-800 shadow-lg" data-aos="fade-up" data-aos-delay="100">
                <h3 class="text-xl font-bold mb-4">{{ __('compro/home.feature_1_title') }}</h3>
                <p class="text-gray-400">{{ __('compro/home.feature_1_desc') }}</p>
            </div>
            <div class="p-6 rounded-xl bg-gray-800 shadow-lg" data-aos="fade-up" data-aos-delay="200">
                <h3 class="text-xl font-bold mb-4">{{ __('compro/home.feature_2_title') }}</h3>
                <p class="text-gray-400">{{ __('compro/home.feature_2_desc') }}</p>
            </div>
            <div class="p-6 rounded-xl bg-gray-800 shadow-lg" data-aos="fade-up" data-aos-delay="300">
                <h3 class="text-xl font-bold mb-4">{{ __('compro/home.feature_3_title') }}</h3>
                <p class="text-gray-400">{{ __('compro/home.feature_3_desc') }}</p>
            </div>
        </div>
    </section>

    <section class="py-16 px-4 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            

            <div class="text-center">
                <div class="w-20 h-20 bg-gray-800 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-4">{{ __('compro/home.benefit_virtual_title') }}</h3>
                <p class="text-gray-300 text-sm leading-relaxed">{{ __('compro/home.benefit_virtual_desc') }}</p>
            </div>

            <div class="text-center">
                <div class="w-20 h-20 bg-gray-800 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-teal-400" fill="currentColor" stroke="none" viewBox="0 0 24 24">
                        <path d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5m0-8c1.65 0 3 1.35 3 3s-1.35 3-3 3-3-1.35-3-3 1.35-3 3-3M4 22h16c.55 0 1-.45 1-1v-1c0-3.86-3.14-7-7-7h-4c-3.86 0-7 3.14-7 7v1c0 .55.45 1 1 1m6-7h4c2.76 0 5 2.24 5 5H5c0-2.76 2.24-5 5-5"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-4">{{ __('compro/home.benefit_experienced_title') }}</h3>
                <p class="text-gray-300 text-sm leading-relaxed">{{ __('compro/home.benefit_experienced_desc', ['count' => $totalTeachers]) }}</p>
            </div>

            <div class="text-center">
                <div class="w-20 h-20 bg-gray-800 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-teal-400" fill="currentColor" stroke="none" viewBox="0 0 24 24">
                        <path class="b" d="m5,2h-3v2h2v16c0,1.1.9,2,2,2h12c1.1,0,2-.9,2-2V4h2v-2H5Zm13,18H6V4h12v16Z"></path><path class="b" d="m12,15c-1.84,0-1.99-.86-2-1h-2c0,.92.66,2.55,3,2.92v1.08h2v-1.09c1.97-.33,3-1.63,3-2.91,0-1.12-.52-3-4-3-2,0-2-.63-2-1,0-.48.7-1,2-1,1.18,0,1.39.64,1.4,1.02l1-.02h1c0-1.03-.67-2.47-2.4-2.88v-1.12h-2v1.09c-1.97.33-3,1.63-3,2.91,0,1.12.52,3,4,3,2,0,2,.68,2,1,0,.42-.62,1-2,1Z"></path>
                    </svg>  
                </div>
                <h3 class="text-xl font-semibold text-white mb-4">{{ __('compro/home.benefit_certificate_title') }}</h3>
                <p class="text-gray-300 text-sm leading-relaxed">{{ __('compro/home.benefit_certificate_desc') }}</p>
            </div>

   
            <div class="text-center">
                <div class="w-20 h-20 bg-gray-800 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-4">{{ __('compro/home.benefit_safe_title') }}</h3>
                <p class="text-gray-300 text-sm leading-relaxed">{{ __('compro/home.benefit_safe_desc') }}</p>
            </div>

            <div class="text-center">
                <div class="w-20 h-20 bg-gray-800 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-4">{{ __('compro/home.benefit_flexible_title') }}</h3>
                <p class="text-gray-300 text-sm leading-relaxed">{{ __('compro/home.benefit_flexible_desc') }}</p>
            </div>

            <div class="text-center">
                <div class="w-20 h-20 bg-gray-800 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-4">{{ __('compro/home.benefit_companies_title') }}</h3>
                <p class="text-gray-300 text-sm leading-relaxed">{{ __('compro/home.benefit_companies_desc', ['count' => $totalCompanies]) }}</p>
            </div>

        </div>
    </section> -->

{{-- Pricing Section --}}
    <section class="py-20 px-6 bg-gray-900">
        <div class="grid md:grid-cols-3 gap-6 lg:gap-8 mb-12">
    @foreach($filteredPackages->take(3) as $index => $package)
        @php
            $displayPrice = $package->pricing_per_student ? $package->harga_per_pelajar : $package->harga;
        @endphp
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 border-2 border-gray-700 hover:border-transparent hover:shadow-2xl hover:shadow-teal-500/20 transition-all duration-300 rounded-2xl p-8 relative group transform hover:-translate-y-2" 
             data-aos="fade-up" 
             data-aos-delay="{{ ($index + 1) * 100 }}">
            
            <!-- Decorative gradient overlay on hover -->
            <div class="absolute inset-0 bg-gradient-to-br from-teal-500/10 to-cyan-500/10 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            
            <div class="text-center relative z-10">
                <h3 class="text-xl font-bold text-white mb-3 group-hover:text-teal-400 transition-colors duration-300">
                    {{ $package->nama_pakej }}
                </h3>
                
                @if($package->pricing_per_student)
                    <span class="inline-flex items-center mb-4 text-xs bg-gradient-to-r from-emerald-500 to-green-500 text-white px-3 py-1.5 rounded-full font-semibold shadow-lg">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Per Student
                    </span>
                @endif
                
                <div class="my-6">
                    <div class="text-4xl lg:text-5xl font-extrabold bg-gradient-to-r from-teal-400 to-cyan-400 bg-clip-text text-transparent mb-2">
                        RM{{ number_format($displayPrice, 0) }}
                    </div>
                    <p class="text-gray-400 text-sm font-medium">
                        /{{ $package->duration_days }} {{ __('compro/pricing.price_duration.daily') }}
                    </p>
                </div>
                
                <a href="{{ route('subscribe.start', $package->id) }}" 
                   class="inline-flex items-center justify-center w-full bg-gradient-to-r from-gray-700 to-gray-800 text-white hover:from-teal-500 hover:to-cyan-500 py-3 px-6 rounded-xl transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 group/btn">
                    <span>{{ __('compro/home.package_choose_button') }}</span>
                    <svg class="w-4 h-4 ml-2 group-hover/btn:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
            </div>
        </div>
    @endforeach
</div>
    </section>

    {{-- Reviews Section --}}
    <!-- <section class="py-20 px-6">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4" data-aos="fade-up">
                    {{ __('compro/home.reviews_title') }}
                </h2>
                <p class="text-gray-400 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                    {{ __('compro/home.reviews_subtext') }}
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($latestReviews as $index => $review)
                    <div class="bg-gray-800 rounded-2xl p-6 border border-gray-700 hover:border-teal-500 transition-all duration-300" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                        {{-- Rating Stars --}}
                        <div class="flex items-center mb-4">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @endfor
                        </div>

                        {{-- Review Content --}}
                        <blockquote class="text-gray-300 mb-6 leading-relaxed">
                            "{{ $review->comment }}"
                        </blockquote>

                        {{-- Student Name --}}
                        <div class="text-right">
                            <h4 class="text-teal-400 font-semibold">
                                - {{ $review->student->user->name ?? 'Anonymous' }}
                            </h4>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div class="w-20 h-20 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.959 8.959 0 01-4.906-1.478L3 21l2.478-5.094A8.959 8.959 0 013 12c0-4.418 3.582-8 8-8s8 3.582 8 8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-white text-lg font-semibold mb-2">{{ __('compro/home.reviews_empty_title') }}</h3>
                        <p class="text-gray-400">{{ __('compro/home.reviews_empty_desc') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section> -->

{{-- Companies Section --}}
    <!-- <section class="py-20 px-6 bg-gray-900">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4" data-aos="fade-up">
                    {{ __('compro/home.companies_title') }}
                </h2>
                <p class="text-gray-400 max-w-2xl mx-auto mb-6" data-aos="fade-up" data-aos-delay="200">
                    {{ __('compro/home.companies_subtext', ['count' => $totalCompanies]) }}
                </p>
                <div class="bg-gradient-to-r from-teal-600 to-green-600 rounded-2xl p-6 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center justify-center space-x-3 mb-3">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                        </svg>
                        <h3 class="text-2xl font-bold text-white">{{ __('compro/home.companies_cta_heading') }}</h3>
                    </div>
                    <p class="text-white text-lg mb-2">
                        {!! __('compro/home.companies_contact_via_whatsapp') !!}
                    </p>
                    <p class="text-green-100 text-base">
                        {{ __('compro/home.companies_contact_note') }}
                    </p>
                </div>
            </div>

            {{-- Companies Grid --}}
            @if($activeCompanies && $activeCompanies->count() > 0)
                <div class="grid md:grid-cols-2 gap-8 mb-12">
                    @foreach($activeCompanies as $index => $company)
                        <div class="bg-gray-800 rounded-2xl p-8 border-2 border-gray-700 hover:border-teal-500 transition-all duration-300 shadow-lg hover:shadow-xl" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                            {{-- Company Header --}}
                            <div class="flex items-center space-x-4 mb-6">
                                <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-white mb-1">
                                        {{ $company->user->name }}
                                    </h3>
                                    <span class="text-teal-400 text-sm font-semibold bg-teal-900 px-3 py-1 rounded-full">
                                        {{ __('compro/home.partner') }}
                                    </span>
                                </div>
                            </div>
                            
                            {{-- Company Details --}}
                            <div class="space-y-4 mb-6">
                                @if($company->alamat)
                                    <div class="flex items-start space-x-3">
                                        <div class="w-6 h-6 bg-gray-700 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <svg class="w-4 h-4 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-gray-300 leading-relaxed">
                                            {{ $company->alamat }}
                                        </p>
                                    </div>
                                @endif
                                
                                @if($company->no_telefon)
                                    <div class="flex items-center space-x-3">
                                        <div class="w-6 h-6 bg-gray-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                        </div>
                                        <span class="text-gray-300 text-lg font-medium">{{ $company->no_telefon }}</span>
                                    </div>
                                @endif

                                @if($company->link)
                                    <div class="flex items-center space-x-3">
                                        <div class="w-6 h-6 bg-gray-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                            </svg>
                                        </div>
                                        <a href="{{ $company->link }}" 
                                           target="_blank" 
                                           rel="noopener noreferrer"
                                           class="text-gray-300 hover:text-teal-400 transition-colors duration-300 flex items-center space-x-2 group">
                                            <span class="break-all">{{ $company->link }}</span>
                                            <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                            </div>

                            {{-- WhatsApp CTA Button --}}
                            @if($company->no_telefon)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $company->no_telefon) }}?text={{ urlencode(__('compro/home.whatsapp_message', ['company' => $company->user->name])) }}" 
                                   target="_blank"
                                   class="w-full bg-gradient-to-r from-green-600 to-green-500 hover:from-green-500 hover:to-green-400 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 flex items-center justify-center space-x-3 shadow-lg hover:shadow-xl transform hover:-translate-y-1 group">
                                    <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                    </svg>
                                    <span class="text-xl">{{ __('compro/home.companies_chat_whatsapp') }}</span>
                                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Show More Button --}}
                <div class="text-center" data-aos="fade-up" data-aos-delay="400">
                    <p class="text-gray-400 text-lg mb-6">
                        {{ __('compro/home.companies_more_note') }}
                    </p>
                    <a href="{{ route('compro.companies.index') }}" class="inline-flex items-center bg-transparent border-2 border-teal-400 text-teal-400 font-semibold px-8 py-4 rounded-full hover:bg-teal-400 hover:text-white transition-all text-lg">
                        <span>{{ __('compro/home.companies_cta') }}</span>
                        <svg class="w-6 h-6 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-white text-lg font-semibold mb-2">{{ __('compro/home.companies_empty_title') }}</h3>
                    <p class="text-gray-400">{{ __('compro/home.companies_empty_desc') }}</p>
                </div>
            @endif
        </div>
    </section> -->

    {{-- Call to Action Section --}}
    <section class="py-20 text-center px-6 relative">
        <h2 class="text-3xl md:text-4xl font-extrabold mb-6" data-aos="fade-up">
                {{ __('compro/home.cta_title') }}
        </h2>
        <p class="text-gray-400 max-w-2xl mx-auto mb-8" data-aos="fade-up" data-aos-delay="200">
                {{ __('compro/home.cta_text') }}
        </p>
        <a href="{{ route('compro.pricing.index') }}" class="bg-teal-400 text-white font-semibold px-8 py-4 rounded-full hover:bg-teal-500 transition"
           data-aos="zoom-in" data-aos-delay="400">
                {{ __('compro/home.cta_button') }}
        </a>
    </section>

@endsection

@push('scripts')
    {{-- AOS Animation --}}
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true, // animasi hanya muncul sekali
        });
    </script>
@endpush

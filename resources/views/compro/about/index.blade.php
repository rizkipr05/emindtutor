@extends('layouts.app')

@section('content')
<div class="mt-5">
    <section class="relative bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800 py-16 px-6 md:px-20 min-h-screen overflow-hidden">
        <!-- Background decorative elements -->
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-20 left-10 w-64 h-64 bg-orange-500/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-32 right-16 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl animate-pulse delay-1000"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-blue-600/5 rounded-full blur-3xl"></div>
        </div>

        <!-- Floating geometric shapes -->
        <div class="absolute inset-0 pointer-events-none opacity-10">
            <div class="absolute top-1/4 left-1/4 w-4 h-4 bg-orange-400 rounded-full animate-float"></div>
            <div class="absolute top-3/4 right-1/4 w-6 h-6 bg-blue-400 rounded-full animate-float-delay"></div>
            <div class="absolute top-1/2 right-1/3 w-3 h-3 bg-orange-300 rounded-full animate-float-slow"></div>
        </div>

        <div class="relative z-10 max-w-6xl mx-auto text-center">
            <!-- Judul -->
            <div data-aos="fade-up">
                <div class="inline-block p-1 bg-gradient-to-r from-blue-500 to-orange-500 rounded-full mb-8">
                    <div class="bg-slate-900 rounded-full px-6 py-2">
                        <span class="text-blue-300 text-sm font-medium">About Us</span>
                    </div>
                </div>
                <h2 class="text-4xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-orange-400 mb-8 leading-tight">
                    {!! str_replace('eMindTutor', '<span class="bg-gradient-to-r from-orange-400 to-orange-500 bg-clip-text text-transparent">eMindTutor</span>', __('compro/about.page_title')) !!}
                </h2>
                <div class="w-32 h-1 bg-gradient-to-r from-blue-500 to-orange-500 mx-auto mb-8 rounded-full"></div>
                <p class="text-xl text-slate-300 leading-relaxed max-w-4xl mx-auto">
                    <span class="font-bold text-orange-300">{!! __('compro/about.intro_tagline') !!}</span>
                    <span class="text-slate-400">{!! __('compro/about.intro_text') !!}</span>
                </p>
            </div>
        </div>
    
        <!-- Nilai Utama -->
        <div class="relative z-10 mt-20" data-aos="fade-up" data-aos-delay="200">
            <div class="text-center mb-16">
                <h3 class="text-3xl md:text-4xl font-black bg-gradient-to-r from-blue-300 to-orange-400 bg-clip-text text-transparent mb-4">
                    {{ __('compro/about.values_title') }}
                </h3>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-orange-500 mx-auto rounded-full"></div>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8 lg:gap-12">
                <div class="group relative" data-aos="fade-up" data-aos-delay="300">
                    <!-- Glow effect -->
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-600 to-orange-600 rounded-3xl blur opacity-20 group-hover:opacity-75 transition duration-1000"></div>
                    
                    <!-- Card -->
                    <div class="relative bg-gradient-to-br from-slate-800/80 to-slate-900/80 backdrop-blur-xl border border-slate-700/50 p-8 rounded-3xl shadow-2xl hover:shadow-orange-500/10 hover:-translate-y-3 transition-all duration-500 transform group-hover:scale-105">
                        <div class="text-center">
                            <!-- Icon with gradient background -->
                            <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-xl group-hover:shadow-blue-500/25 transition-all duration-300">
                                <div class="text-4xl">⚡</div>
                            </div>
                            <h4 class="font-black text-2xl mb-4 text-white group-hover:text-blue-300 transition-colors duration-300">
                                {{ __('compro/about.value_1_title') }}
                            </h4>
                            <p class="text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors duration-300">
                                {{ __('compro/about.value_1_desc') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="group relative" data-aos="fade-up" data-aos-delay="400">
                    <!-- Enhanced glow for middle card -->
                    <div class="absolute -inset-1 bg-gradient-to-r from-orange-500 to-blue-500 rounded-3xl blur opacity-30 group-hover:opacity-100 transition duration-1000"></div>
                    
                    <!-- Featured middle card -->
                    <div class="relative bg-gradient-to-br from-orange-900/20 to-blue-900/20 backdrop-blur-xl border-2 border-orange-500/30 p-8 rounded-3xl shadow-2xl transform scale-105 hover:scale-110 hover:-translate-y-4 transition-all duration-500">
                        <div class="text-center">
                            <!-- Featured icon -->
                            <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-2xl group-hover:shadow-orange-500/50 transition-all duration-300">
                                <div class="text-5xl">🤝</div>
                            </div>
                            <h4 class="font-black text-2xl mb-4 bg-gradient-to-r from-orange-300 to-orange-400 bg-clip-text text-transparent">
                                {{ __('compro/about.value_2_title') }}
                            </h4>
                            <p class="text-slate-300 leading-relaxed font-medium">
                                {{ __('compro/about.value_2_desc') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="group relative" data-aos="fade-up" data-aos-delay="500">
                    <!-- Glow effect -->
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-600 to-blue-600 rounded-3xl blur opacity-20 group-hover:opacity-75 transition duration-1000"></div>
                    
                    <!-- Card -->
                    <div class="relative bg-gradient-to-br from-slate-800/80 to-slate-900/80 backdrop-blur-xl border border-slate-700/50 p-8 rounded-3xl shadow-2xl hover:shadow-purple-500/10 hover:-translate-y-3 transition-all duration-500 transform group-hover:scale-105">
                        <div class="text-center">
                            <!-- Icon with gradient background -->
                            <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-xl group-hover:shadow-purple-500/25 transition-all duration-300">
                                <div class="text-4xl">🚀</div>
                            </div>
                            <h4 class="font-black text-2xl mb-4 text-white group-hover:text-purple-300 transition-colors duration-300">
                                {{ __('compro/about.value_3_title') }}
                            </h4>
                            <p class="text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors duration-300">
                                {{ __('compro/about.value_3_desc') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Call to Action -->
        <div class="relative z-10 mt-24 text-center" data-aos="fade-up" data-aos-delay="600">
            <div class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-xl border border-slate-700/50 rounded-3xl p-12 max-w-4xl mx-auto shadow-2xl">
                <h3 class="text-3xl md:text-4xl font-black bg-gradient-to-r from-blue-300 to-orange-400 bg-clip-text text-transparent mb-6">
                    {{ __('compro/about.cta_title') }}
                </h3>
                <p class="text-xl text-slate-400 mb-8 leading-relaxed">
                    {{ __('compro/about.cta_text') }}
                </p>
                <a href="#contact" class="inline-flex items-center space-x-3 bg-gradient-to-r from-blue-500 to-orange-500 hover:from-blue-600 hover:to-orange-600 text-white font-bold px-10 py-4 rounded-2xl shadow-2xl transform hover:scale-105 transition-all duration-300">
                    <!-- <span class="text-lg">{{ __('compro/about.cta_button') }}</span> --> 
                     <!-- ganti jadi yg dibawah ini aja -->
                    <span class="text-lg">{{ __('compro/home.cta_join_now') }}</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>
</div>

<script>
AOS.init({
    duration: 1000,
    once: true,
    easing: 'ease-out-cubic',
});
</script>

<style>
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

@keyframes float-delay {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-15px); }
}

@keyframes float-slow {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

.animate-float-delay {
    animation: float-delay 8s ease-in-out infinite;
}

.animate-float-slow {
    animation: float-slow 10s ease-in-out infinite;
}
</style>
@endsection
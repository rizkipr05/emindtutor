@extends('layouts.app')

@section('content')
<div class="pt-4">
    <section id="pricing" class="py-20 px-6 md:px-20 bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800 min-h-screen relative overflow-hidden">
        <!-- Background decorative elements -->
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-10 left-10 w-40 h-40 bg-blue-500/20 rounded-full blur-2xl animate-pulse"></div>
            <div class="absolute bottom-20 right-20 w-60 h-60 bg-orange-500/20 rounded-full blur-3xl animate-pulse delay-1000"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-blue-600/10 rounded-full blur-3xl"></div>
        </div>

        <!-- Grid pattern overlay -->
        <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>

        <div class="relative z-10 max-w-7xl mx-auto">
            <div class="text-center mb-20" data-aos="fade-up">
                <div class="inline-block p-1 bg-gradient-to-r from-blue-500 to-orange-500 rounded-full mb-6">
                    <div class="bg-slate-900 rounded-full px-6 py-2">
                        <span class="text-blue-300 text-sm font-medium">{{ __('compro/pricing.sub_title') }}</span>
                    </div>
                </div>
                <h2 class="text-6xl font-black bg-gradient-to-r from-blue-300 to-orange-400 bg-clip-text p-3 text-transparent mb-6 tracking-tight">
                    {{ __('compro/pricing.page_title') }}
                </h2>
                <p class="text-xl text-slate-400 max-w-2xl mx-auto leading-relaxed">
                    {{ __('compro/pricing.page_subtext') }}
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 lg:gap-12">
                @foreach($packages as $key => $package)
                    <div class="group relative" data-aos="fade-up" data-aos-delay="{{ $key * 200 }}">
                        
                        {{-- Badge jika trial --}}
                        @if($package->is_trial)
                            <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 z-20">
                                <div class="bg-gradient-to-r from-emerald-500 to-green-400 text-white px-4 py-2 rounded-full text-xs font-bold shadow-xl border-2 border-white/20">
                                    {{ __('compro/pricing.trial_badge') }}
                                </div>
                            </div>
                        @endif

                        <!-- Card with unique styling per index -->
                        @if($key % 3 == 1)
                            <!-- Middle card - Featured -->
                            <div class="relative bg-gradient-to-br from-blue-900/90 to-orange-900/90 border-2 border-orange-500/50 rounded-3xl p-8 backdrop-blur-xl shadow-2xl transform scale-105 group-hover:scale-110 transition-all duration-500">
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-orange-600/20 rounded-3xl blur-xl opacity-75 group-hover:opacity-100 transition-opacity duration-500"></div>
                                
                                <div class="relative">
                                    <!-- Popular badge -->
                                    <div class="text-center mb-6">
                                        <span class="inline-block bg-gradient-to-r from-orange-500 to-orange-400 text-white text-xs font-bold px-3 py-1 rounded-full">
                                            {{ __('compro/home.package_popular_label') }}
                                        </span>
                                    </div>

                                    <div class="text-center mb-8">
                                        <h3 class="text-3xl font-black text-white mb-2 group-hover:text-orange-200 transition-colors duration-300">
                                            {{ $package->nama_pakej }}
                                        </h3>
                                    </div>

                                    {{-- Harga + durasi --}}
                                    <div class="text-center mb-10">
                                        <div class="relative">
                                            <span class="text-orange-300 text-xl">RM</span>
                                            <span class="text-5xl font-black text-white">{{ number_format($package->harga, 2) }}</span>
                                            <span class="text-orange-300 text-xl ml-1">
                                                @if($package->duration_days == 30)
                                                    {{ __('compro/pricing.price_duration.monthly') }}
                                                @elseif($package->duration_days == 365)
                                                    {{ __('compro/pricing.price_duration.yearly') }}
                                                @else
                                                    /{{ $package->duration_days }} {{ __('compro/pricing.price_duration.daily') }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Deskripsi --}}
                                    <div class="space-y-4 mb-10">
                                        @foreach(explode("\n", $package->deskripsi) as $desc)
                                            @if(!empty(trim($desc)))
                                                <div class="flex items-start space-x-4">
                                                    <div class="flex-shrink-0 w-6 h-6 bg-gradient-to-r from-orange-400 to-orange-500 rounded-full flex items-center justify-center mt-0.5">
                                                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </div>
                                                    <span class="text-slate-200 font-medium">{{ $desc }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>

                                    {{-- Tombol --}}
                                    <a href="{{ route('subscribe.start', $package->id) }}"
                                       class="block text-center w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-400 hover:to-orange-500 text-white font-black text-lg shadow-2xl transform hover:scale-105 transition-all duration-300 border-2 border-orange-400/30">
                                        {{ __('compro/pricing.subscribe_now') }}
                                    </a>
                                </div>
                            </div>
                        @else
                            <!-- Regular cards -->
                            <div class="relative bg-slate-800/70 border border-slate-700/50 rounded-3xl p-8 backdrop-blur-xl shadow-xl group-hover:shadow-2xl group-hover:-translate-y-2 transition-all duration-500">
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-600/10 to-slate-600/10 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                
                                <div class="relative">
                                    <div class="text-center mb-8">
                                        <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($key == 0)
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                @endif
                                            </svg>
                                        </div>
                                        <h3 class="text-2xl font-bold text-white group-hover:text-blue-300 transition-colors duration-300">
                                            {{ $package->nama_pakej }}
                                        </h3>
                                    </div>

                                    {{-- Harga + durasi --}}
                                    <div class="text-center mb-8">
                                        <div class="flex items-baseline justify-center">
                                            <span class="text-slate-400 text-lg">RM</span>
                                            <span class="text-4xl font-bold text-white mx-1">{{ number_format($package->harga, 2) }}</span>
                                            <span class="text-slate-400 text-lg">
                                                @if($package->duration_days == 30)
                                                    {{ __('compro/pricing.price_duration.monthly') }}
                                                @elseif($package->duration_days == 365)
                                                    {{ __('compro/pricing.price_duration.yearly') }}
                                                @else
                                                    /{{ $package->duration_days }} {{ __('compro/pricing.price_duration.daily') }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Deskripsi --}}
                                    <div class="space-y-3 mb-8">
                                        @foreach(explode("\n", $package->deskripsi) as $desc)
                                            @if(!empty(trim($desc)))
                                                <div class="flex items-start space-x-3">
                                                    <div class="flex-shrink-0 w-5 h-5 text-blue-400 mt-0.5">
                                                        <svg fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </div>
                                                    <span class="text-slate-300 text-sm">{{ $desc }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>

                                    {{-- Tombol --}}
                                    <a href="{{ route('subscribe.start', $package->id) }}"
                                       class="block text-center w-full py-3 px-6 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                                        {{ __('compro/pricing.subscribe_now') }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Bottom section -->
            <div class="text-center mt-16" data-aos="fade-up" data-aos-delay="600">
                <div class="inline-flex items-center space-x-2 text-slate-400 text-sm">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ __('compro/pricing.bottom_section.first') }}</span>
                    <span>•</span>
                    <span>{{ __('compro/pricing.bottom_section.second') }}</span>
                    <span>•</span>
                    <span>{{ __('compro/pricing.bottom_section.third') }}</span>
                </div>
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
.bg-grid-pattern {
    background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.1) 1px, transparent 0);
    background-size: 20px 20px;
}
</style>
@endsection
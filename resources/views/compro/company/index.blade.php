@extends('layouts.app')

@section('content')
<div class="pt-4">
    <section id="companies" class="py-20 px-6 md:px-20 bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800 min-h-screen">
        <!-- Background decorative elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-4 -right-4 w-72 h-72 bg-orange-500/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-8 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-1/4 right-1/3 w-64 h-64 bg-orange-400/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10">
            <!-- Header Section -->
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-5xl font-bold bg-gradient-to-r from-blue-400 via-blue-300 to-orange-400 bg-clip-text text-transparent mb-6">
                    {{ __('compro/company.page_title') }}
                </h2>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-orange-500 mx-auto mb-6 rounded-full"></div>
                <p class="text-slate-300 text-lg mt-4 max-w-3xl mx-auto leading-relaxed">
                    {{ __('compro/company.page_subtext') }}
                </p>
            </div>

            <!-- Companies Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($companies as $company)
                    <div class="group relative" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <!-- Glow effect -->
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-600 to-orange-600 rounded-3xl blur opacity-25 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                        
                        <!-- Card -->
                        <div class="relative bg-gradient-to-br from-slate-800 to-slate-900 border border-slate-700/50 shadow-2xl rounded-3xl p-8 backdrop-blur-sm hover:shadow-orange-500/10 hover:shadow-2xl transition-all duration-500 transform group-hover:-translate-y-2">
                            
                            <!-- Company Avatar & Info -->
                            <div class="flex items-center mb-6">
                                <div class="relative">
                                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center bg-gradient-to-br from-blue-500 via-blue-600 to-orange-500 text-white text-2xl font-bold shadow-lg">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <!-- Subtle glow for avatar -->
                                    <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-blue-400 to-orange-400 blur opacity-50 -z-10"></div>
                                </div>
                                <div class="ml-5">
                                    <h3 class="text-xl font-bold text-white group-hover:text-blue-300 transition-colors duration-300">
                                        {{ $company->user->name }}
                                    </h3>
                                    
                                    <!-- Status Badge -->
                                    @if($company->status == 1)
                                        <span class="inline-flex items-center mt-2 px-3 py-1 text-xs font-semibold rounded-full bg-gradient-to-r from-green-500/20 to-emerald-500/20 text-green-300 border border-green-500/30">
                                            <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                                            {{ __('compro/company.status_active') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center mt-2 px-3 py-1 text-xs font-semibold rounded-full bg-gradient-to-r from-red-500/20 to-red-600/20 text-red-300 border border-red-500/30">
                                            <span class="w-2 h-2 bg-red-400 rounded-full mr-2"></span>
                                            {{ __('compro/company.status_inactive') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Company Details -->
                            <div class="space-y-4">
                                <!-- Address -->
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-5 h-5 mt-0.5 text-orange-400">
                                        <svg fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <p class="text-slate-300 text-sm leading-relaxed">{{ $company->alamat }}</p>
                                </div>

                                <!-- Phone -->
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0 w-5 h-5 text-blue-400">
                                        <svg fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                        </svg>
                                    </div>
                                    <a href="tel:{{ $company->no_telefon }}" 
                                       class="text-slate-300 text-sm hover:text-blue-300 transition-colors duration-200">
                                        {{ $company->no_telefon }}
                                    </a>
                                </div>

                                <!-- Website Link -->
                                @if($company->link)
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0 w-5 h-5 text-orange-400">
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.56-.5-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.56.5.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.498-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <a href="{{ $company->link }}" 
                                           target="_blank" 
                                           rel="noopener noreferrer"
                                           class="text-slate-300 text-sm hover:text-orange-300 transition-colors duration-200 truncate">
                                            {{ parse_url($company->link, PHP_URL_HOST) ?: $company->link }}
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <!-- Decorative gradient line -->
                            <div class="mt-8 h-px bg-gradient-to-r from-transparent via-slate-600 to-transparent"></div>

                            <!-- View Details Link -->
                            <div class="mt-6">
                                <a href="{{ route('compro.companies.show', $company->id) }}" 
                                   class="inline-flex items-center space-x-2 text-blue-400 hover:text-orange-400 font-medium text-sm transition-colors duration-300">
                                    <span>{{ __('compro/company.view_details') }}</span>
                                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Empty state if no companies -->
            @if($companies->isEmpty())
                <div class="text-center py-16" data-aos="fade-up">
                    <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-blue-500/20 to-orange-500/20 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-300 mb-2">No Companies Found</h3>
                    <p class="text-slate-400">There are currently no companies to display.</p>
                </div>
            @endif
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
/* Additional custom styles for enhanced effects */
@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
}

.group:hover .animate-float {
  animation: float 3s ease-in-out infinite;
}

/* Glassmorphism effect */
.backdrop-blur-sm {
  backdrop-filter: blur(12px);
}

/* Custom scrollbar for better UX */
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
</style>
@endsection
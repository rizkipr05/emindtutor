@php
    $basePath = rtrim(request()->getBaseUrl(), '/');
    $signupUrl = ($basePath ?: '') . '/daftar';
@endphp

<!-- Footer -->
<footer class="bg-slate-900 text-white px-10 lg:px-20 py-16 relative overflow-hidden">
  <!-- Background decorative elements -->
  <div class="absolute inset-0 opacity-10">
    <div class="absolute top-10 right-20 w-32 h-32 bg-orange-500 rounded-full blur-3xl"></div>
    <div class="absolute bottom-20 left-10 w-24 h-24 bg-cyan-400 rounded-full blur-2xl"></div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 items-start relative z-10">

    <!-- Branding (left, wider) -->
    <div class="lg:col-span-6 md:col-span-2">
      <h2 class="text-3xl font-bold bg-gradient-to-r from-cyan-400 to-orange-400 bg-clip-text text-transparent leading-snug">
        {{ __('compro/footer.branding_title_line1') }} <br>
        {{ __('compro/footer.branding_title_line2') }}
      </h2>
      <!-- Subtext -->
      <p class="text-slate-300 mt-4 max-w-lg leading-relaxed">
          {{ __('compro/footer.branding_subtext') }}
      </p>

      <!-- CTA -->
      <div class="mt-6 flex justify-start">
          <a href="{{ $signupUrl }}" 
            class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-8 py-3 rounded-full font-semibold transition-all duration-300 shadow-lg hover:shadow-orange-500/25 hover:scale-105 transform">
              {{ __('compro/footer.cta_register') }}
          </a>
      </div>
    </div>

    <!-- Right side (grouped into 3 narrow columns) -->
    <div class="lg:col-span-6 md:col-span-2">
      <div class="grid grid-cols-3 gap-6">

        <!-- Company -->
        <div class="bg-white/5 backdrop-blur-sm rounded-lg p-4 border border-white/10">
          <h3 class="text-cyan-400 font-semibold mb-4 flex items-center">
            <span class="w-2 h-2 bg-orange-500 rounded-full mr-2"></span>
            {{ __('compro/footer.company') }}
          </h3>
          <ul class="space-y-3">
            <li><a href="{{ route('compro.about.index') }}" class="text-slate-300 hover:text-orange-400 transition-colors duration-200 hover:underline">{{ __('compro/footer.company_about') }}</a></li>
          </ul>
        </div>

        <!-- Contact Us -->
        <div class="bg-white/5 backdrop-blur-sm rounded-lg p-4 border border-white/10 col-span-2">
          <h3 class="text-cyan-400 font-semibold mb-4 flex items-center">
            <span class="w-2 h-2 bg-orange-500 rounded-full mr-2"></span>
            {{ __('compro/footer.contact_us') }}
          </h3>
          <ul class="space-y-4 text-sm text-slate-200">
            <li class="flex items-start gap-3 group">
              <div class="w-8 h-8 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-full flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h1.5a1 1 0 01.98.79l.3 1.5a1 1 0 01-.21.89L6.5 8.5a11 11 0 005 5l1.31-1.06a1 1 0 01.9-.21l1.5.3A1 1 0 0119 14.5V16a2 2 0 01-2 2h-1C8.477 18 3 12.523 3 6V5z" />
                </svg>
              </div>
              <div>
                <a href="tel:{{ __('compro/footer.contact_phone') }}" class="hover:text-orange-400 transition-colors">{{ __('compro/footer.contact_phone') }}</a>
              </div>
            </li>

            <li class="flex items-start gap-3 group">
              <div class="w-8 h-8 bg-gradient-to-r from-orange-500 to-red-500 rounded-full flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
              </div>
              <div>
                <a href="mailto:{{ __('compro/footer.contact_email') }}" class="hover:text-orange-400 transition-colors">{{ __('compro/footer.contact_email') }}</a>
              </div>
            </li>

            <li class="flex items-start gap-3 group">
              <div class="w-8 h-8 bg-gradient-to-r from-cyan-500 to-orange-500 rounded-full flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                <path d="M16 10c0-2.21-1.79-4-4-4s-4 1.79-4 4 1.79 4 4 4 4-1.79 4-4m-6 0c0-1.1.9-2 2-2s2 .9 2 2-.9 2-2 2-2-.9-2-2"></path><path d="M11.42 21.81c.17.12.38.19.58.19s.41-.06.58-.19c.3-.22 7.45-5.37 7.42-11.82 0-4.41-3.59-8-8-8s-8 3.59-8 8c-.03 6.44 7.12 11.6 7.42 11.82M12 4c3.31 0 6 2.69 6 6 .02 4.44-4.39 8.43-6 9.74-1.61-1.31-6.02-5.29-6-9.74 0-3.31 2.69-6 6-6"></path>
                </svg>
              </div>
              <div>
                <div class="max-w-xs leading-snug text-slate-300">{{ __('compro/footer.contact_office') }}</div>
              </div>
            </li>
          </ul>
        </div>

      </div>
    </div>

  </div>

  <!-- Copyright -->
  <div class="border-t border-slate-700/50 mt-12 pt-6 text-slate-400 text-sm text-center relative z-10">
    <div class="flex items-center justify-center space-x-2">
      <span class="w-1 h-1 bg-cyan-400 rounded-full"></span>
      <span>{{ __('compro/footer.copyright', ['year' => now()->year]) }}</span>
      <span class="w-1 h-1 bg-orange-500 rounded-full"></span>
    </div>
  </div>
</footer>

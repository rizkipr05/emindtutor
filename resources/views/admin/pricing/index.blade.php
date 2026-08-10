@extends('layouts.admin')

@section('content')
    <section id="pricing" class="py-5 md:px-20">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl font-bold text-gray-900">{{ __('compro/pricing.page_title') }}</h2>
            <p class="text-gray-600 mt-4 max-w-2xl mx-auto">
                {{ __('compro/pricing.page_subtext') }}
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            @foreach($packages as $key => $package)
                <div 
                    class="relative bg-white shadow-xl rounded-2xl p-8 hover:scale-105 transition transform duration-300" 
                    data-aos="fade-up" 
                    data-aos-delay="{{ $key * 200 }}"
                >
                    {{-- Badge jika trial --}}
                    @if($package->is_trial)
                        <div class="absolute top-3 right-3 bg-green-500 text-white px-3 py-1 text-xs font-semibold rounded-full shadow">
                            {{ __('compro/pricing.trial_badge') }}
                        </div>
                    @endif

                    {{-- Nama paket --}}
                    <h3 class="text-xl font-semibold text-gray-800">{{ $package->nama_pakej }}</h3>

                    {{-- Harga + durasi --}}
                    <p class="mt-4 text-4xl font-bold text-gray-900">
                        RM{{ number_format($package->harga, 2) }}
                        <span class="text-lg font-normal text-gray-600">
                            @if($package->duration_days == 30)
                                /mo
                            @elseif($package->duration_days == 365)
                                /year
                            @else
                                /{{ $package->duration_days }} days
                            @endif
                        </span>
                    </p>

                    {{-- Deskripsi (multiline → list) --}}
                    <ul class="mt-6 space-y-3 text-gray-600 text-left">
                        @foreach(explode("\n", $package->deskripsi) as $desc)
                            @if(!empty(trim($desc)))
                                <li class="flex items-start">
                                    <span class="text-green-500 mr-2">✔</span> 
                                    <span>{{ $desc }}</span>
                                </li>
                            @endif
                        @endforeach
                    </ul>

                    {{-- Tombol --}}
                    @if($package->is_trial && $trialUsed)
                        <span class="mt-8 block w-full cursor-not-allowed rounded-xl bg-slate-200 py-3 text-center font-semibold text-slate-500">
                            {{ __('admin/settings.trial_already_used') }}
                        </span>
                    @else
                        <a href="{{ route('subscribe.start', $package->id) }}"
                           class="mt-8 block text-center w-full py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
                            {{ __('compro/pricing.subscribe_now') }}
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    </section>

    <script>
      AOS.init({
        duration: 1000,
        once: true,
      });
    </script>
@endsection
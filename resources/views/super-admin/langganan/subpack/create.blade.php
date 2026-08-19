@extends('layouts.superadmin')

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto sm:px-4 lg:px-6 sm:py-8 lg:py-10">
        
        <!-- Header Section -->
        <div class="text-center mb-8 sm:mb-10">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent mb-3">
                {{ __('super_admin/langganan.create.header') }}
            </h1>
            <div class="w-16 sm:w-20 lg:w-24 h-1 bg-gradient-to-r from-teal-500 to-cyan-500 mx-auto rounded-full"></div>
        </div>

        <!-- Form Container -->
        <div class="max-w-4xl mx-auto">
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/50 overflow-hidden">
                
                <!-- Form Header -->
                <div class="bg-gradient-to-r from-teal-500 to-cyan-500 px-6 sm:px-8 py-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold text-white">{{ __('super_admin/langganan.create.header') }}</h2>
                            <p class="text-teal-100 text-sm sm:text-base">{{ __('super_admin/langganan.create.desc') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="m-6 sm:m-8 p-4 sm:p-6 bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 rounded-xl">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.732 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <div>
                                <h3 class="font-semibold text-red-800 mb-2">{{ __('super_admin/langganan.create.form_error_title') }}</h3>
                                <ul class="space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li class="text-red-700 text-sm flex items-center">
                                            <svg class="w-3 h-3 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <circle cx="10" cy="10" r="2"/>
                                            </svg>
                                            {{ $error }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Form Content -->
                <form action="{{ route('subscription-packages.store') }}" method="POST" class="p-6 sm:p-8 space-y-6 sm:space-y-8">
                    @csrf

                    <!-- Basic Information Section -->
                    <div class="space-y-6">
                        <div class="border-l-4 border-teal-400 pl-4">
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-1">{{ __('super_admin/langganan.create.basic_info') }}</h3>
                            <p class="text-sm text-gray-600">{{ __('super_admin/langganan.create.basic_info_desc') }}</p>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Nama Pakej -->
                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-semibold text-gray-700">
                                    <svg class="w-4 h-4 mr-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    {{ __('super_admin/langganan.create.package_name') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama_pakej" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 bg-white/50 backdrop-blur-sm hover:bg-white/80" 
                                       placeholder="{{ __('super_admin/langganan.create.package_name_placeholder') }}" 
                                       required>
                            </div>

                            <!-- Status -->
                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-semibold text-gray-700">
                                    <svg class="w-4 h-4 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                    </svg>
                                    {{ __('super_admin/langganan.create.status') }} <span class="text-red-500">*</span>
                                </label>
                                <select name="status" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 bg-white/50 backdrop-blur-sm hover:bg-white/80">
                                    <option value="aktif">{{ __('super_admin/langganan.create.status_active') }}</option>
                                    <option value="tamat">{{ __('super_admin/langganan.create.status_inactive') }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                {{ __('super_admin/langganan.create.description') }}
                            </label>
                            <textarea name="deskripsi" rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 bg-white/50 backdrop-blur-sm hover:bg-white/80 resize-none" 
                                      placeholder="{{ __('super_admin/langganan.create.description_placeholder') }}"></textarea>
                        </div>
                    </div>

                    <!-- Pricing Type Section -->
                    <div class="space-y-6">
                        <div class="border-l-4 border-purple-400 pl-4">
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-1">Jenis Harga</h3>
                            <p class="text-sm text-gray-600">Pilih apakah paket ini menggunakan harga tetap atau harga per pelajar</p>
                        </div>

                        <!-- Pricing Type Selection -->
                        <div class="bg-gradient-to-br from-purple-50 to-indigo-50 p-6 rounded-xl border border-purple-200">
                            <label class="flex items-center text-sm font-semibold text-gray-700 mb-4">
                                <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                Metode Penetapan Harga <span class="text-red-500">*</span>
                            </label>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Harga Tetap -->
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="pricing_per_student" value="0" class="peer sr-only" checked onchange="togglePricingFields()">
                                    <div class="bg-white border-2 border-gray-300 rounded-xl p-4 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all duration-300 hover:shadow-md">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 mr-3">
                                                <div class="w-10 h-10 rounded-full bg-emerald-100 peer-checked:bg-emerald-500 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-emerald-600 peer-checked:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-800">Harga Tetap</h4>
                                                <p class="text-sm text-gray-600 mt-1">Harga paket tidak berubah berdasarkan jumlah pelajar</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <!-- Harga Per Pelajar -->
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="pricing_per_student" value="1" class="peer sr-only" onchange="togglePricingFields()">
                                    <div class="bg-white border-2 border-gray-300 rounded-xl p-4 peer-checked:border-orange-500 peer-checked:bg-orange-50 transition-all duration-300 hover:shadow-md">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 mr-3">
                                                <div class="w-10 h-10 rounded-full bg-orange-100 peer-checked:bg-orange-500 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-orange-600 peer-checked:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-800">Harga Per Pelajar</h4>
                                                <p class="text-sm text-gray-600 mt-1">Harga dihitung berdasarkan jumlah pelajar aktif</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing & Duration Section -->
<div class="space-y-6">
    <div class="border-l-4 border-orange-400 pl-4">
        <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-1">{{ __('super_admin/langganan.create.pricing_duration') }}</h3>
        <p class="text-sm text-gray-600">{{ __('super_admin/langganan.create.pricing_duration_desc') }}</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- Durasi -->
        <div class="space-y-2">
            <label class="flex items-center text-sm font-semibold text-gray-700">
                <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ __('super_admin/langganan.create.duration') }} <span class="text-red-500">*</span>
            </label>
            <input type="number" name="duration_days" min="1"
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300 bg-white/50 backdrop-blur-sm hover:bg-white/80" 
                   placeholder="{{ __('super_admin/langganan.create.duration_placeholder') }}" 
                   required>
        </div>

        <!-- Harga Tetap (shown when pricing_per_student = 0) -->
        <div class="space-y-2" id="harga_tetap_field">
            <label class="flex items-center text-sm font-semibold text-gray-700">
                <svg class="w-4 h-4 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
                {{ __('super_admin/langganan.create.price') }} <span class="text-red-500">*</span>
            </label>
            <input type="number" name="harga" id="harga" min="0" step="0.01"
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-300 bg-white/50 backdrop-blur-sm hover:bg-white/80" 
                   placeholder="{{ __('super_admin/langganan.create.price_placeholder') }}" 
                   required>
        </div>

        <!-- Harga Per Pelajar (shown when pricing_per_student = 1) -->
        <div class="space-y-2 hidden" id="harga_per_pelajar_field">
            <label class="flex items-center text-sm font-semibold text-gray-700">
                <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Harga Per Pelajar <span class="text-red-500">*</span>
            </label>
            <input type="number" name="harga_per_pelajar" id="harga_per_pelajar" min="0" step="0.01"
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300 bg-white/50 backdrop-blur-sm hover:bg-white/80" 
                   placeholder="Contoh: 1.50">
            <p class="text-xs text-gray-500 mt-1">
                <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                Total harga = Harga per pelajar × Jumlah pelajar aktif
            </p>
        </div>

        <!-- Jenis Pakej -->
        <div class="space-y-2">
            <label class="flex items-center text-sm font-semibold text-gray-700">
                <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                {{ __('super_admin/langganan.create.type_label') }} <span class="text-red-500">*</span>
            </label>
            <select name="is_trial" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 bg-white/50 backdrop-blur-sm hover:bg-white/80">
                <option value="0">{{ __('super_admin/langganan.create.type_paid') }}</option>
                <option value="1">{{ __('super_admin/langganan.create.type_trial') }}</option>
            </select>
        </div>
    </div>
</div>

<!-- Limitations Section (Only for Fixed Price) -->
<div class="space-y-6" id="limitations_section">
    <div class="border-l-4 border-indigo-400 pl-4">
        <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-1">{{ __('super_admin/langganan.create.limitations') }}</h3>
        <p class="text-sm text-gray-600">{{ __('super_admin/langganan.create.limitations_desc') }}</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- Maks Pelajar -->
        <div class="space-y-2">
            <label class="flex items-center text-sm font-semibold text-gray-700">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 4A2 2 0 1 0 12 8 2 2 0 1 0 12 4z"></path><path d="M5 7A2 2 0 1 0 5 11 2 2 0 1 0 5 7z"></path><path d="M19 7A2 2 0 1 0 19 11 2 2 0 1 0 19 7z"></path><path d="m19.5,12h-.7c-.84,0-1.61.42-2.08,1.11l-3.26,4.89h-2.93l-3.26-4.89c-.46-.7-1.24-1.11-2.08-1.11h-.7c-1.38,0-2.5,1.12-2.5,2.5v5.5h5v-3.7l1.87,2.81c.37.56.99.89,1.66.89h2.93c.67,0,1.29-.33,1.66-.89l1.87-2.81v3.7h5v-5.5c0-1.38-1.12-2.5-2.5-2.5Z"></path><path d="m12.39,16l2.61-3.91v-1.59c0-.83-.67-1.5-1.5-1.5h-3c-.83,0-1.5.67-1.5,1.5v1.59l2.61,3.91h.79Z"></path>
                </svg>
                {{ __('super_admin/langganan.create.max_students') }} <span class="text-xs text-gray-500 font-normal ml-1">(Optional)</span>
            </label>
            <input type="number" name="maks_pelajar" id="maks_pelajar" min="0"
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 bg-white/50 backdrop-blur-sm hover:bg-white/80" 
                   placeholder="{{ __('super_admin/langganan.create.max_students_placeholder') }}">
        </div>

        <!-- Maks Guru -->
        <div class="space-y-2">
            <label class="flex items-center text-sm font-semibold text-gray-700">
                <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                {{ __('super_admin/langganan.create.max_teachers') }} <span class="text-xs text-gray-500 font-normal ml-1">(Optional)</span>
            </label>
            <input type="number" name="maks_guru" id="maks_guru" min="0"
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 bg-white/50 backdrop-blur-sm hover:bg-white/80" 
                   placeholder="{{ __('super_admin/langganan.create.max_teachers_placeholder') }}">
        </div>

        <!-- Maks Kelas -->
        <div class="space-y-2">
            <label class="flex items-center text-sm font-semibold text-gray-700">
                <svg class="w-4 h-4 mr-2 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                {{ __('super_admin/langganan.create.max_classes') }} <span class="text-xs text-gray-500 font-normal ml-1">(Optional)</span>
            </label>
            <input type="number" name="maks_kelas" id="maks_kelas" min="0"
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all duration-300 bg-white/50 backdrop-blur-sm hover:bg-white/80" 
                   placeholder="{{ __('super_admin/langganan.create.max_classes_placeholder') }}">
        </div>
    </div>
</div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-end gap-3 sm:gap-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('subscription-packages.index') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-300 font-semibold text-sm sm:text-base order-2 sm:order-1">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            {{ __('super_admin/langganan.create.back') }}
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-teal-500 to-cyan-500 text-white rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 font-semibold text-sm sm:text-base order-1 sm:order-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('super_admin/langganan.create.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function togglePricingFields() {
    const pricingType = document.querySelector('input[name="pricing_per_student"]:checked').value;
    const hargaTetapField = document.getElementById('harga_tetap_field');
    const hargaPerPelajarField = document.getElementById('harga_per_pelajar_field');
    const hargaInput = document.getElementById('harga');
    const hargaPerPelajarInput = document.getElementById('harga_per_pelajar');
    
    // Limitations section and inputs
    const limitationsSection = document.getElementById('limitations_section');
    const maksPelajarInput = document.getElementById('maks_pelajar');
    const maksGuruInput = document.getElementById('maks_guru');
    const maksKelasInput = document.getElementById('maks_kelas');
    
    if (pricingType === '1') {
        // Per Pelajar - Hide limitations
        hargaTetapField.classList.add('hidden');
        hargaPerPelajarField.classList.remove('hidden');
        limitationsSection.classList.add('hidden');
        
        // Handle harga tetap input
        hargaInput.removeAttribute('required');
        hargaInput.setAttribute('disabled', 'disabled');
        hargaInput.value = '0';
        
        // Handle harga per pelajar input
        hargaPerPelajarInput.removeAttribute('disabled');
        hargaPerPelajarInput.setAttribute('required', 'required');
        
        // Disable and remove required from limitations
        maksPelajarInput.removeAttribute('required');
        maksPelajarInput.setAttribute('disabled', 'disabled');
        maksPelajarInput.value = '0';
        
        maksGuruInput.removeAttribute('required');
        maksGuruInput.setAttribute('disabled', 'disabled');
        maksGuruInput.value = '0';
        
        maksKelasInput.removeAttribute('required');
        maksKelasInput.setAttribute('disabled', 'disabled');
        maksKelasInput.value = '0';
    } else {
        // Harga Tetap - Show limitations
        hargaTetapField.classList.remove('hidden');
        hargaPerPelajarField.classList.add('hidden');
        limitationsSection.classList.remove('hidden');
        
        // Handle harga tetap input
        hargaInput.removeAttribute('disabled');
        hargaInput.setAttribute('required', 'required');
        
        // Handle harga per pelajar input
        hargaPerPelajarInput.removeAttribute('required');
        hargaPerPelajarInput.setAttribute('disabled', 'disabled');
        hargaPerPelajarInput.value = '';
        
        // Enable and ensure required is removed from limitations
        maksPelajarInput.removeAttribute('disabled');
        maksPelajarInput.removeAttribute('required');
        if (maksPelajarInput.value === '0') maksPelajarInput.value = '';
        
        maksGuruInput.removeAttribute('disabled');
        maksGuruInput.removeAttribute('required');
        if (maksGuruInput.value === '0') maksGuruInput.value = '';
        
        maksKelasInput.removeAttribute('disabled');
        maksKelasInput.removeAttribute('required');
        if (maksKelasInput.value === '0') maksKelasInput.value = '';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    togglePricingFields();
});
</script>

@endsection
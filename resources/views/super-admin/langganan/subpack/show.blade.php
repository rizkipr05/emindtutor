@extends('layouts.superadmin')

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto px-3 sm:px-4 lg:px-6 py-6 sm:py-8 lg:py-10">
        
        <!-- Header Section -->
        <div class="text-center mb-8 sm:mb-10">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent mb-3">
                {{ __('super_admin/langganan.show.header') }}
            </h1>
            <div class="w-16 sm:w-20 lg:w-24 h-1 bg-gradient-to-r from-teal-500 to-cyan-500 mx-auto rounded-full"></div>
            <p class="text-gray-600 mt-4 text-sm sm:text-base">{{ $subscriptionPackage->nama_pakej }}</p>
        </div>

        <!-- Back Button -->
        <div class="max-w-4xl mx-auto mb-6">
                <a href="{{ route('subscription-packages.index') }}" 
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                {{ __('super_admin/langganan.show.back_to_list') }}
            </a>
        </div>

        <!-- Content Container -->
        <div class="max-w-4xl mx-auto">
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/50 overflow-hidden">
                
                <!-- Header -->
                <div class="bg-gradient-to-r from-teal-500 to-cyan-500 px-6 sm:px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl sm:text-2xl font-bold text-white">{{ __('super_admin/langganan.show.detail_title') }}</h2>
                                <p class="text-teal-100 text-sm sm:text-base">{{ __('super_admin/langganan.show.detail_desc') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <!-- Status Badge -->
                            @if($subscriptionPackage->status == 'aktif')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ __('super_admin/langganan.show.status_active') }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ __('super_admin/langganan.show.status_inactive') }}
                                </span>
                            @endif
                            
                            <!-- Trial Badge -->
                            @if($subscriptionPackage->is_trial)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    {{ __('super_admin/langganan.show.trial') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 sm:p-8 space-y-8">
                    
                    <!-- Basic Information -->
                    <div class="space-y-6">
                        <div class="border-l-4 border-teal-400 pl-4">
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-1">{{ __('super_admin/langganan.show.basic_info') }}</h3>
                            <p class="text-sm text-gray-600">{{ __('super_admin/langganan.show.basic_info_desc') }}</p>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Nama Pakej -->
                            <div class="bg-gradient-to-br from-teal-50 to-cyan-50 rounded-xl p-4 border border-teal-100">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-teal-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    <h4 class="text-sm font-semibold text-gray-700">{{ __('super_admin/langganan.show.package_name') }}</h4>
                                </div>
                                <p class="text-lg font-medium text-gray-900">{{ $subscriptionPackage->nama_pakej }}</p>
                            </div>

                            <!-- Status -->
                            <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl p-4 border border-emerald-100">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-emerald-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                    </svg>
                                    <h4 class="text-sm font-semibold text-gray-700">{{ __('super_admin/langganan.show.status') }}</h4>
                                </div>
                                <p class="text-lg font-medium text-gray-900 capitalize">{{ $subscriptionPackage->status == 'aktif' ? __('super_admin/langganan.show.status_active') : __('super_admin/langganan.show.status_inactive') }}</p>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        @if($subscriptionPackage->deskripsi)
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-100">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <h4 class="text-sm font-semibold text-gray-700">{{ __('super_admin/langganan.show.description') }}</h4>
                            </div>
                            <p class="text-gray-900">
                                {!! nl2br(e($subscriptionPackage->deskripsi)) !!}
                            </p>
                        </div>
                        @endif
                    </div>

                    <!-- Pricing & Duration -->
                    <div class="space-y-6">
                        <div class="border-l-4 border-orange-400 pl-4">
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-1">{{ __('super_admin/langganan.show.pricing_duration') }}</h3>
                            <p class="text-sm text-gray-600">{{ __('super_admin/langganan.show.pricing_duration_desc') }}</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                            <!-- Durasi -->
                            <div class="bg-gradient-to-br from-orange-50 to-yellow-50 rounded-xl p-4 border border-orange-100">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h4 class="text-sm font-semibold text-gray-700">{{ __('super_admin/langganan.show.duration') }}</h4>
                                </div>
                                <p class="text-xl font-bold text-orange-600">{{ $subscriptionPackage->duration_days }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ __('super_admin/langganan.show.days') }}</p>
                            </div>

                            <!-- Harga -->
                            <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl p-4 border border-emerald-100">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-emerald-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    <h4 class="text-sm font-semibold text-gray-700">{{ __('super_admin/langganan.show.price') }}</h4>
                                </div>
                                <p class="text-xl font-bold text-emerald-600">RM {{ number_format($subscriptionPackage->harga, 2) }}</p>
                            </div>

                            <!-- Jenis Pakej -->
                            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-4 border border-purple-100">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    <h4 class="text-sm font-semibold text-gray-700">{{ __('super_admin/langganan.show.type') }}</h4>
                                </div>
                                <p class="text-lg font-medium text-gray-900">
                                    {{ $subscriptionPackage->is_trial ? __('super_admin/langganan.show.trial_package') : __('super_admin/langganan.show.standard_package') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Limitations -->
                    <div class="space-y-6">
                        <div class="border-l-4 border-indigo-400 pl-4">
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-1">{{ __('super_admin/langganan.show.limitations') }}</h3>
                            <p class="text-sm text-gray-600">{{ __('super_admin/langganan.show.limitations_desc') }}</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                            <!-- Maks Pelajar -->
                            <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-xl p-4 border border-indigo-100">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 4A2 2 0 1 0 12 8 2 2 0 1 0 12 4z"></path><path d="M5 7A2 2 0 1 0 5 11 2 2 0 1 0 5 7z"></path><path d="M19 7A2 2 0 1 0 19 11 2 2 0 1 0 19 7z"></path><path d="m19.5,12h-.7c-.84,0-1.61.42-2.08,1.11l-3.26,4.89h-2.93l-3.26-4.89c-.46-.7-1.24-1.11-2.08-1.11h-.7c-1.38,0-2.5,1.12-2.5,2.5v5.5h5v-3.7l1.87,2.81c.37.56.99.89,1.66.89h2.93c.67,0,1.29-.33,1.66-.89l1.87-2.81v3.7h5v-5.5c0-1.38-1.12-2.5-2.5-2.5Z"></path><path d="m12.39,16l2.61-3.91v-1.59c0-.83-.67-1.5-1.5-1.5h-3c-.83,0-1.5.67-1.5,1.5v1.59l2.61,3.91h.79Z"></path>
                                    </svg>
                                    <h4 class="text-sm font-semibold text-gray-700">{{ __('super_admin/langganan.show.max_students') }}</h4>
                                </div>
                                <p class="text-2xl font-bold text-indigo-600">{{ number_format($subscriptionPackage->maks_pelajar) }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ __('super_admin/langganan.show.students') }}</p>
                            </div>

                            <!-- Maks Guru -->
                            <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl p-4 border border-blue-100">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <h4 class="text-sm font-semibold text-gray-700">{{ __('super_admin/langganan.show.max_teachers') }}</h4>
                                </div>
                                <p class="text-2xl font-bold text-blue-600">{{ number_format($subscriptionPackage->maks_guru) }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ __('super_admin/langganan.show.teachers') }}</p>
                            </div>

                            <!-- Maks Kelas -->
                            <div class="bg-gradient-to-br from-cyan-50 to-teal-50 rounded-xl p-4 border border-cyan-100">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-cyan-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <h4 class="text-sm font-semibold text-gray-700">{{ __('super_admin/langganan.show.max_classes') }}</h4>
                                </div>
                                <p class="text-2xl font-bold text-cyan-600">{{ number_format($subscriptionPackage->maks_kelas) }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ __('super_admin/langganan.show.classes') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-end gap-3 sm:gap-4 pt-6 border-t border-gray-200">
                                <a href="{{ route('subscription-packages.index') }}" 
                                    class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-300 font-semibold text-sm sm:text-base order-3 sm:order-1">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            {{ __('super_admin/langganan.show.back') }}
                        </a>
                                <a href="{{ route('subscription-packages.edit', $subscriptionPackage->id) }}" 
                                    class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 font-semibold text-sm sm:text-base order-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            {{ __('super_admin/langganan.show.edit') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
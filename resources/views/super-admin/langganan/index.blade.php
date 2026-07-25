@extends('layouts.superadmin')

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto sm:px-4 lg:px-6 sm:py-8 lg:py-10">
        
        <!-- Header Section -->
        <div class="mb-8 sm:mb-10">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 sm:gap-0">
                <div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent mb-2">
                        {{ __('super_admin/langganan.heading') }}
                    </h1>
                    <div class="w-16 sm:w-20 lg:w-24 h-1 bg-gradient-to-r from-teal-500 to-cyan-500 rounded-full"></div>
                </div>
                
                @php
                    $sub = \App\Models\SubAktif::find(1);
                @endphp
                <form action="{{ route('sub-aktif.toggle') }}" method="POST">
                    @csrf
                    <button type="submit" 
                        class="inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 
                            {{ $sub && $sub->pricing_per_student 
                                ? 'bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600' 
                                : 'bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600' }} 
                            text-white rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 
                            transition-all duration-300 font-semibold text-sm sm:text-base group">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 group-hover:rotate-180 transition-transform duration-300" 
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                        {{ $sub && $sub->pricing_per_student ? 'Gunakan Harga Tetap' : 'Jadikan Per Student' }}
                    </button>
                </form>


                <a href="{{ route('subscription-packages.create') }}" 
                   class="inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-teal-500 to-cyan-500 text-white rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 font-semibold text-sm sm:text-base group">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('super_admin/langganan.add_button') }}
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 sm:mb-8 p-4 sm:p-5 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-800 rounded-xl shadow-lg animate-pulse">
                <div class="flex items-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                    <span class="font-medium text-sm sm:text-base">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Desktop Table View -->
        <div class="hidden lg:block bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gradient-to-r from-teal-500 to-cyan-500 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold">{{ __('super_admin/langganan.table.name') }}</th>
                            <th class="px-6 py-4 text-center font-semibold">{{ __('super_admin/langganan.table.duration') }}</th>
                            <th class="px-6 py-4 text-center font-semibold">{{ __('super_admin/langganan.table.price') }}</th>
                            <!-- <th class="px-6 py-4 text-center font-semibold">{{ __('super_admin/langganan.table.max_students') }}</th>
                            <th class="px-6 py-4 text-center font-semibold">{{ __('super_admin/langganan.table.max_teachers') }}</th>
                            <th class="px-6 py-4 text-center font-semibold">{{ __('super_admin/langganan.table.max_classes') }}</th> -->
                            <th class="px-6 py-4 text-center font-semibold">{{ __('super_admin/langganan.table.status') }}</th>
                            <th class="px-6 py-4 text-center font-semibold">{{ __('super_admin/langganan.table.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($packages as $package)
                            <tr 
                                class="transition-all duration-300 
                                    hover:bg-gradient-to-r hover:from-teal-50 hover:to-cyan-50 
                                    {{ $package->pricing_per_student == 1 ? 'bg-emerald-50' : '' }}">
                                
                                <td class="px-6 py-4 font-semibold text-gray-800">
                                    {{ $package->nama_pakej }}
                                    @if($package->pricing_per_student == 1)
                                        <span class="ml-2 text-xs bg-green-200 text-green-800 px-2 py-0.5 rounded-full font-medium">
                                            Per Student
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center text-gray-700">
                                    <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                        {{ $package->duration_days }} {{ __('super_admin/langganan.table.day') }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($package->pricing_per_student == 1)
                                        <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-bold">
                                            RM {{ number_format($package->harga_per_pelajar, 2) }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-sm font-bold">
                                            RM {{ number_format($package->harga, 2) }}
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold 
                                        {{ $package->status === 'aktif' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                        {{ $package->status ? __('super_admin/langganan.table.status_active') : __('super_admin/langganan.table.status_inactive') }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('subscription-packages.show', $package->id) }}" 
                                        class="inline-flex items-center px-3 py-1.5 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors duration-300 text-sm font-medium">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>

                                        <a href="{{ route('subscription-packages.edit', $package->id) }}" 
                                        class="inline-flex items-center px-3 py-1.5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-300 text-sm font-medium">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>

                                        <form action="{{ route('subscription-packages.destroy', $package->id) }}" method="POST" class="inline" onsubmit="return confirm('Padam pakej ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors duration-300 text-sm font-medium">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-gray-500 text-lg font-medium">{{ __('super_admin/langganan.table.empty') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-4 sm:space-y-6">
            @forelse($packages as $package)
                <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-white/50 overflow-hidden hover:shadow-xl transition-all duration-300">
                    <!-- Card Header -->
                    <div class="bg-gradient-to-r from-teal-500 to-cyan-500 px-4 sm:px-6 py-3 sm:py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg sm:text-xl font-bold text-white">{{ $package->nama_pakej }}</h3>
                            <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-semibold {{ $package->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ __('super_admin/langganan.table.status_' . ($package->status === 'aktif' ? 'active' : 'inactive')) }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Card Body -->
                    <div class="p-4 sm:p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mb-4 sm:mb-6">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs sm:text-sm text-gray-600">{{ __('super_admin/langganan.table.duration') }}</p>
                                    <p class="font-semibold text-sm sm:text-base text-gray-800">{{ $package->duration_days }} hari</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs sm:text-sm text-gray-600">{{ __('super_admin/langganan.table.price') }}</p>
                                    <p class="font-bold text-sm sm:text-base text-emerald-600">RM {{ number_format($package->harga, 2) }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <!--Boxicons v3.0 https://boxicons.com | License  https://docs.boxicons.com/free-->
                                        <path d="M12 4A2 2 0 1 0 12 8 2 2 0 1 0 12 4z"></path><path d="M5 7A2 2 0 1 0 5 11 2 2 0 1 0 5 7z"></path><path d="M19 7A2 2 0 1 0 19 11 2 2 0 1 0 19 7z"></path><path d="m19.5,12h-.7c-.84,0-1.61.42-2.08,1.11l-3.26,4.89h-2.93l-3.26-4.89c-.46-.7-1.24-1.11-2.08-1.11h-.7c-1.38,0-2.5,1.12-2.5,2.5v5.5h5v-3.7l1.87,2.81c.37.56.99.89,1.66.89h2.93c.67,0,1.29-.33,1.66-.89l1.87-2.81v3.7h5v-5.5c0-1.38-1.12-2.5-2.5-2.5Z"></path><path d="m12.39,16l2.61-3.91v-1.59c0-.83-.67-1.5-1.5-1.5h-3c-.83,0-1.5.67-1.5,1.5v1.59l2.61,3.91h.79Z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs sm:text-sm text-gray-600">{{ __('super_admin/langganan.table.max_students') }}</p>
                                    <p class="font-semibold text-sm sm:text-base text-gray-800">{{ $package->maks_pelajar }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs sm:text-sm text-gray-600">{{ __('super_admin/langganan.table.max_teachers') }}</p>
                                    <p class="font-semibold text-sm sm:text-base text-gray-800">{{ $package->maks_guru }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center sm:col-span-2">
                                <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs sm:text-sm text-gray-600">{{ __('super_admin/langganan.table.max_classes') }}</p>
                                    <p class="font-semibold text-sm sm:text-base text-gray-800">{{ $package->maks_kelas }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                            <a href="{{ route('subscription-packages.show', $package->id) }}" 
                            class="flex-1 inline-flex items-center justify-center px-4 py-2 sm:py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors duration-300 text-sm font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                {{ __('super_admin/langganan.table.actions.show') ?? 'Lihat' }}
                            </a>
                            <a href="{{ route('subscription-packages.edit', $package->id) }}" 
                            class="flex-1 inline-flex items-center justify-center px-4 py-2 sm:py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-300 text-sm font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                {{ __('super_admin/langganan.table.actions.edit') ?? 'Edit' }}
                            </a>
                            <form action="{{ route('subscription-packages.destroy', $package->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Padam pakej ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 sm:py-3 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors duration-300 text-sm font-medium">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    {{ __('super_admin/langganan.confirm_delete') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-white/50 p-8 sm:p-12 text-center">
                    <svg class="w-16 h-16 sm:w-20 sm:h-20 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-500 text-lg sm:text-xl font-medium">{{ __('super_admin/langganan.table.empty') }}</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
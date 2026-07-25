@extends('layouts.admin')

@section('content')
<div class="min-h-screen">
    <div class="max-w-md lg:max-w-7xl mx-auto px-4 lg:px-8">

        <!-- Back Navigation -->
    <a href="{{ route('a.dashboard.index') }}" class="group block mb-6">
            <div class="bg-white/80 backdrop-blur-sm hover:bg-white p-4 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-white/50">
                <div class="inline-flex items-center text-gray-700 group-hover:text-sky-600 font-medium transition-colors duration-200">
                    <div class="p-2 rounded-full bg-sky-100 group-hover:bg-sky-200 mr-3 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </div>
                    <span class="text-lg">{{ __('admin/class/index.back') }}</span>
                </div>
            </div>
        </a>

        <!-- Header Section -->
        <div class="bg-white rounded-lg shadow-sm p-4 lg:p-6 mb-6">
            @php
                $progressPercentage = $class['minimum_sessions'] > 0
                    ? min(($class['final_total_sessions'] / $class['minimum_sessions']) * 100, 100)
                    : 0;
            @endphp
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center justify-between lg:justify-start lg:gap-4 mb-2">
                        <h1 class="text-lg lg:text-2xl font-semibold text-gray-800">{{ $class['class_name'] }}</h1>
                        <span class="bg-blue-100 text-blue-800 text-xs lg:text-sm px-2 lg:px-3 py-1 rounded-full">
                            {{ $class['total_sessions'] }} {{ __('admin/class/index.session') }}
                        </span>
                    </div>
                    <p class="text-sm lg:text-base text-gray-600 mb-1">{{ $class['course_name'] }}</p>
                    <p class="text-sm lg:text-base text-gray-500">{{ __('admin/class/index.teacher_label') }}: {{ $class['teacher_name'] }}</p>
                </div>
                
                <!-- Stats moved to header on larger screens -->
                <div class="flex gap-6 lg:gap-8">
                    <div class="text-center">
                        <p class="text-2xl lg:text-3xl font-bold text-blue-600">{{ $class['total_students'] }}</p>
                        <p class="text-xs lg:text-sm text-gray-500">{{ __('admin/class/index.stats.total_students') }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl lg:text-3xl font-bold text-green-600">{{ $class['attendance_rate'] }}%</p>
                        <p class="text-xs lg:text-sm text-gray-500">{{ __('admin/class/index.stats.attendance') }}</p>
                    </div>
                </div>
            </div>
            <!-- Action Buttons -->
            <div class="mt-4 flex justify-end">
                @if(!empty($class['id']))
                    <a href="{{ route('admin.class.edit', $class['id']) }}" 
                       class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-sky-500 to-cyan-600 text-white rounded-xl hover:from-sky-600 hover:to-cyan-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        {{ __('admin/class/index.actions.edit_class') }}
                    </a>
                @endif
            </div>
        </div>

        <!-- Progress Section -->
        <div class="bg-white rounded-lg shadow-sm p-4 lg:p-6 mb-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm lg:text-base font-medium text-gray-700">{{ __('admin/class/index.progress') }}</h3>
                <span class="text-xs lg:text-sm text-gray-500">
                    {{ $class['final_total_sessions'] }}/{{ $class['minimum_sessions'] }} {{ __('admin/class/index.minimum_sessions_unit')[strtolower($class['minimum_sessions_unit'])] ?? $class['minimum_sessions_unit'] }}
                </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2 lg:h-3">
                <div class="bg-blue-600 h-2 lg:h-3 rounded-full transition-all duration-300" 
                     style="width: {{ $progressPercentage }}%">
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-6">
            
            <!-- Session Details Section -->
            <div class="lg:col-span-1 xl:col-span-2">
                <div class="bg-white rounded-lg shadow-sm p-4 lg:p-6 h-fit">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                                    <span class="inline-block w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                                    <h3 class="text-sm lg:text-base font-medium text-gray-700">{{ __('admin/class/index.session_list.title') }}</h3>
                                </div>
                                <span class="bg-green-100 text-green-800 text-xs lg:text-sm px-2 lg:px-3 py-1 rounded-full font-medium">
                                    {{ count($class['session_details']) }} {{ __('admin/class/index.sessions') }}
                                </span>
                    </div>

                    @if(count($class['session_details']) > 0)
                        <div class="space-y-4 max-h-96 lg:max-h-[500px] overflow-y-auto pr-2">
                            @foreach($class['session_details'] as $index => $session)
                            <div class="bg-green-50 border-l-4 border-green-400 p-3 lg:p-4 rounded-r-lg hover:bg-green-100 transition-colors">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex items-center space-x-2">
                                        <span class="bg-green-200 text-green-800 text-xs lg:text-sm px-2 py-1 rounded font-medium">
                                            #{{ $index + 1 }}
                                        </span>
                                        <span class="text-sm lg:text-base font-semibold text-gray-800">
                                            {{ date('d M Y', strtotime($session['date'])) }}
                                        </span>
                                    </div>
                                    <span class="text-xs lg:text-sm text-green-600 bg-green-200 px-2 py-1 rounded-full font-medium">
                                        {{ $session['time'] ?? '-' }}
                                    </span>
                                </div>

                                <div class="space-y-2">
                                    <div class="grid grid-cols-3 gap-2 lg:gap-4">
                                        <div class="text-center bg-white rounded p-2">
                                            <p class="text-lg lg:text-xl font-bold text-green-600">{{ $session['present'] }}</p>
                                            <p class="text-xs text-gray-600">{{ __('admin/class/index.session_item.present') }}</p>
                                        </div>
                                        <div class="text-center bg-white rounded p-2">
                                            <p class="text-lg lg:text-xl font-bold text-yellow-600">{{ $session['late'] }}</p>
                                            <p class="text-xs text-gray-600">{{ __('admin/class/index.session_item.late') }}</p>
                                        </div>
                                        <div class="text-center bg-white rounded p-2">
                                            <p class="text-lg lg:text-xl font-bold text-red-600">{{ $session['absent'] }}</p>
                                            <p class="text-xs text-gray-600">{{ __('admin/class/index.session_item.absent') }}</p>
                                        </div>
                                    </div>

                                    @if(!empty($session['note']))
                                    <div class="mt-3 p-3 bg-white border border-green-200 rounded-lg">
                                        <p class="text-xs lg:text-sm text-gray-700">
                                            <span class="font-medium text-gray-800">Catatan:</span> 
                                            "{{ $session['note'] }}"
                                        </p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 lg:py-12">
                            <svg class="mx-auto h-12 w-12 lg:h-16 lg:w-16 text-green-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm lg:text-base text-gray-500 mb-1">{{ __('admin/class/index.session_none.title') }}</p>
                            <p class="text-xs lg:text-sm text-gray-400">{{ __('admin/class/index.session_none.subtitle') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="space-y-6">
                
                <!-- Cancelled Classes Section -->
                <div class="bg-white rounded-lg shadow-sm p-4 lg:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <span class="inline-block w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                            <h3 class="text-sm lg:text-base font-medium text-gray-700">{{ __('admin/class/index.cancelled.title') }}</h3>
                        </div>
                        <span class="bg-red-100 text-red-800 text-xs lg:text-sm px-2 lg:px-3 py-1 rounded-full font-medium">
                            {{ count($cancelledClasses) }} {{ __('admin/class/index.minimum_sessions_unit.session') }}
                        </span>
                    </div>
                    
                    @if(count($cancelledClasses) > 0)
                        <div class="space-y-3 max-h-64 lg:max-h-80 overflow-y-auto pr-2">
                            @foreach($cancelledClasses as $index => $cancelled)
                            <div class="bg-red-50 border-l-4 border-red-400 p-3 rounded-r-lg hover:bg-red-100 transition-colors">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-center space-x-2">
                                        <span class="bg-red-200 text-red-800 text-xs px-2 py-1 rounded font-medium">
                                            #{{ $index + 1 }}
                                        </span>
                                        <span class="text-sm font-semibold text-gray-800">
                                            {{ date('d M Y', strtotime($cancelled['date'])) }}
                                        </span>
                                    </div>
                                    <span class="text-xs text-red-600 bg-red-200 px-2 py-1 rounded-full font-medium">
                                        {{ $cancelled['time'] }}
                                    </span>
                                </div>
                                
                                <div class="space-y-2">
                                    <p class="text-xs text-gray-600 flex items-center">
                                        <svg class="w-3 h-3 mr-1 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ __('admin/class/index.cancelled.message') }}
                                    </p>
                                    
                                    @if($cancelled['note'])
                                    <div class="mt-2 p-2 bg-white border border-red-200 rounded">
                                        <p class="text-xs text-gray-700">
                                            <span class="font-medium text-gray-800">Catatan:</span> 
                                            "{{ $cancelled['note'] }}"
                                        </p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <svg class="mx-auto h-8 w-8 lg:h-10 lg:w-10 text-green-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm text-gray-500 mb-1">{{ __('admin/class/index.cancelled.empty.title') }}</p>
                            <p class="text-xs text-gray-400">{{ __('admin/class/index.cancelled.empty.subtitle') }}</p>
                        </div>
                    @endif
                </div>

                <!-- Notes -->
                @if(count($class['notes']) > 0)
                <div class="bg-white rounded-lg shadow-sm p-4 lg:p-6">
                    <h3 class="text-sm lg:text-base font-medium text-gray-700 mb-3">{{ __('admin/class/index.notes.title') }}</h3>
                    <div class="space-y-3">
                        @foreach($class['notes']->take(3) as $note)
                        <div class="bg-gray-50 p-3 rounded-lg border-l-4 border-blue-400">
                            <p class="text-sm text-gray-600">{{ $note }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>

        <!-- Class Photos -->
        @if(count($class['class_photos']) > 0)
        <div class="bg-white rounded-lg shadow-sm p-4 lg:p-6 mb-6" x-data="{ open: false, imgSrc: '' }">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg lg:text-xl font-semibold text-gray-800">{{ __('admin/class/index.photos.title') }}</h4>
                <span class="text-sm text-gray-500">{{ __('admin/class/index.photos.hint') }}</span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                @foreach($class['class_photos'] as $photo)
                <div 
                    class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-all duration-200 cursor-pointer transform hover:scale-105"
                    @click="open = true; imgSrc = '{{ asset('storage/' . $photo) }}'">
                    <div class="aspect-square bg-gray-200">
                        <img src="{{ asset('storage/' . $photo) }}" 
                            alt="Foto Kelas" 
                            class="w-full h-full object-cover">
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Modal -->
            <div x-show="open" 
                class="fixed inset-0 bg-black/80 flex items-center justify-center z-50 p-4"
                x-transition
                @click.self="open = false"
                @keydown.escape.window="open = false">
                <div class="max-w-4xl w-full">
                    <img :src="imgSrc" alt="Foto Kelas" class="w-full h-auto rounded-lg shadow-2xl">
                    <div class="text-center mt-4">
                        <button class="px-6 py-2 bg-white rounded-lg shadow hover:bg-gray-100 transition-colors font-medium" @click="open = false">
                            {{ __('admin/class/index.photos.close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@extends('layouts.student')

@section('title', __('student/schedule/schedule.page_title', ['site' => 'EMindTutor']))

@section('content')
<div class="min-h-screen bg-gradient-to-br sm:px-6 lg:px-8">
    <div class="mx-auto">
        
        <!-- Header -->
        <div class="mb-6 lg:mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="p-2 bg-indigo-100 rounded-xl">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="m19,4h-2v-2h-2v2h-6v-2h-2v2h-2c-1.1,0-2,.9-2,2v14c0,1.1.9,2,2,2h14c1.1,0,2-.9,2-2V6c0-1.1-.9-2-2-2ZM5,20v-12h14v-2,14s-14,0-14,0Z"></path><path d="M7 11H17V13H7z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900">{{ __('student/schedule/schedule.header_title') }}</h1>
                </div>
                <p class="text-gray-600 text-sm sm:text-base">{{ __('student/schedule/schedule.header_subtitle') }}</p>
        </div>

        <!-- Mobile View (sm and below) -->
        <div class="block lg:hidden">
            <div class="space-y-4">
                @foreach(array_keys(__('student/schedule/schedule.days_full')) as $dayIndex => $day)
                <div class="bg-white/80 backdrop-blur-sm border border-white/20 rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-500 to-blue-600 px-4 py-3">
                        <h3 class="text-white font-bold text-lg">
                            {{ __('student/schedule/schedule.days_full')[$day] }}
                            <span class="text-indigo-200 text-sm ml-2">
                                {{ __('student/schedule/schedule.days_abbr')[$day] }}
                            </span>
                        </h3>
                    </div>
                    
                    <div class="p-4 space-y-3">
                        @php
                            $dayHasSchedule = false;
                            foreach(['Morning', 'Afternoon', 'Night'] as $timeSlot) {
                                if(isset($weeklySchedule[$timeSlot][$day]) && count($weeklySchedule[$timeSlot][$day]) > 0) {
                                    $dayHasSchedule = true;
                                    break;
                                }
                            }
                        @endphp

                        @if($dayHasSchedule)
                            @foreach(array_keys(__('student/schedule/schedule.time_slots')) as $timeSlot)
                                @if(isset($weeklySchedule[$timeSlot][$day]) && count($weeklySchedule[$timeSlot][$day]) > 0)
                                <div class="border-l-4 border-gray-300 pl-3 mb-4">
                                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                                        {{ __('student/schedule/schedule.time_slots')[$timeSlot] }}
                                    </div>
                                    
                                    @foreach($weeklySchedule[$timeSlot][$day] as $schedule)
                                    <div class="mb-3 p-4 rounded-xl border shadow-sm bg-gradient-to-r from-blue-50 to-blue-100 border-blue-200">
                                        
                                        <!-- Course Info -->
                                        <div class="space-y-2">
                                            <h4 class="font-bold text-gray-900 text-base">{{ $schedule['course_name'] }}</h4>
                                            <!-- Time Range -->
                                                <div class="font-medium text-xs mb-2">
                                                    {{ $schedule['time_range'] }}
                                                </div>
                                            
                                            <!-- Teacher -->
                                            <div class="mt-3 pt-3 border-t border-white/50">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" 
                                                        fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5m0-8c1.65 0 3 1.35 3 3s-1.35 3-3 3-3-1.35-3-3 1.35-3 3-3M12 14c-4.42 0-8 3.58-8 8h16c0-4.42-3.58-8-8-8"></path>
                                                    </svg>
                                                    <span class="font-medium text-gray-700 text-sm">{{ $schedule['teacher_name'] }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            @endforeach
                        @else
                            <div class="text-center py-8">
                                <div class="inline-flex items-center justify-center w-12 h-12 bg-gray-100 rounded-full mb-3">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-500 text-sm">{{ __('student/schedule/schedule.no_classes_today') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Tablet and Desktop View (lg and above) -->
        <div class="hidden lg:block">
            <div class="bg-white/80 backdrop-blur-sm border border-white/20 rounded-3xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-full">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-24 bg-gray-200/50">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ __('student/schedule/schedule.table_time') }}
                                    </div>
                                </th>
                                @foreach(array_keys(__('student/schedule/schedule.days_full')) as $dayIndex => $day)
                                <th class="px-4 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider min-w-40">
                                    <div class="space-y-1">
                                        <div>{{ __('student/schedule/schedule.days_abbr')[$day] }}</div>
                                        <div class="text-xs text-gray-500 normal-case">{{ __('student/schedule/schedule.days_full')[$day] }}</div>
                                    </div>
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach(['Morning', 'Afternoon', 'Night'] as $timeSlot)
                            <tr class="min-h-32">
                                <td class="px-4 py-6 whitespace-nowrap text-sm font-bold text-gray-700 bg-gray-50/50 align-top border-r border-gray-200">
                                    <div class="flex items-center gap-2">
                                        @if($timeSlot === 'Morning')
                                            <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                            </svg>
                                            {{ __('student/schedule/schedule.time_slots')['Morning'] }}
                                        @elseif($timeSlot === 'Afternoon')
                                            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                            </svg>
                                            {{ __('student/schedule/schedule.time_slots')['Afternoon'] }}
                                        @else
                                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                            </svg>
                                            {{ __('student/schedule/schedule.time_slots')['Night'] }}
                                        @endif
                                    </div>
                                </td>
                                @foreach(array_keys(__('student/schedule/schedule.days_full')) as $day)
                                <td class="px-1 py-2 align-top border-r border-gray-100 last:border-r-0">
                                    @if(isset($weeklySchedule[$timeSlot][$day]) && count($weeklySchedule[$timeSlot][$day]) > 0)
                                        <div class="space-y-2">
                                            @foreach($weeklySchedule[$timeSlot][$day] as $schedule)
                                            <div class="p-3 rounded-xl border-l-4 shadow-sm hover:shadow-md transition-all duration-200 bg-gradient-to-r from-blue-50 to-blue-100 border-blue-400 hover:from-blue-100 hover:to-blue-200">
                                                
                                                <!-- Course Name -->
                                                <div class="font-bold text-gray-900 text-sm mb-1">
                                                    {{ $schedule['course_name'] }}
                                                </div>
                        
                                                <!-- Time Range -->
                                                <div class="font-medium text-xs mb-2">
                                                    {{ $schedule['time_range'] }}
                                                </div>
                                                
                                                <!-- Teacher -->
                                                <div class="mt-2 pt-2 border-t border-white/50">
                                                    <div class="flex items-center gap-1 text-xs text-gray-600 mb-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" 
                                                            fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5m0-8c1.65 0 3 1.35 3 3s-1.35 3-3 3-3-1.35-3-3 1.35-3 3-3M12 14c-4.42 0-8 3.58-8 8h16c0-4.42-3.58-8-8-8"></path>
                                                        </svg>
                                                        {{ $schedule['teacher_name'] }}
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        @if(collect($weeklySchedule)->flatten(2)->isEmpty())
        <div class="bg-white/80 backdrop-blur-sm border border-white/20 rounded-2xl sm:rounded-3xl shadow-lg p-8 sm:p-12 text-center mt-6">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('student/schedule/schedule.no_schedule_title') }}</h3>
            <p class="text-gray-500 mb-4">{{ __('student/schedule/schedule.no_schedule_message') }}</p>
            <p class="text-gray-400 text-sm">{{ __('student/schedule/schedule.contact_admin') }}</p>
        </div>
        @endif

    </div>
</div>
@endsection
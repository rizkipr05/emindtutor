@extends('layouts.teacher')

@section('title', __('teacher/attendance.page_title'))

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">{{ __('teacher/attendance.heading') }}</h1>
    <p class="text-gray-600 mt-2">{{ __('teacher/attendance.subheading') }}</p>
    </div>

    @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($classSchedules as $schedule)
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 
                @if($schedule->current_status === 'ongoing') border-green-500
                @elseif($schedule->current_status === 'upcoming') border-yellow-500
                @elseif($schedule->current_status === 'finished') border-gray-400
                @else border-gray-300
                @endif">
                
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ $schedule->course ? $schedule->nama_kelas : 'Course tidak dijumpai' }}
                    </h3>
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                        @if($schedule->current_status === 'ongoing') bg-green-100 text-green-800
                        @elseif($schedule->current_status === 'upcoming') bg-yellow-100 text-yellow-800
                        @elseif($schedule->current_status === 'finished') bg-gray-100 text-gray-800
                        @else bg-gray-100 text-gray-600
                        @endif">
                        @if($schedule->current_status === 'ongoing') {{ __('teacher/attendance.status_ongoing') }}
                        @elseif($schedule->current_status === 'upcoming') {{ __('teacher/attendance.status_upcoming') }}
                        @elseif($schedule->current_status === 'finished') {{ __('teacher/attendance.status_finished') }}
                        @else {{ __('teacher/attendance.status_not_today') }}
                        @endif
                        
                    </span>
                </div>

                <!-- Schedule Days -->
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">{{ __('teacher/attendance.schedule_label') }}</p>

                    @foreach($schedule->classScheduleDays as $day)
                        @php
                            // Hitung end_time = jam_mulai + durasi_menit
                            $jamMulai = Carbon\Carbon::parse($day->jam_mulai);
                            // Perbaikan ada di baris bawah ini:
                            $endTime = $jamMulai->copy()->addMinutes((int) $day->durasi_menit);
                        @endphp

                        <div class="flex items-center text-sm text-gray-700 mb-1">
                            @php $dayKey = 'teacher/schedule.days_full.' . $day->hari; @endphp
                            <span class="capitalize font-medium">
                                @if(
                                    \Illuminate\Support\Facades\Lang::has($dayKey)
                                )
                                    {{ __($dayKey) }}
                                @else
                                    {{ ucfirst($day->hari) }}
                                @endif
                            </span>
                            <span class="mx-2">:</span>

                            <span>
                                {{ $jamMulai->format('H:i') }} - {{ $endTime->format('H:i') }}
                            </span>

                            <span class="text-gray-500 ml-2">({{ __('teacher/attendance.duration_minutes', ['minutes' => $day->durasi_menit]) }})</span>
                        </div>
                    @endforeach
                </div>

                <!-- Course Details -->
                <div class="text-sm text-gray-600 mb-4">
                        @if($schedule->course)
                        <p><span class="font-medium">{{ __('teacher/attendance.type_label') }}:</span> {{ __('teacher/attendance.teaching_type.' . $schedule->course->jenis) }}</p>
                        <p><span class="font-medium">{{ __('teacher/attendance.mode_label') }}:</span> {{ __('teacher/attendance.teaching_mode.' . $schedule->course->teaching_mode) }}</p>
                    @else
                        <p class="text-red-600"><span class="font-medium">{{ __('teacher/attendance.error_label') }}:</span> {{ __('teacher/attendance.data_course_not_found') }}</p>
                    @endif
                </div>

                <!-- Teacher Status -->
                @if($schedule->current_status === 'ongoing')
                    <div class="mb-4 p-2 rounded {{ $schedule->teacher_checked_in ? 'bg-green-50 border border-green-200' : 'bg-yellow-50 border border-yellow-200' }}">
                        <p class="text-sm {{ $schedule->teacher_checked_in ? 'text-green-700' : 'text-yellow-700' }}">
                            <i class="fas {{ $schedule->teacher_checked_in ? 'fa-check-circle' : 'fa-clock' }}"></i>
                            {{ $schedule->teacher_checked_in ? __('teacher/attendance.teacher_checked_in') : __('teacher/attendance.teacher_not_checked_in') }}
                        </p>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex space-x-2">
                        <a href="{{ route('teacher.attendance.take', $schedule->id) }}" 
                           class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg transition duration-200">
                            <i class="fas fa-user-check mr-2"></i>{{ __('teacher/attendance.take_attendance') }}
                        </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('teacher/attendance.no_classes') }}</h3>
                <p class="text-gray-600">{{ __('teacher/attendance.no_classes_detail') }}</p>
            </div>
        @endforelse
    </div>
</div>

<style>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');
</style>
@endsection
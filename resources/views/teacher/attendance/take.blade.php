@extends('layouts.teacher')

@section('title', __('teacher/attendance.take_title', ['class' => $classSchedule->course->localized_name]))

@section('content')
<div class="max-w-6xl mx-auto" x-data="attendanceManager()" x-init="init()">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $classSchedule->course->localized_name }}</h1>
                <p class="text-gray-600 mt-1">{{ __('teacher/attendance.attendance_date', ['date' => Carbon\Carbon::now()->locale(app()->getLocale() === 'en' ? 'en' : 'ms')->translatedFormat('d M Y')]) }}</p>
            </div>
            <a href="{{ route('teacher.attendance.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>{{ __('teacher/attendance.back') }}
            </a>
            @if($teacherAttendance && $teacherAttendance->check_in)
                <a href="{{ route('teacher.teaching-reports.create', ['classSchedule' => $classSchedule, 'date' => now('Asia/Kuala_Lumpur')->toDateString()]) }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-file-alt mr-2"></i>{{ __('reports.teaching.open') }}
                </a>
            @endif
        </div>
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

    <!-- Attendance Identity Card -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
            <div>
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                        <i class="fas fa-fingerprint text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">{{ __('teacher/attendance.attendance_identity_title') }}</h2>
                        <p class="text-gray-600 mt-1">
                            @if($teacherAttendance && $teacherAttendance->check_in)
                                {{ __('teacher/attendance.clock_in_label') }}: {{ \Carbon\Carbon::parse($teacherAttendance->check_in)->format('H:i') }}
                                @if($teacherAttendance->check_out)
                                    | {{ __('teacher/attendance.clock_out_label') }}: {{ \Carbon\Carbon::parse($teacherAttendance->check_out)->format('H:i') }}
                                @endif
                            @else
                                {{ __('teacher/attendance.not_checked_in') }}
                            @endif
                        </p>
                    </div>
                </div>

                <div class="mt-5 space-y-4">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ __('teacher/attendance.gps_status_label') }}</p>
                                <p class="text-xs text-slate-500 mt-1" x-text="gpsMessage"></p>
                            </div>
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold"
                                  :class="gpsBadgeClass"
                                  x-text="gpsStatusLabel"></span>
                        </div>

                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            <div class="rounded-xl bg-white p-3 border border-slate-200">
                                <p class="text-xs uppercase tracking-wide text-slate-500">{{ __('teacher/attendance.latitude_label') }}</p>
                                <p class="mt-1 text-sm font-semibold text-slate-800" x-text="latitudeText"></p>
                            </div>
                            <div class="rounded-xl bg-white p-3 border border-slate-200">
                                <p class="text-xs uppercase tracking-wide text-slate-500">{{ __('teacher/attendance.longitude_label') }}</p>
                                <p class="mt-1 text-sm font-semibold text-slate-800" x-text="longitudeText"></p>
                            </div>
                        </div>

                        <div class="mt-4 space-y-3">
                            <label class="flex items-start gap-3 rounded-xl bg-white p-3 border border-slate-200">
                                <input type="checkbox" x-model="weakGpsOutsideArea" class="mt-1 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                <span>
                                    <span class="block text-sm font-medium text-slate-800">{{ __('teacher/attendance.weak_gps_checkbox') }}</span>
                                    <span class="block text-xs text-slate-500">{{ __('teacher/attendance.weak_gps_checkbox_help') }}</span>
                                </span>
                            </label>

                            <label class="flex items-start gap-3 rounded-xl bg-white p-3 border border-slate-200">
                                <input type="checkbox" x-model="missedClockAction" class="mt-1 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                <span>
                                    <span class="block text-sm font-medium text-slate-800">{{ __('teacher/attendance.missed_clock_checkbox') }}</span>
                                    <span class="block text-xs text-slate-500">{{ __('teacher/attendance.missed_clock_checkbox_help') }}</span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <p class="text-sm font-semibold text-slate-800 mb-3">{{ __('teacher/attendance.gps_map_label') }}</p>
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
                    <template x-if="mapUrl">
                        <iframe :src="mapUrl"
                                class="h-72 w-full"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </template>
                    <template x-if="!mapUrl">
                        <div class="h-72 flex flex-col items-center justify-center text-center p-6">
                            <i class="fas fa-map-marker-alt text-3xl text-slate-400 mb-3"></i>
                            <p class="text-sm font-medium text-slate-700">{{ __('teacher/attendance.map_waiting_title') }}</p>
                            <p class="text-xs text-slate-500 mt-1">{{ __('teacher/attendance.map_waiting_detail') }}</p>
                        </div>
                    </template>
                </div>

                <div class="mt-4 flex flex-wrap gap-3">
                    @if(!$teacherAttendance || !$teacherAttendance->check_in)
                        <button @click="checkIn()"
                                :disabled="loading"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-white font-semibold transition hover:bg-emerald-700 disabled:bg-slate-400">
                            <i class="fas fa-right-to-bracket"></i>
                            <span x-text="loading ? '{{ __('teacher/attendance.processing') }}' : '{{ __('teacher/attendance.clock_in_button') }}'"></span>
                        </button>
                    @elseif(!$teacherAttendance->check_out)
                        <button @click="showCheckoutModal = true"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-rose-600 px-5 py-3 text-white font-semibold transition hover:bg-rose-700">
                            <i class="fas fa-right-from-bracket"></i>
                            <span>{{ __('teacher/attendance.clock_out_button') }}</span>
                        </button>
                    @else
                        <span class="inline-flex items-center rounded-xl bg-slate-100 px-5 py-3 text-slate-600 font-medium">
                            <i class="fas fa-check-circle mr-2"></i>{{ __('teacher/attendance.class_finished') }}
                        </span>
                    @endif

                    <button type="button"
                            @click="refreshLocation()"
                            :disabled="loadingLocation"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 px-5 py-3 text-slate-700 font-medium transition hover:bg-slate-50 disabled:text-slate-400">
                        <i class="fas fa-location-crosshairs"></i>
                        <span x-text="loadingLocation ? '{{ __('teacher/attendance.detecting_location') }}' : '{{ __('teacher/attendance.refresh_location_button') }}'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    @if($teacherAttendance && $teacherAttendance->check_in)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Student Attendance Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">
                        <i class="fas fa-users mr-2 text-blue-600"></i>{{ __('teacher/attendance.students_list_title') }}
                    </h2>

                    @if($activeStudents->count() > 0)
                        <form action="{{ route('teacher.attendance.store', $classSchedule->id) }}" method="POST" enctype="multipart/form-data" id="attendanceForm">
                            @csrf

                            <div class="mb-6 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div class="flex items-start justify-between gap-4 flex-wrap">
                                    <div>
                                        <h3 class="text-sm font-semibold text-slate-800">{{ __('teacher/attendance.students_photo_title') }}</h3>
                                        <p class="text-xs text-slate-500 mt-1">{{ __('teacher/attendance.students_photo_help') }}</p>
                                    </div>
                                    @php
                                        $existingAttendancePhoto = $existingAttendances->first()?->attendance_photo;
                                    @endphp
                                    @if($existingAttendancePhoto)
                                        <a href="{{ asset('storage/' . $existingAttendancePhoto) }}" target="_blank" class="text-sm font-medium text-blue-600 hover:text-blue-700">
                                            {{ __('teacher/attendance.view_uploaded_photo') }}
                                        </a>
                                    @endif
                                </div>

                                <div class="mt-4">
                                    <input type="file"
                                           name="attendance_photo"
                                           accept="image/*"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('attendance_photo')
                                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                @if($existingAttendancePhoto)
                                    <div class="mt-4">
                                        <img src="{{ asset('storage/' . $existingAttendancePhoto) }}"
                                             alt="{{ __('teacher/attendance.all_students_photo_alt') }}"
                                             class="w-full max-h-64 object-cover rounded-xl border border-slate-200">
                                    </div>
                                @endif
                            </div>
                            
                            <div class="space-y-4">
                                @foreach($activeStudents as $student)
                                    @php
                                        $existingAttendance = $existingAttendances[$student->user_id] ?? null;
                                    @endphp
                                    
                                    <div class="border rounded-lg p-4 {{ $existingAttendance ? 'bg-gray-50' : 'bg-white' }}">
                                        <div class="flex items-center justify-between flex-wrap gap-4">
                                            <div class="flex items-center">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($student->user->name) }}&background=3B82F6&color=ffffff" 
                                                     alt="{{ $student->user->name }}" 
                                                     class="w-12 h-12 rounded-full mr-4">
                                                <div>
                                                    <h3 class="font-medium text-gray-800">{{ $student->user->name }}</h3>
                                                    <p class="text-sm text-gray-600">{{ $student->user->email }}</p>
                                                    @if($student->ic_number)
                                                        <p class="text-xs text-gray-500">{{ __('teacher/attendance.ic_label') }}: {{ $student->ic_number }}</p>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Attendance Buttons -->
                                            <div class="flex space-x-2" x-data="{ selected: '{{ $existingAttendance->status ?? '' }}' }">
                                                <!-- Present -->
                                                <label class="cursor-pointer">
                                                    <input type="radio" 
                                                           name="attendances[{{ $student->user_id }}]" 
                                                           value="present"
                                                           x-model="selected"
                                                           class="sr-only"
                                                           {{ $teacherAttendance->check_out ? 'disabled' : '' }}>
                                                    <div class="flex items-center px-4 py-2 rounded-lg border-2 transition-all duration-200 {{ $teacherAttendance->check_out ? 'opacity-50' : '' }}"
                                                         :class="selected === 'present' ? 'border-green-500 bg-green-50 text-green-700' : 'border-gray-300 hover:border-green-400'">
                                                        <i class="fas fa-check mr-2"></i>
                                                        <span class="text-sm font-medium">{{ __('teacher/attendance.present') }}</span>
                                                    </div>
                                                </label>

                                                <!-- Late -->
                                                <label class="cursor-pointer">
                                                    <input type="radio" 
                                                           name="attendances[{{ $student->user_id }}]" 
                                                           value="late"
                                                           x-model="selected"
                                                           class="sr-only"
                                                           {{ $teacherAttendance->check_out ? 'disabled' : '' }}>
                                                    <div class="flex items-center px-4 py-2 rounded-lg border-2 transition-all duration-200 {{ $teacherAttendance->check_out ? 'opacity-50' : '' }}"
                                                         :class="selected === 'late' ? 'border-yellow-500 bg-yellow-50 text-yellow-700' : 'border-gray-300 hover:border-yellow-400'">
                                                        <i class="fas fa-clock mr-2"></i>
                                                        <span class="text-sm font-medium">{{ __('teacher/attendance.late') }}</span>
                                                    </div>
                                                </label>

                                                <!-- Absent -->
                                                <label class="cursor-pointer">
                                                    <input type="radio" 
                                                           name="attendances[{{ $student->user_id }}]" 
                                                           value="absent"
                                                           x-model="selected"
                                                           class="sr-only"
                                                           {{ $teacherAttendance->check_out ? 'disabled' : '' }}>
                                                    <div class="flex items-center px-4 py-2 rounded-lg border-2 transition-all duration-200 {{ $teacherAttendance->check_out ? 'opacity-50' : '' }}"
                                                         :class="selected === 'absent' ? 'border-red-500 bg-red-50 text-red-700' : 'border-gray-300 hover:border-red-400'">
                                                        <i class="fas fa-times mr-2"></i>
                                                        <span class="text-sm font-medium">{{ __('teacher/attendance.absent') }}</span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>

                                        @if($existingAttendance)
                                            <div class="mt-3 pt-3 border-t border-gray-200">
                                                <span class="text-xs text-gray-500">
                                                    {{ __('teacher/attendance.ditandakan') }}: 
                                                    {{ \Carbon\Carbon::parse($existingAttendance->attendance_time)->format('H:i') }},
                                                    {{ \Carbon\Carbon::parse($existingAttendance->attendance_date)->format('d M Y') }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            @if(!$teacherAttendance->check_out)
                                <div class="mt-6">
                                    <button type="submit" 
                                            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg transition duration-200 font-medium">
                                        <i class="fas fa-save mr-2"></i>{{ __('teacher/attendance.save_attendance') }}
                                    </button>
                                </div>
                            @endif
                        </form>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-400 text-4xl mb-4">
                                <i class="fas fa-user-slash"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('teacher/attendance.no_active_students_title') }}</h3>
                            <p class="text-gray-600">{{ __('teacher/attendance.no_active_students_detail') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <!-- Teacher Status Card -->
                @if($teacherAttendance)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-clock mr-2 text-green-600"></i>{{ __('teacher/attendance.teacher_status_title') }}
                        </h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">{{ __('teacher/attendance.check_in_label') }}:</span>
                                <span class="font-medium text-green-600">{{ \Carbon\Carbon::parse($teacherAttendance->check_in)->format('H:i') }}</span>
                            </div>
                            
                            @if($teacherAttendance->check_out)
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">{{ __('teacher/attendance.check_out_label') }}:</span>
                                    <span class="font-medium text-red-600">{{ \Carbon\Carbon::parse($teacherAttendance->check_out)->format('H:i') }}</span>
                                </div>
                                
                                @if($teacherAttendance->class_photo)
                                    <div class="mt-4">
                                        <p class="text-sm font-medium text-gray-700 mb-2">{{ __('teacher/attendance.class_photo') }}:</p>
                                        <img src="{{ asset('storage/' . $teacherAttendance->class_photo) }}" 
                                             alt="Class Photo" 
                                             class="w-full rounded-lg border">
                                    </div>
                                @endif

                                @if($teacherAttendance->note)
                                    <div class="mt-4">
                                        <p class="text-sm font-medium text-gray-700 mb-2">{{ __('teacher/attendance.teacher_review') }}:</p>
                                        <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">{{ $teacherAttendance->note }}</p>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Class Info Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-info-circle mr-2 text-blue-600"></i>{{ __('teacher/attendance.class_info_title') }}
                        </h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('teacher/attendance.class_type') }}:</span>
                            <span class="font-medium">{{ ucfirst($classSchedule->course->jenis) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('teacher/attendance.duration') }}:</span>
                            <span class="font-medium">
                                @if(isset($scheduleDayToday) && $scheduleDayToday)
                                    {{ __('teacher/attendance.duration_minutes', ['minutes' => $scheduleDayToday->durasi_menit]) }}
                                @else
                                    {{ $classSchedule->course->sessions_duration }} {{ __('teacher/attendance.duration_unit.' . $classSchedule->course->sessions_duration_unit) }}
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('teacher/attendance.student_amount') }}:</span>
                            <span class="font-medium">{{ __('teacher/attendance.student_count', ['count' => $activeStudents->count()]) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Attendance Summary -->
                @if($existingAttendances->count() > 0)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-chart-pie mr-2 text-purple-600"></i>{{ __('teacher/attendance.summary') }}
                        </h3>
                        
                        @php
                            $presentCount = $existingAttendances->where('status', 'present')->count();
                            $lateCount = $existingAttendances->where('status', 'late')->count();
                            $absentCount = $existingAttendances->where('status', 'absent')->count();
                        @endphp
                        
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                    <span class="text-gray-600">{{ __('teacher/attendance.present') }}</span>
                                </div>
                                <span class="font-medium">{{ $presentCount }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                                    <span class="text-gray-600">{{ __('teacher/attendance.late') }}</span>
                                </div>
                                <span class="font-medium">{{ $lateCount }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                    <span class="text-gray-600">{{ __('teacher/attendance.absent') }}</span>
                                </div>
                                <span class="font-medium">{{ $absentCount }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @else
        <!-- Teacher Must Check-in First -->
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <div class="text-gray-400 text-6xl mb-4">
                <i class="fas fa-sign-in-alt"></i>
            </div>
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">{{ __('teacher/attendance.teacher_checkin_required_title') }}</h2>
            <p class="text-gray-600 mb-6">{{ __('teacher/attendance.teacher_checkin_required_msg') }}</p>
            <button @click="checkIn()"
                    :disabled="loading"
                    class="bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white px-8 py-3 rounded-lg transition duration-200 font-medium">
                <i class="fas fa-sign-in-alt mr-2"></i>
                <span x-text="loading ? '{{ __('teacher/attendance.processing') }}' : '{{ __('teacher/attendance.clock_in_button') }}'"></span>
            </button>
        </div>
    @endif

    <!-- Check-out Modal -->
    <div x-show="showCheckoutModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/75 bg-opacity-50 flex items-center justify-center z-50"
         @click.away="showCheckoutModal = false">
        
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('teacher/attendance.check_out_label') }} {{ __('teacher/attendance.class_finished') }}</h3>
            
            <div class="space-y-4">
                <!-- Upload Class Photo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('teacher/attendance.upload_class_photo') }}</label>
                    <input type="file" 
                           x-ref="classPhoto"
                           accept="image/*"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">{{ __('teacher/attendance.photo_help') }}</p>
                </div>
                
                <!-- Teacher Note -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('teacher/attendance.teacher_note_label') }}</label>
                    <textarea x-ref="teacherNote"
                              rows="4" 
                              placeholder="{{ __('teacher/attendance.teacher_note_placeholder') }}"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button @click="showCheckoutModal = false" 
                        class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200">
                    {{ __('teacher/attendance.cancel') }}
                </button>
                <button @click="checkOut()" 
                        :disabled="loading"
                        class="bg-red-600 hover:bg-red-700 disabled:bg-gray-400 text-white px-6 py-2 rounded-lg transition duration-200">
                    <span x-text="loading ? '{{ __('teacher/attendance.processing') }}' : '{{ __('teacher/attendance.check_out_button') }}'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function attendanceManager() {
    return {
        loading: false,
        loadingLocation: false,
        showCheckoutModal: false,
        latitude: null,
        longitude: null,
        accuracy: null,
        gpsStatus: 'loading',
        gpsMessage: '{{ __('teacher/attendance.gps_loading') }}',
        weakGpsOutsideArea: false,
        missedClockAction: false,

        init() {
            this.refreshLocation();
        },

        get latitudeText() {
            return this.latitude !== null ? Number(this.latitude).toFixed(6) : '-';
        },

        get longitudeText() {
            return this.longitude !== null ? Number(this.longitude).toFixed(6) : '-';
        },

        get gpsStatusLabel() {
            if (this.gpsStatus === 'ok') {
                return '{{ __('teacher/attendance.gps_ok') }}';
            }

            if (this.gpsStatus === 'weak') {
                return '{{ __('teacher/attendance.gps_weak') }}';
            }

            if (this.gpsStatus === 'error') {
                return '{{ __('teacher/attendance.gps_unavailable') }}';
            }

            return '{{ __('teacher/attendance.gps_checking') }}';
        },

        get gpsBadgeClass() {
            if (this.gpsStatus === 'ok') {
                return 'bg-emerald-100 text-emerald-700';
            }

            if (this.gpsStatus === 'weak') {
                return 'bg-amber-100 text-amber-700';
            }

            if (this.gpsStatus === 'error') {
                return 'bg-rose-100 text-rose-700';
            }

            return 'bg-slate-200 text-slate-700';
        },

        get mapUrl() {
            if (this.latitude === null || this.longitude === null) {
                return '';
            }

            const lat = Number(this.latitude);
            const lng = Number(this.longitude);
            const delta = 0.005;
            const bbox = `${lng - delta}%2C${lat - delta}%2C${lng + delta}%2C${lat + delta}`;
            return `https://www.openstreetmap.org/export/embed.html?bbox=${bbox}&layer=mapnik&marker=${lat}%2C${lng}`;
        },

        refreshLocation() {
            if (!navigator.geolocation) {
                this.gpsStatus = 'error';
                this.gpsMessage = '{{ __('teacher/attendance.gps_not_supported') }}';
                return;
            }

            if (!window.isSecureContext && window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1') {
                this.gpsStatus = 'error';
                this.gpsMessage = '{{ __('teacher/attendance.gps_https_required') }}';
                return;
            }

            this.loadingLocation = true;
            this.gpsStatus = 'loading';
            this.gpsMessage = '{{ __('teacher/attendance.gps_loading') }}';

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    this.latitude = position.coords.latitude;
                    this.longitude = position.coords.longitude;
                    this.accuracy = position.coords.accuracy;

                    if (position.coords.accuracy <= 50) {
                        this.gpsStatus = 'ok';
                        this.gpsMessage = `{{ __('teacher/attendance.gps_accuracy_label') }}` + ': ' + Math.round(position.coords.accuracy) + 'm';
                    } else {
                        this.gpsStatus = 'weak';
                        this.gpsMessage = `{{ __('teacher/attendance.gps_accuracy_label') }}` + ': ' + Math.round(position.coords.accuracy) + 'm';
                    }

                    this.loadingLocation = false;
                },
                (error) => {
                    this.gpsStatus = 'error';

                    if (error.code === error.PERMISSION_DENIED) {
                        this.gpsMessage = '{{ __('teacher/attendance.gps_permission_denied') }}';
                    } else if (error.code === error.POSITION_UNAVAILABLE) {
                        this.gpsMessage = '{{ __('teacher/attendance.gps_position_unavailable') }}';
                    } else if (error.code === error.TIMEOUT) {
                        this.gpsMessage = '{{ __('teacher/attendance.gps_timeout') }}';
                    } else {
                        this.gpsMessage = '{{ __('teacher/attendance.gps_unknown_error') }}';
                    }

                    this.loadingLocation = false;
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        },

        validateGpsBeforeSubmit() {
            if (this.missedClockAction) {
                return true;
            }

            if (this.latitude === null || this.longitude === null) {
                alert('{{ __('teacher/attendance.gps_required_alert') }}');
                return false;
            }

            if (this.gpsStatus === 'weak' && !this.weakGpsOutsideArea) {
                alert('{{ __('teacher/attendance.gps_weak_alert') }}');
                return false;
            }

            if (this.gpsStatus === 'error') {
                alert('{{ __('teacher/attendance.gps_error_alert') }}');
                return false;
            }

            return true;
        },
        
        async checkIn() {
            if (!this.validateGpsBeforeSubmit()) {
                return;
            }

            this.loading = true;
            try {
                const response = await fetch(`{{ route('teacher.attendance.checkin', $classSchedule->id) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        latitude: this.latitude,
                        longitude: this.longitude,
                        accuracy: this.accuracy,
                        gps_status: this.gpsStatus,
                        gps_weak_outside_area: this.weakGpsOutsideArea,
                        missed_clock_action: this.missedClockAction
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.error || '{{ __('teacher/attendance.generic_error') }}');
                }
            } catch (error) {
                alert('{{ __('teacher/attendance.generic_error_with_message', ['message' => '']) }}' + error.message);
            } finally {
                this.loading = false;
            }
        },
        
        async checkOut() {
            if (!this.validateGpsBeforeSubmit()) {
                return;
            }

            this.loading = true;
            try {
                const formData = new FormData();
                
                if (this.$refs.classPhoto && this.$refs.classPhoto.files[0]) {
                    formData.append('class_photo', this.$refs.classPhoto.files[0]);
                }
                
                if (this.$refs.teacherNote && this.$refs.teacherNote.value) {
                    formData.append('teacher_note', this.$refs.teacherNote.value);
                }

                formData.append('latitude', this.latitude ?? '');
                formData.append('longitude', this.longitude ?? '');
                formData.append('accuracy', this.accuracy ?? '');
                formData.append('gps_status', this.gpsStatus);
                formData.append('gps_weak_outside_area', this.weakGpsOutsideArea ? '1' : '0');
                formData.append('missed_clock_action', this.missedClockAction ? '1' : '0');
                
                formData.append('_token', '{{ csrf_token() }}');
                
                const response = await fetch(`{{ route('teacher.attendance.checkout', $classSchedule->id) }}`, {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showCheckoutModal = false;
                    location.reload();
                } else {
                    alert(data.error || '{{ __('teacher/attendance.generic_error') }}');
                }
            } catch (error) {
                alert('{{ __('teacher/attendance.generic_error_with_message', ['message' => '']) }}' + error.message);
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>

<style>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');
</style>
@endsection

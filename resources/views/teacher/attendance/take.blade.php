@extends('layouts.teacher')

@section('title', __('teacher/attendance.take_title', ['class' => $classSchedule->course->localized_name]))

@section('content')
<div class="max-w-6xl mx-auto" x-data="attendanceManager()">
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

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Student Attendance Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">
                        <i class="fas fa-users mr-2 text-blue-600"></i>{{ __('teacher/attendance.students_list_title') }}
                    </h2>

                    @if($activeStudents->count() > 0)
                        <p class="mb-5 text-sm text-slate-500">Tekan Clock in apabila pelajar hadir. Tekan sekali lagi untuk Clock out. Masa akan direkod secara automatik.</p>
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

                                            <button @click="openCamera('{{ route('teacher.attendance.student.clock', [$classSchedule, $student]) }}', '{{ $existingAttendance ? 'Clock out' : 'Clock in' }}')" {{ $existingAttendance && $existingAttendance->check_out_time ? 'disabled' : '' }} class="inline-flex items-center rounded-lg px-4 py-2 text-sm font-medium {{ $existingAttendance ? 'bg-rose-600 hover:bg-rose-700' : 'bg-emerald-600 hover:bg-emerald-700' }} disabled:bg-slate-300 text-white">
                                                <i class="fas fa-camera mr-2"></i>{{ $existingAttendance ? 'Foto & Clock out' : 'Foto & Clock in' }}
                                            </button>
                                        </div>

                                        @if($existingAttendance)
                                            <div class="mt-3 pt-3 border-t border-gray-200">
                                                <span class="text-xs text-gray-500">
                                                    Clock in: {{ \Carbon\Carbon::parse($existingAttendance->attendance_time)->format('H:i') }} · {{ \Carbon\Carbon::parse($existingAttendance->attendance_date)->format('d M Y') }}
                                                    @if($existingAttendance->check_out_time) · Clock out: {{ \Carbon\Carbon::parse($existingAttendance->check_out_time)->format('H:i') }} @endif
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                        </div>
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

    <div x-show="showCamera" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4">
        <div class="w-full max-w-lg rounded-2xl bg-white p-5 shadow-xl">
            <div class="flex items-center justify-between"><h3 class="text-lg font-bold text-slate-900" x-text="cameraAction"></h3><button @click="closeCamera()" class="text-slate-500 hover:text-slate-800">✕</button></div>
            <p class="mt-1 text-sm text-slate-500">Ambil foto pelajar menggunakan kamera sebelum clocking.</p>
            <div class="mt-4 overflow-hidden rounded-xl bg-slate-900"><video x-ref="cameraVideo" autoplay playsinline class="max-h-96 w-full"></video></div>
            <p x-show="cameraError" x-text="cameraError" class="mt-3 text-sm text-red-600"></p>
            <div class="mt-5 flex justify-end gap-3"><button @click="closeCamera()" class="rounded-lg border px-4 py-2">Batal</button><button @click="captureAndClock()" :disabled="cameraBusy" class="rounded-lg bg-teal-600 px-4 py-2 font-medium text-white disabled:bg-slate-400"><span x-text="cameraBusy ? 'Menyimpan…' : 'Ambil Foto & Simpan'"></span></button></div>
        </div>
    </div>
</div>

<script>
function attendanceManager() {
    return {
        loading: false,
        showCamera: false,
        cameraAction: '',
        cameraUrl: '',
        cameraStream: null,
        cameraBusy: false,
        cameraError: '',

        async openCamera(url, action) {
            this.cameraUrl = url;
            this.cameraAction = action;
            this.cameraError = '';
            this.showCamera = true;
            try {
                this.cameraStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false });
                this.$nextTick(() => { this.$refs.cameraVideo.srcObject = this.cameraStream; });
            } catch (error) {
                this.cameraError = 'Kamera tidak dapat dibuka. Sila benarkan akses kamera pada pelayar.';
            }
        },

        closeCamera() {
            this.cameraStream?.getTracks().forEach((track) => track.stop());
            this.cameraStream = null;
            this.showCamera = false;
        },

        async captureAndClock() {
            if (!this.cameraStream) return;
            this.cameraBusy = true;
            const video = this.$refs.cameraVideo;
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
            const photo = await new Promise((resolve) => canvas.toBlob(resolve, 'image/jpeg', 0.9));
            const formData = new FormData();
            formData.append('attendance_photo', photo, 'attendance.jpg');
            formData.append('_token', '{{ csrf_token() }}');
            try {
                const response = await fetch(this.cameraUrl, { method: 'POST', body: formData, headers: { Accept: 'text/html' } });
                if (!response.ok) throw new Error('Gagal menyimpan clocking.');
                location.reload();
            } catch (error) {
                this.cameraError = error.message;
            } finally {
                this.cameraBusy = false;
            }
        }
    }
}
</script>

<style>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');
[x-cloak] { display: none !important; }
</style>
@endsection

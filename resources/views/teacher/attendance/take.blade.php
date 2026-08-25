@extends('layouts.teacher')

@section('title', __('teacher/attendance.take_title', ['class' => $classSchedule->course->localized_name]))

@section('content')
<div class="w-full" x-data="attendanceManager()">
    @php
        $isCheckedIn = (bool) $teacherAttendance?->check_in;
        $isCheckedOut = (bool) $teacherAttendance?->check_out;
        $presentCount = $existingAttendances->where('status', 'present')->count();
        $lateCount = $existingAttendances->where('status', 'late')->count();
        $absentCount = $existingAttendances->where('status', 'absent')->count();
    @endphp

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <p class="mb-1 text-sm font-semibold uppercase tracking-wide text-teal-600">{{ __('teacher/attendance.attendance_date', ['date' => Carbon\Carbon::now('Asia/Kuala_Lumpur')->locale(app()->getLocale() === 'en' ? 'en' : 'ms')->translatedFormat('d M Y')]) }}</p>
            <h1 class="text-3xl font-bold text-slate-900">{{ $classSchedule->nama_kelas }}</h1>
            <p class="mt-1 text-sm text-slate-500">{{ $classSchedule->course?->localized_name }}</p>
        </div>
        <a href="{{ route('teacher.attendance.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
            <i class="fas fa-arrow-left mr-2"></i>{{ __('teacher/attendance.back') }}
        </a>
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

    <section class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-full {{ $isCheckedOut ? 'bg-slate-100 text-slate-600' : ($isCheckedIn ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600') }}"><i class="fas {{ $isCheckedOut ? 'fa-flag-checkered' : ($isCheckedIn ? 'fa-check-circle' : 'fa-clock') }} text-xl"></i></div>
                <div>
                    <h2 class="font-bold text-slate-900">{{ __('teacher/attendance.teacher_status_title') }}</h2>
                    <p class="text-sm text-slate-500">
                        @if($isCheckedOut) Clock out {{ $teacherAttendance->check_out->format('H:i') }}
                        @elseif($isCheckedIn) Clock in {{ $teacherAttendance->check_in->format('H:i') }}
                        @else {{ __('teacher/attendance.not_checked_in') }}
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                @if(! $isCheckedIn)
                    <button @click="teacherCheckIn()" :disabled="teacherBusy" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 disabled:bg-slate-400"><i class="fas fa-sign-in-alt mr-2"></i><span x-text="teacherBusy ? 'Memproses…' : '{{ __('teacher/attendance.check_in_button') }}'"></span></button>
                @elseif(! $isCheckedOut)
                    <button @click="openTeacherCheckout()" class="rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-rose-700"><i class="fas fa-sign-out-alt mr-2"></i>{{ __('teacher/attendance.check_out_button') }}</button>
                @else
                    <span class="rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-600"><i class="fas fa-check mr-2"></i>{{ __('teacher/attendance.class_finished') }}</span>
                @endif
            </div>
        </div>
        <p x-show="teacherError" x-text="teacherError" class="mt-3 text-sm text-red-600"></p>
    </section>

    <div class="attendance-content-grid">
        <div class="min-w-0">
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-col gap-4 border-b border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">{{ app()->getLocale() === 'en' ? 'Student List' : 'Senarai Pelajar' }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ app()->getLocale() === 'en' ? 'Click View to open student clocking records.' : 'Tekan Lihat untuk buka rekod clocking pelajar.' }}</p>
                    </div>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="search" x-model="search" placeholder="{{ app()->getLocale() === 'en' ? 'Search student name...' : 'Cari nama pelajar...' }}" class="w-full rounded-lg border-slate-300 py-2 pl-9 pr-3 text-sm sm:w-56">
                    </div>
                </div>

                @if($activeStudents->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="border-b border-slate-200 bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="w-12 px-4 py-3">#</th>
                                    <th class="px-4 py-3">{{ app()->getLocale() === 'en' ? 'Student Name' : 'Nama Pelajar' }}</th>
                                    <th class="px-4 py-3">{{ app()->getLocale() === 'en' ? 'Today\'s Status' : 'Status Hari Ini' }}</th>
                                    <th class="px-4 py-3">Last Clocking</th>
                                    <th class="px-4 py-3 text-right">{{ app()->getLocale() === 'en' ? 'Action' : 'Tindakan' }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($activeStudents as $student)
                                    @php
                                        $studentSessions = $existingAttendances->get($student->id) ?? collect();
                                        $openSession = $studentSessions->first(fn ($s) => ! $s->clock_out_at);
                                        $lastSession = $studentSessions->sortByDesc('clock_in_at')->first();
                                        
                                        if ($openSession) {
                                            $statusText = 'Clocked In';
                                            $statusClass = 'bg-emerald-100 text-emerald-800';
                                            $lastClockTime = $openSession->clock_in_at->format('h:i A') . ' (IN)';
                                        } elseif ($lastSession) {
                                            $statusText = 'Clocked Out';
                                            $statusClass = 'bg-rose-100 text-rose-800';
                                            $lastClockTime = $lastSession->clock_out_at ? $lastSession->clock_out_at->format('h:i A') . ' (OUT)' : '-';
                                        } else {
                                            $statusText = app()->getLocale() === 'en' ? 'No Record' : 'Belum Rekod';
                                            $statusClass = 'bg-slate-100 text-slate-600';
                                            $lastClockTime = '-';
                                        }
                                    @endphp
                                    <tr x-show="'{{ strtolower(addslashes($student->user->name)) }}'.includes(search.toLowerCase())" class="hover:bg-slate-50">
                                        <td class="px-4 py-4 text-slate-500">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-4">
                                            <div class="flex items-center gap-3">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($student->user->name) }}&background=3B82F6&color=ffffff" alt="{{ $student->user->name }}" class="h-9 w-9 rounded-full">
                                                <div>
                                                    <p class="font-semibold text-slate-800">{{ $student->user->name }}</p>
                                                    <p class="text-xs text-slate-500">{{ $student->ic_number ?: $student->user->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-slate-600">{{ $lastClockTime }}</td>
                                        <td class="px-4 py-4 text-right">
                                            <a href="{{ route('teacher.attendance.student.clocking', [$classSchedule, $student]) }}" class="inline-flex items-center rounded-lg border border-indigo-200 bg-indigo-50 px-3 py-2 text-xs font-semibold text-indigo-700 hover:bg-indigo-100">
                                                <i class="fas fa-eye mr-1"></i>{{ app()->getLocale() === 'en' ? 'View' : 'Lihat' }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-12 text-center">
                            <div class="text-gray-400 text-4xl mb-4">
                                <i class="fas fa-user-slash"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('teacher/attendance.no_active_students_title') }}</h3>
                            <p class="text-gray-600">{{ __('teacher/attendance.no_active_students_detail') }}</p>
                        </div>
                    @endif
                </div>
            </div>

        <aside class="min-w-0 space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-info-circle mr-2 text-blue-600"></i>{{ __('teacher/attendance.class_info_title') }}
                        </h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between gap-4">
                            <span class="text-gray-600">{{ __('teacher/attendance.class_type') }}:</span>
                            <span class="font-medium">{{ ucfirst($classSchedule->course->jenis) }}</span>
                        </div>
                        <div class="flex justify-between gap-4">
                            <span class="text-gray-600">{{ __('teacher/attendance.duration') }}:</span>
                            <span class="font-medium">
                                @if(isset($scheduleDayToday) && $scheduleDayToday)
                                    {{ __('teacher/attendance.duration_minutes', ['minutes' => $scheduleDayToday->durasi_menit]) }}
                                @else
                                    {{ $classSchedule->course->sessions_duration }} {{ __('teacher/attendance.duration_unit.' . $classSchedule->course->sessions_duration_unit) }}
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between gap-4">
                            <span class="text-gray-600">{{ __('teacher/attendance.student_amount') }}:</span>
                            <span class="font-medium">{{ __('teacher/attendance.student_count', ['count' => $activeStudents->count()]) }}</span>
                        </div>
                    </div>
                </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-chart-pie mr-2 text-purple-600"></i>{{ __('teacher/attendance.summary') }}
                        </h3>
                        
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
        </aside>
        </div>

    <div x-show="showTeacherCheckout" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4">
        <div class="w-full max-w-lg rounded-2xl bg-white p-5 shadow-xl">
            <div class="flex items-center justify-between"><h3 class="text-lg font-bold text-slate-900">{{ __('teacher/attendance.check_out_button') }}</h3><button @click="showTeacherCheckout = false" class="text-slate-500 hover:text-slate-800">✕</button></div>
            <p class="mt-1 text-sm text-slate-500">Muat naik foto dokumentasi kelas sebelum clock out.</p>
            <input x-ref="classPhoto" type="file" accept="image/jpeg,image/png,image/webp" class="mt-4 block w-full text-sm" @change="teacherPhotoError = ''">
            <textarea x-model="teacherNote" rows="3" maxlength="2000" class="mt-4 w-full rounded-lg border-slate-300" placeholder="{{ __('teacher/attendance.teacher_note_placeholder') }}"></textarea>
            <p x-show="teacherPhotoError" x-text="teacherPhotoError" class="mt-2 text-sm text-red-600"></p>
            <div class="mt-5 flex justify-end gap-3"><button @click="showTeacherCheckout = false" class="rounded-lg border px-4 py-2">{{ __('teacher/attendance.cancel') }}</button><button @click="teacherCheckOut()" :disabled="teacherBusy" class="rounded-lg bg-rose-600 px-4 py-2 font-medium text-white disabled:bg-slate-400"><span x-text="teacherBusy ? '{{ __('teacher/attendance.processing') }}' : '{{ __('teacher/attendance.check_out_button') }}'"></span></button></div>
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

    <div x-show="showStatusModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4">
        <div class="w-full max-w-lg rounded-2xl bg-white p-5 shadow-xl">
            <div class="flex items-center justify-between"><h3 class="text-lg font-bold text-slate-900" x-text="statusTitle"></h3><button @click="closeStatusModal()" class="text-slate-500 hover:text-slate-800">✕</button></div>
            <p class="mt-1 text-sm text-slate-500">Masukkan sebab untuk rekod kehadiran <span class="font-semibold" x-text="statusStudent"></span>.</p>
            <textarea x-model="statusNote" rows="4" maxlength="2000" class="mt-4 w-full rounded-lg border-slate-300" placeholder="Contoh: Datang lewat kerana urusan keluarga" required></textarea>
            <p x-show="statusError" x-text="statusError" class="mt-2 text-sm text-red-600"></p>
            <div class="mt-5 flex justify-end gap-3"><button @click="closeStatusModal()" class="rounded-lg border px-4 py-2">Batal</button><button @click="saveStatus()" :disabled="statusBusy" class="rounded-lg px-4 py-2 font-medium text-white disabled:bg-slate-400" :class="statusType === 'late' ? 'bg-amber-500' : 'bg-red-600'"><span x-text="statusBusy ? 'Menyimpan…' : 'Simpan status'"></span></button></div>
        </div>
    </div>
</div>

<script>
function attendanceManager() {
    return {
        loading: false,
        search: '',
        teacherBusy: false,
        teacherError: '',
        teacherPhotoError: '',
        showTeacherCheckout: false,
        teacherNote: '',
        showCamera: false,
        cameraAction: '',
        cameraUrl: '',
        cameraStream: null,
        cameraBusy: false,
        cameraError: '',
        showStatusModal: false,
        statusUrl: '',
        statusType: '',
        statusStudent: '',
        statusNote: '',
        statusBusy: false,
        statusError: '',

        get statusTitle() {
            return this.statusType === 'late' ? 'Tandai Late' : 'Tandai Absent';
        },

        async teacherCheckIn() {
            this.teacherBusy = true;
            this.teacherError = '';
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');

            try {
                const response = await fetch('{{ route('teacher.attendance.checkin', $classSchedule) }}', {
                    method: 'POST',
                    body: formData,
                    headers: { Accept: 'application/json' },
                });
                const data = await response.json();
                if (!response.ok) throw new Error(data.error || 'Gagal clock in.');
                location.reload();
            } catch (error) {
                this.teacherError = error.message;
            } finally {
                this.teacherBusy = false;
            }
        },

        openTeacherCheckout() {
            this.teacherNote = '';
            this.teacherPhotoError = '';
            this.showTeacherCheckout = true;
        },

        async teacherCheckOut() {
            const photo = this.$refs.classPhoto.files[0];
            if (!photo) {
                this.teacherPhotoError = 'Sila muat naik foto kelas sebelum clock out.';
                return;
            }

            this.teacherBusy = true;
            this.teacherPhotoError = '';
            const formData = new FormData();
            formData.append('class_photo', photo);
            formData.append('teacher_note', this.teacherNote.trim());
            formData.append('_token', '{{ csrf_token() }}');

            try {
                const response = await fetch('{{ route('teacher.attendance.checkout', $classSchedule) }}', {
                    method: 'POST',
                    body: formData,
                    headers: { Accept: 'application/json' },
                });
                const data = await response.json();
                if (!response.ok) throw new Error(data.error || 'Gagal clock out.');
                location.reload();
            } catch (error) {
                this.teacherPhotoError = error.message;
            } finally {
                this.teacherBusy = false;
            }
        },

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

        openStatusModal(url, status, student) {
            this.statusUrl = url;
            this.statusType = status;
            this.statusStudent = student;
            this.statusNote = '';
            this.statusError = '';
            this.showStatusModal = true;
        },

        closeStatusModal() {
            this.showStatusModal = false;
        },

        async saveStatus() {
            if (!this.statusNote.trim()) {
                this.statusError = 'Sila masukkan sebab kehadiran.';
                return;
            }
            this.statusBusy = true;
            this.statusError = '';
            const formData = new FormData();
            formData.append('status', this.statusType);
            formData.append('note', this.statusNote.trim());
            formData.append('_token', '{{ csrf_token() }}');
            try {
                const response = await fetch(this.statusUrl, { method: 'POST', body: formData, headers: { Accept: 'text/html' } });
                if (!response.ok) throw new Error('Gagal menyimpan status kehadiran.');
                location.reload();
            } catch (error) {
                this.statusError = error.message;
            } finally {
                this.statusBusy = false;
            }
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

.attendance-content-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr);
    gap: 1.5rem;
}

@media (min-width: 1024px) {
    .attendance-content-grid {
        grid-template-columns: minmax(0, 3fr) minmax(260px, 1fr);
    }
}
</style>
@endsection

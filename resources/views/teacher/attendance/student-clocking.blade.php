@extends('layouts.teacher')

@section('title', 'Kehadiran Student')

@section('content')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<div class="mx-auto max-w-6xl" x-data="studentClocking()" x-init="
    getLocation();
    $watch('openStart', value => { if (value && latitude && longitude) initMap('map-start'); });
    $watch('openEnd', value => { if (value && latitude && longitude) initMap('map-end'); });
">
    @php
        $totalHours = intdiv($totalMinutes, 60);
        $totalRemainder = $totalMinutes % 60;
    @endphp

    <div class="mb-6 flex items-center justify-between gap-4">
        <a href="{{ route('teacher.attendance.take', $classSchedule) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700 hover:text-teal-700"><i class="fas fa-arrow-left"></i> Kembali</a>
        <a href="{{ route('teacher.attendance.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-800">Tutup <i class="fas fa-times ml-1"></i></a>
    </div>

    @if(session('success'))<div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">{{ session('error') }}</div>@endif
    @if($errors->any())<div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">{{ $errors->first() }}</div>@endif

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="p-6">
            <div class="flex items-center gap-4">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($student->user->name) }}&background=0F766E&color=ffffff" alt="{{ $student->user->name }}" class="h-16 w-16 rounded-full ring-4 ring-slate-100">
                <div><h1 class="text-xl font-bold text-slate-900">{{ $student->user->name }}</h1><p class="mt-1 text-sm text-slate-500"><i class="fas fa-calendar-alt mr-1"></i>Tarikh: {{ now('Asia/Kuala_Lumpur')->format('d M Y') }}</p></div>
            </div>

            <div class="mt-6 grid overflow-hidden rounded-xl border border-slate-200 md:grid-cols-2">
                <div class="border-b border-slate-200 p-5 md:border-b-0 md:border-r">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ app()->getLocale() === 'en' ? 'Current Status' : 'Status Terkini' }}</p>
                    <p class="mt-2 text-lg font-bold {{ $openSession ? 'text-emerald-600' : ($sessions->isNotEmpty() ? 'text-rose-600' : 'text-slate-600') }}">
                        @if($openSession)
                            Clocked In
                        @elseif($sessions->isNotEmpty())
                            Clocked Out
                        @else
                            {{ app()->getLocale() === 'en' ? 'Not Clocked In' : 'Belum Clock In' }}
                        @endif
                    </p>
                    <p class="mt-1 text-sm text-slate-500">
                        @if($openSession)
                            {{ app()->getLocale() === 'en' ? 'Since' : 'Sejak' }} {{ $openSession->clock_in_at->format('h:i A') }}
                        @elseif($sessions->isNotEmpty())
                            {{ app()->getLocale() === 'en' ? 'Last clock out at' : 'Clock out terakhir pada' }} {{ $sessions->sortByDesc('clock_out_at')->first()->clock_out_at?->format('h:i A') ?? '-' }}
                        @else
                            {{ app()->getLocale() === 'en' ? 'No active session' : 'Tiada sesi aktif' }}
                        @endif
                    </p>
                </div>
                <div class="p-5">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ app()->getLocale() === 'en' ? 'Action' : 'Tindakan' }}</p>
                    <div class="mt-3 flex flex-wrap gap-3">
                        @if(! $openSession)
                            @php
                                $today = \Carbon\Carbon::today('Asia/Kuala_Lumpur');
                                $hasCompletedSessionToday = $sessions->where('clock_date', $today)->whereNotNull('clock_out_at')->isNotEmpty();
                            @endphp
                            @if($hasCompletedSessionToday)
                                <span class="rounded-full bg-slate-100 px-5 py-3 text-sm font-bold text-slate-500"><i class="fas fa-check-double mr-1"></i> {{ app()->getLocale() === 'en' ? 'Completed Today' : 'Selesai Hari Ini' }}</span>
                            @else
                                <button @click="openClockIn()" class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-5 py-3 text-sm font-bold text-white shadow-sm hover:bg-emerald-700"><i class="fas fa-sign-in-alt"></i> CLOCK IN</button>
                            @endif
                        @endif
                        @if($openSession)
                            <button @click="openEnd = true; getLocation();" class="inline-flex items-center gap-2 rounded-full bg-rose-600 px-5 py-3 text-sm font-bold text-white shadow-sm hover:bg-rose-700"><i class="fas fa-sign-out-alt"></i> CLOCK OUT</button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-4 rounded-lg bg-indigo-50 px-4 py-3 text-sm text-indigo-800"><i class="fas fa-info-circle mr-2"></i>{{ app()->getLocale() === 'en' ? 'Can clock in / out multiple times a day. All records will be saved.' : 'Boleh clock in / out berkali-kali dalam sehari. Semua rekod akan disimpan.' }}</div>
        </div>

        <div class="border-t border-slate-200 p-6"><h2 class="mb-4 text-lg font-bold text-slate-900">{{ app()->getLocale() === 'en' ? 'Today\'s Clocking Records' : 'Rekod Clocking Hari Ini' }}</h2>
            <div class="overflow-x-auto"><table class="min-w-full text-sm"><thead class="bg-slate-50 text-left text-slate-500"><tr><th class="px-4 py-3">#</th><th class="px-4 py-3">Clock In</th><th class="px-4 py-3">Clock Out</th><th class="px-4 py-3">{{ app()->getLocale() === 'en' ? 'Total Time' : 'Jumlah Masa' }}</th><th class="px-4 py-3">{{ app()->getLocale() === 'en' ? 'Created By' : 'Dibuat Oleh' }}</th><th class="px-4 py-3 text-right">{{ app()->getLocale() === 'en' ? 'Action' : 'Tindakan' }}</th></tr></thead><tbody class="divide-y divide-slate-100">
                @forelse($sessions->sortBy('clock_in_at') as $session)
                    @php $minutes = $session->clock_out_at ? $session->clock_in_at->diffInMinutes($session->clock_out_at) : null; @endphp
                    <tr>
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 font-medium">{{ $session->clock_in_at->format('h:i A') }}</td>
                        <td class="px-4 py-3">{{ $session->clock_out_at?->format('h:i A') ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @if($minutes !== null)
                                {{ intdiv($minutes, 60) ? intdiv($minutes, 60) . (app()->getLocale() === 'en' ? 'h ' : 'j ') : '' }}{{ $minutes % 60 }}m
                            @else
                                <span class="rounded bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700">{{ app()->getLocale() === 'en' ? 'Still Clocked In' : 'Masih Clock In' }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ auth()->user()->name }}</td>
                        <td class="px-4 py-3 text-right flex justify-end gap-2">
                            <button @click="openEditModal({{ json_encode([
                                'id' => $session->id,
                                'clock_in_time' => $session->clock_in_at->format('H:i'),
                                'clock_out_time' => $session->clock_out_at?->format('H:i') ?? '',
                                'class_report' => $session->class_report ?? ''
                            ]) }})" class="rounded-lg border border-slate-200 bg-white p-2 text-slate-600 hover:bg-slate-50"><i class="fas fa-edit"></i></button>
                            <form method="POST" action="{{ route('teacher.attendance.student.clocking.destroy', $session) }}" onsubmit="return confirm('{{ app()->getLocale() === 'en' ? 'Are you sure you want to delete this clocking record?' : 'Adakah anda pasti mahu memadam rekod clocking ini?' }}')">
                                @csrf @method('DELETE')
                                <button class="rounded-lg border border-red-200 bg-white p-2 text-red-600 hover:bg-red-50"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty<tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">{{ app()->getLocale() === 'en' ? 'No clocking records today.' : 'Belum ada rekod clocking hari ini.' }}</td></tr>@endforelse
            </tbody></table></div>
            <div class="mt-4 rounded-lg bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700">{{ app()->getLocale() === 'en' ? 'Total Time Today:' : 'Jumlah Masa Hari Ini:' }} <span class="ml-2 text-teal-700">{{ $totalHours ? $totalHours . (app()->getLocale() === 'en' ? 'h ' : 'j ') : '' }}{{ $totalRemainder }}m</span></div>
        </div>
    </section>

    <div x-show="openStart" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-black/60 p-4"><div class="mx-auto my-8 max-w-4xl rounded-2xl bg-white shadow-xl"><div class="flex items-center justify-between border-b p-5"><h2 class="text-lg font-bold">Buat Clock In</h2><button @click="closeClockIn()">✕</button></div><form method="POST" enctype="multipart/form-data" action="{{ route('teacher.attendance.student.clocking.start', [$classSchedule, $student]) }}" class="p-5">@csrf
        <input type="hidden" name="latitude" x-model="latitude"><input type="hidden" name="longitude" x-model="longitude">
        <p class="mb-4 text-sm text-slate-500">Pastikan kamera dan lokasi diaktifkan.</p><div class="grid gap-5 md:grid-cols-2"><div class="rounded-xl border p-4"><h3 class="font-semibold">Tangkap Gambar</h3><div class="relative mt-3 aspect-video overflow-hidden rounded-lg bg-slate-900"><video x-ref="startVideo" x-show="!capturedPhoto" autoplay playsinline muted class="h-full w-full object-cover"></video><img x-show="capturedPhoto" :src="capturedPhoto" alt="Foto clock in" class="h-full w-full object-cover"><div x-show="cameraError" x-text="cameraError" class="absolute inset-x-3 bottom-3 rounded bg-red-600/90 px-3 py-2 text-xs text-white"></div></div><canvas x-ref="startCanvas" class="hidden"></canvas><input x-ref="startPhoto" type="file" name="clock_in_photo" accept="image/*" capture="user" class="mt-3 block w-full text-sm" required @change="previewSelectedPhoto($event)"><div class="mt-3 flex gap-2"><button type="button" @click="startCamera()" class="rounded-lg border px-3 py-2 text-sm bg-white text-slate-700 hover:bg-slate-50 border-slate-300">Buka Kamera</button><button type="button" @click="captureStart()" x-show="cameraReady" class="rounded-lg bg-emerald-600 px-3 py-2 text-sm text-white hover:bg-emerald-700">Ambil Foto</button><button type="button" @click="startCamera()" x-show="capturedPhoto" class="rounded-lg bg-slate-800 px-3 py-2 text-sm text-white hover:bg-slate-900">Ambil Semula</button></div></div><div class="rounded-xl border p-4"><h3 class="font-semibold">Lokasi (GPS)</h3><div class="mt-3 aspect-video rounded-lg bg-slate-100 relative overflow-hidden border border-slate-200"><div id="map-start" class="h-full w-full"></div><div x-show="!latitude || !longitude" class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-emerald-50 text-center text-sm text-emerald-700 p-4"><i class="fas fa-map-marker-alt mb-2 text-2xl animate-bounce"></i><span x-text="locationText"></span></div></div><button type="button" @click="getLocation()" class="mt-3 rounded-lg border px-3 py-2 text-sm bg-white text-slate-700 hover:bg-slate-50 border-slate-300">Muat Semula GPS</button></div></div>
        <label class="mt-5 block text-sm font-semibold">Tempoh Kelas (minit)<select name="planned_duration_minutes" class="mt-2 block w-full rounded-lg border-slate-300"><option value="">Pilih tempoh</option><option value="30">30 minit</option><option value="60">1 jam</option><option value="90">1 jam 30 minit</option><option value="120">2 jam</option></select></label><div class="mt-6 flex justify-end gap-3"><button type="button" @click="closeClockIn()" class="rounded-lg border px-4 py-2">Batal</button><button @click.prevent="submitClockIn($event)" class="rounded-lg bg-emerald-600 px-5 py-2 font-semibold text-white">Sahkan Clock In</button></div>
    </form></div></div>

    @if($openSession)
    <div x-show="openEnd" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-black/60 p-4">
        <div class="mx-auto my-8 max-w-5xl rounded-2xl bg-white shadow-xl">
            <div class="flex items-center justify-between border-b p-5">
                <h2 class="text-xl font-bold text-slate-900">Clock Out & Laporan Kelas</h2>
                <button @click="openEnd = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <form method="POST" enctype="multipart/form-data" action="{{ route('teacher.attendance.student.clocking.end', $openSession) }}" class="p-6">
                @csrf
                <input type="hidden" name="latitude" x-model="latitude">
                <input type="hidden" name="longitude" x-model="longitude">

                <!-- Student Header Info -->
                <div class="mb-6 flex items-center gap-4">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($student->user->name) }}&background=0F766E&color=ffffff" alt="{{ $student->user->name }}" class="h-12 w-12 rounded-full">
                    <div>
                        <h3 class="font-bold text-slate-900">{{ $student->user->name }}</h3>
                        <p class="text-xs text-slate-500">
                            <i class="fas fa-calendar-alt mr-1"></i> Tarikh: {{ $openSession->clock_date->format('d M Y') }} · 
                            <span class="text-emerald-600 font-semibold">Clocked In sejak {{ $openSession->clock_in_at->format('h:i A') }}</span>
                        </p>
                    </div>
                </div>

                <!-- Top Stats Grid -->
                <div class="mb-6 grid grid-cols-3 gap-4 text-center">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Clock In</p>
                        <p class="mt-2 text-lg font-bold text-emerald-600">{{ $openSession->clock_in_at->format('h:i A') }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Jangka Masa</p>
                        <p class="mt-2 text-lg font-bold text-slate-700">
                            @if($openSession->planned_duration_minutes)
                                {{ $openSession->planned_duration_minutes }} min
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Masa Sekarang</p>
                        <p class="mt-2 text-lg font-bold text-indigo-600">{{ now('Asia/Kuala_Lumpur')->format('h:i A') }}</p>
                    </div>
                </div>

                <div class="mb-6 rounded-lg bg-blue-50 p-4 text-sm text-blue-800">
                    <i class="fas fa-info-circle mr-2"></i> Sila lengkapkan laporan kelas dan sahkan clock out.
                </div>

                <!-- Main Form Grid -->
                <div class="grid gap-6 lg:grid-cols-2">
                    <!-- 1. Lokasi GPS -->
                    <div class="rounded-xl border border-slate-200 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-bold text-slate-800"><span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700 mr-2">1</span> Lokasi (GPS)</h4>
                            <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-semibold text-emerald-700"><i class="fas fa-check-circle mr-1"></i> Lokasi Dikesan</span>
                        </div>
                        <div class="aspect-video rounded-lg bg-slate-100 relative overflow-hidden border border-slate-200">
                            <div id="map-end" class="h-full w-full"></div>
                            <div x-show="!latitude || !longitude" class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-emerald-50 text-center text-xs text-emerald-700 p-4">
                                <i class="fas fa-map-marker-alt mb-1 text-xl animate-bounce"></i>
                                <span x-text="locationText"></span>
                            </div>
                        </div>
                        <p class="mt-2 text-[11px] text-slate-500" x-show="latitude && longitude">
                            <i class="fas fa-map-marker-alt text-emerald-600 mr-1"></i> <span x-text="latitude.toFixed(5) + '° N, ' + longitude.toFixed(5) + '° E'"></span>
                        </p>
                    </div>

                    <!-- 2. Laporan Kelas -->
                    <div class="rounded-xl border border-slate-200 p-4">
                        <h4 class="font-bold text-slate-800 mb-3"><span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700 mr-2">2</span> Laporan Kelas</h4>
                        <p class="text-xs text-slate-500 mb-2">Sila tulis laporan ringkas mengenai kelas yang telah dijalankan.</p>
                        <textarea required name="class_report" rows="4" maxlength="1500" class="w-full rounded-lg border-slate-300 text-sm" placeholder="Tulis laporan kelas di sini..."></textarea>
                        
                        <div class="mt-4 rounded-lg bg-slate-50 p-3 text-xs text-slate-600">
                            <p class="font-semibold text-slate-700 mb-1"><i class="fas fa-info-circle mr-1"></i> Contoh Laporan</p>
                            <ul class="list-disc pl-4 space-y-0.5">
                                <li>Bacaan Surah Al-Fatihah</li>
                                <li>Tajwid: Mad Asli</li>
                                <li>Latihan sebutan</li>
                                <li>Ulang kaji bacaan</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3 border-t pt-5">
                    <button type="button" @click="openEnd = false" class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</button>
                    <button class="rounded-xl bg-emerald-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700"><i class="fas fa-check-circle mr-2"></i> Sahkan Clock Out</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <div x-show="openEdit" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-black/60 p-4">
        <div class="mx-auto my-8 max-w-4xl rounded-2xl bg-white shadow-xl">
            <div class="flex items-center justify-between border-b p-5">
                <h2 class="text-xl font-bold text-slate-900">Edit Laporan Kelas</h2>
                <button @click="openEdit = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <form method="POST" :action="'/teacher/attendance/clocking/' + editData.id" class="p-6">
                @csrf
                @method('PUT')

                <!-- Student Header Info -->
                <div class="mb-6 flex items-center gap-4">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($student->user->name) }}&background=0F766E&color=ffffff" alt="{{ $student->user->name }}" class="h-12 w-12 rounded-full">
                    <div>
                        <h3 class="font-bold text-slate-900">{{ $student->user->name }}</h3>
                        <p class="text-xs text-slate-500">
                            <i class="fas fa-calendar-alt mr-1"></i> Tarikh: {{ now('Asia/Kuala_Lumpur')->format('d M Y') }}
                        </p>
                    </div>
                </div>

                <!-- Top Stats Grid -->
                <div class="mb-6 grid grid-cols-3 gap-4 text-center">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Clock In</p>
                        <p class="mt-2 text-lg font-bold text-emerald-600" x-text="formatTime(editData.clock_in_time)"></p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Clock Out</p>
                        <p class="mt-2 text-lg font-bold text-rose-600" x-text="formatTime(editData.clock_out_time) || '-'"></p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Jumlah Masa</p>
                        <p class="mt-2 text-lg font-bold text-indigo-600" x-text="calculateDuration(editData.clock_in_time, editData.clock_out_time)"></p>
                    </div>
                </div>

                <!-- Main Form Grid -->
                <div class="grid gap-6">
                    <!-- Laporan Kelas -->
                    <div class="rounded-xl border border-slate-200 p-4">
                        <h4 class="font-bold text-slate-800 mb-3">Laporan Kelas</h4>
                        <p class="text-xs text-slate-500 mb-2">Sila edit laporan ringkas mengenai kelas yang telah dijalankan.</p>
                        <textarea required name="class_report" rows="6" maxlength="1500" x-model="editData.class_report" class="w-full rounded-lg border-slate-300 text-sm" placeholder="Tulis laporan kelas di sini..."></textarea>
                        
                        <div class="mt-4 rounded-lg bg-slate-50 p-3 text-xs text-slate-600">
                            <p class="font-semibold text-slate-700 mb-1"><i class="fas fa-info-circle mr-1"></i> Contoh Laporan</p>
                            <div class="grid grid-cols-2 gap-4">
                                <ul class="list-disc pl-4 space-y-0.5">
                                    <li>Bacaan Surah Al-Fatihah</li>
                                    <li>Tajwid: Mad Asli</li>
                                    <li>Latihan sebutan</li>
                                </ul>
                                <ul class="list-disc pl-4 space-y-0.5">
                                    <li>Ulang kaji bacaan</li>
                                    <li>Penerangan hukum tajwid</li>
                                    <li>dan lain-lain...</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3 border-t pt-5">
                    <button type="button" @click="openEdit = false" class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</button>
                    <button class="rounded-xl bg-emerald-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700"><i class="fas fa-check-circle mr-2"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function studentClocking() {
    return {
        openStart: false,
        openEnd: false,
        openEdit: false,
        editData: { id: '', clock_in_time: '', clock_out_time: '', class_report: '' },
        latitude: '',
        longitude: '',
        locationText: 'Mengesan lokasi...',
        stream: null,
        cameraReady: false,
        capturedPhoto: '',
        cameraError: '',

        async getLocation() {
            if (!navigator.geolocation) {
                this.locationText = 'GPS tidak disokong oleh pelayar.';
                return;
            }
            this.locationText = 'Mengesan lokasi...';
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    this.latitude = position.coords.latitude;
                    this.longitude = position.coords.longitude;
                    this.locationText = `Lokasi dikesan: ${this.latitude.toFixed(5)}, ${this.longitude.toFixed(5)}`;
                    if (this.openStart) {
                        this.initMap('map-start');
                    }
                    if (this.openEnd) {
                        this.initMap('map-end');
                    }
                },
                () => { this.locationText = 'Lokasi tidak dapat dikesan. Sila benarkan GPS.'; },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        },

        openClockIn() {
            this.openStart = true;
            this.capturedPhoto = '';
            this.cameraError = '';
            this.cameraReady = false;
            this.getLocation();
            this.$nextTick(() => {
                this.startCamera();
                if (this.latitude && this.longitude) {
                    this.initMap('map-start');
                }
            });
        },

        openEditModal(data) {
            this.editData = { ...data };
            this.openEdit = true;
        },

        initMap(elementId) {
            this.$nextTick(() => {
                const container = document.getElementById(elementId);
                if (!container || !this.latitude || !this.longitude) return;
                
                // Clean up existing map instance if any
                if (container._leaflet_id) {
                    container.innerHTML = '';
                    delete container._leaflet_id;
                }

                const map = L.map(elementId).setView([this.latitude, this.longitude], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);
                L.marker([this.latitude, this.longitude]).addTo(map);
            });
        },

        async startCamera() {
            this.stopCamera();
            this.capturedPhoto = '';
            this.cameraError = '';
            this.cameraReady = false;
            try {
                this.stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false });
                const video = this.$refs.startVideo;
                video.srcObject = this.stream;
                await video.play();
                this.cameraReady = true;
            } catch (error) {
                this.cameraError = 'Kamera tidak dapat dibuka. Sila benarkan akses kamera atau pilih fail foto.';
            }
        },

        captureStart() {
            const video = this.$refs.startVideo;
            if (!this.cameraReady || !video.videoWidth || !video.videoHeight) {
                this.cameraError = 'Kamera belum sedia. Sila tunggu sebentar atau buka semula kamera.';
                return;
            }
            const canvas = this.$refs.startCanvas;
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
            canvas.toBlob((blob) => {
                if (!blob) {
                    this.cameraError = 'Foto tidak dapat diambil. Sila cuba semula.';
                    return;
                }
                const file = new File([blob], 'clock-in.jpg', { type: 'image/jpeg' });
                const transfer = new DataTransfer();
                transfer.items.add(file);
                this.$refs.startPhoto.files = transfer.files;
                this.capturedPhoto = URL.createObjectURL(blob);
                this.stopCamera();
            }, 'image/jpeg', 0.9);
        },

        previewSelectedPhoto(event) {
            const file = event.target.files[0];
            if (file) {
                this.capturedPhoto = URL.createObjectURL(file);
                this.stopCamera();
            }
        },

        formatTime(timeStr) {
            if (!timeStr) return '';
            const [hours, minutes] = timeStr.split(':');
            const h = parseInt(hours);
            const ampm = h >= 12 ? 'PM' : 'AM';
            const displayHour = h % 12 || 12;
            return `${displayHour.toString().padStart(2, '0')}:${minutes} ${ampm}`;
        },

        calculateDuration(inTime, outTime) {
            if (!inTime || !outTime) return '-';
            const [inH, inM] = inTime.split(':').map(Number);
            const [outH, outM] = outTime.split(':').map(Number);
            let diffMins = (outH * 60 + outM) - (inH * 60 + inM);
            if (diffMins < 0) diffMins += 24 * 60;
            const h = Math.floor(diffMins / 60);
            const m = diffMins % 60;
            const hourUnit = '{{ app()->getLocale() === 'en' ? 'h ' : 'j ' }}';
            return `${h ? h + hourUnit : ''}${m}m`;
        },

        stopCamera() {
            this.stream?.getTracks().forEach((track) => track.stop());
            this.stream = null;
            this.cameraReady = false;
        },

        closeClockIn() {
            this.stopCamera();
            this.openStart = false;
        },

        submitClockIn(event) {
            if (!this.$refs.startPhoto.files.length) {
                this.cameraError = 'Sila ambil atau pilih foto untuk clock in.';
                return;
            }
            event.target.closest('form').submit();
        },
    };
}
</script>
<style>@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');
[x-cloak]{display:none!important}
#map-start, #map-end {
    height: 100%;
    width: 100%;
    min-height: 150px;
    border-radius: 0.5rem;
}
</style>
@endsection

@extends('layouts.teacher')

@section('title', 'Tambah Jadwal Kelas')

@section('content')
<div class="min-h-screen">
    <div class="mx-auto px-4 lg:px-8">

        @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Ada ralat pada borang</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm p-4 lg:p-6 mb-6">
            <h1 class="text-xl lg:text-2xl font-bold text-gray-800">Tambah Jadwal Kelas</h1>
            <p class="text-sm lg:text-base text-gray-600 mt-1">
                Buat jadwal kelas baru untuk kursus di bawah syarikat anda.
            </p>
            <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                Tetapkan hari dan waktu kelas. Anda boleh tambah lebih daripada satu hari untuk kelas yang sama.
            </div>
        </div>

        <form action="{{ route('teacher.schedule.store') }}" method="POST" id="classForm">
            @csrf

            <div class="bg-white rounded-lg shadow-sm p-4 lg:p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Maklumat Asas</h3>

                <div class="space-y-4">
                    <div>
                        <label for="course_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Kursus <span class="text-red-500">*</span>
                        </label>
                        <select id="course_id"
                                name="course_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                            <option value="">Pilih kursus</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ (string) old('course_id') === (string) $course->id ? 'selected' : '' }}>
                                    {{ $course->nama_kos }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="nama_kelas" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Kelas <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="nama_kelas"
                               name="nama_kelas"
                               value="{{ old('nama_kelas') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>

                    <div>
                        <label for="link_group" class="block text-sm font-medium text-gray-700 mb-2">Link Group</label>
                        <input type="url"
                               id="link_group"
                               name="link_group"
                               value="{{ old('link_group') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea id="notes"
                                  name="notes"
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-4 lg:p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Pola Ulang & Periode</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="recurrence_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Ulangan <span class="text-red-500">*</span>
                        </label>
                        <select id="recurrence_type"
                                name="recurrence_type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                            <option value="weekly" {{ old('recurrence_type', 'weekly') === 'weekly' ? 'selected' : '' }}>Mingguan</option>
                            <option value="monthly" {{ old('recurrence_type') === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Mingguan: setiap hari yang dipilih. Bulanan: minggu ke-n yang sama setiap bulan.</p>
                    </div>

                    <div>
                        <label for="recurrence_start_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Tarikh Mula
                        </label>
                        <input type="date"
                               id="recurrence_start_date"
                               name="recurrence_start_date"
                               value="{{ old('recurrence_start_date', now('Asia/Kuala_Lumpur')->toDateString()) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="recurrence_end_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Tarikh Tamat
                        </label>
                        <input type="date"
                               id="recurrence_end_date"
                               name="recurrence_end_date"
                               value="{{ old('recurrence_end_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-1 text-xs text-gray-500">Kosongkan jika jadwal masih berlanjut.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-4 lg:p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Hari & Waktu Kelas</h3>
                    <button type="button"
                            onclick="addScheduleDay()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Tambah Hari
                    </button>
                </div>

                <div id="scheduleDaysContainer" class="space-y-4">
                    <div class="schedule-day-item border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-medium text-gray-700">Hari #1</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                                <select name="schedule_days[0][hari]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                    <option value="">Pilih hari</option>
                                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $hari)
                                    <option value="{{ $hari }}" {{ old('schedule_days.0.hari') === $hari ? 'selected' : '' }}>
                                        {{ __('teacher/schedule.days_full.' . $hari) }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                                <input type="time" name="schedule_days[0][jam_mulai]" value="{{ old('schedule_days.0.jam_mulai') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Durasi (Minit)</label>
                                <input type="number" name="schedule_days[0][durasi_menit]" value="{{ old('schedule_days.0.durasi_menit', 60) }}" min="15" max="480" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-4 justify-between">
                <button type="button"
                        onclick="window.location='{{ route('teacher.schedule.index') }}'"
                        class="w-full lg:w-auto bg-gray-600 hover:bg-gray-700 text-white py-2 lg:py-3 px-6 lg:px-8 rounded-lg text-sm lg:text-base font-medium transition-colors">
                    Batal
                </button>
                <button type="submit"
                        id="submitBtn"
                        class="w-full lg:w-auto bg-indigo-600 hover:bg-indigo-700 text-white py-2 lg:py-3 px-6 lg:px-8 rounded-lg text-sm lg:text-base font-medium transition-colors">
                    Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let scheduleIndex = 1;
const scheduleDayOptions = `
    <option value="">Pilih hari</option>
    <option value="Monday">{{ __('teacher/schedule.days_full.Monday') }}</option>
    <option value="Tuesday">{{ __('teacher/schedule.days_full.Tuesday') }}</option>
    <option value="Wednesday">{{ __('teacher/schedule.days_full.Wednesday') }}</option>
    <option value="Thursday">{{ __('teacher/schedule.days_full.Thursday') }}</option>
    <option value="Friday">{{ __('teacher/schedule.days_full.Friday') }}</option>
    <option value="Saturday">{{ __('teacher/schedule.days_full.Saturday') }}</option>
    <option value="Sunday">{{ __('teacher/schedule.days_full.Sunday') }}</option>
`;

function addScheduleDay() {
    const container = document.getElementById('scheduleDaysContainer');
    const item = document.createElement('div');
    item.className = 'schedule-day-item border border-gray-200 rounded-lg p-4';
    item.innerHTML = `
        <div class="flex items-center justify-between mb-3">
            <h4 class="font-medium text-gray-700">Hari #${scheduleIndex + 1}</h4>
            <button type="button" onclick="removeScheduleDay(this)" class="text-red-600 hover:text-red-800 text-sm font-medium">
                Hapus
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                <select name="schedule_days[${scheduleIndex}][hari]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    ${scheduleDayOptions}
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                <input type="time" name="schedule_days[${scheduleIndex}][jam_mulai]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Durasi (Minit)</label>
                <input type="number" name="schedule_days[${scheduleIndex}][durasi_menit]" value="60" min="15" max="480" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
        </div>
    `;

    container.appendChild(item);
    scheduleIndex++;
    refreshScheduleHeaders();
}

function removeScheduleDay(button) {
    const items = document.querySelectorAll('.schedule-day-item');
    if (items.length <= 1) {
        alert('Sekurang-kurangnya satu jadual hari diperlukan.');
        return;
    }

    button.closest('.schedule-day-item').remove();
    refreshScheduleHeaders();
}

function refreshScheduleHeaders() {
    document.querySelectorAll('.schedule-day-item').forEach((item, index) => {
        const title = item.querySelector('h4');
        if (title) {
            title.textContent = `Hari #${index + 1}`;
        }
    });
}

document.getElementById('classForm').addEventListener('submit', function () {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Menyimpan...';
});
</script>
@endsection

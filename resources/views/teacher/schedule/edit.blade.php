@extends('layouts.teacher')

@section('content')
<div class="min-h-screen">
    <div class="mx-auto px-4 lg:px-8">
        
        <!-- Error Alert -->
        @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">{{ __('teacher/schedule_edit.errors.title') }}</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <!-- Success Alert -->
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <p class="ml-3 text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
        @endif
        
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm p-4 lg:p-6 mb-6">
            <h1 class="text-xl lg:text-2xl font-bold text-gray-800">{{ __('teacher/schedule_edit.header.title') }}</h1>
            <p class="text-sm lg:text-base text-gray-600 mt-1">
                {{ $classSchedule->course->nama_kos }} - {{ $classSchedule->nama_kelas }}
            </p>
            <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ __('teacher/schedule_edit.intro_notice') }}
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3 mb-6">
            <div class="rounded-2xl border border-sky-100 bg-sky-50 p-4">
                <div class="text-xs font-bold uppercase tracking-wide text-sky-700">1</div>
                <p class="mt-1 text-sm text-gray-700">{{ __('teacher/schedule_edit.steps.one') }}</p>
            </div>
            <div class="rounded-2xl border border-amber-100 bg-amber-50 p-4">
                <div class="text-xs font-bold uppercase tracking-wide text-amber-700">2</div>
                <p class="mt-1 text-sm text-gray-700">{{ __('teacher/schedule_edit.steps.two') }}</p>
            </div>
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-4">
                <div class="text-xs font-bold uppercase tracking-wide text-emerald-700">3</div>
                <p class="mt-1 text-sm text-gray-700">{{ __('teacher/schedule_edit.steps.three') }}</p>
            </div>
        </div>

        <form action="{{ route('teacher.schedule.update', $classSchedule->id) }}" method="POST" id="classForm">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm p-4 lg:p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('teacher/schedule_edit.sections.basic_info') }}</h3>
                
                <div class="space-y-4">
                    <!-- Nama Kelas -->
                    <div>
                        <label for="nama_kelas" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('teacher/schedule_edit.fields.class_name') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="nama_kelas" 
                               name="nama_kelas" 
                               value="{{ old('nama_kelas', $classSchedule->nama_kelas) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama_kelas') border-red-500 @enderror"
                               required>
                        @error('nama_kelas')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Link Group -->
                    <div>
                        <label for="link_group" class="block text-sm font-medium text-gray-700 mb-2">{{ __('teacher/schedule_edit.fields.link_group') }}</label>
                        <input type="url" 
                               id="link_group" 
                               name="link_group" 
                               value="{{ old('link_group', $classSchedule->link_group) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('link_group') border-red-500 @enderror">
                        @error('link_group')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">{{ __('teacher/schedule_edit.fields.notes') }}</label>
                        <textarea id="notes" 
                                  name="notes" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes', $classSchedule->notes) }}</textarea>
                        @error('notes')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
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
                            <option value="weekly" {{ old('recurrence_type', $classSchedule->recurrence_type ?? 'weekly') === 'weekly' ? 'selected' : '' }}>Mingguan</option>
                            <option value="monthly" {{ old('recurrence_type', $classSchedule->recurrence_type) === 'monthly' ? 'selected' : '' }}>Bulanan</option>
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
                               value="{{ old('recurrence_start_date', optional($classSchedule->recurrence_start_date)->toDateString() ?? now('Asia/Kuala_Lumpur')->toDateString()) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="recurrence_end_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Tarikh Tamat
                        </label>
                        <input type="date"
                               id="recurrence_end_date"
                               name="recurrence_end_date"
                               value="{{ old('recurrence_end_date', optional($classSchedule->recurrence_end_date)->toDateString()) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-1 text-xs text-gray-500">Kosongkan jika jadwal masih berlanjut.</p>
                    </div>
                </div>
            </div>

            <!-- Schedule Days -->
            <div class="bg-white rounded-lg shadow-sm p-4 lg:p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">{{ __('teacher/schedule_edit.sections.schedule') }}</h3>
                    <button type="button" 
                            onclick="addScheduleDay()" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        {{ __('teacher/schedule_edit.actions.add_day') }}
                    </button>
                </div>

                <div id="scheduleDaysContainer" class="space-y-4">
                    @forelse($classSchedule->classScheduleDays as $index => $day)
                    <div class="schedule-day-item border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-medium text-gray-700">{{ __('teacher/schedule_edit.schedule_item.title_prefix') }} #{{ $index + 1 }}</h4>
                            @if($index > 0)
                            <button type="button" onclick="removeScheduleDay(this)" class="text-red-600 hover:text-red-800 text-sm font-medium flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                {{ __('teacher/schedule_edit.actions.remove') }}
                            </button>
                            @endif
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('teacher/schedule_edit.fields.day') }}</label>
                                <select name="schedule_days[{{ $index }}][hari]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('schedule_days.'.$index.'.hari') border-red-500 @enderror" required>
                                    <option value="">{{ __('teacher/schedule_edit.select.select_day') }}</option>
                                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $hari)
                                    <option value="{{ $hari }}" {{ old('schedule_days.'.$index.'.hari', $day->hari) === $hari ? 'selected' : '' }}>{{ __('teacher/schedule_edit.day_names.' . strtolower($hari)) }}</option>
                                    @endforeach
                                </select>
                                @error('schedule_days.'.$index.'.hari')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('teacher/schedule_edit.fields.start_time') }}</label>
                                <input type="time" name="schedule_days[{{ $index }}][jam_mulai]" value="{{ old('schedule_days.'.$index.'.jam_mulai', $day->jam_mulai) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('schedule_days.'.$index.'.jam_mulai') border-red-500 @enderror" required>
                                @error('schedule_days.'.$index.'.jam_mulai')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('teacher/schedule_edit.fields.duration_minutes') }}</label>
                                <input type="number" name="schedule_days[{{ $index }}][durasi_menit]" value="{{ old('schedule_days.'.$index.'.durasi_menit', $day->durasi_menit) }}" min="15" max="480" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('schedule_days.'.$index.'.durasi_menit') border-red-500 @enderror" required>
                                @error('schedule_days.'.$index.'.durasi_menit')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="schedule-day-item border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-medium text-gray-700">{{ __('teacher/schedule_edit.schedule_item.title_prefix') }} #1</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('teacher/schedule_edit.fields.day') }}</label>
                                <select name="schedule_days[0][hari]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                    <option value="">{{ __('teacher/schedule_edit.select.select_day') }}</option>
                                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $hari)
                                    <option value="{{ $hari }}" {{ old('schedule_days.0.hari') === $hari ? 'selected' : '' }}>{{ __('teacher/schedule_edit.day_names.' . strtolower($hari)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('teacher/schedule_edit.fields.start_time') }}</label>
                                <input type="time" name="schedule_days[0][jam_mulai]" value="{{ old('schedule_days.0.jam_mulai') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('teacher/schedule_edit.fields.duration_minutes') }}</label>
                                <input type="number" name="schedule_days[0][durasi_menit]" value="{{ old('schedule_days.0.durasi_menit') }}" min="15" max="480" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>

                @error('schedule_days')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-white rounded-lg shadow-sm p-4 lg:p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Exception / Tarikh Batal</h3>
                        <p class="text-sm text-gray-500 mt-1">Tambah tarikh yang mahu dibatalkan tanpa mengubah pola jadwal utama.</p>
                    </div>
                    <button type="button"
                            onclick="addException()"
                            class="bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Tambah Tarikh Batal
                    </button>
                </div>

                <div id="exceptionsContainer" class="space-y-3">
                    @php
                        $oldExceptions = old('exceptions');
                        $exceptions = $oldExceptions !== null
                            ? collect($oldExceptions)
                            : $classSchedule->exceptions->map(fn ($exception) => [
                                'exception_date' => $exception->exception_date->toDateString(),
                                'reason' => $exception->reason,
                            ]);
                    @endphp

                    @forelse($exceptions as $exceptionIndex => $exception)
                        <div class="exception-item rounded-lg border border-rose-100 bg-rose-50 p-4">
                            <div class="grid grid-cols-1 md:grid-cols-[1fr_2fr_auto] gap-3 items-end">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tarikh</label>
                                    <input type="date"
                                           name="exceptions[{{ $exceptionIndex }}][exception_date]"
                                           value="{{ $exception['exception_date'] ?? '' }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Sebab</label>
                                    <input type="text"
                                           name="exceptions[{{ $exceptionIndex }}][reason]"
                                           value="{{ $exception['reason'] ?? '' }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500"
                                           placeholder="Contoh: cuti umum">
                                </div>
                                <button type="button"
                                        onclick="removeException(this)"
                                        class="bg-white px-4 py-2 text-sm font-semibold text-rose-700 rounded-lg border border-rose-200 hover:bg-rose-100">
                                    Buang
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-gray-500 rounded-lg border border-dashed border-gray-200 p-4" id="noExceptionsMessage">
                            Tiada tarikh batal.
                        </div>
                    @endforelse
                </div>
            </div>

            @if(!empty($upcomingOccurrences))
            <div class="bg-white rounded-lg shadow-sm p-4 lg:p-6 mb-6">
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">{{ __('teacher/schedule_edit.preview.title') }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ __('teacher/schedule_edit.preview.desc') }}</p>
                </div>

                <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-4">
                    @foreach($upcomingOccurrences as $occurrence)
                    <div class="rounded-2xl border border-indigo-100 bg-indigo-50 p-4">
                        <div class="text-sm font-semibold text-indigo-800">
                            {{ $occurrence['date']->translatedFormat('d M Y') }}
                        </div>
                        <div class="mt-1 text-xs text-gray-500">
                            {{ __('teacher/schedule.days_full')[$occurrence['day']] ?? $occurrence['day'] }}
                        </div>
                        <div class="mt-2 text-sm font-medium text-gray-800">
                            {{ $occurrence['time_range'] }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex flex-col lg:flex-row gap-4 justify-between">
                <div class="flex w-full flex-col gap-4 lg:w-auto lg:flex-row">
                    <button type="button" 
                            onclick="window.location='{{ route('teacher.schedule.index') }}'" 
                            class="w-full lg:w-auto bg-gray-600 hover:bg-gray-700 text-white py-2 lg:py-3 px-6 lg:px-8 rounded-lg text-sm lg:text-base font-medium transition-colors">
                        {{ __('teacher/schedule_edit.buttons.cancel') }}
                    </button>
                    <button type="submit"
                            form="deleteScheduleForm"
                            class="w-full lg:w-auto bg-red-600 hover:bg-red-700 text-white py-2 lg:py-3 px-6 lg:px-8 rounded-lg text-sm lg:text-base font-medium transition-colors">
                        Hapus Jadwal
                    </button>
                </div>
                <button type="submit" 
                        id="submitBtn"
                        class="w-full lg:w-auto bg-indigo-600 hover:bg-indigo-700 text-white py-2 lg:py-3 px-6 lg:px-8 rounded-lg text-sm lg:text-base font-medium transition-colors">
                    {{ __('teacher/schedule_edit.buttons.save') }}
                </button>
            </div>
        </form>
        <form id="deleteScheduleForm"
              action="{{ route('teacher.schedule.destroy', $classSchedule->id) }}"
              method="POST"
              onsubmit="return confirm('Hapus jadwal kelas ini?')">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>

<script>
let scheduleIndex = {{ count($classSchedule->classScheduleDays) > 0 ? count($classSchedule->classScheduleDays) : 1 }};
let exceptionIndex = {{ $exceptions->count() }};
const dayNamesMap = {
    'monday': "{{ __('teacher/schedule_edit.day_names.monday') }}",
    'tuesday': "{{ __('teacher/schedule_edit.day_names.tuesday') }}",
    'wednesday': "{{ __('teacher/schedule_edit.day_names.wednesday') }}",
    'thursday': "{{ __('teacher/schedule_edit.day_names.thursday') }}",
    'friday': "{{ __('teacher/schedule_edit.day_names.friday') }}",
    'saturday': "{{ __('teacher/schedule_edit.day_names.saturday') }}",
    'sunday': "{{ __('teacher/schedule_edit.day_names.sunday') }}"
};

// Form validation
document.getElementById('classForm').addEventListener('submit', function(e) {
    const scheduleDays = document.querySelectorAll('.schedule-day-item');
    if (scheduleDays.length === 0) {
        e.preventDefault();
        alert("{{ __('teacher/schedule_edit.alerts.min_one_schedule') }}");
        return;
    }

    // Disable submit button
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.textContent = "{{ __('teacher/schedule_edit.buttons.saving') }}";
});

function addScheduleDay() {
    const container = document.getElementById('scheduleDaysContainer');
    const newDay = document.createElement('div');
    newDay.className = 'schedule-day-item border border-gray-200 rounded-lg p-4';
    newDay.innerHTML = `
        <div class="flex items-center justify-between mb-3">
            <h4 class="font-medium text-gray-700">${"{{ __('teacher/schedule_edit.schedule_item.title_prefix') }}"} #${scheduleIndex + 1}</h4>
            <button type="button" onclick="removeScheduleDay(this)" class="text-red-600 hover:text-red-800 text-sm font-medium flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                {{ __('teacher/schedule_edit.actions.remove') }}
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('teacher/schedule_edit.fields.day') }}</label>
                <select name="schedule_days[${scheduleIndex}][hari]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">{{ __('teacher/schedule_edit.select.select_day') }}</option>
                    <option value="Monday">${dayNamesMap.monday}</option>
                    <option value="Tuesday">${dayNamesMap.tuesday}</option>
                    <option value="Wednesday">${dayNamesMap.wednesday}</option>
                    <option value="Thursday">${dayNamesMap.thursday}</option>
                    <option value="Friday">${dayNamesMap.friday}</option>
                    <option value="Saturday">${dayNamesMap.saturday}</option>
                    <option value="Sunday">${dayNamesMap.sunday}</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('teacher/schedule_edit.fields.start_time') }}</label>
                <input type="time" name="schedule_days[${scheduleIndex}][jam_mulai]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('teacher/schedule_edit.fields.duration_minutes') }}</label>
                <input type="number" name="schedule_days[${scheduleIndex}][durasi_menit]" min="15" max="480" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
        </div>
    `;
    container.appendChild(newDay);
    scheduleIndex++;
    updateScheduleNumbers();
}

function removeScheduleDay(button) {
    const items = document.querySelectorAll('.schedule-day-item');
    if (items.length <= 1) {
        alert("{{ __('teacher/schedule_edit.alerts.min_one_schedule') }}");
        return;
    }
    button.closest('.schedule-day-item').remove();
    updateScheduleNumbers();
}

function updateScheduleNumbers() {
    const items = document.querySelectorAll('.schedule-day-item');
    items.forEach((item, index) => {
        const header = item.querySelector('h4');
        header.textContent = `${"{{ __('teacher/schedule_edit.schedule_item.title_prefix') }}"} #${index + 1}`;
    });
}

function addException() {
    const container = document.getElementById('exceptionsContainer');
    const emptyMessage = document.getElementById('noExceptionsMessage');
    if (emptyMessage) {
        emptyMessage.remove();
    }

    const item = document.createElement('div');
    item.className = 'exception-item rounded-lg border border-rose-100 bg-rose-50 p-4';
    item.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-[1fr_2fr_auto] gap-3 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tarikh</label>
                <input type="date"
                       name="exceptions[${exceptionIndex}][exception_date]"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sebab</label>
                <input type="text"
                       name="exceptions[${exceptionIndex}][reason]"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500"
                       placeholder="Contoh: cuti umum">
            </div>
            <button type="button"
                    onclick="removeException(this)"
                    class="bg-white px-4 py-2 text-sm font-semibold text-rose-700 rounded-lg border border-rose-200 hover:bg-rose-100">
                Buang
            </button>
        </div>
    `;
    container.appendChild(item);
    exceptionIndex++;
}

function removeException(button) {
    button.closest('.exception-item').remove();

    const container = document.getElementById('exceptionsContainer');
    if (!container.querySelector('.exception-item')) {
        const emptyMessage = document.createElement('div');
        emptyMessage.id = 'noExceptionsMessage';
        emptyMessage.className = 'text-sm text-gray-500 rounded-lg border border-dashed border-gray-200 p-4';
        emptyMessage.textContent = 'Tiada tarikh batal.';
        container.appendChild(emptyMessage);
    }
}
</script>
@endsection

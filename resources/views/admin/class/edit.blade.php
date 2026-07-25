@extends('layouts.admin')

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
                    <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan:</h3>
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
            <h1 class="text-xl lg:text-2xl font-bold text-gray-800">{{ __('admin/class/edit.header.title') }}</h1>
            <p class="text-sm lg:text-base text-gray-600 mt-1">
                {{ $classSchedule->course->nama_kos }} - {{ $classSchedule->teacher->user->name }}
            </p>
        </div>

        <form action="{{ route('admin.class.update', $classSchedule->id) }}" method="POST" id="classForm">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm p-4 lg:p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('admin/class/edit.sections.basic_info') }}</h3>
                
                <div class="space-y-4">
                    <!-- Nama Kelas -->
                    <div>
                        <label for="nama_kelas" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('admin/class/edit.fields.class_name') }} <span class="text-red-500">*</span>
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
                        <label for="link_group" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin/class/edit.fields.link_group') }}</label>
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
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin/class/edit.fields.notes') }}</label>
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

            <!-- Students -->
            <div class="bg-white rounded-lg shadow-sm p-4 lg:p-6 mb-6">
                <div class="flex items-center justify-between gap-4 mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">{{ __('admin/class/edit.sections.students') }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ __('admin/class/edit.students.help') }}</p>
                    </div>
                    <span class="text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                        {{ $students->count() }} {{ __('admin/class/edit.students.available') }}
                    </span>
                </div>

                @php
                    $checkedStudentIds = array_map('intval', old('student_ids', $selectedStudentIds ?? []));
                @endphp

                @if($students->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
                        @foreach($students as $student)
                            <label class="flex items-start gap-3 border border-gray-200 rounded-lg p-3 cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition-colors">
                                <input
                                    type="checkbox"
                                    name="student_ids[]"
                                    value="{{ $student->id }}"
                                    class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    {{ in_array($student->id, $checkedStudentIds, true) ? 'checked' : '' }}>
                                <span class="min-w-0">
                                    <span class="block text-sm font-medium text-gray-800 truncate">
                                        {{ $student->user->name ?? '-' }}
                                    </span>
                                    <span class="block text-xs text-gray-500 truncate">
                                        {{ $student->user->email ?? '-' }}
                                    </span>
                                    @if($student->ic_number)
                                        <span class="block text-xs text-gray-400 mt-1">IC: {{ $student->ic_number }}</span>
                                    @endif
                                </span>
                            </label>
                        @endforeach
                    </div>
                @else
                    <div class="border border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <p class="text-sm font-medium text-gray-700">{{ __('admin/class/edit.students.empty_title') }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ __('admin/class/edit.students.empty_subtitle') }}</p>
                    </div>
                @endif

                @error('student_ids')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
                @error('student_ids.*')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Schedule Days -->
            <div class="bg-white rounded-lg shadow-sm p-4 lg:p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">{{ __('admin/class/edit.sections.schedule') }}</h3>
                    <button type="button" 
                            onclick="addScheduleDay()" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        {{ __('admin/class/edit.actions.add_day') }}
                    </button>
                </div>

                <div id="scheduleDaysContainer" class="space-y-4">
                    @forelse($classSchedule->classScheduleDays as $index => $day)
                    <div class="schedule-day-item border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-medium text-gray-700">{{ __('admin/class/edit.schedule_item.title_prefix') }} #{{ $index + 1 }}</h4>
                            @if($index > 0)
                            <button type="button" onclick="removeScheduleDay(this)" class="text-red-600 hover:text-red-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                            @endif
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin/class/edit.fields.day') }}</label>
                                <select name="schedule_days[{{ $index }}][hari]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('schedule_days.'.$index.'.hari') border-red-500 @enderror" required>
                                    <option value="">{{ __('admin/class/edit.select.select_day') }}</option>
                                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $hari)
                                    <option value="{{ $hari }}" {{ old('schedule_days.'.$index.'.hari', $day->hari) === $hari ? 'selected' : '' }}>{{ __('admin/class/edit.day_names.' . strtolower($hari)) }}</option>
                                    @endforeach
                                </select>
                                @error('schedule_days.'.$index.'.hari')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin/class/edit.fields.start_time') }}</label>
                                <input type="time" name="schedule_days[{{ $index }}][jam_mulai]" value="{{ old('schedule_days.'.$index.'.jam_mulai', $day->jam_mulai) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('schedule_days.'.$index.'.jam_mulai') border-red-500 @enderror" required>
                                @error('schedule_days.'.$index.'.jam_mulai')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin/class/edit.fields.duration_minutes') }}</label>
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
                            <h4 class="font-medium text-gray-700">{{ __('admin/class/edit.schedule_item.title_prefix') }} #1</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin/class/edit.fields.day') }}</label>
                                <select name="schedule_days[0][hari]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                    <option value="">{{ __('admin/class/edit.select.select_day') }}</option>
                                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $hari)
                                    <option value="{{ $hari }}" {{ old('schedule_days.0.hari') === $hari ? 'selected' : '' }}>{{ __('admin/class/edit.day_names.' . strtolower($hari)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                                <input type="time" name="schedule_days[0][jam_mulai]" value="{{ old('schedule_days.0.jam_mulai') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Durasi (menit)</label>
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

            <!-- Action Buttons -->
            <div class="flex flex-col lg:flex-row gap-4 justify-between">
                <button type="button" 
                        onclick="history.back()" 
                        class="w-full lg:w-auto bg-gray-600 hover:bg-gray-700 text-white py-2 lg:py-3 px-6 lg:px-8 rounded-lg text-sm lg:text-base font-medium transition-colors">
                    {{ __('admin/class/edit.buttons.cancel') }}
                </button>
                <button type="submit" 
                        id="submitBtn"
                        class="w-full lg:w-auto bg-blue-600 hover:bg-blue-700 text-white py-2 lg:py-3 px-6 lg:px-8 rounded-lg text-sm lg:text-base font-medium transition-colors">
                    {{ __('admin/class/edit.buttons.save') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let scheduleIndex = {{ count($classSchedule->classScheduleDays) }};

// Form validation
document.getElementById('classForm').addEventListener('submit', function(e) {
    const scheduleDays = document.querySelectorAll('.schedule-day-item');
    if (scheduleDays.length === 0) {
        e.preventDefault();
    alert("{{ __('admin/class/edit.alerts.min_one_schedule') }}");
        return;
    }

    // Disable submit button
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.textContent = "{{ __('admin/class/edit.buttons.saving') }}";
});

function addScheduleDay() {
    const container = document.getElementById('scheduleDaysContainer');
    const newDay = document.createElement('div');
    newDay.className = 'schedule-day-item border border-gray-200 rounded-lg p-4';
    newDay.innerHTML = `
        <div class="flex items-center justify-between mb-3">
            <h4 class="font-medium text-gray-700">${"{{ __('admin/class/edit.schedule_item.title_prefix') }}"} #${scheduleIndex + 1}</h4>
            <button type="button" onclick="removeScheduleDay(this)" class="text-red-600 hover:text-red-800" aria-label="{{ __('admin/class/edit.actions.remove') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin/class/edit.fields.day') }}</label>
                <select name="schedule_days[${scheduleIndex}][hari]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">{{ __('admin/class/edit.select.select_day') }}</option>
                    <option value="Monday">{{ __('admin/class/edit.day_names.monday') }}</option>
                    <option value="Tuesday">{{ __('admin/class/edit.day_names.tuesday') }}</option>
                    <option value="Wednesday">{{ __('admin/class/edit.day_names.wednesday') }}</option>
                    <option value="Thursday">{{ __('admin/class/edit.day_names.thursday') }}</option>
                    <option value="Friday">{{ __('admin/class/edit.day_names.friday') }}</option>
                    <option value="Saturday">{{ __('admin/class/edit.day_names.saturday') }}</option>
                    <option value="Sunday">{{ __('admin/class/edit.day_names.sunday') }}</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin/class/edit.fields.start_time') }}</label>
                <input type="time" name="schedule_days[${scheduleIndex}][jam_mulai]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin/class/edit.fields.duration_minutes') }}</label>
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
        alert('Minimal harus ada 1 jadwal hari');
        return;
    }
    button.closest('.schedule-day-item').remove();
    updateScheduleNumbers();
}

function updateScheduleNumbers() {
    const items = document.querySelectorAll('.schedule-day-item');
    items.forEach((item, index) => {
        const header = item.querySelector('h4');
        header.textContent = `Jadwal #${index + 1}`;
    });
}
</script>
@endsection

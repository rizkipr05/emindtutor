@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <h2 class="text-2xl font-semibold text-gray-900 mb-6">{{ __('admin/students/approval.header') }}</h2>

    @php
        use Carbon\Carbon;
        $days = [
            'senin' => 'Mon',
            'selasa' => 'Tue',
            'rabu' => 'Wed',
            'kamis' => 'Thu',
            'jumat' => 'Fri',
            'sabtu' => 'Sat',
            'minggu' => 'Sun',
        ];
    @endphp

    {{-- Student Info Card --}}
    <div class="bg-gray-50 rounded-lg p-6 mb-6 border border-gray-200">
    <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            {{ __('admin/students/approval.student_info') }}
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">{{ __('admin/students/approval.labels.name') }}</p>
                <p class="font-medium text-gray-900">{{ $student->user->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">{{ __('admin/students/approval.labels.ic_number') }}</p>
                <p class="font-medium text-gray-900 font-mono">{{ $student->ic_number }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-gray-600">{{ __('admin/students/approval.labels.course') }}</p>
                <p class="font-medium text-gray-900">{{ $invoice->course->nama_kos }} 
                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full ml-2">{{ __('admin/students/approval.jenis.' . $invoice->course->jenis) }}</span>
                </p>
            </div>
        </div>

        @if($invoice->payment)
            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="flex items-start space-x-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-2">{{ __('admin/students/approval.labels.payment_proof') }}</p>
                        <img src="{{ asset('storage/' . $invoice->payment->bukti_bayar) }}" 
                            alt="{{ __('admin/students/approval.labels.payment_proof') }}" 
                            class="rounded-lg border shadow-sm w-32">
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">{{ __('admin/students/approval.labels.paid_on') }}</p>
                        <p class="font-medium text-gray-900">
                            {{ $invoice->payment->dibayar_pada 
                                ? \Carbon\Carbon::parse($invoice->payment->dibayar_pada)->format('d M Y H:i') 
                                : '-' }}
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div class="mt-4 pt-4 border-t border-gray-200">
                <p class="text-sm text-gray-500">{{ __('admin/students/approval.payment_missing') }}</p>
            </div>
        @endif
    </div>

    <form action="{{ route('admin.students.approve', [$student->id, $invoice->id]) }}" method="POST" class="space-y-6">
        @csrf

        {{-- GROUP COURSE --}}
    @if($invoice->course->jenis === 'kumpulan')
            @if($semuaJadwal->count() > 0)
                <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="font-semibold text-gray-900 mb-4">{{ __('admin/students/approval.choose_available_class') }}</h3>
                    <select name="class_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        @foreach($semuaJadwal as $class)
                            @php
                                $jumlahSiswa = $class->students->count();
                                $max = $invoice->course->max_student;
                            @endphp
                            <option value="{{ $class->id }}">
                {{ $class->nama_kelas }} — {{ $jumlahSiswa }}/{{ $max }} {{ __('admin/students/approval.students') }}
                            </option>
                        @endforeach
                    </select>
            <p class="text-sm text-gray-500 mt-2">{{ __('admin/students/approval.available_note') }}</p>
                </div>
                
                <div class="text-center">
            <span class="bg-gray-200 text-gray-700 px-4 py-2 rounded-full text-sm font-medium">{{ __('admin/students/approval.or') }}</span>
                </div>
            @endif
        @endif

        {{-- BUAT KELAS BARU --}}
        @if($invoice->course->jenis === 'individu' || $semuaJadwal->count() === 0 || true)
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ __('admin/students/approval.create_new_class') }}
                </h3>

                <div class="space-y-4">
                    {{-- Nama Kelas --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin/students/approval.labels.class_name') }}</label>
                        <input type="text" name="nama_kelas" 
                            value="{{ old('nama_kelas', $invoice->course->nama_kos . ' - ' . $student->user->name) }}" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        @error('nama_kelas')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Pilih Guru --}}
                    <div x-data="{ selectedTeacher: null }">
                        <template x-if="!selectedTeacher">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin/students/approval.labels.select_teacher') }}</label>
                                <select x-model="selectedTeacher" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                    <option value="">{{ __('admin/students/approval.select_teacher_default') }}</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">
                                            {{ $teacher->user->name }} — {{ __('admin/students/approval.rate') }}: RM{{ number_format($teacher->allowance_rate, 2) }}
                                            — {{ $teacher->students_count }} {{ __('admin/students/approval.students') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </template>

                        <input type="hidden" name="teacher_id" :value="selectedTeacher">

                        <template x-if="selectedTeacher">
                            <div>
                                @foreach($teachers as $teacher)
                                    <div x-show="selectedTeacher == {{ $teacher->id }}" x-data="{ open: false }"
                                        class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                                        
                                                <div class="flex justify-between items-center px-4 py-3 bg-gradient-to-r from-teal-50 to-blue-50">
                                                    <div>
                                                        <h4 class="font-semibold text-gray-900">{{ $teacher->name }}</h4>
                                                        <span class="text-sm text-gray-600">{{ __('admin/students/approval.active_students', ['count' => $teacher->active_students_count ?? 0]) }}</span>
                                                    </div>
                                            <div class="flex items-center space-x-2">
                                                        <button type="button" @click="open = !open" 
                                                            class="text-teal-600 hover:text-teal-700 text-sm font-medium">
                                                            <span x-text="open ? '{{ __('admin/students/approval.hide_schedule') }}' : '{{ __('admin/students/approval.view_schedule') }}'"></span>
                                                        </button>
                                                        <button type="button" @click="selectedTeacher = null"
                                                            class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-sm">
                                                            {{ __('admin/students/approval.change') }}
                                                        </button>
                                            </div>
                                        </div>

                                        <div x-show="open" x-collapse class="p-4">
                                            <div class="overflow-x-auto">
                                                <table class="w-full text-xs border border-gray-200 rounded">
                                                    <thead class="bg-gray-100">
                                                        <tr>
                                                            <th class="border px-2 py-1 text-gray-600">{{ __('admin/students/approval.labels.time') }}</th>
                                                            @php
                                                                $dayKeyToTrans = [
                                                                    'senin' => 'monday',
                                                                    'selasa' => 'tuesday',
                                                                    'rabu' => 'wednesday',
                                                                    'kamis' => 'thursday',
                                                                    'jumat' => 'friday',
                                                                    'sabtu' => 'saturday',
                                                                    'minggu' => 'sunday',
                                                                ];
                                                            @endphp
                                                            @foreach($days as $dayKey => $label)
                                                                <th class="border px-2 py-1 text-gray-600">{{ __('admin/students/approval.days_short.' . ($dayKeyToTrans[$dayKey] ?? $dayKey)) }}</th>
                                                            @endforeach
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @for ($hour = 6; $hour <= 22; $hour++)
                                                            <tr>
                                                                <td class="border px-2 py-1 font-medium bg-gray-50 text-center">
                                                                    {{ str_pad($hour,2,'0',STR_PAD_LEFT) }}:00
                                                                </td>
                                                                @foreach(array_keys($days) as $dayKey)
                                                                    @php
                                                                        $timetable = $teacher->timetable ?? collect();
                                                                        $dbDayName = $dayMap[$dayKey];
                                                                        $slot = $timetable->first(function($t) use ($dbDayName, $hour) {
                                                                            if (empty($t['day']) || empty($t['start']) || empty($t['end'])) return false;
                                                                            if (strcasecmp($t['day'], $dbDayName) !== 0) return false;
                                                                            try {
                                                                                $start = Carbon::parse($t['start']);
                                                                                $end   = Carbon::parse($t['end']);
                                                                            } catch (\Exception $e) {
                                                                                return false;
                                                                            }
                                                                            $cellStart = Carbon::createFromTime($hour, 0, 0);
                                                                            $cellEnd   = (clone $cellStart)->addHour();
                                                                            return $start < $cellEnd && $end > $cellStart;
                                                                        });
                                                                    @endphp
                                                                    <td class="border h-8 text-center
                                                                        @if($slot)
                                                                            {{ $slot['type'] === 'unavailable' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}
                                                                        @else
                                                                            bg-white
                                                                        @endif
                                                                    ">
                                                                        @if($slot)
                                                                            @if($slot['type'] === 'unavailable')
                                                                                <span class="text-[10px]">✕</span>
                                                                            @else
                                                                                <span class="text-[10px]">●</span>
                                                                            @endif
                                                                        @endif
                                                                    </td>
                                                                @endforeach
                                                            </tr>
                                                        @endfor
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </template>
                    </div>

                    {{-- Allowance & Link Group Row --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin/students/approval.labels.allowance') }}</label>
                            <div class="flex space-x-2">
                                <input type="number" step="0.01" name="allowance" value="{{ old('allowance') }}"
                                    class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-teal-500 focus:border-teal-500" 
                                    placeholder="20.00">
                                <select name="allowance_type" class="border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                    <option value="">{{ __('admin/students/approval.labels.select_type') }}</option>
                                    <option value="per_session">{{ __('admin/students/approval.allowance_types.per_session') }}</option>
                                    <option value="per_hour">{{ __('admin/students/approval.allowance_types.per_hour') }}</option>
                                    <option value="per_month">{{ __('admin/students/approval.allowance_types.per_month') }}</option>
                                </select>
                            </div>
                            @error('allowance')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin/students/approval.labels.link_group') }}</label>
                            <input type="url" name="link_group" value="{{ old('link_group') }}" 
                                placeholder="https://chat.whatsapp.com/xxxx" 
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            @error('link_group')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin/students/approval.labels.customer_instruction') }}</label>
                        <textarea name="notes" rows="3" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">{{ old('notes') }}</textarea>
                        @error('notes')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Jadwal --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin/students/approval.labels.schedule') }}</label>
                        <div id="jadwal-container" class="space-y-2">
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-md">
                                @php
                                    $weekdayMap = [
                                        'monday' => 'Monday',
                                        'tuesday' => 'Tuesday',
                                        'wednesday' => 'Wednesday',
                                        'thursday' => 'Thursday',
                                        'friday' => 'Friday',
                                        'saturday' => 'Saturday',
                                        'sunday' => 'Sunday',
                                    ];
                                @endphp
                                <select name="days[0][hari]" class="border border-gray-300 rounded px-2 py-1 text-sm">
                                    @foreach($weekdayMap as $key => $eng)
                                        <option value="{{ $eng }}">{{ __('admin/students/approval.days.' . $key) }}</option>
                                    @endforeach
                                </select>
                                <input type="time" name="days[0][jam_mulai]" class="border border-gray-300 rounded px-2 py-1 text-sm">
                                <input type="number" name="days[0][durasi_menit]" placeholder="{{ __('admin/students/approval.duration_placeholder') }}" 
                                    class="border border-gray-300 rounded px-2 py-1 text-sm w-32">
                                <button type="button" onclick="addJadwal()" 
                                    class="text-teal-600 hover:text-teal-700 font-medium text-sm">{{ __('admin/students/approval.add') }}</button>
                            </div>
                        </div>
                        @error('days')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
        @endif

        <div>
            <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                          <a href="{{ route('admin.students.pending') }}" 
                              class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        {{ __('admin/students/approval.buttons.cancel') }}
                    </a>
                        
                    <button type="submit" 
                        class="inline-flex items-center px-6 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        {{ __('admin/students/approval.buttons.approve_assign') }}
                    </button>
                </div>
        </div>
    </form>
</div>

<script src="//unpkg.com/alpinejs" defer></script>
<script>
    let jadwalIndex = 1;
    function addJadwal() {
        const container = document.getElementById('jadwal-container');
        const weekdayMap = {
            'Monday': @json(__('admin/students/approval.days.monday')),
            'Tuesday': @json(__('admin/students/approval.days.tuesday')),
            'Wednesday': @json(__('admin/students/approval.days.wednesday')),
            'Thursday': @json(__('admin/students/approval.days.thursday')),
            'Friday': @json(__('admin/students/approval.days.friday')),
            'Saturday': @json(__('admin/students/approval.days.saturday')),
            'Sunday': @json(__('admin/students/approval.days.sunday')),
        };

        let optionsHtml = '';
        for (const eng in weekdayMap) {
            optionsHtml += `<option value="${eng}">${weekdayMap[eng]}</option>`;
        }

        const jadwalHtml = `
            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-md">
                <select name="days[${jadwalIndex}][hari]" class="border border-gray-300 rounded px-2 py-1 text-sm">
                    ${optionsHtml}
                </select>
                <input type="time" name="days[${jadwalIndex}][jam_mulai]" class="border border-gray-300 rounded px-2 py-1 text-sm">
                <input type="number" name="days[${jadwalIndex}][durasi_menit]" placeholder="{{ __('admin/students/approval.duration_placeholder') }}" 
                    class="border border-gray-300 rounded px-2 py-1 text-sm w-32">
                <button type="button" onclick="this.parentElement.remove()" 
                    class="text-red-500 hover:text-red-700 text-sm">{{ __('admin/students/approval.remove') }}</button>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', jadwalHtml);
        jadwalIndex++;
    }
</script>
@endsection
@extends('layouts.student')

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto px-2">
        <!-- Header -->
        <div class="mb-8">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ __('student/progress/index.title') }}</h1>
                                <p class="text-gray-600">{{ __('student/progress/index.subtitle') }}</p>
                    </div>
                    <div class="hidden md:flex items-center space-x-4">
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-user-graduate mr-2"></i>
                            {{ Auth::user()->name }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="mb-8">
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <form method="GET" action="{{ route('student.progress.index') }}" class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-48">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt mr-1 text-blue-500"></i>
                            {{ __('student/progress/index.select_month') }}
                        </label>
                        <select name="month" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $selectedMonth == $i ? 'selected' : '' }}>
                                    {{ __('student/progress/index.months.' . $i) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    
                    <div class="flex-1 min-w-32">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-1 text-blue-500"></i>
                            {{ __('student/progress/index.year') }}
                        </label>
                        <select name="year" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            @for($year = now()->year - 2; $year <= now()->year + 1; $year++)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    
                    <div>
                        <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-200 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-search mr-2"></i>
                            {{ __('student/progress/index.filter') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Progress Cards -->
        @if($monthlyReport)
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-5 text-emerald-900 shadow-sm">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="font-bold"><i class="fas fa-check-circle mr-2"></i>{{ __('reports.monthly.collected') }}</p>
                        <p class="mt-1 text-sm">{{ __('reports.monthly.collected_detail', ['month' => \Carbon\Carbon::parse($monthlyReport->period_start)->translatedFormat('F Y'), 'date' => $monthlyReport->collected_at->translatedFormat('d M Y, H:i')]) }}</p>
                    </div>
                    <div class="rounded-lg bg-white px-4 py-2 text-sm shadow-sm">
                        <span class="font-semibold">{{ __('reports.monthly.overall_attendance') }}</span>
                        {{ data_get($monthlyReport->summary, 'attendance_percentage', 0) }}%
                    </div>
                </div>
            </div>
        @else
            <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900 shadow-sm">
                <i class="fas fa-clock mr-2"></i>{{ __('reports.monthly.live_notice') }}
            </div>
        @endif

        @if(empty($progressData))
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-12 text-center">
                <div class="text-gray-400 mb-4">
                    <i class="fas fa-calendar-times text-6xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">{{ __('student/progress/index.no_data') }}</h3>
                <p class="text-gray-500">{{ __('student/progress/index.no_data_desc', ['month' => DateTime::createFromFormat('!m', $selectedMonth)->format('F'), 'year' => $selectedYear]) }}</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach($progressData as $progress)
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300">
                        <!-- Class Header -->
                        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-6">
                            <div class="flex flex-col md:flex-row md:items-center justify-between">
                                <div>
                                    <h2 class="text-2xl font-bold mb-2">{{ $progress['course_name'] }}</h2>
                                    <p class="text-indigo-100 text-lg">{{ $progress['class_name'] }}</p>
                                    <div class="flex flex-wrap gap-4 mt-3 text-sm">
                                        <span class="bg-white/20 px-3 py-1 rounded-full">
                                            <i class="fas fa-calendar-day mr-1"></i>
                                            {{ \Carbon\Carbon::parse($progress['invoice_period']['start'])->format('d') }} {{ __('student/progress/index.months.' . \Carbon\Carbon::parse($progress['invoice_period']['start'])->format('n')) }} {{ \Carbon\Carbon::parse($progress['invoice_period']['start'])->format('Y') }} -
                                            {{ \Carbon\Carbon::parse($progress['invoice_period']['end'])->format('d') }} {{ __('student/progress/index.months.' . \Carbon\Carbon::parse($progress['invoice_period']['end'])->format('n')) }} {{ \Carbon\Carbon::parse($progress['invoice_period']['end'])->format('Y') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-4 md:mt-0 text-right">
                                    <div class="bg-white/20 rounded-xl p-4">
                                        <div class="text-3xl font-bold">{{ $progress['progress']['attendance_percentage'] }}%</div>
                                            <div class="text-indigo-100 text-sm">{{ __('student/progress/index.attendance') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Stats Section -->
                        <div class="p-6 bg-gray-50">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="bg-white rounded-xl p-4 text-center shadow-sm">
                                    <div class="text-2xl font-bold text-blue-600">{{ $progress['progress']['total_sessions'] }}</div>
                                    <div class="text-gray-600 text-sm">{{ __('student/progress/index.total_sessions') }}</div>
                                </div>
                                <div class="bg-white rounded-xl p-4 text-center shadow-sm">
                                    <div class="text-2xl font-bold text-green-600">{{ $progress['progress']['attended_sessions'] }}</div>
                                    <div class="text-gray-600 text-sm">{{ __('student/progress/index.attended_sessions') }}</div>
                                </div>
                                <div class="bg-white rounded-xl p-4 text-center shadow-sm">
                                    <div class="text-2xl font-bold text-purple-600">{{ $progress['target']['minimum_sessions'] }}</div>
                                    <div class="text-gray-600 text-sm">{{ __('student/progress/index.target_min', ['unit' => __('student/progress/index.minimum_session_unit.' . $progress['target']['minimum_unit'])]) }}</div>
                                </div>
                                <div class="bg-white rounded-xl p-4 text-center shadow-sm">
                                    <div class="text-2xl font-bold text-orange-600">{{ $progress['target']['sessions_duration'] }}</div>
                                    <div class="text-gray-600 text-sm">{{ __('student/progress/index.duration_unit', ['unit' => __('student/progress/index.minimum_session_duration.' . $progress['target']['duration_unit'])]) }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="px-6 pb-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">{{ __('student/progress/index.progress_kehadiran') }}</span>
                                <span class="text-sm text-gray-500">{{ $progress['progress']['attended_sessions'] }}/{{ $progress['progress']['total_sessions'] }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-gradient-to-r from-green-500 to-blue-500 h-3 rounded-full transition-all duration-500" style="width: {{ $progress['progress']['attendance_percentage'] }}%"></div>
                            </div>
                        </div>

                        <!-- Schedule Info -->
                        <div class="px-6 pb-4">
                            <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-clock mr-2 text-blue-500"></i>
                                {{ __('student/progress/index.class_schedule') }}
                            </h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($progress['class_schedule_days'] as $schedule)
                                    <div class="bg-blue-50 border border-blue-200 px-3 py-2 rounded-lg text-sm">
                                        <span class="font-medium text-blue-800">{{ __('student/progress/index.day_names.' . $schedule['hari']) }}</span>
                                        <span class="text-blue-600 ml-1">{{ $schedule['jam_mulai'] }}</span>
                                        <span class="text-gray-500 ml-1">({{ $schedule['durasi_menit'] }} min)</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Attendance Details -->
                        @if(!empty($progress['attendance_details']))
                            <div class="border-t border-gray-200">
                                <div class="p-6">
                                        <h4 class="font-semibold text-gray-800 mb-4 flex items-center">
                                        <i class="fas fa-list-check mr-2 text-green-500"></i>
                                        {{ __('student/progress/index.attendance_details') }} ({{ __('student/progress/index.sessions_count', ['count' => count($progress['attendance_details'])]) }})
                                    </h4>
                                    
                                    <div class="space-y-4">
                                        @foreach($progress['attendance_details'] as $detail)
                                            <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition-all duration-200">
                                                <div class="flex flex-col md:flex-row md:items-center justify-between">
                                                    <div class="flex-1">
                                                        <div class="flex items-center mb-2">
                                                            <div class="flex items-center mr-4">
                                                                <i class="fas fa-calendar-day mr-2 text-blue-500"></i>
                                                                <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($detail['date'])->format('d') }} {{ __('student/progress/index.months.' . \Carbon\Carbon::parse($detail['date'])->format('n')) }} {{ \Carbon\Carbon::parse($detail['date'])->format('Y') }}</span>
                                                            </div>
                                                            <div class="flex items-center mr-4">
                                                                <i class="fas fa-clock mr-2 text-purple-500"></i>
                                                                <span class="text-gray-600">{{ $detail['time'] }}</span>
                                                                @if($detail['check_out'])
                                                                    - {{ \Carbon\Carbon::parse($detail['check_out'])->format('H:i') }}
                                                                @endif
                                                            </div>
                                                            <div class="flex items-center">
                                                                @if(($detail['student_status'] ?? null) === 'late')
                                                                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-medium">
                                                                        <i class="fas fa-clock mr-1"></i>Late
                                                                    </span>
                                                                @elseif($detail['student_present'])
                                                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-medium">
                                                                        <i class="fas fa-check mr-1"></i>{{ __('student/progress/index.attendance_status.hadir') }}
                                                                    </span>
                                                                @else
                                                                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-medium">
                                                                        <i class="fas fa-times mr-1"></i>{{ __('student/progress/index.attendance_status.tidak_hadir') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="text-sm text-gray-600 mb-2">
                                                            <i class="fas fa-chalkboard-teacher mr-1 text-orange-500"></i>
                                                            {{ __('student/progress/index.teacher') }}: <span class="font-medium">{{ $detail['teacher_name'] }}</span>
                                                        </div>
                                                        
                                                        @if($detail['note'])
                                                            <div class="text-sm bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-2">
                                                                <i class="fas fa-sticky-note mr-2 text-yellow-600"></i>
                                                                <span class="font-medium text-yellow-800">{{ __('student/progress/index.teacher_note') }}:</span>
                                                                <p class="text-yellow-700 mt-1">{{ $detail['note'] }}</p>
                                                            </div>
                                                        @endif
                                                        
                                                        @if($detail['student_note'])
                                                            <div class="text-sm bg-blue-50 border border-blue-200 rounded-lg p-3">
                                                                <i class="fas fa-user-edit mr-2 text-blue-600"></i>
                                                                <span class="font-medium text-blue-800">{{ in_array($detail['student_status'] ?? null, ['late', 'absent']) ? 'Sebab kehadiran' : __('student/progress/index.student_note') }}:</span>
                                                                <p class="text-blue-700 mt-1">{{ $detail['student_note'] }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    
                                                    @if($detail['class_photo'])
                                                        <div class="mt-4 md:mt-0 md:ml-4">
                                                            <div class="w-24 h-24 bg-gray-100 rounded-lg overflow-hidden cursor-pointer hover:shadow-lg transition-all duration-200" onclick="openImageModal('{{ asset('storage/' . $detail['class_photo']) }}')">
                                                                <img src="{{ asset('storage/' . $detail['class_photo']) }}" alt="Foto Kelas" class="w-full h-full object-cover">
                                                            </div>
                                                            <p class="text-xs text-gray-500 text-center mt-1">{{ __('student/progress/index.click_to_enlarge') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <button onclick="closeImageModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300 text-2xl">
            <i class="fas fa-times"></i>
        </button>
        <img id="modalImage" src="" alt="Foto Kelas" class="max-w-full max-h-full rounded-lg">
    </div>
</div>

<script>
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

// Close modal when clicking outside the image
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});
</script>
@endsection
@extends('layouts.admin')

@section('title', __('admin/reports/index.header.title'))

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container mx-auto px-1">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-xl p-4 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-chart-line text-blue-600"></i>
                    {{ __('admin/reports/index.header.title') }}
                </h1>
                <p class="text-gray-600">{{ __('admin/reports/index.header.subtitle') }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">{{ __('admin/reports/index.header.period') }}</p>
                <p class="text-lg font-semibold text-gray-800">{{ now()->format('F Y') }}</p>
            </div>
        </div>
        
        {{-- Filter Bulan dan Tahun --}}
        <form method="GET" action="{{ route('admin.reports.index') }}" class="flex items-center gap-4 mb-6 mt-6">
            <div>
                <label for="month" class="block text-sm font-medium text-gray-700">{{ __('admin/reports/index.filters.month') }}</label>
                <select name="month" id="month" class="mt-1 block w-full border rounded-md px-3 py-2">
                    @foreach(__('admin/reports/index.months') as $key => $value)
                        <option value="{{ $key }}" {{ $month == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="year" class="block text-sm font-medium text-gray-700">{{ __('admin/reports/index.filters.year') }}</label>
                <select name="year" id="year" class="mt-1 block w-full border rounded-md px-3 py-2">
                    @for ($i = now()->year; $i >= now()->year - 5; $i--)
                        <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            	<div class="mt-6">
                	<button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">{{ __('admin/reports/index.filters.filter') }}</button>
            	</div>
        </form>
    </div>

    <!-- Ringkasan Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-r from-green-400 to-green-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">{{ __('admin/reports/index.cards.present') }}</p>
                    <p class="text-3xl font-bold">{{ $attendanceSummary['students']['present'] }}</p>
                </div>
                <i class="fas fa-user-check text-3xl text-green-200"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-yellow-400 to-orange-500 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm">{{ __('admin/reports/index.cards.late') }}</p>
                    <p class="text-3xl font-bold">{{ $attendanceSummary['students']['late'] }}</p>
                </div>
                <i class="fas fa-clock text-3xl text-yellow-200"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-red-400 to-red-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm">{{ __('admin/reports/index.cards.absent') }}</p>
                    <p class="text-3xl font-bold">{{ $attendanceSummary['students']['absent'] }}</p>
                </div>
                <i class="fas fa-user-times text-3xl text-red-200"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-blue-400 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">{{ __('admin/reports/index.cards.teachers') }}</p>
                    <p class="text-3xl font-bold">{{ $attendanceSummary['teachers']['active_teachers'] }}</p>
                </div>
                <i class="fas fa-chalkboard-teacher text-3xl text-blue-200"></i>
            </div>
        </div>
    </div>

    <!-- Toggle Navigation -->
    <div class="bg-white rounded-2xl shadow-xl p-3 mb-8">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                <button onclick="showSection('attendance')" 
                id="btn-attendance" 
                class="toggle-btn active flex items-center justify-center px-4 py-3 rounded-xl font-semibold transition-all duration-300">
                <i class="fas fa-users mr-2"></i>
                <span class="hidden sm:inline">{{ __('admin/reports/index.tabs.attendance.label') }}</span>
                <span class="sm:hidden">{{ __('admin/reports/index.tabs.attendance.short') }}</span>
            </button>
            <button onclick="showSection('performance')" 
                id="btn-performance" 
                class="toggle-btn flex items-center justify-center px-4 py-3 rounded-xl font-semibold transition-all duration-300">
                <i class="fas fa-chart-bar mr-2"></i>
                <span class="hidden sm:inline">{{ __('admin/reports/index.tabs.performance.label') }}</span>
                <span class="sm:hidden">{{ __('admin/reports/index.tabs.performance.short') }}</span>
            </button>
            <button onclick="showSection('cancelled')" 
                id="btn-cancelled" 
                class="toggle-btn flex items-center justify-center px-4 py-3 rounded-xl font-semibold transition-all duration-300">
                <i class="fas fa-times-circle mr-2"></i>
                <span class="hidden sm:inline">{{ __('admin/reports/index.tabs.cancelled.label') }}</span>
                <span class="sm:hidden">{{ __('admin/reports/index.tabs.cancelled.short') }}</span>
            </button>
        </div>
    </div>


    <!-- Section 1: Laporan Kehadiran -->
    <div id="section-attendance" class="section-content">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                <i class="fas fa-clipboard-list text-blue-600"></i>
                {{ __('admin/reports/index.attendance.title') }}
            </h2>
            
            <div class="grid gap-6">
                @foreach($classAttendanceReports as $class)
                <div class="bg-gray-50 rounded-xl p-6 border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-4">
                        <div>
                            <a href="{{ route('admin.class.show', $class['id']) }}" class="hover:underline">
                                <h3 class="text-xl font-bold text-gray-800">{{ $class['class_name'] }}</h3>
                            </a>
                            <p class="text-gray-600">{{ __('admin/reports/index.attendance.teacher') }}: {{ $class['teacher_name'] }}</p>
                            <p class="text-sm text-gray-500">{{ $class['total_students'] }} {{ __('admin/reports/index.attendance.students') }} • {{ $class['total_sessions'] }} {{ __('admin/reports/index.attendance.sessions') }}</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 mt-4 lg:mt-0">
                                <div class="text-center lg:text-right mb-4 lg:mb-0 lg:mr-6">
                                	<p class="text-sm text-gray-500">{{ __('admin/reports/index.attendance.attendance_rate') }}</p>
                                	<p class="text-2xl font-bold {{ $class['attendance_rate'] >= 80 ? 'text-green-600' : ($class['attendance_rate'] >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                		{{ $class['attendance_rate'] }}%
                                	</p>
                                </div>
                        </div>
                    </div>
                    
                    <!-- Toggle Detail -->
                        <button onclick="toggleDetails('{{ $class['id'] }}')" class="w-full text-left bg-blue-50 hover:bg-blue-100 rounded-lg p-3 transition-colors duration-200 mb-4">
                        	<span class="text-blue-700 font-medium">
                        		<i class="fas fa-chevron-down mr-2 transform transition-transform duration-200" id="chevron-{{ $class['id'] }}"></i>
                        		{{ __('admin/reports/index.attendance.view_details') }}
                        	</span>
                        </button>
                    
                    <div id="details-{{ $class['id'] }}" class="hidden">
                        <!-- Session Details -->
                        @if(count($class['session_details']) > 0)
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-3">{{ __('admin/reports/index.attendance.session_history') }}</h4>
                            <div class="grid gap-3">
                                @foreach($class['session_details'] as $session)
                                <div class="bg-white rounded-lg p-4 border">
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($session['date'])->format('d F Y') }}</span>
                                            <div class="flex gap-4 text-sm">
                                            	<span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>{{ $session['present'] }} {{ __('admin/reports/index.attendance.present') }}</span>
                                            	<span class="text-yellow-600"><i class="fas fa-clock mr-1"></i>{{ $session['late'] }} {{ __('admin/reports/index.attendance.late') }}</span>
                                            	<span class="text-red-600"><i class="fas fa-times-circle mr-1"></i>{{ $session['absent'] }} {{ __('admin/reports/index.attendance.absent') }}</span>
                                        	</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Notes from Teacher -->
                        @if(count($class['notes']) > 0)
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-3">{{ __('admin/reports/index.attendance.teacher_notes') }}</h4>
                            <div class="space-y-2">
                                @foreach($class['notes'] as $note)
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded">
                                    <p class="text-gray-700">{{ $note }}</p>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Class Photos -->
                        @if(count($class['class_photos']) > 0)
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-3">{{ __('admin/reports/index.attendance.class_photos') }}</h4>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($class['class_photos'] as $photo)
                                <div class="aspect-square bg-gray-200 rounded-lg overflow-hidden">
                                    <img src="{{ asset('storage/' . $photo) }}" alt="{{ __('admin/reports/index.attendance.class_photos') }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-200">
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Section 2: Perbandingan Prestasi -->
    <div id="section-performance" class="section-content hidden">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                <i class="fas fa-chart-bar text-green-600"></i>
                    {{ __('admin/reports/index.performance.title') }}
            </h2>
            
            <div class="bg-gray-50 rounded-xl p-6 mb-6">
                <div class="h-96">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>

            <!-- Performance Summary Table -->
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('admin/reports/index.performance.table_headers.class') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('admin/reports/index.performance.table_headers.attendance_rate') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('admin/reports/index.performance.table_headers.status') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('admin/reports/index.performance.table_headers.total_sessions') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('admin/reports/index.performance.table_headers.target') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($classAttendanceReports as $class)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $class['class_name'] }}</div>
                                    <div class="text-sm text-gray-500">{{ $class['teacher_name'] }}</div>
                                    <div class="text-xs text-gray-400">
                                        {{ $class['course_name'] }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-full bg-gray-200 rounded-full h-2 mr-3">
                                            <div class="h-2 rounded-full {{ $class['attendance_rate'] >= 80 ? 'bg-green-500' : ($class['attendance_rate'] >= 60 ? 'bg-yellow-500' : 'bg-red-500') }}" 
                                                style="width: {{ $class['attendance_rate'] }}%"></div>
                                        </div>
                                        <span class="text-sm font-medium {{ $class['attendance_rate'] >= 80 ? 'text-green-600' : ($class['attendance_rate'] >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $class['attendance_rate'] }}%
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($class['attendance_rate'] >= 80)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>{{ __('admin/reports/index.performance.status.excellent') }}
                                        </span>
                                    @elseif($class['attendance_rate'] >= 60)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>{{ __('admin/reports/index.performance.status.good') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>{{ __('admin/reports/index.performance.status.needs_attention') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $class['total_sessions'] }} {{ __('admin/reports/index.minimum_sessions_unit.session') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $class['minimum_sessions'] }} {{ __('admin/reports/index.minimum_sessions_unit.'.$class['minimum_sessions_unit']) }}
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Section 3: Kelas Batal -->
    <div id="section-cancelled" class="section-content hidden">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                <i class="fas fa-times-circle text-red-600"></i>
                {{ __('admin/reports/index.cancelled.title') }}
            </h2>
            
            @if(count($cancelledClasses) > 0)
                <div class="grid gap-4">
                    @foreach($cancelledClasses as $cancelled)
                    <div class="bg-red-50 border-l-4 border-red-400 rounded-lg p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-red-800">{{ $cancelled['class_name'] }}</h3>
                                <p class="text-red-700">Guru: {{ $cancelled['teacher_name'] }}</p>
                                <p class="text-sm text-red-600 mt-1">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ \Carbon\Carbon::parse($cancelled['date'])->format('d F Y') }} - {{ $cancelled['time'] }}
                                </p>
                                @if($cancelled['note'])
                                    <div class="mt-3 p-3 bg-white rounded border">
                                        <p class="text-sm text-gray-700"><strong>{{ __('admin/reports/index.cancelled.note_label') }}</strong> {{ $cancelled['note'] }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-ban mr-1"></i>
                                    {{ __('admin/reports/index.cancelled.status_cancelled') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="mx-auto h-24 w-24 text-gray-400 mb-4">
                        <i class="fas fa-check-circle text-6xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('admin/reports/index.cancelled.no_classes') }}</h3>
                    <p class="text-gray-500">{{ __('admin/reports/index.cancelled.no_classes_message') }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    // Auto-submit form when filter changes
    document.querySelectorAll('select[name="month"], select[name="year"]').forEach(select => {
        select.addEventListener('change', function() {
            // Add loading state
            const form = this.closest('form');
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>' + @json(__('admin/reports/index.filters.loading'));
            submitBtn.disabled = true;
            
            // Submit form
            form.submit();
        });
    });

    // Toggle functionality
    function showSection(sectionName) {
        // Hide all sections
        document.querySelectorAll('.section-content').forEach(section => {
            section.classList.add('hidden');
        });
        
        // Remove active class from all buttons
        document.querySelectorAll('.toggle-btn').forEach(btn => {
            btn.classList.remove('active');
            btn.classList.add('text-gray-600', 'hover:text-gray-800', 'hover:bg-gray-100');
            btn.classList.remove('bg-blue-600', 'text-white', 'shadow-lg');
        });
        
        // Show selected section
        document.getElementById(`section-${sectionName}`).classList.remove('hidden');
        
        // Add active class to clicked button
        const activeBtn = document.getElementById(`btn-${sectionName}`);
        activeBtn.classList.add('active', 'bg-blue-600', 'text-white', 'shadow-lg');
        activeBtn.classList.remove('text-gray-600', 'hover:text-gray-800', 'hover:bg-gray-100');

        // Initialize chart if performance section is shown
        if (sectionName === 'performance') {
            setTimeout(() => {
                initPerformanceChart();
            }, 100);
        }
    }

    // Toggle class details
    function toggleDetails(classId) {
        const details = document.getElementById(`details-${classId}`);
        const chevron = document.getElementById(`chevron-${classId}`);
        
        if (details.classList.contains('hidden')) {
            details.classList.remove('hidden');
            chevron.style.transform = 'rotate(180deg)';
        } else {
            details.classList.add('hidden');
            chevron.style.transform = 'rotate(0deg)';
        }
    }

    // Initialize performance chart
    let performanceChart;
    
    console.log(@json($classPerformanceChart));
    function initPerformanceChart() {
        const ctx = document.getElementById('performanceChart')?.getContext('2d');
        if (!ctx) return;

        if (performanceChart) {
            performanceChart.destroy();
        }

        const chartData = @json($classPerformanceChart);

        performanceChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: @json(__('admin/reports/index.performance.chart_label')),
                    data: chartData.data,
                    backgroundColor: chartData.colors,
                    borderColor: chartData.colors, // ✅ Fix di sini
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return @json(__('admin/reports/index.performance.tooltip_label_prefix')) + ` ${context.parsed.y}%`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        },
                        grid: { color: 'rgba(0, 0, 0, 0.1)' }
                    },
                    x: { grid: { display: false } }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeInOutQuart'
                }
            }
        });
    }


    // Initialize default styles for toggle buttons
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.toggle-btn:not(.active)').forEach(btn => {
            btn.classList.add('text-gray-600', 'hover:text-gray-800', 'hover:bg-gray-100');
        });
        
        document.querySelector('.toggle-btn.active').classList.add('bg-blue-600', 'text-white', 'shadow-lg');
    });

    // Add smooth transitions
    document.querySelectorAll('.section-content').forEach(section => {
        section.style.transition = 'all 0.3s ease-in-out';
    });

    // Add loading states for download buttons
    document.querySelectorAll('a[href*="/download/"]').forEach(link => {
        link.addEventListener('click', function() {
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>' + @json(__('admin/reports/index.buttons.downloading'));
            this.classList.add('opacity-75', 'cursor-not-allowed');
            
            setTimeout(() => {
                this.innerHTML = originalText;
                this.classList.remove('opacity-75', 'cursor-not-allowed');
            }, 2000);
        });
    });

    // Add hover effects for cards
    document.querySelectorAll('[class*="hover:shadow-lg"]').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Add filter animation
    // document.querySelector('form').addEventListener('submit', function() {
    //     document.body.classList.add('opacity-75');
    //     const loadingOverlay = document.createElement('div');
    //     loadingOverlay.className = 'fixed inset-0 bg-white bg-opacity-50 flex items-center justify-center z-50';
    //     loadingOverlay.innerHTML = `
    //         <div class="bg-white rounded-lg shadow-xl p-6 flex items-center">
    //             <i class="fas fa-spinner fa-spin text-2xl text-blue-600 mr-3"></i>
    //             <span class="text-gray-700 font-medium">Memuat laporan...</span>
    //         </div>
    //     `;
    //     document.body.appendChild(loadingOverlay);
    // });
</script>
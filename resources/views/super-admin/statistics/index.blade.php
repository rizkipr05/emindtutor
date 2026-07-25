@extends('layouts.superadmin')

@section('title', __('super_admin/statistics.header'))

@section('content')
<div class="min-h-screen">
    <div class="mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">{{ __('super_admin/statistics.header') }}</h1>
            <p class="mt-2 text-gray-600">{{ __('super_admin/statistics.subheader') }}</p>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <form method="GET" action="{{ route('superadmin.stats.index') }}" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-48">
                    <label for="period" class="block text-sm font-medium text-gray-700 mb-2">{{ __('super_admin/statistics.filter.period_label') }}</label>
                    <select name="period" id="period" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>{{ __('super_admin/statistics.period_options.daily') }}</option>
                        <option value="weekly" {{ $period == 'weekly' ? 'selected' : '' }}>{{ __('super_admin/statistics.period_options.weekly') }}</option>
                        <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>{{ __('super_admin/statistics.period_options.monthly') }}</option>
                    </select>
                </div>
                <div class="flex-1 min-w-48">
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">{{ __('super_admin/statistics.filter.start_date') }}</label>
                    <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="flex-1 min-w-48">
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">{{ __('super_admin/statistics.filter.end_date') }}</label>
                    <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition duration-150 ease-in-out">
                        {{ __('super_admin/statistics.filter.apply') }}
                    </button>
                    <a href="{{ route('superadmin.stats.export.pdf', ['period' => $period, 'startDate' => $startDate, 'endDate' => $endDate]) }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md font-medium transition duration-150 ease-in-out flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        {{ __('super_admin/statistics.export_pdf') }}
                    </a>
                    <a href="{{ route('superadmin.stats.export.excel', ['period' => $period, 'startDate' => $startDate, 'endDate' => $endDate]) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium transition duration-150 ease-in-out flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        {{ __('super_admin/statistics.export_excel') }}
                    </a>
                </div>
            </form>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('super_admin/statistics.summary_cards.total_classes') }}</h3>
                        <p class="text-2xl font-bold text-blue-600">{{ collect($chartData['course'])->sum('total') }}</p>
                        <p class="text-sm text-gray-600">{{ __('super_admin/statistics.summary_cards.total_classes_desc') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('super_admin/statistics.summary_cards.total_students') }}</h3>
                        <p class="text-2xl font-bold text-green-600">{{ collect($chartData['student'])->sum('total') }}</p>
                        <p class="text-sm text-gray-600">{{ __('super_admin/statistics.summary_cards.total_students_desc') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('super_admin/statistics.summary_cards.total_revenue') }}</h3>
                        <p class="text-2xl font-bold text-yellow-600">RM {{ number_format(collect($chartData['revenue'])->sum('total'), 2) }}</p>
                        <p class="text-sm text-gray-600">{{ __('super_admin/statistics.summary_cards.total_revenue_desc') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="space-y-8">
            <!-- Course Chart -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">{{ __('super_admin/statistics.charts.classes_title') }}</h2>
                    <span class="text-sm text-gray-500">{{ __('super_admin/statistics.charts.classes_sub') }}</span>
                </div>
                <div class="h-80">
                    <canvas id="courseChart"></canvas>
                </div>
            </div>

            <!-- Student Chart -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">{{ __('super_admin/statistics.charts.students_title') }}</h2>
                    <span class="text-sm text-gray-500">{{ ucfirst($period) }} - {{ $startDate }} {{ __('super_admin/statistics.charts.period_label', ['period' => '', 'start' => $startDate, 'end' => $endDate]) }}</span>
                </div>
                <div class="h-80">
                    <canvas id="studentChart"></canvas>
                </div>
            </div>

            <!-- Revenue Chart -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">{{ __('super_admin/statistics.charts.revenue_title') }}</h2>
                    <span class="text-sm text-gray-500">{{ ucfirst($period) }} - {{ $startDate }} {{ __('super_admin/statistics.charts.period_label', ['period' => '', 'start' => $startDate, 'end' => $endDate]) }}</span>
                </div>
                <div class="h-80">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

<script>
// Chart configuration
const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'top',
        },
        tooltip: {
            mode: 'index',
            intersect: false,
        }
    },
    scales: {
        y: {
            beginAtZero: true
        }
    }
};

// Generate colors for companies
const colors = [
    '#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6',
    '#EC4899', '#14B8A6', '#F97316', '#6366F1', '#84CC16'
];

// Course Chart (Bar Chart)
const courseData = @json($chartData['course']);
const courseChart = new Chart(document.getElementById('courseChart'), {
    type: 'bar',
    data: {
        labels: courseData.map(item => item.company_name),
        datasets: [{
            label: "{{ __('super_admin/statistics.charts.classes_title') }}",
            data: courseData.map(item => item.total),
            backgroundColor: colors.slice(0, courseData.length),
            borderColor: colors.slice(0, courseData.length),
            borderWidth: 1
        }]
    },
    options: {
        ...chartOptions,
        plugins: {
            ...chartOptions.plugins,
            title: {
                display: true,
                text: "{{ __('super_admin/statistics.charts.classes_title') }}"
            }
        }
    }
});

// Student Chart (Bar Chart)
const studentData = @json($chartData['student']);
const studentChart = new Chart(document.getElementById('studentChart'), {
    type: 'bar',
    data: {
        labels: studentData[0]?.data?.map(item => item.period) || [],
        datasets: studentData.map((company, index) => ({
            label: company.company_name,
            data: company.data?.map(item => item.count) || [],
            backgroundColor: colors[index % colors.length],
            borderColor: colors[index % colors.length],
            borderWidth: 1
        }))
    },
    options: {
        ...chartOptions,
        plugins: {
            ...chartOptions.plugins,
            title: {
                display: true,
                text: "{{ __('super_admin/statistics.charts.students_title') }}"
            }
        }
    }
});

// Revenue Chart (Line Chart)
const revenueData = @json($chartData['revenue']);
const revenueChart = new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: revenueData[0]?.data?.map(item => item.period) || [],
        datasets: revenueData.map((company, index) => ({
            label: company.company_name,
            data: company.data?.map(item => item.amount) || [],
            borderColor: colors[index % colors.length],
            backgroundColor: colors[index % colors.length] + '20',
            fill: false,
            tension: 0.4
        }))
    },
    options: {
        ...chartOptions,
        plugins: {
            ...chartOptions.plugins,
            title: {
                display: true,
                text: "{{ __('super_admin/statistics.charts.revenue_chart_title') }}"
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'RM ' + value.toLocaleString();
                    }
                }
            }
        }
    }
});

</script>
@endsection
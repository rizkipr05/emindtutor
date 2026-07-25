@php
    $role = auth()->user()->role;
    // Konversi collection ke array jika diperlukan
    $dataArray = is_array($data) ? $data : (is_object($data) && method_exists($data, 'toArray') ? $data->toArray() : []);
    $totalInvoices = array_sum($dataArray);
@endphp

@extends(
    $role === 'super admin' ? 'layouts.superadmin'
    : ($role === 'admin' ? 'layouts.admin'
    : ($role === 'teacher' ? 'layouts.teacher'
    : 'layouts.student'))
)

@section('content')
<div class="max-w-6xl mx-auto px-4">
    <!-- Header Card with Gradient -->
    <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl shadow-xl p-8 mb-6 text-white">
        <div class="flex justify-between items-center">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold">Payment Analytics</h2>
                </div>
                <p class="text-teal-100 ml-14">Track invoice status and payment distribution</p>
            </div>
            
            <div class="text-right">
                <p class="text-teal-100 text-sm mb-1">Total Invoices</p>
                <p class="text-5xl font-bold">{{ $totalInvoices }}</p>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        @if(empty($labels) || empty($data) || count($labels) === 0)
            <div class="p-16 text-center">
                <div class="inline-block p-6 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full mb-4">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No Invoice Data</h3>
                <p class="text-gray-500">No invoice data available yet. Check back later!</p>
            </div>
        @else
            <div class="p-8">
                <!-- Stats Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Paid Card -->
                    <div class="relative overflow-hidden bg-gradient-to-br from-green-50 to-emerald-100 rounded-2xl p-6 border-2 border-green-200 hover:shadow-lg transition-all duration-300 group">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-green-500/10 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between mb-3">
                                <div class="bg-green-500 rounded-xl p-3 shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-green-600 text-sm font-semibold px-3 py-1 bg-green-200 rounded-full">
                                    {{ $totalInvoices > 0 ? round(($data[0] ?? 0) / $totalInvoices * 100) : 0 }}%
                                </span>
                            </div>
                            <p class="text-green-700 font-semibold text-sm mb-1 uppercase tracking-wide">Paid</p>
                            <p class="text-green-900 text-4xl font-bold">{{ $data[0] ?? 0 }}</p>
                            <p class="text-green-600 text-xs mt-2">Completed payments</p>
                        </div>
                    </div>

                    <!-- Overdue Card -->
                    <div class="relative overflow-hidden bg-gradient-to-br from-red-50 to-rose-100 rounded-2xl p-6 border-2 border-red-200 hover:shadow-lg transition-all duration-300 group">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-red-500/10 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between mb-3">
                                <div class="bg-red-500 rounded-xl p-3 shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-red-600 text-sm font-semibold px-3 py-1 bg-red-200 rounded-full">
                                    {{ $totalInvoices > 0 ? round(($data[1] ?? 0) / $totalInvoices * 100) : 0 }}%
                                </span>
                            </div>
                            <p class="text-red-700 font-semibold text-sm mb-1 uppercase tracking-wide">Overdue</p>
                            <p class="text-red-900 text-4xl font-bold">{{ $data[1] ?? 0 }}</p>
                            <p class="text-red-600 text-xs mt-2">Requires attention</p>
                        </div>
                    </div>

                    <!-- Upcoming Card -->
                    <div class="relative overflow-hidden bg-gradient-to-br from-yellow-50 to-amber-100 rounded-2xl p-6 border-2 border-yellow-200 hover:shadow-lg transition-all duration-300 group">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-yellow-500/10 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between mb-3">
                                <div class="bg-yellow-500 rounded-xl p-3 shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-yellow-600 text-sm font-semibold px-3 py-1 bg-yellow-200 rounded-full">
                                    {{ $totalInvoices > 0 ? round(($data[2] ?? 0) / $totalInvoices * 100) : 0 }}%
                                </span>
                            </div>
                            <p class="text-yellow-700 font-semibold text-sm mb-1 uppercase tracking-wide">Upcoming</p>
                            <p class="text-yellow-900 text-4xl font-bold">{{ $data[2] ?? 0 }}</p>
                            <p class="text-yellow-600 text-xs mt-2">Pending payments</p>
                        </div>
                    </div>
                </div>

                <!-- Chart Container -->
                <div class="bg-gradient-to-br from-gray-50 to-slate-100 rounded-2xl p-8 shadow-inner">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-800">Invoice Status Distribution</h3>
                        <div class="flex gap-2">
                            <span class="px-3 py-1 bg-white rounded-lg text-xs font-semibold text-gray-600 shadow-sm">Visual Overview</span>
                        </div>
                    </div>
                    <canvas id="invoicesChart" height="100"></canvas>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
@if(!empty($labels) && !empty($data))
const ctx = document.getElementById('invoicesChart').getContext('2d');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($labels),
        datasets: [{
            label: 'Number of Invoices',
            data: @json($data),
            backgroundColor: [
                'rgba(34, 197, 94, 0.6)',   // Paid (green)
                'rgba(239, 68, 68, 0.6)',   // Overdue (red)
                'rgba(234, 179, 8, 0.6)'    // Upcoming (yellow)
            ],
            borderColor: [
                'rgba(34, 197, 94, 1)',
                'rgba(239, 68, 68, 1)',
                'rgba(234, 179, 8, 1)'
            ],
            borderWidth: 2,
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            title: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 },
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});
@endif
</script>
@endsection
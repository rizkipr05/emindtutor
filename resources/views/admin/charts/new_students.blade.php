@php
    $role = auth()->user()->role;
    // Konversi collection ke array jika diperlukan
    $dataArray = is_array($data) ? $data : (is_object($data) && method_exists($data, 'toArray') ? $data->toArray() : []);
@endphp

@extends($role === 'super admin' ? 'layouts.superadmin' 
    : ($role === 'admin' ? 'layouts.admin' 
    : ($role === 'teacher' ? 'layouts.teacher' 
    : 'layouts.student')))
@section('content')

<div class="max-w-6xl mx-auto px-4">
    <!-- Header Card with Gradient -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-xl p-8 mb-6 text-white">
        <div class="flex justify-between items-center">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold">New Students Analytics</h2>
                </div>
                <p class="text-blue-100 ml-14">Track student enrollment trends over time</p>
            </div>
            
            <div class="bg-white/10 backdrop-blur-md rounded-xl p-1 shadow-lg">
                <form method="GET" action="{{ route('analytics.new-students') }}">
                    <select name="type" onchange="this.form.submit()" 
                        class="bg-white text-gray-700 px-5 py-3 rounded-lg font-semibold cursor-pointer focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all duration-200 hover:bg-gray-50">
                        <option value="monthly" {{ $type === 'monthly' ? 'selected' : '' }}>📅 Monthly View</option>
                        <option value="weekly" {{ $type === 'weekly' ? 'selected' : '' }}>📊 Weekly View</option>
                    </select>
                </form>
            </div>
        </div>
    </div>

    <!-- Chart Card -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        @if(empty($labels) || empty($data) || count($labels) === 0)
            <div class="p-16 text-center">
                <div class="inline-block p-6 bg-gray-100 rounded-full mb-4">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No Data Available</h3>
                <p class="text-gray-500">No student registration data available yet. Check back later!</p>
            </div>
        @else
            <div class="p-8">
                <!-- Chart Container -->
                <div class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl p-6 shadow-inner">
                    <canvas id="studentsChart" height="100"></canvas>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
@if(!empty($labels) && !empty($data))
    const ctx = document.getElementById('studentsChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'New Students',
                data: @json($data),
                borderColor: 'rgba(37, 99, 235, 1)',
                backgroundColor: 'rgba(37, 99, 235, 0.2)',
                tension: 0.3,
                fill: true,
                borderWidth: 2,
                pointBackgroundColor: 'rgba(37, 99, 235, 1)',
                pointRadius: 4,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            },
            plugins: {
                legend: { display: true, position: 'bottom' },
                title: {
                    display: true,
                    text: '{{ ucfirst($type) }} New Student Statistics',
                    font: { size: 16 }
                }
            }
        }
    });
@endif
</script>
@endsection
@php
    $role = auth()->user()->role;
    // Konversi collection ke array jika diperlukan
    $revenuesArray = is_array($revenues) ? $revenues : (is_object($revenues) && method_exists($revenues, 'toArray') ? $revenues->toArray() : []);
    
    // Calculate totals
    $totalRevenue = array_sum($revenuesArray);
    $totalPayments = ($paymentData['paid'] ?? 0) + ($paymentData['overdue'] ?? 0) + ($paymentData['upcoming'] ?? 0);
    $paidPercentage = $totalPayments > 0 ? round(($paymentData['paid'] ?? 0) / $totalPayments * 100) : 0;
@endphp

@extends('layouts.' . ($role === 'super admin' ? 'superadmin' : 'admin'))

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-2xl shadow-xl p-8 mb-6 text-white">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold">Sales Analytics</h2>
                </div>
                <p class="text-purple-100 ml-14">Comprehensive revenue and payment insights</p>
            </div>
            
            <!-- Quick Stats -->
            <div class="flex gap-4">
                <div class="bg-white/10 backdrop-blur-md rounded-xl px-6 py-3 border border-white/20">
                    <p class="text-purple-100 text-xs mb-1">Total Revenue</p>
                    <p class="text-2xl font-bold">RM {{ number_format($totalRevenue, 2) }}</p>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-xl px-6 py-3 border border-white/20">
                    <p class="text-purple-100 text-xs mb-1">Payment Rate</p>
                    <p class="text-2xl font-bold">{{ $paidPercentage }}%</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- 1️⃣ Revenue Tracking --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-cyan-500 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white">Revenue Tracking</h3>
                </div>
                <p class="text-blue-100 text-sm mt-1 ml-11">Monthly revenue performance</p>
            </div>
            
            <div class="p-6">
                <!-- Revenue Stats Mini Cards -->
                <div class="grid grid-cols-3 gap-3 mb-6">
                    <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-lg p-3 text-center border border-blue-100">
                        <p class="text-xs text-blue-600 font-semibold mb-1">Peak Month</p>
                        <p class="text-lg font-bold text-blue-700">RM {{ count($revenuesArray) > 0 ? number_format(max($revenuesArray), 0) : 0 }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-lg p-3 text-center border border-indigo-100">
                        <p class="text-xs text-indigo-600 font-semibold mb-1">Average</p>
                        <p class="text-lg font-bold text-indigo-700">RM {{ count($revenuesArray) > 0 ? number_format(array_sum($revenuesArray) / count($revenuesArray), 0) : 0 }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-teal-50 to-emerald-50 rounded-lg p-3 text-center border border-teal-100">
                        <p class="text-xs text-teal-600 font-semibold mb-1">Total</p>
                        <p class="text-lg font-bold text-teal-700">RM {{ number_format($totalRevenue, 0) }}</p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl p-4 shadow-inner">
                    <canvas id="revenueChart" height="200"></canvas>
                </div>
            </div>
        </div>

        {{-- 2️⃣ Payment Analytics --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-500 to-teal-500 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white">Payment Analytics</h3>
                </div>
                <p class="text-emerald-100 text-sm mt-1 ml-11">Payment status distribution</p>
            </div>
            
            <div class="p-6">
                <!-- Payment Stats Cards -->
                <div class="grid grid-cols-3 gap-3 mb-6">
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-4 text-center border-2 border-green-200">
                        <div class="flex justify-center mb-2">
                            <div class="bg-green-500 rounded-full p-2">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs text-green-600 font-semibold mb-1">Paid</p>
                        <p class="text-2xl font-bold text-green-700">{{ $paymentData['paid'] ?? 0 }}</p>
                        <p class="text-xs text-green-500 mt-1">{{ $totalPayments > 0 ? round(($paymentData['paid'] ?? 0) / $totalPayments * 100) : 0 }}%</p>
                    </div>
                    
                    <div class="bg-gradient-to-br from-red-50 to-rose-50 rounded-lg p-4 text-center border-2 border-red-200">
                        <div class="flex justify-center mb-2">
                            <div class="bg-red-500 rounded-full p-2">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs text-red-600 font-semibold mb-1">Overdue</p>
                        <p class="text-2xl font-bold text-red-700">{{ $paymentData['overdue'] ?? 0 }}</p>
                        <p class="text-xs text-red-500 mt-1">{{ $totalPayments > 0 ? round(($paymentData['overdue'] ?? 0) / $totalPayments * 100) : 0 }}%</p>
                    </div>
                    
                    <div class="bg-gradient-to-br from-yellow-50 to-amber-50 rounded-lg p-4 text-center border-2 border-yellow-200">
                        <div class="flex justify-center mb-2">
                            <div class="bg-yellow-500 rounded-full p-2">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs text-yellow-600 font-semibold mb-1">Upcoming</p>
                        <p class="text-2xl font-bold text-yellow-700">{{ $paymentData['upcoming'] ?? 0 }}</p>
                        <p class="text-xs text-yellow-500 mt-1">{{ $totalPayments > 0 ? round(($paymentData['upcoming'] ?? 0) / $totalPayments * 100) : 0 }}%</p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-gray-50 to-emerald-50 rounded-xl p-4 shadow-inner">
                    <canvas id="paymentChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Data dari controller
    const months = @json($months);
    const revenues = @json($revenues);
    const paymentData = @json($paymentData);

    // 🎯 1️⃣ Revenue Tracking Chart
    const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctxRevenue, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Total Revenue (RM)',
                data: revenues,
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgba(37, 99, 235, 1)',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Revenue (RM)',
                        font: { size: 12, weight: 'bold' },
                        color: '#4B5563'
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Month',
                        font: { size: 12, weight: 'bold' },
                        color: '#4B5563'
                    },
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: { display: false },
                title: { display: false }
            }
        }
    });

    // 🎯 2️⃣ Payment Analytics (Doughnut Chart)
    const ctxPayment = document.getElementById('paymentChart').getContext('2d');
    new Chart(ctxPayment, {
        type: 'doughnut',
        data: {
            labels: ['Paid', 'Overdue', 'Upcoming'],
            datasets: [{
                data: [
                    paymentData.paid,
                    paymentData.overdue,
                    paymentData.upcoming
                ],
                backgroundColor: [
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(234, 179, 8, 0.8)'
                ],
                borderColor: [
                    'rgba(22, 163, 74, 1)',
                    'rgba(220, 38, 38, 1)',
                    'rgba(202, 138, 4, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { 
                        font: { size: 12, weight: 'bold' },
                        padding: 15,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                }
            }
        }
    });
</script>
@endsection
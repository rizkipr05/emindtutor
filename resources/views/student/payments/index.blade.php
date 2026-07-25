@extends('layouts.student')

@section('title', __('student/payments/index.title'))

@section('content')
<div class="min-h-screen">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h1 class="text-3xl font-bold text-gray-900">{{ __('student/payments/index.title') }}</h1>
                    <p class="mt-2 text-sm text-gray-600">
                        {{ __('student/payments/index.subtitle') }}
                    </p>
                </div>
                <div class="mt-4 flex space-x-3 md:mt-0 md:ml-4">
                    <span class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        {{ count($upcomingInvoices) }} {{ __('student/payments/index.tabs.upcoming') }}
                    </span>
                    <span class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        {{ count($paidInvoices) }} {{ __('student/payments/index.tabs.paid') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button onclick="showTab('upcoming')" id="upcoming-tab" class="tab-button active whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200">
                        {{ __('student/payments/index.tabs.upcoming') }}
                        <span class="ml-2 bg-yellow-100 text-yellow-800 py-0.5 px-2.5 rounded-full text-xs font-medium">
                            {{ count($upcomingInvoices) }}
                        </span>
                    </button>
                    <button onclick="showTab('paid')" id="paid-tab" class="tab-button whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200">
                        {{ __('student/payments/index.tabs.paid') }}
                        <span class="ml-2 bg-green-100 text-green-800 py-0.5 px-2.5 rounded-full text-xs font-medium">
                            {{ count($paidInvoices) }}
                        </span>
                    </button>
                </nav>
            </div>
        </div>

        <!-- Upcoming Invoices Tab -->
        <div id="upcoming-content" class="tab-content">
            @if($upcomingInvoices->count() > 0)
                <!-- Summary Card for Upcoming -->
                <div class="mb-6 bg-white rounded-lg shadow-md border border-gray-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('student/payments/index.summary_upcoming_title') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="text-center p-4 bg-yellow-50 rounded-lg">
                                <p class="text-2xl font-bold text-yellow-600">{{ count($upcomingInvoices) }}</p>
                                <p class="text-sm text-yellow-800">{{ __('student/payments/index.summary.total_invoices') }}</p>
                            </div>
                            <div class="text-center p-4 bg-blue-50 rounded-lg">
                                <p class="text-2xl font-bold text-blue-600">
                                    RM {{ number_format($upcomingInvoices->sum('jumlah') ?? 0, 0, ',', '.') }}
                                </p>
                                <p class="text-sm text-blue-800">{{ __('student/payments/index.summary.total_amount') }}</p>
                            </div>
                            <div class="text-center p-4 bg-red-50 rounded-lg">
                                <p class="text-2xl font-bold text-red-600">
                                    {{ $upcomingInvoices->filter(function($invoice) { 
                                        return $invoice->due_date && \Carbon\Carbon::parse($invoice->due_date)->isPast(); 
                                    })->count() }}
                                </p>
                                <p class="text-sm text-red-800">{{ __('student/payments/index.summary.overdue') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Invoices Grid -->
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($upcomingInvoices as $invoice)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-200">
                        <div class="p-6">
                            <!-- Invoice Header -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ __('student/payments/index.invoice.number', ['number' => $invoice->nomor_invoice ?? 'INV-' . $invoice->id]) }}</p>
                                        <p class="text-sm text-gray-500">{{ $invoice->description ?? __('student/payments/index.invoice.default_description') }}</p>
                                    </div>
                                </div>

                                @php
                                    // determine latest payment safely
                                    $latestPayment = null;
                                    if (method_exists($invoice, 'payments')) {
                                        // payments() should be hasMany; get latest via query
                                        $latestPayment = $invoice->payments()->latest()->first();
                                    } elseif (isset($invoice->payment)) {
                                        // fallback: payment property may be a collection or single model
                                        if ($invoice->payment instanceof \Illuminate\Support\Collection) {
                                            $latestPayment = $invoice->payment->last();
                                        } else {
                                            $latestPayment = $invoice->payment;
                                        }
                                    }
                                @endphp

                                @if ($latestPayment)
                                    @if (isset($latestPayment->status) && $latestPayment->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            {{ __('student/payments/index.invoice.status.pending') }}
                                        </span>
                                    @elseif (isset($latestPayment->status) && $latestPayment->status === 'success')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ __('student/payments/index.invoice.status.success') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ __('student/payments/index.invoice.status.failed') }}
                                        </span>
                                    @endif
                                @else
                                    {{-- belum ada payment sama sekali --}}
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ __('student/payments/index.invoice.status.unpaid') }}
                                    </span>
                                @endif


                            </div>

                            <!-- Invoice Details -->
                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">{{ __('student/payments/index.invoice.labels.amount') }}</span>
                                    <span class="text-sm font-semibold text-gray-900">
                                        RM {{ number_format($invoice->jumlah ?? 0, 0, ',', '.') }}
                                    </span>
                                </div>
                                
                                @if($invoice->due_date)
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">{{ __('student/payments/index.invoice.labels.due_date') }}</span>
                                    <span class="text-sm text-gray-900">
                                        @php $d_due = \Carbon\Carbon::parse($invoice->due_date); @endphp
                                        {{ $d_due->format('d') }} {{ __('student/payments/index.months.' . $d_due->format('n')) }} {{ $d_due->format('Y') }}
                                    </span>
                                </div>
                                @endif

                                @if($invoice->period)
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">{{ __('student/payments/index.invoice.labels.period') }}</span>
                                    <span class="text-sm text-gray-900">{{ $invoice->period }}</span>
                                </div>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-3">
                                @if (!$invoice->payment)
                                    {{-- Belum ada payment, boleh bayar --}}
                                    <a href="{{ route('student.payments.create', $invoice->id) }}" 
                                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        {{ __('student/payments/index.invoice.actions.pay_now') }}
                                    </a>
                                @else
                                    {{-- Sudah ada payment, disable tombol --}}
                                    <button disabled
                                        class="flex-1 bg-gray-400 text-white text-sm font-medium py-2 px-4 rounded-md cursor-not-allowed">
                                        {{ __('student/payments/index.invoice.actions.paid_or_pending') }}
                                    </button>
                                @endif
                            </div>


                            @if($invoice->due_date && \Carbon\Carbon::parse($invoice->due_date)->isPast())
                            <div class="mt-3 p-2 bg-red-50 border border-red-200 rounded-md">
                                <p class="text-xs text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ __('student/payments/index.invoice.overdue_notice') }}
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

            @else
                <!-- Empty State for Upcoming -->
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('student/payments/index.empty.upcoming_title') }}</h3>
                    <p class="text-gray-600 mb-6">{{ __('student/payments/index.empty.upcoming_desc') }}</p>
                </div>
            @endif
        </div>

        <!-- Paid Invoices Tab -->
        <div id="paid-content" class="tab-content hidden">
            @if($paidInvoices->count() > 0)
                <!-- Summary Card for Paid -->
                <div class="mb-6 bg-white rounded-lg shadow-md border border-gray-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('student/payments/index.summary_upcoming_title') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="text-center p-4 bg-green-50 rounded-lg">
                                <p class="text-2xl font-bold text-green-600">{{ count($paidInvoices) }}</p>
                                <p class="text-sm text-green-800">{{ __('student/payments/index.label_paid_total') }}</p>
                            </div>
                            <div class="text-center p-4 bg-blue-50 rounded-lg">
                                <p class="text-2xl font-bold text-blue-600">
                                    RM {{ number_format($paidInvoices->sum('jumlah') ?? 0, 0, ',', '.') }}
                                </p>
                                <p class="text-sm text-blue-800">{{ __('student/payments/index.label_total_amount') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paid Invoices Grid -->
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($paidInvoices as $invoice)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-200">
                        <div class="p-6">
                            <!-- Invoice Header -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ __('student/payments/index.invoice.number', ['number' => $invoice->nomor_invoice ?? 'INV-' . $invoice->id]) }}</p>
                                        <p class="text-sm text-gray-500">{{ $invoice->description ?? __('student/payments/index.invoice.default_description') }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ __('student/payments/index.status_paid') }}
                                </span>
                            </div>

                            <!-- Invoice Details -->
                            <div class="space-y-3 mb-1">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">{{ __('student/payments/index.invoice.labels.amount') }}</span>
                                    <span class="text-sm font-semibold text-gray-900">
                                        RM {{ number_format($invoice->jumlah ?? 0, 0, ',', '.') }}
                                    </span>
                                </div>
                                
                                @if($invoice->payment && $invoice->payment->dibayar_pada)
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">{{ __('student/payments/index.invoice.labels.due_date') }}</span>
                                        <span class="text-sm text-gray-900">
                                            @php $d_paid = \Carbon\Carbon::parse($invoice->payment->dibayar_pada); @endphp
                                            {{ $d_paid->format('d') }} {{ __('student/payments/index.months.' . $d_paid->format('n')) }} {{ $d_paid->format('Y') }}
                                        </span>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

            @else
                <!-- Empty State for Paid -->
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('student/payments/index.empty.paid_title') }}</h3>
                    <p class="text-gray-600 mb-6">{{ __('student/payments/index.empty.paid_desc') }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .tab-button {
        border-color: transparent;
        color: #6B7280;
    }
    
    .tab-button.active {
        border-color: #3B82F6;
        color: #3B82F6;
    }
    
    .tab-button:hover:not(.active) {
        border-color: #D1D5DB;
        color: #374151;
    }
</style>

<script>
    function showTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        
        // Remove active class from all tabs
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active');
        });
        
        // Show selected tab content
        document.getElementById(tabName + '-content').classList.remove('hidden');
        
        // Add active class to selected tab
        document.getElementById(tabName + '-tab').classList.add('active');
    }
</script>
@endsection
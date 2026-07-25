@extends('layouts.admin')

@section('content')
<div class="min-h-screen">
    <!-- Header Section -->
    <div class="mx-auto">
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ __('admin/bayaran/verify.title') }}</h2>
            <div class="h-1 w-24 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full"></div>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <!-- Status Badge Header -->
            <div class="bg-gradient-to-r from-gray-800 to-gray-700 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-white mb-1">{{ __('admin/bayaran/verify.invoice', ['id' => $payment->studentInvoice->id]) }}</h3>
                        <p class="text-gray-300">{{ $payment->studentInvoice->course->nama_kos ?? '-' }}</p>
                    </div>
                    <div class="flex space-x-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                            @if($payment->studentInvoice->status_bayaran === 'paid')
                                bg-green-100 text-green-800
                            @elseif($payment->studentInvoice->status_bayaran === 'upcoming')
                                bg-yellow-100 text-yellow-800
                            @else
                                bg-red-100 text-red-800
                            @endif">
                            {{ __('admin/bayaran/verify.student_invoice_status.' . $payment->studentInvoice->status_bayaran) }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                            @if($payment->status === 'success')
                                bg-green-100 text-green-800
                            @elseif($payment->status === 'pending')
                                bg-yellow-100 text-yellow-800
                            @else
                                bg-red-100 text-red-800
                            @endif">
                            {{ __('admin/bayaran/verify.payment_status.' . $payment->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="p-8">
                <div class="grid md:grid-cols-2 gap-8 mb-8">
                    <!-- Payment Info -->
                    <div class="space-y-6">
                        <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">{{ __('admin/bayaran/verify.payment_info_title') }}</h4>
                        
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('admin/bayaran/verify.labels.amount_due') }}</p>
                                    <p class="text-2xl font-bold text-gray-900">RM{{ number_format($payment->studentInvoice->jumlah, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('admin/bayaran/verify.labels.invoice_status') }}</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ $payment->studentInvoice->status_bayaran }}</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-2 h-2 bg-purple-500 rounded-full mt-2"></div>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('admin/bayaran/verify.labels.payment_status') }}</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ $payment->status }}</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-2 h-2 bg-orange-500 rounded-full mt-2"></div>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('admin/bayaran/verify.labels.paid_at') }}</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ $payment->dibayar_pada ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Proof -->
                    <div class="space-y-6">
                        <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">{{ __('admin/bayaran/verify.proof_title') }}</h4>
                        
                        <div class="bg-gray-50 rounded-xl p-6 border-2 border-dashed border-gray-300">
                            @if($payment->bukti_bayar)
                                @if(Str::endsWith($payment->bukti_bayar, ['.jpg','.jpeg','.png']))
                                    <div class="text-center">
                                        <img src="{{ asset('storage/' . $payment->bukti_bayar) }}" 
                                             alt="Bukti Bayar" 
                                             class="w-full max-w-sm mx-auto rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                                        <p class="text-sm text-gray-500 mt-2">{{ __('admin/bayaran/verify.proof.click_image') }}</p>
                                    </div>
                                @elseif(Str::endsWith($payment->bukti_bayar, '.pdf'))
                                    <div class="text-center py-8">
                                        <svg class="mx-auto h-16 w-16 text-red-500 mb-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                        </svg>
                                                     <a href="{{ asset('storage/' . $payment->bukti_bayar) }}" 
                                                         target="_blank" 
                                                         class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"></path>
                                                <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"></path>
                                            </svg>
                                            {{ __('admin/bayaran/verify.proof.view_pdf') }}
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-gray-500 font-medium">{{ __('admin/bayaran/verify.proof.empty_title') }}</p>
                                    <p class="text-sm text-gray-400 mt-1">{{ __('admin/bayaran/verify.proof.empty_desc') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                @if($payment->status === 'pending')
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h5 class="text-lg font-semibold text-gray-900">{{ __('admin/bayaran/verify.verify_title') }}</h5>
                                <p class="text-sm text-gray-500">{{ __('admin/bayaran/verify.verify_desc') }}</p>
                            </div>
                            <form action="{{ route('admin.bayaran.verify', $payment->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white text-sm font-medium rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ __('admin/bayaran/verify.verify_button') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
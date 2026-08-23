@extends('layouts.superadmin')

@section('content')
<div class="min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ url()->previous() }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                {{ __('super_admin/companies.show.back') }} 
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-red-800">{{ session('error') }}</div>
        @endif

        <!-- Company Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $company->user->name }}</h1>
                        <p class="text-lg text-gray-600 mt-2">{{ $company->nama_wakil_syarikat }}</p>
                        @if($company->alamat)
                            <p class="text-sm text-gray-500 mt-1">{{ $company->alamat }}</p>
                        @endif
                    </div>
                    <div class="flex items-center space-x-3">
                        <!-- Account Status -->
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $company->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <span class="w-2 h-2 {{ $company->status ? 'bg-green-400' : 'bg-red-400' }} rounded-full mr-2"></span>
                            {{ $company->status ? __('super_admin/companies.show.status.active') : __('super_admin/companies.show.status.inactive') }}
                        </span>
                        
                        <!-- Latest Invoice Status -->
                        @php
                            $latestInvoice = $company->invoices()->latest('tarikh_terbit')->first();
                        @endphp
                        @if($latestInvoice)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                {{ $latestInvoice->status_bayaran === 'paid' ? 'bg-green-100 text-green-800' : 
                                   ($latestInvoice->status_bayaran === 'overdue' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ __('super_admin/companies.show.invoice_status.' . $latestInvoice->status_bayaran) }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                Tiada Invoice
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="p-6 bg-gray-50">
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('companies.account', $company) }}"
                       class="inline-flex items-center px-4 py-2 border border-violet-300 text-sm font-medium rounded-md text-violet-700 bg-white hover:bg-violet-50 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m9 3a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2h5l2 2h7a2 2 0 012 2v10z"/>
                        </svg>
                        Rekod Akaun
                    </a>
                    <a href="{{ route('companies.edit', $company->id) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        {{ __('super_admin/companies.show.actions.edit') }}
                    </a>

                    <form action="{{ route('companies.toggleStatus', $company->id) }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white transition-colors duration-200 {{ $company->status ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}">
                                @if($company->status)
                                <svg class="w-4 h-4 mr-2" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.49 2 2 6.49 2 12s4.49 10 10 10 10-4.49 10-10S17.51 2 12 2M4 12c0-1.85.63-3.54 1.69-4.9L16.9 18.31A8 8 0 0 1 12 20c-4.41 0-8-3.59-8-8m14.31 4.9L7.1 5.69A8 8 0 0 1 12 4c4.41 0 8 3.59 8 8 0 1.85-.63 3.54-1.69 4.9"></path>
                                </svg>
                                {{ __('super_admin/companies.show.actions.deactivate') }}
                            @else
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ __('super_admin/companies.show.actions.activate') }}
                            @endif
                        </button>
                    </form>

                    <form action="{{ route('companies.destroy', $company->id) }}" method="POST" 
                          class="inline-block" onsubmit="return confirm('Hapus perusahaan beserta seluruh akun, kursus, pelajar, invoice, langganan, dan riwayat terkait? Tindakan ini permanen.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            {{ __('super_admin/companies.show.actions.delete') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Company Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        {{ __('super_admin/companies.show.info_title') }}
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="text-sm font-medium text-gray-600">{{ __('super_admin/companies.show.label.name') }}</label>
                            <p class="text-base text-gray-900 font-medium mt-1">{{ $company->user->name }}</p>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="text-sm font-medium text-gray-600">{{ __('super_admin/companies.show.label.rep_name') }}</label>
                            <p class="text-base text-gray-900 mt-1">{{ $company->nama_wakil_syarikat ?: '-' }}</p>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="text-sm font-medium text-gray-600">{{ __('super_admin/companies.show.label.address') }}</label>
                            <p class="text-base text-gray-900 mt-1">{{ $company->alamat ?: __('super_admin/companies.show.label.address') }}</p>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="text-sm font-medium text-gray-600">{{ __('super_admin/companies.show.label.phone') }}</label>
                            <p class="text-base text-gray-900 mt-1">
                                <a href="tel:{{ $company->no_telefon }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $company->no_telefon ?: '-' }}
                                </a>
                            </p>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="text-sm font-medium text-gray-600">{{ __('super_admin/companies.show.label.email') }}</label>
                            <p class="text-base text-gray-900 mt-1">
                                <a href="mailto:{{ $company->user->email }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $company->user->email }}
                                </a>
                            </p>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="text-sm font-medium text-gray-600">{{ __('super_admin/companies.show.label.registered_at') }}</label>
                            <p class="text-base text-gray-900 mt-1">{{ $company->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="text-sm font-medium text-gray-600">{{ __('super_admin/companies.show.label.account_status') }}</label>
                            <div class="mt-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $company->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <span class="w-2 h-2 {{ $company->status ? 'bg-green-400' : 'bg-red-400' }} rounded-full mr-2"></span>
                                    {{ $company->status ? __('super_admin/companies.show.status.active') : __('super_admin/companies.show.status.inactive') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Latest Invoice & Subscription -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        {{ __('super_admin/companies.show.invoice_subscription') }}
                    </h2>
                    
                    @php
                        $latestInvoice = $company->invoices()->latest('tarikh_terbit')->first();
                    @endphp
                    
                    @if($latestInvoice)
                        <!-- Latest Invoice Info -->
                        <div class="border border-gray-200 rounded-lg p-4 mb-6">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-base font-medium text-gray-900">{{ $latestInvoice->nomor_invoice }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">{{ __('super_admin/companies.show.latest_invoice') }}</p>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                    {{ $latestInvoice->status_bayaran === 'paid' ? 'bg-green-100 text-green-800' : 
                                       ($latestInvoice->status_bayaran === 'overdue' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ __('super_admin/companies.show.invoice_status.' . $latestInvoice->status_bayaran) }}
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-600">{{ __('super_admin/companies.show.invoice.amount') }}</label>
                                    <p class="text-lg font-semibold text-gray-900 mt-1">RM {{ number_format($latestInvoice->jumlah, 2) }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">{{ __('super_admin/companies.show.invoice.issued_date') }}</label>
                                    <p class="text-sm text-gray-900 mt-1">{{ \Carbon\Carbon::parse($latestInvoice->tarikh_terbit)->format('d M Y') }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">{{ __('super_admin/companies.show.invoice.end_date') }}</label>
                                    <p class="text-sm text-gray-900 mt-1">{{ \Carbon\Carbon::parse($latestInvoice->tarikh_tamat)->format('d M Y') }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">{{ __('super_admin/companies.show.invoice.period_status') }}</label>
                                    @php
                                        $endDate = \Carbon\Carbon::parse($latestInvoice->tarikh_tamat);
                                        $now = \Carbon\Carbon::now();
                                        $daysRemaining = (int) $now->diffInDays($endDate, false);
                                    @endphp
                                    <p class="text-sm mt-1 font-medium
                                        {{ $daysRemaining > 7 ? 'text-green-600' : 
                                           ($daysRemaining > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                                        @if($daysRemaining > 0)
                                            {{ trans_choice('super_admin/companies.show.invoice.days_remaining', $daysRemaining, ['count' => $daysRemaining]) }}
                                        @elseif($daysRemaining === 0)
                                            {{ __('super_admin/companies.show.invoice.expires_today') }}
                                        @else
                                            {{ __('super_admin/companies.show.invoice.expired_days_ago', ['count' => abs($daysRemaining)]) }}
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <form action="{{ route('companies.invoice-reminder', $company) }}" method="POST" class="mt-5 flex gap-2 border-t border-gray-100 pt-4">
                                @csrf
                                <button name="channel" value="whatsapp" type="submit" class="inline-flex flex-1 items-center justify-center rounded-lg bg-emerald-600 px-3 py-2 text-sm font-medium text-white hover:bg-emerald-700">Kirim WhatsApp</button>
                                <button name="channel" value="email" type="submit" class="inline-flex flex-1 items-center justify-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700">Kirim Email</button>
                            </form>
                        </div>
                        
                        <!-- Current Subscription Status -->
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-blue-900 mb-3">{{ __('super_admin/companies.show.subscription.current_status') }}</h4>
                            <div class="flex items-center justify-between">
                                <div>
                                    @if($latestInvoice->status_bayaran === 'paid' && $daysRemaining > 0)
                                        <span class="inline-flex items-center text-sm text-blue-700">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ __('super_admin/companies.show.subscription.active') }}
                                        </span>
                                    @elseif($latestInvoice->status_bayaran === 'paid' && $daysRemaining <= 0)
                                        <span class="inline-flex items-center text-sm text-red-700">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ __('super_admin/companies.show.subscription.expired') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center text-sm text-yellow-700">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                            {{ __('super_admin/companies.show.subscription.pending') }}
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="text-right">
                                    <p class="text-xs text-blue-600">{{ __('super_admin/companies.show.subscription.period_label') }}</p>
                                    <p class="text-sm font-medium text-blue-800">
                                        {{ \Carbon\Carbon::parse($latestInvoice->tarikh_terbit)->format('d M Y') }} - 
                                        {{ \Carbon\Carbon::parse($latestInvoice->tarikh_tamat)->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- No Invoice -->
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">Tiada Invoice</h3>
                            <p class="mt-2 text-gray-500">Syarikat ini belum mempunyai sebarang invoice atau langganan.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Invoice History -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ __('super_admin/companies.show.invoice_history') }}
                    </h2>
                    
                    @if($company->invoices()->count() > 0)
                        <div class="space-y-3 max-h-96 overflow-y-auto">
                            @foreach($company->invoices()->orderBy('tarikh_terbit', 'desc')->get() as $invoice)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-900">{{ $invoice->nomor_invoice }}</span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                            {{ $invoice->status_bayaran === 'paid' ? 'bg-green-100 text-green-800' : 
                                               ($invoice->status_bayaran === 'overdue' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ __('super_admin/companies.show.invoice_status.' . $latestInvoice->status_bayaran) }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between text-sm text-gray-600">
                                        <span>RM {{ number_format($invoice->jumlah, 2) }}</span>
                                        <span>{{ \Carbon\Carbon::parse($invoice->tarikh_terbit)->format('d M Y') }}</span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ __('super_admin/companies.show.invoice_history_end') }}: {{ \Carbon\Carbon::parse($invoice->tarikh_tamat)->format('d M Y') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Tiada sejarah invoice</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Rekod Akaun dipindahkan ke halaman khusus: companies.account --}}
        @if (false)
        <!-- Rekod Akaun: manual invoice, pembayaran dan baki kredit -->
        @php
            $balanceIsCredit = $accountBalance < 0;
            $balanceClass = $balanceIsCredit ? 'text-emerald-600' : ($accountBalance > 0 ? 'text-red-600' : 'text-gray-900');
        @endphp
        <section class="mt-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Rekod Akaun</h2>
                    <p class="text-sm text-gray-500">Ringkasan invoice dan pembayaran company.</p>
                </div>
                <div class="rounded-lg bg-emerald-50 px-6 py-3 text-center">
                    <p class="text-xs font-medium text-emerald-900">Akaun Semasa (Baki)</p>
                    <p class="text-2xl font-bold {{ $balanceClass }}">{{ $accountBalance < 0 ? '-' : '' }}RM {{ number_format(abs($accountBalance), 2) }}</p>
                    <p class="text-xs font-medium {{ $balanceIsCredit ? 'text-emerald-700' : 'text-gray-600' }}">{{ $balanceIsCredit ? 'Pembayaran berlebihan (kredit)' : ($accountBalance > 0 ? 'Baki perlu dibayar' : 'Tiada baki') }}</p>
                </div>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-3">
                <div class="rounded-lg border border-blue-100 bg-blue-50 p-4"><p class="text-sm text-gray-600">Jumlah Invoice</p><p class="mt-1 text-xl font-bold text-gray-900">RM {{ number_format($totalInvoiceAmount, 2) }}</p></div>
                <div class="rounded-lg border border-emerald-100 bg-emerald-50 p-4"><p class="text-sm text-gray-600">Jumlah Pembayaran</p><p class="mt-1 text-xl font-bold text-gray-900">RM {{ number_format($totalPaymentAmount, 2) }}</p></div>
                <div class="rounded-lg border border-purple-100 bg-purple-50 p-4"><p class="text-sm text-gray-600">Baki Akaun</p><p class="mt-1 text-xl font-bold {{ $balanceClass }}">{{ $accountBalance < 0 ? '-' : '' }}RM {{ number_format(abs($accountBalance), 2) }}</p></div>
            </div>

            <div class="mt-6 grid gap-6 xl:grid-cols-3">
                <div class="space-y-6 xl:col-span-2">
                    <div class="rounded-lg border border-gray-200">
                        <div class="flex items-center justify-between border-b border-gray-100 p-4">
                            <h3 class="font-semibold text-gray-900">Invoice Dikeluarkan</h3>
                            <details class="relative">
                                <summary class="cursor-pointer list-none rounded-lg border border-blue-200 px-3 py-2 text-sm font-medium text-blue-700 hover:bg-blue-50">+ Buat Invoice Manual</summary>
                                <form action="{{ route('companies.manual-invoices.store', $company) }}" method="POST" class="absolute right-0 z-10 mt-2 w-80 rounded-lg border border-gray-200 bg-white p-4 shadow-xl">
                                    @csrf
                                    <p class="mb-3 font-semibold text-gray-900">Buat Invoice Manual</p>
                                    <label class="mb-1 block text-xs font-medium">Keterangan</label><textarea name="description" required rows="3" class="mb-3 w-full rounded border-gray-300 text-sm" placeholder="Contoh: Caj tambahan"></textarea>
                                    <label class="mb-1 block text-xs font-medium">Jumlah (RM)</label><input name="amount" type="number" min="0.01" step="0.01" required class="mb-3 w-full rounded border-gray-300 text-sm">
                                    <label class="mb-1 block text-xs font-medium">Tarikh jatuh tempo</label><input name="due_date" type="date" value="{{ now()->addDays(7)->toDateString() }}" required class="mb-3 w-full rounded border-gray-300 text-sm">
                                    <button class="w-full rounded bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700">Generate & Simpan</button>
                                </form>
                            </details>
                        </div>
                        <div class="overflow-x-auto"><table class="w-full text-sm"><thead class="bg-gray-50 text-left text-xs text-gray-500"><tr><th class="p-3">Tarikh</th><th class="p-3">No. Invoice</th><th class="p-3">Jenis / Keterangan</th><th class="p-3 text-right">Jumlah</th><th class="p-3">Status</th></tr></thead><tbody>
                            @forelse($accountInvoices as $invoice)<tr class="border-t"><td class="p-3">{{ optional($invoice->tarikh_terbit)->format('d M Y') ?? $invoice->created_at->format('d M Y') }}</td><td class="p-3 font-medium">{{ $invoice->nomor_invoice }}<a href="{{ route('invoices.receipt', $invoice) }}" class="ml-2 text-xs text-blue-600 hover:underline">PDF</a></td><td class="p-3"><span class="rounded bg-blue-50 px-2 py-1 text-xs text-blue-700">{{ $invoice->invoice_type === 'manual' ? 'Manual' : 'Langganan' }}</span><p class="mt-1 text-xs text-gray-500">{{ $invoice->description ?: '-' }}</p></td><td class="p-3 text-right">RM {{ number_format($invoice->jumlah, 2) }}</td><td class="p-3"><span class="rounded-full px-2 py-1 text-xs {{ $invoice->status_bayaran === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ ucfirst($invoice->status_bayaran) }}</span></td></tr>@empty<tr><td colspan="5" class="p-5 text-center text-gray-500">Belum ada invoice.</td></tr>@endforelse
                        </tbody><tfoot class="border-t bg-gray-50 font-semibold"><tr><td colspan="3" class="p-3">Jumlah Invoice</td><td class="p-3 text-right">RM {{ number_format($totalInvoiceAmount, 2) }}</td><td></td></tr></tfoot></table></div>
                    </div>

                    <div class="rounded-lg border border-gray-200">
                        <div class="flex items-center justify-between border-b border-gray-100 p-4">
                            <h3 class="font-semibold text-gray-900">Sejarah Pembayaran (Dana Masuk)</h3>
                            <details class="relative">
                                <summary class="cursor-pointer list-none rounded-lg border border-emerald-200 px-3 py-2 text-sm font-medium text-emerald-700 hover:bg-emerald-50">+ Catat Pembayaran</summary>
                                <form action="{{ route('companies.manual-payments.store', $company) }}" method="POST" class="absolute right-0 z-10 mt-2 w-80 rounded-lg border border-gray-200 bg-white p-4 shadow-xl">
                                    @csrf
                                    <p class="mb-3 font-semibold text-gray-900">Catat Pembayaran</p>
                                    <label class="mb-1 block text-xs font-medium">Invoice / Referensi</label><select name="company_invoice_id" required class="mb-3 w-full rounded border-gray-300 text-sm">@foreach($accountInvoices as $invoice)<option value="{{ $invoice->id }}">{{ $invoice->nomor_invoice }} — RM {{ number_format($invoice->jumlah, 2) }}</option>@endforeach</select>
                                    <label class="mb-1 block text-xs font-medium">Jumlah (RM)</label><input name="amount" type="number" min="0.01" step="0.01" required class="mb-3 w-full rounded border-gray-300 text-sm">
                                    <label class="mb-1 block text-xs font-medium">Metode pembayaran</label><select name="payment_method" required class="mb-3 w-full rounded border-gray-300 text-sm"><option value="transfer_bank">Transfer Bank</option><option value="cash">Tunai</option><option value="cheque">Cek</option><option value="other">Lainnya</option></select>
                                    <label class="mb-1 block text-xs font-medium">Tarikh pembayaran</label><input name="paid_at" type="date" value="{{ now()->toDateString() }}" required class="mb-3 w-full rounded border-gray-300 text-sm">
                                    <label class="mb-1 block text-xs font-medium">No. rujukan (opsional)</label><input name="reference_number" class="mb-3 w-full rounded border-gray-300 text-sm"><textarea name="notes" rows="2" class="mb-3 w-full rounded border-gray-300 text-sm" placeholder="Catatan (opsional)"></textarea>
                                    <button class="w-full rounded bg-emerald-600 px-3 py-2 text-sm font-medium text-white hover:bg-emerald-700">Simpan Pembayaran</button>
                                </form>
                            </details>
                        </div>
                        <div class="overflow-x-auto"><table class="w-full text-sm"><thead class="bg-gray-50 text-left text-xs text-gray-500"><tr><th class="p-3">Tarikh</th><th class="p-3">No. Rujukan</th><th class="p-3">Metode</th><th class="p-3">Invoice</th><th class="p-3 text-right">Jumlah</th><th class="p-3 text-right">Baki Selepas</th></tr></thead><tbody>
                            @forelse($accountPayments as $payment)<tr class="border-t"><td class="p-3">{{ optional($payment->dibayar_pada)->format('d M Y') ?? $payment->created_at->format('d M Y') }}</td><td class="p-3 font-medium">{{ $payment->reference_number ?: $payment->transaction_id }}</td><td class="p-3">{{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</td><td class="p-3 text-xs">{{ $payment->invoice?->nomor_invoice }}</td><td class="p-3 text-right">RM {{ number_format($payment->jumlah, 2) }}</td><td class="p-3 text-right {{ $payment->balance_after < 0 ? 'font-semibold text-emerald-600' : '' }}">{{ $payment->balance_after < 0 ? '-' : '' }}RM {{ number_format(abs((float) $payment->balance_after), 2) }}</td></tr>@empty<tr><td colspan="6" class="p-5 text-center text-gray-500">Belum ada pembayaran dicatat.</td></tr>@endforelse
                        </tbody><tfoot class="border-t bg-gray-50 font-semibold"><tr><td colspan="4" class="p-3">Jumlah Pembayaran</td><td class="p-3 text-right">RM {{ number_format($totalPaymentAmount, 2) }}</td><td></td></tr></tfoot></table></div>
                    </div>
                </div>
                <aside class="rounded-lg bg-gray-50 p-4"><h3 class="font-semibold text-gray-900">Aliran Akaun</h3><div class="mt-4 space-y-4 border-l-2 border-gray-200 pl-4">@foreach($accountInvoices->take(5) as $invoice)<div><p class="text-xs text-gray-500">{{ optional($invoice->tarikh_terbit)->format('d M Y') ?? $invoice->created_at->format('d M Y') }} · Invoice</p><p class="text-sm font-medium">{{ $invoice->nomor_invoice }}</p><p class="text-sm">+ RM {{ number_format($invoice->jumlah, 2) }}</p></div>@endforeach @foreach($accountPayments->take(5) as $payment)<div><p class="text-xs text-gray-500">{{ optional($payment->dibayar_pada)->format('d M Y') ?? $payment->created_at->format('d M Y') }} · Bayaran</p><p class="text-sm font-medium">{{ $payment->reference_number ?: $payment->transaction_id }}</p><p class="text-sm text-emerald-700">- RM {{ number_format($payment->jumlah, 2) }}</p></div>@endforeach</div></aside>
            </div>
            <p class="mt-5 rounded bg-blue-50 p-3 text-xs text-blue-800">Baki akaun dikira daripada jumlah invoice ditolak jumlah pembayaran. Baki negatif bermaksud company mempunyai pembayaran berlebihan yang automatik mengurangkan baki invoice berikutnya.</p>
        </section>

        @endif

        <!-- Company Statistics -->
        <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    {{ __('super_admin/companies.show.account_summary') }}
                </h2>
                
                <!-- Financial Overview (Top Row) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

                    <!-- Paid Invoices -->
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-6 border border-green-100 hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold text-green-600">{{ $totalPaidInvoices }}</div>
                                <div class="text-sm text-green-700 mt-1">{{ __('super_admin/companies.show.stats.paid_invoices') ?? 'Invois Dibayar' }}</div>
                            </div>
                            <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Total Revenue -->
                    <div class="bg-gradient-to-br from-purple-50 to-violet-50 rounded-xl p-6 border border-purple-100 hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-2xl font-bold text-purple-600">
                                    RM {{ $totalRevenue }}
                                </div>
                                <div class="text-sm text-purple-700 mt-1">{{ __('super_admin/companies.show.stats.total_revenue') ?? 'Jumlah Pendapatan' }}</div>
                            </div>
                            <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 15.5H6c0 2.59 2.42 4.12 5 4.44V22h2v-2.07c2.25-.3 5-1.59 5-4.43s-2.75-4.13-5-4.43V6.1c1.33.24 3 .94 3 2.4h2c0-2.84-2.75-4.13-5-4.43V2h-2v2.07c-2.25.3-5 1.59-5 4.43s2.67 4.11 5 4.43v4.97c-1.45-.25-3-1.02-3-2.4m8 0c0 1.46-1.67 2.16-3 2.4v-4.8c1.33.24 3 .94 3 2.4m-8-7c0-1.46 1.67-2.16 3-2.4v4.8c-1.37-.25-3-1-3-2.4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Operational Overview (Bottom Row) -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-base font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        {{ __('super_admin/companies.show.operational_overview') }}
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Students and Teachers -->
                        <div class="bg-gradient-to-br from-cyan-50 to-blue-50 rounded-xl p-6 border border-cyan-100 hover:shadow-lg transition-shadow duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-sm font-semibold text-gray-700">{{ __('super_admin/companies.show.students_teachers') }}</h4>
                                <div class="w-10 h-10 bg-cyan-500 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-600 flex items-center">
                                        <div class="w-2 h-2 bg-cyan-400 rounded-full mr-2"></div>
                                        {{ __('super_admin/companies.show.student') }}
                                    </span>
                                    <span class="text-lg font-bold text-cyan-600">
                                        @php
                                            $studentsCount = $company->students()->count();
                                        @endphp
                                        {{ $studentsCount }}
                                    </span>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-600 flex items-center">
                                        <div class="w-2 h-2 bg-blue-400 rounded-full mr-2"></div>
                                        {{ __('super_admin/companies.show.teacher') }}
                                    </span>
                                    <span class="text-lg font-bold text-blue-600">
                                        @php
                                            $teachersCount = $company->teachers()->count();
                                        @endphp
                                        {{ $teachersCount }}
                                    </span>
                                </div>
                                
                                <div class="pt-3 border-t border-cyan-200">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-700">{{ __('super_admin/companies.show.total') }}</span>
                                        <span class="text-xl font-bold text-cyan-600">
                                            {{ $studentsCount + $teachersCount }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Courses -->
                        <div class="bg-gradient-to-br from-teal-50 to-emerald-50 rounded-xl p-6 border border-teal-100 hover:shadow-lg transition-shadow duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-sm font-semibold text-gray-700">{{ __('super_admin/companies.show.available_courses') }}</h4>
                                <div class="w-10 h-10 bg-teal-500 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="text-center mb-4">
                                <div class="text-3xl font-bold text-teal-600">
                                    @php
                                        $totalCourses = $company->courses()->count();
                                    @endphp
                                    {{ $totalCourses }}
                                </div>
                                <p class="text-xs text-gray-600">{{ __('super_admin/companies.show.course') }}</p>
                            </div>
                            
                            <div class="pt-3 border-t border-teal-200">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-600">{{ __('super_admin/companies.show.status_show') }}</span>
                                    <span class="text-sm font-bold text-teal-600">
                                        {{ $totalCourses > 0 ? __('super_admin/companies.show.available') : __('super_admin/companies.show.none') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

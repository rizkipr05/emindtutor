@extends('layouts.superadmin')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ __('super_admin/companies.heading') }}</h1>
                    <p class="text-gray-600 mt-1">{{ __('super_admin/companies.subheading') }}</p>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="{{ route('companies.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Company
                    </a>

                    <div class="text-right pl-4 border-l border-gray-200">
                        <span class="text-sm text-gray-500 block">{{ __('super_admin/companies.total_label') }}</span>
                        <span class="text-2xl font-bold text-gray-900">{{ count($companies) }}</span>
                    </div>
                </div>
            </div>
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
            <div class="mb-6 flex items-center rounded-lg border border-red-200 bg-red-50 p-4 text-red-800">
                <svg class="mr-3 h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.5a.75.75 0 00-1.5 0v4a.75.75 0 001.5 0v-4zm0 7a.75.75 0 00-1.5 0v.01a.75.75 0 001.5 0V13.5z" clip-rule="evenodd"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($companies as $company)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200 flex flex-col h-full">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0"> <h3 class="text-lg font-semibold text-gray-900 break-words leading-tight" title="{{ $company->user->name }}">
                                    {{ $company->user->name }}
                                </h3>
                                <p class="text-sm text-gray-600 mt-1 break-words">{{ $company->nama_wakil_syarikat }}</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $company->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $company->status ? __('super_admin/companies.status_badge.active') : __('super_admin/companies.status_badge.inactive') }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6 space-y-3 flex-grow">
                        <div class="space-y-2">
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span class="text-gray-600">{{ $company->no_telefon ?: '-' }}</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                                <span class="text-gray-600 truncate">{{ $company->user->email }}</span>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">{{ __('super_admin/companies.subscription') }}</span>

                                @if($company->activeInvoice)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ __('super_admin/companies.subscription_status.ongoing') }}
                                    </span>
                                @elseif($company->pendingInvoice)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Menunggu Pembayaran
                                    </span>
                                @elseif($company->latestPaidInvoice)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ __('super_admin/companies.subscription_status.expired') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ __('super_admin/companies.subscription_status.none') }}
                                    </span>
                                @endif
                            </div>

                            @if($company->activeInvoice)
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ __('super_admin/companies.expires') }}: 
                                    {{ \Carbon\Carbon::parse($company->activeInvoice->tarikh_tamat)->format('d M Y') }}
                                </div>
                            @elseif($company->pendingInvoice)
                                <div class="text-xs text-gray-500 mt-1">Invoice belum dibayar</div>
                            @elseif($company->latestPaidInvoice)
                                <div class="text-xs text-gray-500 mt-1">
                                    Expired: 
                                    {{ \Carbon\Carbon::parse($company->latestPaidInvoice->tarikh_tamat)->format('d M Y') }}
                                </div>
                            @endif

                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 rounded-b-lg mt-auto">
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('companies.show', $company->id) }}" 
                               class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200" title="View Details">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            
                            @if(false)
                            <a href="{{ route('invoices.create', $company->id) }}" 
                               class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 transition-colors duration-200"
                               title="Manual Subscribe / Renew">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </a>
                            @endif

                            <form action="{{ route('companies.toggleStatus', $company->id) }}" method="POST" class="inline-block flex-1">
                                @csrf
                                <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 text-sm font-medium rounded-md text-white transition-colors duration-200 {{ $company->status ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}"
                                title="{{ $company->status ? 'Deactivate' : 'Activate' }}">
                                    @if($company->status)
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.49 2 2 6.49 2 12s4.49 10 10 10 10-4.49 10-10S17.51 2 12 2M4 12c0-1.85.63-3.54 1.69-4.9L16.9 18.31A8 8 0 0 1 12 20c-4.41 0-8-3.59-8-8m14.31 4.9L7.1 5.69A8 8 0 0 1 12 4c4.41 0 8 3.59 8 8 0 1.85-.63 3.54-1.69 4.9"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @endif
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($companies->isEmpty())
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">{{ __('super_admin/companies.empty_title') }}</h3>
                <p class="mt-2 text-gray-500">{{ __('super_admin/companies.empty_desc') }}</p>
                <div class="mt-6">
                    <a href="{{ route('companies.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Company Baru
                    </a>
                </div>
            </div>
        @endif

        @if(method_exists($companies, 'links'))
            <div class="mt-6">
                {{ $companies->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

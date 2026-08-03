@extends('layouts.superadmin')

@section('content')
<div class="mx-auto px-2">
    <h1 class="text-2xl font-bold mb-6">{{ __('super_admin/invoices.heading') }}</h1>

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white shadow rounded-xl">
        <table class="min-w-full border border-gray-200 divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Invoice No</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Company</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Amount</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Issued</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Expires</th>
                    <th class="px-4 py-3 text-sm font-semibold text-gray-700 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($invoices as $invoice)
                    {{-- LOGIKA CEK EXPIRED 7 HARI --}}
                    @php
                        // Invoice dianggap expired jika:
                        // 1. Belum Paid
                        // 2. Tanggal buat + 7 hari < Hari ini (sudah lewat)
                        $isExpired = $invoice->status_bayaran !== 'paid' && $invoice->created_at->addDays(7)->isPast();
                    @endphp

                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-800">
                            {{ $invoice->nomor_invoice }}
                            @if($isExpired)
                                <div class="text-xs text-red-500 mt-1">Expired since {{ $invoice->created_at->addDays(7)->format('d M') }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-800">
                            {{ $invoice->company?->user?->name ?? 'Company tidak ditemukan' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-800">
                            RM {{ number_format($invoice->jumlah, 2) }}
                        </td>
                        
                        {{-- KOLOM STATUS --}}
                        <td class="px-4 py-3 text-sm">
                            @if($invoice->status_bayaran === 'paid')
                                <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Paid</span>
                            
                            @elseif($isExpired)
                                <span class="px-2 py-1 text-xs font-semibold text-gray-700 bg-gray-200 rounded-full">Expired / Overdue</span>
                            
                            @elseif($invoice->status_bayaran === 'pending' || $invoice->status_bayaran === 'upcoming')
                                <span class="px-2 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">Upcoming</span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $invoice->tarikh_terbit ? \Carbon\Carbon::parse($invoice->tarikh_terbit)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $invoice->tarikh_tamat ? \Carbon\Carbon::parse($invoice->tarikh_tamat)->format('d/m/Y') : '-' }}
                        </td>
                        
                        {{-- KOLOM ACTION --}}
                        <td class="px-4 py-3 text-sm text-center">
                            <div class="flex items-center justify-center space-x-2">
                                @if($invoice->company)
                                <a href="{{ route('companies.show', $invoice->company->id) }}"
                                   class="inline-flex items-center px-3 py-1 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow">
                                    Show
                                </a>
                                @endif
                                
                                @if($invoice->status_bayaran === 'paid')
                                    {{-- Tombol Resit --}}
                                    <a href="{{ route('invoices.receipt', $invoice->id) }}" target="_blank"
                                       class="inline-flex items-center px-3 py-1 text-xs font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 shadow">
                                        Resit
                                    </a>
                                
                                @elseif(!$isExpired)
                                    <span class="text-xs text-amber-600">Menunggu pembayaran ToyyibPay</span>
                                @else
                                    {{-- Jika Expired, tombol Confirm hilang, ganti tanda silang/info --}}
                                    <span class="text-xs text-gray-400 italic">Expired</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                            Tidak ada data invoice.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

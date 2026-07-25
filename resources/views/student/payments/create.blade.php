@extends('layouts.student')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-3 mb-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">{{ __('student/payments/create.title') }}</h2>
                    <p class="text-sm text-gray-600 mt-1">{{ __('student/payments/create.subtitle') }}</p>
                </div>
            </div>
            
            <!-- Breadcrumb -->
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('student.payments.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            {{ __('student/payments/create.breadcrumb.payments') }}
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ __('student/payments/create.breadcrumb.current') }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        {{-- SEKSYEN BARU: Daftar Akaun Bank --}}
        <div class="mb-8">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">
                {{ __('student/payments/create.bank_info.title') }}
            </h3>
            <div class="grid grid-cols-1 gap-4">
                @forelse($bankDetails as $bank)
                    <div class="bg-white border border-gray-200 rounded-xl p-4 flex items-center justify-between shadow-sm">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center font-bold text-xs">
                                {{ $bank->nama_bank }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ $bank->nombor_akaun_bank }}</p>
                            </div>
                        </div>
                        {{-- Button Copy dengan Tooltip/Aria Label --}}
                        <button type="button" 
                            onclick="copyText('{{ $bank->nombor_akaun_bank }}')" 
                            title="{{ __('student/payments/create.bank_info.copy_button') }}"
                            class="text-blue-600 hover:text-blue-700 p-2 rounded-lg hover:bg-blue-50 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                    </div>
                @empty
                    <div class="bg-amber-50 border border-amber-200 p-4 rounded-xl text-amber-700 text-sm italic">
                        {{ __('student/payments/create.bank_info.empty') }}
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Error global --}}
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 rounded-lg p-4 mb-8">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">{{ __('student/payments/create.error_title') }}</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Form store payment --}}
        <div class="bg-white shadow-lg rounded-xl border border-gray-100 overflow-hidden">
            <!-- Form Header -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white">{{ __('student/payments/create.form.header') }}</h3>
                <p class="text-blue-100 text-sm mt-1">{{ __('student/payments/create.form.subtitle') }}</p>
            </div>

            <!-- Form Body -->
            <div class="p-6">
                <form action="{{ route('student.payments.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    {{-- Info Invoice (read only) --}}
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('student/payments/create.invoice.info_label') }}</label>
                                <div class="bg-white rounded-md border border-gray-200">
                                    <input type="text" 
                                           class="w-full px-3 py-3 border-0 rounded-md bg-white text-gray-700 cursor-not-allowed focus:outline-none" 
                                           value="#{{ $invoice->nomor_invoice }} - {{ $invoice->course->nama_kos ?? 'Tanpa Kursus' }}" 
                                           readonly>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ __('student/payments/create.invoice.helper') }}</p>
                                {{-- tetap kirim id invoice via hidden --}}
                                <input type="hidden" name="student_invoice_id" value="{{ $invoice->id }}">
                            </div>
                        </div>
                    </div>

                    {{-- Tanggal Dibayar --}}
                    <div>
                        <div class="flex items-center space-x-2 mb-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <label for="dibayar_pada" class="block text-sm font-medium text-gray-700">{{ __('student/payments/create.date.label') }}</label>
                            <span class="text-red-500">*</span>
                        </div>
                        <input type="date" name="dibayar_pada" id="dibayar_pada" 
                               value="{{ old('dibayar_pada') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('dibayar_pada') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">{{ __('student/payments/create.date.helper') }}</p>
                        @error('dibayar_pada')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Upload Bukti Bayar --}}
                    <div id="drop-zone" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors duration-200 @error('bukti_bayar') border-red-300 @enderror">
                        <div class="space-y-1 text-center">
                            {{-- Icon yang akan bertukar warna --}}
                            <svg id="upload-icon" class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="bukti_bayar" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                    {{-- Teks ini akan berubah --}}
                                    <span id="upload-text">{{ __('student/payments/create.upload.button') }}</span>
                                    <input type="file" name="bukti_bayar" id="bukti_bayar" class="sr-only" accept="image/*,.pdf">
                                </label>
                                <p id="upload-or" class="pl-1">{{ __('student/payments/create.upload.or') }}</p>
                            </div>
                            <p id="upload-hint" class="text-xs text-gray-500">{{ __('student/payments/create.upload.hint') }}</p>
                        </div>

                        @error('bukti_bayar')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Tombol --}}
                    <div class="bg-gray-50 rounded-lg p-4 border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0 sm:space-x-4">
                                <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                {{ __('student/payments/create.note') }}
                            </div>
                            <div class="flex space-x-3">
                                <a href="{{ route('student.payments.index') }}" 
                                   class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    {{ __('student/payments/create.buttons.back') }}
                                </a>
                                <button type="submit" 
                                        class="inline-flex items-center justify-center px-6 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-all duration-200 transform hover:scale-105">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    {{ __('student/payments/create.buttons.submit') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="mt-8 bg-white rounded-lg border border-gray-200 p-4">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-900">{{ __('student/payments/create.footer.title') }}</h4>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('student/payments/create.footer.content') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Skrip Copy --}}
<script>
    function copyText(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Nombor akaun berjaya disalin!');
        });
    }
</script>

<script>
    document.getElementById('bukti_bayar').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name;
        const uploadText = document.getElementById('upload-text');
        const uploadIcon = document.getElementById('upload-icon');
        const dropZone = document.getElementById('drop-zone');
        const uploadOr = document.getElementById('upload-or');
        const uploadHint = document.getElementById('upload-hint');

        if (fileName) {
            // 1. Tukar teks butang kepada nama fail
            uploadText.textContent = fileName;
            uploadText.classList.remove('text-blue-600');
            uploadText.classList.add('text-green-600', 'font-bold');

            // 2. Tukar warna icon dan kotak
            uploadIcon.classList.remove('text-gray-400');
            uploadIcon.classList.add('text-green-500');
            dropZone.classList.remove('border-gray-300');
            dropZone.classList.add('border-green-500', 'bg-green-50');

            // 3. Sembunyikan teks sampingan supaya fokus pada nama fail
            uploadOr.classList.add('hidden');
            uploadHint.textContent = "{{ __('student/payments/create.bank_info.copy_success') }}".includes('Account') ? "File selected successfully!" : "Fail berjaya dipilih!";
        }
    });
</script>
@endsection

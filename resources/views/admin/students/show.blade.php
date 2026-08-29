@extends('layouts.admin')

@section('title', 'eMindTutor | Student Details')

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto px-2">
        
        <!-- Back Navigation -->
        <a href="{{ route('admin.students.approved') }}" class="group block mb-6">
            <div class="bg-white/80 backdrop-blur-sm hover:bg-white p-4 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-white/50">
                <div class="inline-flex items-center text-gray-700 group-hover:text-sky-600 font-medium transition-colors duration-200">
                    <div class="p-2 rounded-full bg-sky-100 group-hover:bg-sky-200 mr-3 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </div>
                    <span class="text-lg">{{ $student->user->name ?? __('admin/students/show.back.default') }}</span>
                </div>
            </div>
        </a>

        <!-- Main Content Card -->
        <div class="bg-white/90 backdrop-blur-sm shadow-2xl rounded-3xl border border-white/50 overflow-hidden">
            
            <!-- Header with gradient -->
            <div class="bg-gradient-to-r from-sky-600 via-cyan-600 to-sky-600 p-6 text-white">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h1 class="text-2xl font-bold flex items-center">
                        <div class="p-2 bg-white/20 rounded-lg mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        {{ __('admin/students/show.header.title') }}
                    </h1>
                    <a href="{{ route('admin.students.account', $student) }}" class="inline-flex items-center rounded-lg bg-white/15 px-4 py-2 text-sm font-semibold text-white ring-1 ring-white/40 hover:bg-white/25">
                        Rekod Akaun
                    </a>
                </div>
            </div>

            <div class="p-4">
                <!-- Toggle Tab Menu -->
                <div class="mb-4">
                    <div class="bg-gray-100 p-1 rounded-2xl flex flex-col sm:flex-row sm:space-x-1 space-y-1 sm:space-y-0" id="tabToggle">
                        <button class="tab-btn active px-6 py-3 rounded-xl font-medium transition-all duration-300 text-sm" data-tab="teacher">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ __('admin/students/show.tabs.info') }}
                            </span>
                        </button>
                        <button class="tab-btn px-6 py-3 rounded-xl font-medium transition-all duration-300 text-sm" data-tab="classes">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                {{ __('admin/students/show.tabs.classes') }}
                            </span>
                        </button>
                        <button class="tab-btn px-6 py-3 rounded-xl font-medium transition-all duration-300 text-sm" data-tab="reviews">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 24 24" stroke="none">
                                    <path d="m19,3H5c-1.1,0-2,.9-2,2v14c0,1.1.9,2,2,2h14c1.1,0,2-.9,2-2V5c0-1.1-.9-2-2-2ZM5,19V5h14v14s-14,0-14,0Z"></path><path d="M8.5 10.5A1.5 1.5 0 1 0 8.5 13.5 1.5 1.5 0 1 0 8.5 10.5z"></path><path d="M11 11H17V13H11z"></path><path d="M7 7H17V9H7z"></path><path d="M7 15H17V17H7z"></path>
                                </svg>
                                {{ __('admin/students/show.tabs.invoice') }}
                            </span>
                        </button>
                    </div>
                </div>


                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- Teacher Info Tab -->
                    <div id="teacher-content" class="tab-pane">
                        <div class="bg-gradient-to-r from-sky-50 to-cyan-50 rounded-2xl p-8 border border-sky-100 text-center">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                
                                <!-- Personal Information -->
                                <div class="space-y-4">
                                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                                        <div class="w-2 h-6 bg-gradient-to-b from-sky-500 to-cyan-500 rounded-full mr-3"></div>
                                        {{ __('admin/students/show.sections.personal') }}
                                    </h3>

                                    <div class="space-y-3">
                                        <!-- Field -->
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 items-start group">
                                            <div class="text-sm font-semibold text-gray-600 pt-1">{{ __('admin/students/show.fields.name') }}</div>
                                            <div class="sm:col-span-2 bg-white rounded-lg p-3 shadow-sm border border-gray-200 group-hover:border-blue-300 transition-colors">
                                                {{ $student->user->name ?? '-' }}
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 items-start group">
                                            <div class="text-sm font-semibold text-gray-600 pt-1">{{ __('admin/students/show.fields.email') }}</div>
                                            <div class="sm:col-span-2 bg-white rounded-lg p-3 shadow-sm border border-gray-200 group-hover:border-blue-300 transition-colors">
                                                {{ $student->user->email ?? '-' }}
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 items-start group">
                                            <div class="text-sm font-semibold text-gray-600 pt-1">{{ __('admin/students/show.fields.phone') }}</div>
                                            <div class="sm:col-span-2 bg-white rounded-lg p-3 shadow-sm border border-gray-200 group-hover:border-blue-300 transition-colors">
                                                {{ $student->phone ?? '-' }}
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 items-start group">
                                            <div class="text-sm font-semibold text-gray-600 pt-1">{{ __('admin/students/show.fields.ic_number') }}</div>
                                            <div class="sm:col-span-2 bg-white rounded-lg p-3 shadow-sm border border-gray-200 group-hover:border-blue-300 transition-colors">
                                                {{ $student->ic_number?? '-' }}
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 items-start group">
                                            <div class="text-sm font-semibold text-gray-600 pt-1">{{ __('admin/students/show.fields.address') }}</div>
                                            <div class="sm:col-span-2 bg-white rounded-lg p-3 shadow-sm border border-gray-200 group-hover:border-blue-300 transition-colors">
                                                {{ $student->address?? '-' }}
                                            </div>
                                        </div>

                                        <!-- <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 items-start group">
                                            <div class="text-sm font-semibold text-gray-600 pt-1">Status</div>
                                            <div class="sm:col-span-2">
                                                
                                            </div>
                                        </div> -->
                                    </div>
                                </div>

                                <!-- Financial Information -->
                                <div class="space-y-4">
                                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                                        <div class="w-2 h-6 bg-gradient-to-b from-green-500 to-emerald-500 rounded-full mr-3"></div>
                                        {{ __('admin/students/show.sections.sponsor') }}
                                    </h3>

                                    <div class="space-y-3">
                                        
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 items-start group">
                                            <div class="text-sm font-semibold text-gray-600 pt-1">{{ __('admin/students/show.fields.sponsor_name') }}</div>
                                            <div class="sm:col-span-2 bg-white rounded-lg p-3 shadow-sm border border-gray-200 group-hover:border-green-300 transition-colors">
                                                {{ $student->sponsor_name ?? '-' }}
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 items-start group">
                                            <div class="text-sm font-semibold text-gray-600 pt-1">{{ __('admin/students/show.fields.sponsor_phone') }}</div>
                                            <div class="sm:col-span-2 bg-white rounded-lg p-3 shadow-sm border border-gray-200 group-hover:border-green-300 transition-colors">
                                                {{ $student->sponsor_phone ?? '-' }}
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 items-start group">
                                            <div class="text-sm font-semibold text-gray-600 pt-1">{{ __('admin/students/show.fields.sponsor_email') }}</div>
                                            <div class="sm:col-span-2 bg-white rounded-lg p-3 shadow-sm border border-gray-200 group-hover:border-green-300 transition-colors">
                                                {{ $student->sponsor_email ?? '-'}}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                    <!-- Classes Tab  -->
                    <div id="classes-content" class="tab-pane hidden">
                        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-4 border border-indigo-100 text-center">
                        @foreach($student->classScheduleStudents as $css)
                            <div class="bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                <!-- Header Section -->
                                <div class="bg-gradient-to-r from-teal-500 to-teal-600 p-4 sm:p-6">
                                    <a href="{{ route('admin.class.show', $css->class_schedule_id) }}" class="hover:text-blue-300 decoration-white hover:underline transition-all duration-300">
                                        <h3 class="text-lg sm:text-xl font-bold text-white mb-1">
                                            {{ $css->classSchedule->nama_kelas }}
                                        </h3>
                                    </a>
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                        <p class="text-blue-100 text-sm sm:text-base font-medium">
                                            {{ $css->classSchedule->course->nama_kos }}
                                        </p>
                                        <a href="{{ route('admin.teachers.show', $css->classSchedule->teacher_id) }}" 
                                        class="flex items-center gap-2 p-2 rounded-lg bg-teal-300/20 hover:bg-teal-600 transition-all duration-200 shadow hover:shadow-lg transform hover:scale-105">
                                            <svg class="w-5 h-5 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-white text-sm sm:text-base font-medium">
                                                {{ $css->classSchedule->teacher->user->name }}
                                            </span>
                                        </a>
                                    </div>
                                </div>

                                <!-- Schedule Content -->
                                <div class="p-4 sm:p-6">
                                    <div class="flex items-center gap-2 mb-4">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <h4 class="font-semibold text-gray-800 text-sm sm:text-base">{{ __('admin/students/show.classes.schedule_heading') }}</h4>
                                    </div>

                                    <div class="grid gap-3 sm:gap-4">
                                        @foreach($css->classSchedule->classScheduleDays as $day)
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 sm:p-4 bg-gray-50 rounded-lg border-l-4 border-blue-400 hover:bg-gray-100 transition-colors duration-200">
                                                <div class="flex items-center gap-3 mb-2 sm:mb-0">
                                                    <div class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0"></div>
                                                    @php
                                                        $dayKey = strtolower($day->hari ?? '');
                                                        $transKey = 'admin/students/show.day_names.' . $dayKey;
                                                        $transDay = trans($transKey);
                                                    @endphp
                                                    <span class="font-medium text-gray-800 text-sm sm:text-base">
                                                        {{ $transDay !== $transKey ? $transDay : $day->hari }}
                                                    </span>
                                                </div>
                                                
                                                <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 ml-5 sm:ml-0">
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        <span class="text-sm text-gray-700 font-medium">
                                                            {{ $day->jam_mulai }}
                                                        </span>
                                                    </div>
                                                    
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                        </svg>
                                                        <span class="text-sm text-gray-600 bg-gray-200 px-2 py-1 rounded-full">
                                                            {{ $day->durasi_menit }} {{ __('admin/students/show.minute') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    </div>

                    <!-- Invoice Tab  -->
                    <div id="reviews-content" class="tab-pane hidden">
                        @if(session('success'))
                            <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>
                        @endif
                        @if($errors->any())
                            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ $errors->first() }}</div>
                        @endif
                        <div class="mb-4 rounded-2xl border border-sky-100 bg-sky-50 p-4 text-left">
                            <h3 class="font-bold text-slate-800">Buat Invoice Manual</h3>
                            <p class="mt-1 text-sm text-slate-600">Cipta invoice secara manual untuk pelajar ini.</p>
                            <form method="POST" action="{{ route('admin.students.invoices.store', $student) }}" class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
                                @csrf
                                <label class="text-sm font-medium text-slate-700">Kursus
                                    <select name="course_id" required class="mt-1 block w-full rounded-lg border-slate-300 text-sm">
                                        <option value="">Pilih kursus</option>
                                        @foreach($courses as $course)<option value="{{ $course->id }}" data-fee="{{ $course->fee }}">{{ $course->localized_name }}</option>@endforeach
                                    </select>
                                </label>
                                <label class="text-sm font-medium text-slate-700">Jumlah (RM)
                                    <input type="number" name="jumlah" min="0" step="0.01" required class="mt-1 block w-full rounded-lg border-slate-300 text-sm" placeholder="0.00">
                                </label>
                                <label class="text-sm font-medium text-slate-700">Tarikh mula
                                    <input type="date" name="tarikh_terbit" value="{{ now('Asia/Kuala_Lumpur')->toDateString() }}" required class="mt-1 block w-full rounded-lg border-slate-300 text-sm">
                                </label>
                                <label class="text-sm font-medium text-slate-700">Tarikh tamat
                                    <input type="date" name="tarikh_tamat" value="{{ now('Asia/Kuala_Lumpur')->addMonth()->toDateString() }}" required class="mt-1 block w-full rounded-lg border-slate-300 text-sm">
                                </label>
                                <div class="flex items-end gap-2"><label class="min-w-0 flex-1 text-sm font-medium text-slate-700">Status
                                    <select name="status_bayaran" class="mt-1 block w-full rounded-lg border-slate-300 text-sm"><option value="upcoming">Belum bayar</option><option value="paid">Sudah bayar</option></select>
                                </label><button class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700">Buat</button></div>
                            </form>
                        </div>
                        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-2xl p-4 border border-yellow-100 text-center">
                            <div class="space-y-2">
                                @forelse($student->invoices->sortByDesc('created_at') as $invoice)
                                    <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-4">
                                            <!-- Left Section -->
                                            <div class="flex-1 min-w-0">
                                                <div class="flex flex-wrap items-center gap-2 sm:gap-4 text-sm">
                                                    <span class="font-semibold text-gray-800">#{{ $invoice->nomor_invoice }}</span>
                                                    @if($invoice->status_bayaran === 'paid')
                                                    <a href="{{ route('admin.students.invoices.receipt', [$student->id, $invoice->id]) }}" target="_blank" class="inline-flex items-center ml-2 px-3 py-1 rounded-md bg-red-600 text-white text-xs font-semibold shadow hover:bg-red-700 transition-colors" aria-label="Download Payment Receipt PDF">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                            <polyline points="7 10 12 15 17 10" />
                                                            <line x1="12" y1="15" x2="12" y2="3" />
                                                        </svg>
                                                        PDF
                                                    </a>
                                                    @endif
                                                    <span class="text-gray-600">{{ $invoice->course->nama_kos }}</span>
                                                    <span class="font-medium text-gray-900">RM {{ number_format($invoice->jumlah, 0, '.', ',') }}</span>
                                                    <span class="text-gray-500">
                                                        {{ __('admin/students/show.issued') }}: 
                                                        @if($invoice->tarikh_terbit)
                                                            {{ \Carbon\Carbon::parse($invoice->tarikh_terbit)->format('d M Y') }}
                                                        @else
                                                            {{ \Carbon\Carbon::parse($invoice->created_at)->format('d M Y') }}
                                                        @endif
                                                    </span>
                                                </div>
                                                <span class="text-red-500 flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ __('admin/students/show.expired') }}: {{ \Carbon\Carbon::parse($invoice->tarikh_tamat)->format('d M Y') }}
                                                </span>
                                            </div>

                                            <!-- Right Section - Status -->
                                            <div class="flex-shrink-0">
                                                @php
                                                    $statusConfig = [
                                                        'paid' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Paid'],
                                                        'overdue' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Overdue'],
                                                        'upcoming' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Upcoming']
                                                    ];
                                                    
                                                    $currentStatus = in_array($invoice->status_bayaran, ['paid', 'overdue', 'upcoming'], true)
                                                        ? $invoice->status_bayaran
                                                        : 'upcoming';
                                                    $config = $statusConfig[$currentStatus];
                                                @endphp
                                                
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                                                    {{ __('admin/students/show.invoice.status.' . $currentStatus) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-gray-500">{{ __('admin/students/show.invoice.no_invoices') }}</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-4 flex justify-end">
                    <a href="{{ route('admin.students.edit', $student->id) }}" 
                       class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-sky-500 to-cyan-600 text-white rounded-xl hover:from-sky-600 hover:to-cyan-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        {{ __('admin/students/show.actions.edit_student') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.dataset.tab;

            // Remove active class from all buttons
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'bg-white', 'text-blue-600', 'shadow-lg');
                btn.classList.add('text-gray-600', 'hover:text-gray-800');
            });

            // Add active class to clicked button
            this.classList.add('active', 'bg-white', 'text-blue-600', 'shadow-lg');
            this.classList.remove('text-gray-600', 'hover:text-gray-800');

            // Hide all tab panes
            tabPanes.forEach(pane => {
                pane.classList.add('hidden');
                pane.classList.remove('active');
            });

            // Show target tab pane
            const targetPane = document.getElementById(targetTab + '-content');
            if (targetPane) {
                targetPane.classList.remove('hidden');
                targetPane.classList.add('active');
            }
        });
    });
});
function toggleDetails(id) {
    let el = document.getElementById('details-' + id);
    el.classList.toggle('hidden');
}
</script>

<style>
.tab-btn.active {
    background: white;
    color: #2563eb;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.tab-btn:not(.active) {
    color: #6b7280;
}

.tab-btn:not(.active):hover {
    color: #374151;
}
</style>
@endsection
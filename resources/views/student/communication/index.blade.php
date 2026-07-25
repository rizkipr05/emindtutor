@php
    $role = auth()->user()->role;
    $isTeacher = $role === 'teacher';

    $pageEyebrow = $isTeacher ? 'Maklumat - Pengumuman' : null;
    $pageTitle = $isTeacher ? 'Pengumuman (Announcement)' : '📢 Announcements';
    $pageDescription = $isTeacher
        ? 'Kekal dikemas kini dengan berita dan maklumat terkini.'
        : 'Stay updated with the latest news and information';
@endphp

@extends(
    $role === 'student' ? 'layouts.student' :
    ($role === 'teacher' ? 'layouts.teacher' :
    ($role === 'admin' ? 'layouts.admin' :
    ($role === 'super admin' ? 'layouts.superadmin' : 'layouts.app')))
)

@section('content')
<div class="min-h-screen py-8 px-4">
    <div class="max-w-6xl mx-auto">
        {{-- Header Card --}}
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-6 border border-gray-100">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    @if($pageEyebrow)
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-cyan-700 mb-2">{{ $pageEyebrow }}</p>
                    @endif
                    @if($isTeacher)
                        <h2 class="text-3xl font-bold text-slate-900 mb-2">
                            {{ $pageTitle }}
                        </h2>
                    @else
                        <h2 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent mb-2">
                            {{ $pageTitle }}
                        </h2>
                    @endif
                    <p class="text-gray-500 text-sm">{{ $pageDescription }}</p>
                </div>

                {{-- Tombol Create untuk role yang boleh mengirim announcement --}}
                @if(in_array($role, ['teacher', 'admin', 'super admin']))
                    <a href="{{ route('broadcasts.create') }}" 
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create Announcement
                    </a>
                @endif
            </div>
        </div>

        {{-- Tabs --}}
        @if($role === 'admin')
            {{-- Admin bisa lihat Sent & Received --}}
            <div class="bg-white rounded-2xl shadow-xl mb-6 p-2 border border-gray-100">
                <div class="flex gap-2">
                    <button id="tab-sent" 
                            class="tab-btn flex-1 text-gray-600 px-6 py-3 rounded-xl hover:bg-gray-50 focus:outline-none font-medium transition-all duration-300"
                            onclick="switchTab('sent')">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Sent Announcements
                        </span>
                    </button>
                    <button id="tab-received" 
                            class="tab-btn flex-1 text-gray-600 px-6 py-3 rounded-xl hover:bg-gray-50 focus:outline-none font-medium transition-all duration-300"
                            onclick="switchTab('received')">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            Announcements For You
                        </span>
                    </button>
                </div>
            </div>
        @elseif($role === 'super admin')
            {{-- Super Admin hanya lihat Sent --}}
            <div class="bg-white rounded-2xl shadow-xl mb-6 p-2 border border-gray-100">
                <div class="flex">
                    <button id="tab-sent" 
                            class="tab-btn flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-xl font-medium focus:outline-none shadow-lg">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Sent Announcements
                        </span>
                    </button>
                </div>
            </div>
        @endif

        {{-- Content Container --}}
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
            @if($isTeacher)
                <div class="mb-6 rounded-2xl border border-cyan-100 bg-cyan-50 px-4 py-3 text-sm text-cyan-900">
                    Semua pengumuman baharu untuk guru akan dipaparkan di sini.
                </div>
            @endif

            {{-- Sent Announcements (Admin & Super Admin) --}}
            @if(in_array($role, ['admin', 'super admin']))
                <div id="sent" class="tab-content">
                    @if($sentAnnouncements->isEmpty())
                        <div class="text-center py-16">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-lg font-medium">No announcements sent yet</p>
                            <p class="text-gray-400 text-sm mt-2">Create your first announcement to get started</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($sentAnnouncements as $announcement)
                                <a href="{{ route('broadcasts.show', $announcement->id) }}" 
                                   class="group block p-5 border-2 border-gray-100 rounded-xl bg-gradient-to-r from-gray-50 to-white hover:from-blue-50 hover:to-indigo-50 hover:border-blue-200 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0 shadow-md">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                                    </svg>
                                                </div>
                                                <p class="text-base text-gray-800 font-bold group-hover:text-blue-600 transition-colors">
                                                    {{ $announcement->title }}
                                                </p>
                                            </div>
                                            <div class="flex items-center gap-2 ml-13">
                                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Sent {{ \Carbon\Carbon::parse($announcement->created_at)
                                                        ->locale(app()->getLocale() === 'en' ? 'en' : 'ms')
                                                        ->diffForHumans()
                                                    }}
                                                </span>
                                            </div>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 group-hover:translate-x-1 transition-all flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            {{-- Received Announcements (Admin, Teacher, Student) --}}
            @if($role !== 'super admin')
                <div id="received" class="tab-content {{ $role === 'admin' ? 'hidden' : '' }}">
                    @if($receivedAnnouncements->isEmpty())
                        <div class="text-center py-16">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-lg font-medium">No announcements for you</p>
                            <p class="text-gray-400 text-sm mt-2">Check back later for new updates</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($receivedAnnouncements as $announcement)
                                <a href="{{ route('broadcasts.show', $announcement->id) }}" 
                                   class="group block p-5 border-2 border-gray-100 rounded-xl bg-gradient-to-r from-gray-50 to-white hover:from-purple-50 hover:to-pink-50 hover:border-purple-200 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-3">
                                                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center flex-shrink-0 shadow-md">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                                    </svg>
                                                </div>
                                                <p class="text-base text-gray-800 font-bold group-hover:text-purple-600 transition-colors">
                                                    {{ $announcement->title }}
                                                </p>
                                            </div>
                                            <div class="flex flex-wrap items-center gap-2 ml-13">
                                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-purple-100 text-purple-700 text-xs font-medium rounded-full">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                    {{ $announcement->creator->name ?? 'Unknown' }}
                                                </span>
                                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-medium rounded-full">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                    </svg>
                                                    {{ $announcement->creator->role ?? 'Unknown' }}
                                                </span>
                                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ \Carbon\Carbon::parse($announcement->created_at)
                                                        ->locale(app()->getLocale() === 'en' ? 'en' : 'ms')
                                                        ->diffForHumans()
                                                    }}
                                                </span>
                                            </div>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-600 group-hover:translate-x-1 transition-all flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Tab script --}}
<script>
function switchTab(tab) {
    document.querySelectorAll('.tab-content').forEach(div => div.classList.add('hidden'));
    document.getElementById(tab).classList.remove('hidden');

    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('bg-gradient-to-r', 'from-blue-600', 'to-indigo-600', 'text-white', 'shadow-lg');
        btn.classList.add('text-gray-600');
    });
    
    const activeBtn = document.getElementById('tab-' + tab);
    activeBtn.classList.remove('text-gray-600');
    activeBtn.classList.add('bg-gradient-to-r', 'from-blue-600', 'to-indigo-600', 'text-white', 'shadow-lg');
}

// Default open tab
@if($role === 'admin')
    switchTab('sent');
@elseif($role !== 'super admin')
    document.getElementById('received').classList.remove('hidden');
@endif
</script>
@endsection

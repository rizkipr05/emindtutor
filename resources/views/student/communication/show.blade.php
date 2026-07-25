@php
    $role = auth()->user()->role;
@endphp

@extends(
    $role === 'student' ? 'layouts.student' :
    ($role === 'teacher' ? 'layouts.teacher' :
    ($role === 'admin' ? 'layouts.admin' :
    ($role === 'super admin' ? 'layouts.superadmin' : 'layouts.app')))
)

@section('content')
<div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('broadcasts.index') }}" 
               class="inline-flex items-center gap-2 text-gray-600 hover:text-blue-600 transition-colors duration-200 group">
                <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span class="font-medium">Back to Announcements</span>
            </a>
        </div>

        {{-- Main Card --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            {{-- Header Section with Gradient --}}
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                </svg>
                            </div>
                            <h1 class="text-2xl md:text-3xl font-bold text-white leading-tight">
                                {{ $announcement->title }}
                            </h1>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Author Info Section --}}
            <div class="px-8 py-5 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-md">
                            {{ strtoupper(substr($announcement->creator->name ?? 'U', 0, 1)) }}
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="text-base font-semibold text-gray-800">
                            {{ $announcement->creator->name ?? 'Unknown' }}
                        </p>
                        <div class="flex items-center gap-3 mt-1 text-sm text-gray-600">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-100 text-blue-700 rounded-full font-medium">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                                {{ ucfirst($announcement->creator->role ?? 'Unknown') }}
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ \Carbon\Carbon::parse($announcement->created_at)
                                    ->locale(app()->getLocale() === 'en' ? 'en' : 'ms')
                                    ->diffForHumans()
                                }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Message Content --}}
            <div class="px-8 py-8">
                <div class="prose prose-lg max-w-none">
                    <div class="text-gray-700 leading-relaxed whitespace-pre-line text-base">
                        {{ $announcement->message }}
                    </div>
                </div>
            </div>

            {{-- Target Audience Section --}}
            <div class="px-8 py-5 bg-gradient-to-r from-gray-50 to-blue-50 border-t border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">
                            Target Audience
                        </p>
                        <p class="text-sm font-medium text-gray-800 capitalize">
                            {{ str_replace('_', ' ', $announcement->target_audience) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
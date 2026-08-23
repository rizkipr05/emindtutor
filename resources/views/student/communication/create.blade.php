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
<div class="min-h-screen py-8 px-4">
    <div class="max-w-3xl mx-auto">
        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('broadcasts.index') }}" 
               class="inline-flex items-center gap-2 text-gray-600 hover:text-blue-600 transition-colors font-medium group">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Announcements
            </a>
        </div>

        {{-- Main Card --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white mb-1">Create Announcement</h2>
                        <p class="text-blue-100 text-sm">Share important updates with your audience</p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('broadcasts.store') }}" method="POST" class="p-8">
                @csrf
                
                {{-- Title Field --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                        Title
                        <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="title" 
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none" 
                        placeholder="Enter announcement title..."
                        value="{{ old('title') }}" 
                        required>
                    @error('title')
                        <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Message Field --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Message
                        <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="message" 
                        rows="6" 
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none resize-none" 
                        placeholder="Write your announcement message here..."
                        required>{{ old('message') }}</textarea>
                    <p class="text-gray-500 text-xs mt-2 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Be clear and concise with your message
                    </p>
                    @error('message')
                        <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Target Audience Field --}}
                <div class="mb-8">
                    <label class="block text-gray-700 font-semibold mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Target Audience
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select 
                            name="target_audience" 
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 pr-10 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none appearance-none bg-white cursor-pointer" 
                            required>
                            @if(auth()->user()->role === 'super admin')
                                <option value="all" {{ old('target_audience', $defaultAudience ?? 'all') === 'all' ? 'selected' : '' }}>All Users</option>
                                <option value="companies" {{ old('target_audience', $defaultAudience ?? 'all') === 'companies' ? 'selected' : '' }}>All Companies</option>
                                <option value="teachers" {{ old('target_audience', $defaultAudience ?? 'all') === 'teachers' ? 'selected' : '' }}>All Teachers</option>
                                <option value="students" {{ old('target_audience', $defaultAudience ?? 'all') === 'students' ? 'selected' : '' }}>All Students</option>
                            @elseif(auth()->user()->role === 'teacher')
                                <option value="students">Students</option>
                            @else
                                {{-- Role lain: hanya bisa kirim ke Teachers dan Students --}}
                                <option value="teachers" {{ old('target_audience') === 'teachers' ? 'selected' : '' }}>Teachers</option>
                                <option value="students" {{ old('target_audience') === 'students' ? 'selected' : '' }}>Students</option>
                            @endif
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                    
                    {{-- Audience Info Badge --}}
                    <div class="mt-3">
                        @if(auth()->user()->role === 'super admin')
                            <div class="inline-flex items-center gap-2 bg-purple-50 text-purple-700 px-3 py-2 rounded-lg text-sm border border-purple-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span class="font-medium">Broadcasting to all companies</span>
                            </div>
                        @elseif(auth()->user()->role === 'teacher')
                            <div class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 px-3 py-2 rounded-lg text-sm border border-blue-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 013-3h2a3 3 0 013 3v2H9zm0-10a3 3 0 110-6 3 3 0 010 6zm8 1a2 2 0 100-4 2 2 0 000 4z"></path>
                                </svg>
                                <span class="font-medium">Pengumuman akan dihantar kepada semua siswa dalam company anda</span>
                            </div>
                        @else
                            <div class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 px-3 py-2 rounded-lg text-sm border border-blue-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <span class="font-medium">Select teachers or students in your organization</span>
                            </div>
                        @endif
                    </div>
                    
                    @error('target_audience')
                        <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                    <button 
                        type="submit" 
                        class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-semibold flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Publish Announcement
                    </button>
                    <a 
                        href="{{ route('broadcasts.index') }}" 
                        class="flex-1 sm:flex-initial bg-gray-100 text-gray-700 px-6 py-3 rounded-xl hover:bg-gray-200 transition-all duration-300 font-semibold flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        {{-- Info Card --}}
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start gap-3">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h4 class="text-blue-900 font-semibold mb-1">Quick Tips</h4>
                <ul class="text-blue-800 text-sm space-y-1">
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600 mt-0.5">•</span>
                        <span>Keep your title clear and attention-grabbing</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600 mt-0.5">•</span>
                        <span>Make sure your message is concise and easy to understand</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600 mt-0.5">•</span>
                        <span>
                            @if(auth()->user()->role === 'super admin')
                                Your announcement will be sent to all companies
                            @elseif(auth()->user()->role === 'teacher')
                                Pengumuman guru akan dihantar kepada semua siswa dalam company anda
                            @else
                                Choose the right audience: teachers for staff updates, students for class announcements
                            @endif
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

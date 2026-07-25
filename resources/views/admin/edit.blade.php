@extends('layouts.admin')

@section('content')
<!-- Tukar Kata Laluan -->
        <div class="bg-white/80 backdrop-blur-sm border border-white/20 p-4 sm:p-6 lg:p-8 rounded-2xl sm:rounded-3xl shadow-lg hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-red-100 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ __('admin/edit_password.title') }}</h2>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 shadow-sm">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-green-800 text-sm sm:text-base">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Error Message -->
            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 shadow-sm">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <ul class="list-disc list-inside text-red-800 text-sm space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label class="flex items-center gap-2 text-sm sm:text-base font-semibold text-gray-700 mb-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            {{ __('admin/edit_password.current_password') }}
                        </label>
                        <input type="password" name="current_password"
                            class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-xl p-3 sm:p-4 transition-all duration-200 text-sm sm:text-base @error('current_password') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror"
                            placeholder="{{ __('admin/edit_password.current_password_placeholder') }}"
                            required>
                        @error('current_password')
                            <p class="text-red-600 text-sm mt-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="flex items-center gap-2 text-sm sm:text-base font-semibold text-gray-700 mb-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            {{ __('admin/edit_password.new_password') }}
                        </label>
                        <input type="password" name="new_password"
                            class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-xl p-3 sm:p-4 transition-all duration-200 text-sm sm:text-base @error('new_password') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror"
                            placeholder="{{ __('admin/edit_password.new_password_placeholder') }}"
                            required>
                        @error('new_password')
                            <p class="text-red-600 text-sm mt-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="flex items-center gap-2 text-sm sm:text-base font-semibold text-gray-700 mb-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ __('admin/edit_password.confirm_new_password') }}
                        </label>
                        <input type="password" name="new_password_confirmation"
                            class="w-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-xl p-3 sm:p-4 transition-all duration-200 text-sm sm:text-base"
                            placeholder="{{ __('admin/edit_password.confirm_new_password_placeholder') }}"
                            required>
                    </div>
                </div>

                <div class="pt-4 flex flex-col sm:flex-row items-center gap-3">
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold px-6 py-3 sm:px-8 sm:py-4 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 text-sm sm:text-base">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        {{ __('admin/edit_password.update_password') }}
                    </button>
            <a href="{{ route('a.dashboard.index') }}" class="w-full sm:w-auto text-center font-semibold text-red-700 px-6 py-3 
                rounded-xl bg-gray-200 hover:underline transition text-sm sm:text-base">{{ __('admin/edit_password.cancel') }}</a>
                </div>
            </form>
        </div>
@endsection
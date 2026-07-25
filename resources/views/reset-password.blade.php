@extends('layouts.auth') 

@section('title', 'Set New Password - eMindTutor')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-2xl bg-white rounded-2xl shadow-lg p-12">
        
        <a href="{{ route('compro.index') }}" class="flex justify-center mb-3">
            <img src="{{ asset('images/logo.png') }}" alt="eMindTutor" class="h-20">
        </a>

        <h2 class="text-3xl font-bold text-center mb-4 text-blue-900">
            Reset Your Password
        </h2>
        <p class="text-center text-gray-500 mb-8">
            Please provide a new secure password for your account.
        </p>

        @if ($errors->any())
            <div class="mb-6">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">
                    Email Address
                </label>
                <input type="email" id="email" name="email" 
                    value="{{ $email ?? old('email') }}"
                    class="w-full mt-2 px-4 py-3 border bg-gray-50 rounded-xl shadow-sm focus:outline-none cursor-not-allowed" 
                    required readonly>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">
                    New Password
                </label>
                <input type="password" id="password" name="password" 
                    class="w-full mt-2 px-4 py-3 border rounded-xl shadow-sm focus:ring-2 focus:ring-blue-900 focus:outline-none @error('password') border-red-500 @enderror" 
                    placeholder="Minimum 8 characters"
                    required>
                @error('password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                    Confirm New Password
                </label>
                <input type="password" id="password_confirmation" name="password_confirmation" 
                    class="w-full mt-2 px-4 py-3 border rounded-xl shadow-sm focus:ring-2 focus:ring-blue-900 focus:outline-none" 
                    placeholder="Repeat your new password"
                    required>
            </div>

            <button type="submit" 
                class="w-full bg-blue-900 hover:bg-blue-800 text-white font-semibold py-3 rounded-full transition text-center">
                Update Password
            </button>
        </form>
    </div>
</div>
@endsection
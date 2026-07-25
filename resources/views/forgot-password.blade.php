@extends('layouts.auth') 

@section('title', 'Forgot Password - eMindTutor')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-2xl bg-white rounded-2xl shadow-lg p-12">
        
        <a href="{{ route('compro.index') }}" class="flex justify-center mb-3">
            <img src="{{ asset('images/logo.png') }}" alt="eMindTutor" class="h-20">
        </a>

        <h2 class="text-3xl font-bold text-center mb-4 text-blue-900">
            Forgot Password
        </h2>
        <p class="text-center text-gray-500 mb-8">
            Enter your email and we will send you a reset link.
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

        @if (session('status'))
            <div class="mb-6">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">
                    Email Address
                </label>
                <input type="email" id="email" name="email" 
                    value="{{ old('email') }}"
                    class="w-full mt-2 px-4 py-3 border rounded-xl shadow-sm focus:ring-2 focus:ring-blue-900 focus:outline-none @error('email') border-red-500 @enderror" 
                    placeholder="Enter your registered email"
                    required autofocus>
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" 
                class="w-full bg-blue-900 hover:bg-blue-800 text-white font-semibold py-3 rounded-full transition text-center">
                Send Reset Link
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            Remember your password? 
            <a href="{{ route('login') }}" class="text-blue-900 font-semibold hover:underline">
                Back to Login
            </a>
        </p>
    </div>
</div>
@endsection
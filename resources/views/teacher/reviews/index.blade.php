@extends('layouts.teacher') 

@section('title', __('teacher/reviews.page_title', ['site' => 'EMindTutor']))
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-bold mb-6">{{ __('teacher/reviews.header') }}</h2>

    @if($existingReviews->isEmpty())
        <div class="bg-white shadow rounded-xl p-6 text-center text-gray-500">
            {{ __('teacher/reviews.no_reviews') }}
        </div>
    @else
        <div class="space-y-4">
            @foreach($existingReviews as $review)
                <div class="bg-white shadow rounded-xl p-6">
                    <div class="flex items-center justify-between mb-2">
                        <!-- Nama Student -->
                        <p class="font-semibold text-gray-700">
                            {{ $review->student->user->name ?? __('teacher/reviews.anonymous') }}
                        </p>
                        <!-- Tanggal -->
                        <span class="text-sm text-gray-500">
                            {{ $review->created_at->format('d M Y') }}
                        </span>
                    </div>

                    <!-- Rating (bintang) -->
                    <div class="flex items-center mb-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg" 
                                 class="h-5 w-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                 viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.946a1 
                                1 0 00.95.69h4.148c.969 0 1.371 
                                1.24.588 1.81l-3.357 2.44a1 1 0 
                                00-.364 1.118l1.287 3.946c.3.922-.755 
                                1.688-1.54 1.118l-3.357-2.44a1 1 0 
                                00-1.175 0l-3.357 2.44c-.784.57-1.838-.196-1.539-1.118l1.287-3.946a1 
                                1 0 00-.364-1.118L2.075 9.373c-.783-.57-.38-1.81.588-1.81h4.149a1 
                                1 0 00.95-.69l1.287-3.946z" />
                            </svg>
                        @endfor
                    </div>

                    <!-- Komentar -->
                    <p class="text-gray-600">{{ $review->comment }}</p>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

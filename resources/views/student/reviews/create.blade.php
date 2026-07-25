@extends('layouts.student')

@section('title', __('student/reviews/create.title'))

@section('content')
<div class="min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-white border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-900">{{ __('student/reviews/create.header') }}</h3>
                    <a href="{{ route('student.reviews.index') }}" 
                       class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        {{ __('student/reviews/create.breadcrumb.back') }}
                    </a>
                </div>
            </div>

            <!-- Body -->
            <div class="px-6 py-6">
                <form action="{{ route('student.reviews.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Hidden Fields -->
                    <input type="hidden" name="class_schedule_id" value="{{ $classSchedule->id }}">
                    
                    <!-- Info Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Teacher Info -->
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-blue-600 uppercase tracking-wide">{{ __('student/reviews/create.labels.teacher_name') }}</p>
                                    <p class="text-lg font-semibold text-gray-900 mt-1">{{ $teacher->user->name }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Course Info -->
                        <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-green-600 uppercase tracking-wide">{{ __('student/reviews/create.labels.course_name') }}</p>
                                    <p class="text-lg font-semibold text-gray-900 mt-1">{{ $course->nama_kos }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-amber-50 to-amber-100 rounded-xl p-6 border border-amber-200">
                        <p class="text-sm font-medium text-amber-700 uppercase tracking-wide">Class Schedule</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $classSchedule->nama_kelas }}</p>
                    </div>

                    <!-- Rating Section -->
                    <div class="space-y-2">
                        <label for="rating" class="block text-sm font-semibold text-gray-900">
                            {{ __('student/reviews/create.labels.rating') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="rating-input">
                            <input type="hidden" name="rating" id="rating" value="{{ old('rating', 0) }}">
                            <div class="flex items-center space-x-1 mb-3" id="starRating">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" class="star-btn p-1 rounded-md hover:bg-gray-50 transition-colors duration-200" data-rating="{{ $i }}">
                                        <svg class="w-8 h-8 text-gray-300 hover:text-yellow-400 transition-colors duration-200 cursor-pointer star-icon" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    </button>
                                @endfor
                            </div>
                            <p class="text-sm text-gray-600">{{ __('student/reviews/create.rating.helper') }}</p>
                        </div>
                        @error('rating')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Comment Section -->
                    <div class="space-y-2">
                        <label for="comment" class="block text-sm font-semibold text-gray-900">{{ __('student/reviews/create.labels.comment') }}</label>
                        <div class="relative">
                            <textarea 
                                name="comment" 
                                id="comment" 
                                rows="5"
                                maxlength="1000"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none @error('comment') border-red-300 focus:ring-red-500 @enderror"
                                placeholder="{{ __('student/reviews/create.comment.placeholder') }}">{{ old('comment') }}</textarea>
                            <div class="flex justify-between items-center mt-2">
                                <p class="text-sm text-gray-600">{{ __('student/reviews/create.comment.max_hint', ['max' => 1000]) }}</p>
                                <p class="text-sm text-gray-500" id="charCounter">0/1000 {{ __('student/reviews/create.js.characters') }}</p>
                            </div>
                        </div>
                        @error('comment')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-4 pt-6">
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            {{ __('student/reviews/create.buttons.submit') }}
                        </button>
                        <a href="{{ route('student.reviews.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            {{ __('student/reviews/create.buttons.cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Translated strings for JS
    const JS_GIVE_RATING_ALERT = {!! json_encode(__('student/reviews/create.js.give_rating_alert')) !!};
    const JS_CHAR_UNIT = {!! json_encode(__('student/reviews/create.js.characters')) !!};
    const starBtns = document.querySelectorAll('.star-btn');
    const starIcons = document.querySelectorAll('.star-icon');
    const ratingInput = document.getElementById('rating');
    let currentRating = parseInt('{{ old("rating", 0) }}') || 0;
    
    // Set initial rating
    updateStars(currentRating);
    
    starBtns.forEach((btn, index) => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            currentRating = index + 1;
            ratingInput.value = currentRating;
            updateStars(currentRating);
        });
        
        btn.addEventListener('mouseenter', function() {
            updateStars(index + 1, true);
        });
    });
    
    document.getElementById('starRating').addEventListener('mouseleave', function() {
        updateStars(currentRating);
    });
    
    function updateStars(rating, isHover = false) {
        starIcons.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        });
    }
    
    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        if (currentRating === 0) {
            e.preventDefault();
            alert(JS_GIVE_RATING_ALERT);
        }
    });
    
    // Character counter for comment
    const commentTextarea = document.getElementById('comment');
    const charCounter = document.getElementById('charCounter');
    
    if (commentTextarea && charCounter) {
        function updateCharCounter() {
            const currentLength = commentTextarea.value.length;
            const maxLength = 1000;
            charCounter.textContent = `${currentLength}/${maxLength} ${JS_CHAR_UNIT}`;
            
            if (currentLength > maxLength * 0.9) {
                charCounter.classList.remove('text-gray-500');
                charCounter.classList.add('text-yellow-600');
            } else if (currentLength === maxLength) {
                charCounter.classList.remove('text-yellow-600');
                charCounter.classList.add('text-red-600');
            } else {
                charCounter.classList.remove('text-yellow-600', 'text-red-600');
                charCounter.classList.add('text-gray-500');
            }
        }
        
        commentTextarea.addEventListener('input', updateCharCounter);
        updateCharCounter(); // Initial call
    }
});
</script>

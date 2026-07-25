@extends('layouts.admin')

@section('content')
<div class="min-h-screen">
    <div class="mx-auto">
        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <form action="{{ route('company.reviews.store') }}" method="POST">
                @csrf
                
                <div class="p-8 space-y-8">
                    
                    <!-- Rating Bintang Interaktif -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-4">
                            Penilaian Anda
                        </label>
                        
                        <div class="flex items-center gap-3 mb-2">
                            <div class="flex gap-2" id="star-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" 
                                            class="star-btn transition-all duration-200 hover:scale-110 focus:outline-none"
                                            data-rating="{{ $i }}">
                                        <svg class="w-12 h-12 text-gray-300 hover:text-yellow-400 transition-colors duration-150" 
                                             fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </button>
                                @endfor
                            </div>
                            <span id="rating-text" class="text-lg font-medium text-gray-400 ml-2">
                                Klik bintang
                            </span>
                        </div>
                        
                        <input type="hidden" name="rating" id="rating-input" required>
                        
                        @error('rating')
                            <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Komentar -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            Ulasan Anda <span class="text-gray-400 font-normal">(Pilihan)</span>
                        </label>
                        <div class="relative">
                            <textarea name="comment" 
                                      rows="5"
                                      class="w-full rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-200 p-4 resize-none"
                                      placeholder="Beritahu kami tentang pengalaman anda...">{{ old('comment') }}</textarea>
                            <div class="absolute bottom-3 right-3 text-xs text-gray-400">
                                <span id="char-count">0</span> aksara
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Footer Actions -->
                <div class="bg-gray-50 px-8 py-5 flex items-center justify-end gap-3 border-t border-gray-100">
                    <a href="{{ route('company.reviews.index') }}"
                       class="px-6 py-2.5 border-2 border-gray-300 rounded-xl text-gray-700 font-medium hover:bg-gray-100 hover:border-gray-400 transition-all duration-200">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-medium hover:from-blue-700 hover:to-indigo-700 shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                        Hantar Ulasan
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star-btn');
    const ratingInput = document.getElementById('rating-input');
    const ratingText = document.getElementById('rating-text');
    const commentTextarea = document.querySelector('textarea[name="comment"]');
    const charCount = document.getElementById('char-count');
    
    let selectedRating = 0;
    
    const ratingLabels = {
        1: 'Sangat tidak puas',
        2: 'Tidak puas',
        3: 'Sederhana',
        4: 'Baik',
        5: 'Sangat baik'
    };
    
    // Rating bintang
    stars.forEach((star, index) => {
        star.addEventListener('click', function() {
            selectedRating = index + 1;
            ratingInput.value = selectedRating;
            updateStars(selectedRating);
            ratingText.textContent = ratingLabels[selectedRating];
            ratingText.classList.remove('text-gray-400');
            ratingText.classList.add('text-indigo-600');
        });
        
        star.addEventListener('mouseenter', function() {
            updateStars(index + 1);
        });
    });
    
    document.getElementById('star-rating').addEventListener('mouseleave', function() {
        updateStars(selectedRating);
    });
    
    function updateStars(rating) {
        stars.forEach((star, index) => {
            const svg = star.querySelector('svg');
            if (index < rating) {
                svg.classList.remove('text-gray-300');
                svg.classList.add('text-yellow-400');
            } else {
                svg.classList.remove('text-yellow-400');
                svg.classList.add('text-gray-300');
            }
        });
    }
    
    // Character counter
    if (commentTextarea && charCount) {
        commentTextarea.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
    }
});
</script>

@endsection
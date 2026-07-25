<div 
    x-data="{ open: true }"
    x-show="open"
    x-transition
    class="fixed inset-0 z-50 flex items-center justify-center bg-white/60 backdrop-blur-xlsm">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
        
        <h2 class="text-xl font-semibold text-gray-800 mb-2">
            Kami Butuh Feedback Anda ⭐
        </h2>

        <p class="text-gray-600 mb-6">
            Anda sudah menggunakan layanan kami selama beberapa waktu.
            Silakan berikan review untuk membantu kami meningkatkan kualitas layanan.
        </p>

        <div class="flex justify-end gap-3">
            <button 
                @click="open = false"
                class="px-4 py-2 rounded-lg border text-gray-600 hover:bg-gray-100"
            >
                Nanti Saja
            </button>

            <a 
                href="{{ route('company.reviews.create') }}"
                class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700"
            >
                Beri Review
            </a>
        </div>

    </div>
</div>
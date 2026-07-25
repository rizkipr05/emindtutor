@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-xl mx-auto">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Edit Review
    </h1>

    <form action="{{ route('company.reviews.update', $review->id) }}"
          method="POST"
          class="bg-white p-6 rounded-xl shadow space-y-5">
        @csrf
        @method('PUT')

        {{-- Rating --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Rating
            </label>
            <select name="rating"
                    class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                    required>
                @for($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" {{ $review->rating == $i ? 'selected' : '' }}>
                        {{ $i }} Bintang
                    </option>
                @endfor
            </select>
        </div>

        {{-- Comment --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Komentar
            </label>
            <textarea name="comment" rows="4"
                      class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">{{ $review->comment }}</textarea>
        </div>

        {{-- Action --}}
        <div class="flex justify-end gap-3">
            <a href="{{ route('company.reviews.index') }}"
               class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100">
                Batal
            </a>

            <button
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Update Review
            </button>
        </div>

    </form>

</div>
@endsection

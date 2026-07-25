@extends('layouts.admin')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-center">
        <div class="w-full max-w-5xl">

            <!-- Card Add Rule -->
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
                <!-- Header -->
                <div class="bg-green-600 text-white text-center py-4">
                    <h4 class="text-lg font-semibold">{{ __('admin/teachers/rules/create.header.title') }}</h4>
                </div>

                <!-- Body -->
                <div class="p-6">
                    <form action="{{ route('admin.teachers.rules.store') }}" method="POST">
                        @csrf

                        <!-- Rule Content -->
                        <div class="mb-4">
                            <label for="content" class="block font-semibold text-gray-700 mb-2">
                                {{ __('admin/teachers/rules/create.fields.content') }}
                            </label>
                            <textarea name="content" id="content" rows="6" 
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('content') border-red-500 @enderror" 
                                placeholder="{{ __('admin/teachers/rules/create.placeholders.content') }}">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-between items-center mt-6">
                            <a href="{{ route('admin.teachers.rules.index') }}" 
                               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                                {{ __('admin/teachers/rules/create.buttons.back') }}
                            </a>
                            <button type="submit" 
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition flex items-center gap-1">
                                {!! __('admin/teachers/rules/create.buttons.save') !!}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
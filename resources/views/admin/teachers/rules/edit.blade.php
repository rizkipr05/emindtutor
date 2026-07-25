@extends('layouts.admin')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-center">
        <div class="w-full max-w-5xl">

            <!-- Card Edit Aturan -->
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
                <!-- Header -->
                <div class="bg-yellow-400 text-gray-900 px-6 py-4">
                    <h4 class="text-lg font-semibold flex items-center gap-2">
                        {{ __('admin/teachers/rules/edit.header.title') }}
                    </h4>
                </div>

                <!-- Body -->
                <div class="p-6">
                    <form action="{{ route('admin.teachers.rules.update', $rule->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Isi Peraturan -->
                        <div class="mb-4">
                            <label for="content" class="block font-semibold text-gray-700 mb-2">
                                    {{ __('admin/teachers/rules/edit.fields.content') }}
                                </label>
                            <textarea name="content" id="content" rows="6"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400 @error('content') border-red-500 @enderror">{{ old('content', $rule->rule_description) }}</textarea>
                            @error('content')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tombol -->
                        <div class="flex justify-between items-center mt-6">
                            <a href="{{ route('admin.teachers.rules.index') }}"
                               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition flex items-center gap-1">
                                {!! __('admin/teachers/rules/edit.buttons.back') !!}
                            </a>
                            <button type="submit"
                                class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-medium px-4 py-2 rounded-lg transition flex items-center gap-1">
                                {!! __('admin/teachers/rules/edit.buttons.update') !!}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
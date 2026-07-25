@extends('layouts.admin')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-center">
        <div class="w-full max-w-5xl">
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
                <!-- Header -->
                <div class="bg-blue-600 text-white flex justify-between items-center px-6 py-4">
                    <h4 class="text-lg font-semibold">{{ __('admin/teachers/rules/index.title') }}</h4>
                    <div class="flex items-center space-x-2">
                        @if($teacherRule)
                            <a href="{{ route('admin.teachers.rules.edit', $teacherRule->id) }}" 
                               class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm transition">
                                {{ __('admin/teachers/rules/index.actions.edit') }}
                            </a>
                            <form action="{{ route('admin.teachers.rules.destroy', $teacherRule->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                    onclick="return confirm('{{ __('admin/teachers/rules/index.actions.confirm_delete') }}')"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition">
                                    {{ __('admin/teachers/rules/index.actions.delete') }}
                                </button>
                            </form>
                        @else
                            <a href="{{ route('admin.teachers.rules.create') }}" 
                               class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm transition">
                                {{ __('admin/teachers/rules/index.actions.create') }}
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Body -->
                <div class="p-6 min-h-[200px]">
                    @if($teacherRule)
                    <p class="text-gray-800 whitespace-pre-line text-base leading-relaxed">{{ $teacherRule->rule_description }}</p>

                    @else
                        <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded text-center">
                            {{ __('admin/teachers/rules/index.empty') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ __('admin/students/create.manual.header') }}</h1>
        <p class="text-gray-600">{{ __('admin/students/create.manual.subheader') }}</p>
    </div>



    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Error Messages -->
    @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg">
            <div class="flex items-start">
                <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h4 class="font-semibold mb-1">{{ __('admin/students/create.manual.errors_title') }}</h4>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Toggle Buttons -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex space-x-1 bg-gray-100 p-1 rounded-lg mb-6">
            <button type="button" id="manualTab" 
                    class="flex-1 py-2 px-4 text-sm font-medium rounded-md transition-colors duration-200 bg-white text-blue-600 shadow-sm">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                {{ __('admin/students/create.tabs.manual') }}
            </button>
            <button type="button" id="importTab" 
                    class="flex-1 py-2 px-4 text-sm font-medium rounded-md transition-colors duration-200 text-gray-500 hover:text-gray-700">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                {{ __('admin/students/create.tabs.import') }}
            </button>
        </div>

        <!-- Manual Input Form -->
        <div id="manualForm" class="form-container">
            <form action="{{ route('admin.students.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('admin/students/create.manual.fields.name.label') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror" 
                               placeholder="{{ __('admin/students/create.manual.fields.name.placeholder') }}">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('admin/students/create.manual.fields.email.label') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror" 
                               placeholder="{{ __('admin/students/create.manual.fields.email.placeholder') }}">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- IC Number -->
                    <div>
                   <label for="ic_number" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin/students/create.manual.fields.ic_number.label') }}</label>
                   <input type="text" 
                       id="ic_number" 
                       name="ic_number" 
                       value="{{ old('ic_number') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('ic_number') border-red-500 @enderror" 
                       placeholder="{{ __('admin/students/create.manual.fields.ic_number.placeholder') }}">
                        @error('ic_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                   <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin/students/create.manual.fields.phone.label') }}</label>
                   <input type="text" 
                       id="phone" 
                       name="phone" 
                       value="{{ old('phone') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror" 
                       placeholder="{{ __('admin/students/create.manual.fields.phone.placeholder') }}">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Sponsor Name -->
                    <div>
                        <label for="sponsor_name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin/students/create.manual.fields.sponsor_name.label') }}</label>
                        <input type="text" 
                            id="sponsor_name" 
                            name="sponsor_name" 
                            value="{{ old('sponsor_name') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sponsor_name') border-red-500 @enderror" 
                            placeholder="{{ __('admin/students/create.manual.fields.sponsor_name.placeholder') }}">
                        @error('sponsor_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sponsor Phone -->
                    <div>
                        <label for="sponsor_phone" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin/students/create.manual.fields.sponsor_phone.label') }}</label>
                        <input type="text" 
                            id="sponsor_phone" 
                            name="sponsor_phone" 
                            value="{{ old('sponsor_phone') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sponsor_phone') border-red-500 @enderror" 
                            placeholder="{{ __('admin/students/create.manual.fields.sponsor_phone.placeholder') }}">
                        @error('sponsor_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sponsor Email -->
                    <div>
                        <label for="sponsor_email" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin/students/create.manual.fields.sponsor_email.label') }}</label>
                        <input type="email" 
                            id="sponsor_email" 
                            name="sponsor_email" 
                            value="{{ old('sponsor_email') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sponsor_email') border-red-500 @enderror" 
                            placeholder="{{ __('admin/students/create.manual.fields.sponsor_email.placeholder') }}">
                        @error('sponsor_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin/students/create.manual.fields.address.label') }}</label>
                    <textarea id="address" 
                              name="address" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-500 @enderror" 
                              placeholder="{{ __('admin/students/create.manual.fields.address.placeholder') }}">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Course -->
                <div>
                    <label for="course_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin/students/create.manual.fields.course.label') }} <span class="text-red-500">*</span></label>
                    <select id="course_id" 
                            name="course_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('course_id') border-red-500 @enderror">
                        <option value="">{{ __('admin/students/create.manual.fields.course.placeholder') }}</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->nama_kos }}
                            </option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" 
                            onclick="window.history.back()" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                        {{ __('admin/students/create.manual.buttons.cancel') }}
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ __('admin/students/create.manual.buttons.save') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Import Excel Form -->
        <div id="importForm" class="form-container hidden">
            <form action="{{ route('admin.students.import') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- Course Selection for Import -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-blue-800 mb-2">{{ __('admin/students/create.import.course_block.title') }}</h4>
                            <p class="text-sm text-blue-700 mb-3">{{ __('admin/students/create.import.course_block.desc') }}</p>
                            
                            <div>
                                <label for="import_course_id" class="block text-sm font-medium text-blue-700 mb-2">
                                    {{ __('admin/students/create.manual.fields.course.label') }} <span class="text-red-500">*</span>
                                </label>
                                <select id="import_course_id" 
                                        name="course_id" 
                                        class="w-full px-3 py-2 border border-blue-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white @error('course_id') border-red-500 @enderror">
                                    <option value=""> {{ __('admin/students/create.manual.fields.course.placeholder') }} </option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                            {{ $course->nama_kos }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('course_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- File Upload Area -->
                <label for="file-upload" class="cursor-pointer">
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-gray-400 transition-colors duration-200">
                        <div class="space-y-4">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            
                            <div>
                                    <span class="text-lg font-medium text-gray-700">{{ __('admin/students/create.import.file_label') }}</span>
                                    <input id="file-upload" 
                                        name="file" 
                                        type="file" 
                                        accept=".xlsx,.xls" 
                                        class="sr-only @error('file') border-red-500 @enderror">
                                <p class="text-gray-500 mt-1">{{ __('admin/students/create.import.description') }}</p>
                                <p class="text-sm text-gray-400 mt-2">{{ __('admin/students/create.import.template') }}: .xlsx, .xls (Max: 10MB)</p>
                            </div>
                            
                            <div id="fileName" class="hidden">
                                <div class="bg-green-50 border border-green-200 rounded-md p-3 mt-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                            <span id="selectedFileName" class="text-green-700 font-medium"></span>
                                        </div>
                                        <button type="button" 
                                                id="removeFile" 
                                                class="text-red-500 hover:text-red-700 ml-2">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </label>

                @error('file')
                    <p class="text-sm text-red-600 text-center">{{ $message }}</p>
                @enderror

                <!-- Template Download -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h4 class="text-sm font-medium text-yellow-800">{{ __('admin/students/create.import.template') }}</h4>
                            <p class="text-sm text-yellow-700 mt-1">
                                {{ __('admin/students/create.import.template_desc', ['cols' => 'Name, Email, IC Number, Phone, Address']) }}<br>
                                <span class="text-xs">{{ __('admin/students/create.import.course_note') }}</span>
                            </p>
                            <div class="mt-2">
                                    <a href="{{ asset('templates/example_import_student.xlsx') }}" class="inline-flex items-center text-sm font-medium text-yellow-600 hover:text-yellow-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>  
                                    {{ __('admin/students/create.import.download') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" 
                            onclick="window.history.back()" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200">
                        {{ __('admin/students/create.import.buttons.cancel') }}
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        {{ __('admin/students/create.import.buttons.import') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const manualTab = document.getElementById('manualTab');
    const importTab = document.getElementById('importTab');
    const manualForm = document.getElementById('manualForm');
    const importForm = document.getElementById('importForm');
    const fileInput = document.getElementById('file-upload');
    const fileName = document.getElementById('fileName');
    const selectedFileName = document.getElementById('selectedFileName');
    const removeFile = document.getElementById('removeFile');

    // Tab switching
    manualTab.addEventListener('click', function() {
        // Update tab styles
        manualTab.className = 'flex-1 py-2 px-4 text-sm font-medium rounded-md transition-colors duration-200 bg-white text-blue-600 shadow-sm';
        importTab.className = 'flex-1 py-2 px-4 text-sm font-medium rounded-md transition-colors duration-200 text-gray-500 hover:text-gray-700';
        
        // Show/hide forms
        manualForm.classList.remove('hidden');
        importForm.classList.add('hidden');
    });

    importTab.addEventListener('click', function() {
        // Update tab styles
        importTab.className = 'flex-1 py-2 px-4 text-sm font-medium rounded-md transition-colors duration-200 bg-white text-green-600 shadow-sm';
        manualTab.className = 'flex-1 py-2 px-4 text-sm font-medium rounded-md transition-colors duration-200 text-gray-500 hover:text-gray-700';
        
        // Show/hide forms
        importForm.classList.remove('hidden');
        manualForm.classList.add('hidden');
    });

    // File upload handling
    fileInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            const file = e.target.files[0];
            selectedFileName.textContent = file.name;
            fileName.classList.remove('hidden');
        }
    });

    removeFile.addEventListener('click', function() {
        fileInput.value = '';
        fileName.classList.add('hidden');
    });

    // Drag and drop functionality
    const dropZone = document.querySelector('.border-dashed');
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.classList.add('border-blue-400', 'bg-blue-50');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-blue-400', 'bg-blue-50');
    }

    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length > 0) {
            fileInput.files = files;
            const changeEvent = new Event('change', { bubbles: true });
            fileInput.dispatchEvent(changeEvent);
        }
    }
});
</script>
@endsection
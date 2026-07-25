@extends('layouts.admin')

@section('content')
<div class="min-h-screen">
    <div class="mx-auto px-2 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                            <svg class="w-6 h-6 mr-3 text-blue-600" fill="currentColor" stroke="none" viewBox="0 0 24 24">
                                <path d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5m0-8c1.65 0 3 1.35 3 3s-1.35 3-3 3-3-1.35-3-3 1.35-3 3-3M4 22h16c.55 0 1-.45 1-1v-1c0-3.86-3.14-7-7-7h-4c-3.86 0-7 3.14-7 7v1c0 .55.45 1 1 1m6-7h4c2.76 0 5 2.24 5 5H5c0-2.76 2.24-5 5-5"></path>
                            </svg>
                            {{ __('admin/teachers/index.title') }}
                        </h1>
                        <p class="text-sm text-gray-500 mt-1">{{ __('admin/teachers/index.desc') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Flash Message --}}
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Action Buttons Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.teachers.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ __('admin/teachers/index.add') }}
                        </a>

                        <button id="btnActivate" 
                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm opacity-50 cursor-not-allowed" 
                                disabled>
                            <svg class="w-4 h-4 mr-2" fill="currentColor" stroke="none" viewBox="0 0 24 24">
                                <path d="M9 15.59 4.71 11.3 3.3 12.71l5 5c.2.2.45.29.71.29s.51-.1.71-.29l11-11-1.41-1.41L9.02 15.59Z"></path>
                            </svg>
                            {{ __('admin/teachers/index.activate') }}
                        </button>
                        
                        <button id="btnDeactivate" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm opacity-50 cursor-not-allowed" 
                                disabled>
                            <svg class="w-4 h-4 mr-2" fill="currentColor" stroke="none" viewBox="0 0 24 24">
                                <path d="M12 2C6.49 2 2 6.49 2 12s4.49 10 10 10 10-4.49 10-10S17.51 2 12 2M4 12c0-1.85.63-3.54 1.69-4.9L16.9 18.31A8 8 0 0 1 12 20c-4.41 0-8-3.59-8-8m14.31 4.9L7.1 5.69A8 8 0 0 1 12 4c4.41 0 8 3.59 8 8 0 1.85-.63 3.54-1.69 4.9"></path>
                            </svg>
                            {{ __('admin/teachers/index.deactivate') }}
                        </button>
                    </div>
                    
                    <div class="text-sm text-gray-500">
                        {{ __('admin/teachers/index.total') }}: <span class="font-semibold text-gray-700">{{ $teachers->count() }}</span> {{ __('admin/teachers/index.teachers') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Teachers Table Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <form id="bulkActionForm" method="POST">
                @csrf
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left">
                                    <div class="flex items-center">
                                        <input type="checkbox" id="selectAll" 
                                               class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('admin/teachers/index.name') }}
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('admin/teachers/index.phone') }}
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('admin/teachers/index.status') }}
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('admin/teachers/index.action') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($teachers as $teacher)
                                <tr class="hover:bg-gray-50 transition-colors duration-200 
                                    @if($teacher->status === 'inactive') bg-red-50 @endif">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" 
                                            class="teacher-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" 
                                            data-status="{{ $teacher->status }}" 
                                            value="{{ $teacher->id }}" 
                                            name="teacher_ids[]">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-blue-600">
                                                        {{ strtoupper(substr($teacher->user->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $teacher->user->name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                            {{ $teacher->phone ?: __('admin/teachers/index.empty_phone') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($teacher->status === 'active') bg-green-100 text-green-800 
                                            @else bg-red-100 text-red-800 @endif">
                                            <span class="w-1.5 h-1.5 mr-1.5 rounded-full 
                                                @if($teacher->status === 'active') bg-green-400 
                                                @else bg-red-400 @endif">
                                            </span>
                                            {{ $teacher->status === 'active' ? __('admin/teachers/index.active') : __('admin/teachers/index.inactive') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('admin.teachers.show', $teacher->id) }}" 
                                               class="text-blue-600 hover:text-blue-900 transition-colors duration-200 flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                {{ __('admin/teachers/index.view') }}
                                            </a>
                                            <a href="{{ route('admin.teachers.edit', $teacher->id) }}" 
                                               class="text-yellow-600 hover:text-yellow-900 transition-colors duration-200 flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                {{ __('admin/teachers/index.edit') }}
                                            </a>
                                            <div
                                               class="text-red-600 hover:text-yellow-900 transition-colors duration-200 flex items-center">
                                                <form id="deleteForm" 
                                                    action="{{ route('admin.teachers.destroy', $teacher->id) }}" 
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" 
                                                            onclick="confirmDelete('{{ $teacher->ic_number }}')" 
                                                            class="text-red-600 hover:text-yellow-900 transition-colors duration-200 flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" stroke="none" viewBox="0 0 24 24">
                                                            <path d="m17,6v-2c0-1.1-.9-2-2-2h-6c-1.1,0-2,.9-2,2v2H2v2h2v12c0,1.1.9,2,2,2h12c1.1,0,2-.9,2-2v-12h2v-2h-5Zm-8-2h6v2h-6v-2Zm9,16H6v-12h12v12Z"></path><path d="M14.29 10.29 12 12.59 9.71 10.29 8.29 11.71 10.59 14 8.29 16.29 9.71 17.71 12 15.41 14.29 17.71 15.71 16.29 13.41 14 15.71 11.71 14.29 10.29z"></path>
                                                        </svg>
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            @if($teachers->isEmpty())
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-2.239"></path>
                                            </svg>
                                            <h3 class="text-sm font-medium text-gray-900 mb-1">{{ __('admin/teachers/index.no_teacher') }}</h3>
                                            <p class="text-sm text-gray-500">Mulakan dengan menambah guru baru</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script --}}

{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const checkboxes = document.querySelectorAll('.teacher-checkbox');
    const selectAll = document.getElementById('selectAll');
    const btnActivate = document.getElementById('btnActivate');
    const btnDeactivate = document.getElementById('btnDeactivate');
    const form = document.getElementById('bulkActionForm');

    function updateButtons() {
        let checked = document.querySelectorAll('.teacher-checkbox:checked');
        if (checked.length === 0) {
            btnActivate.disabled = true;
            btnDeactivate.disabled = true;
            btnActivate.classList.add('opacity-50', 'cursor-not-allowed');
            btnDeactivate.classList.add('opacity-50', 'cursor-not-allowed');
            return;
        }

        let statuses = Array.from(checked).map(cb => cb.dataset.status);

        // Kalau semua status 'active' => hanya nyahaktif aktif
        if (statuses.every(s => s === 'active')) {
            btnActivate.disabled = true;
            btnDeactivate.disabled = false;
            btnActivate.classList.add('opacity-50', 'cursor-not-allowed');
            btnDeactivate.classList.remove('opacity-50', 'cursor-not-allowed');
        }
        // Kalau semua status 'inactive' => hanya aktifkan aktif
        else if (statuses.every(s => s === 'inactive')) {
            btnActivate.disabled = false;
            btnDeactivate.disabled = true;
            btnActivate.classList.remove('opacity-50', 'cursor-not-allowed');
            btnDeactivate.classList.add('opacity-50', 'cursor-not-allowed');
        }
        // Kalau campuran => kedua tombol disable
        else {
            btnActivate.disabled = true;
            btnDeactivate.disabled = true;
            btnActivate.classList.add('opacity-50', 'cursor-not-allowed');
            btnDeactivate.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }

    selectAll.addEventListener('change', () => {
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateButtons();
    });

    checkboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            if (!cb.checked) selectAll.checked = false;
            updateButtons();
        });
    });

    btnDeactivate.addEventListener('click', () => {
        let checked = document.querySelectorAll('.teacher-checkbox:checked');
        if (checked.length === 0) {
            alert("Sila pilih guru yang ingin dinyahaktifkan.");
        } else {
            form.action = "{{ route('admin.teachers.bulkDeactivate') }}";
            form.submit();
        }
    });

    btnActivate.addEventListener('click', () => {
        let checked = document.querySelectorAll('.teacher-checkbox:checked');
        if (checked.length === 0) {
            alert("Sila pilih guru yang ingin diaktifkan.");
        } else {
            form.action = "{{ route('admin.teachers.bulkActivate') }}";
            form.submit();
        }
    });

function confirmDelete(correctIC) {
    Swal.fire({
        title: 'Yakin ingin hapus guru ini?',
        text: "Tindakan ini tidak boleh diundur!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Sahkan Nombor IC',
                input: 'text',
                inputLabel: 'Masukkan nombor IC guru',
                showCancelButton: true,
                confirmButtonText: 'Sahkan',
                cancelButtonText: 'Batal',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Nombor IC wajib diisi';
                    }
                }
            }).then((icResult) => {
                if (icResult.isConfirmed) {
                    if (icResult.value.trim() === correctIC) {
                        document.getElementById('deleteForm').submit();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Nombor IC tidak sepadan',
                            text: 'Sila cuba lagi.',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        }
    });
}
</script>
@endsection
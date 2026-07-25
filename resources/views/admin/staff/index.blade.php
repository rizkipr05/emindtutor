@extends('layouts.admin')

@section('content')
<div class="min-h-screen p-2 lg:p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl lg:text-3xl font-bold text-slate-800 mb-2">{{ __('admin/staff/index.title') }}</h1>
            <p class="text-slate-600">{{ __('admin/staff/index.subtitle') }}</p>
        </div>

        {{-- Flash Message --}}
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-xl mb-6 flex items-center shadow-sm">
                <div class="w-5 h-5 rounded-full bg-emerald-500 flex items-center justify-center mr-3 flex-shrink-0">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                {{ session('success') }}
            </div>
        @endif

        {{-- Actions Bar --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 lg:p-6 mb-6">
            <div class="flex flex-col sm:flex-row gap-4 sm:justify-between sm:items-center">
                <a href="{{ route('admin.staff.create') }}" 
                   class="inline-flex items-center justify-center bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium py-3 px-6 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md text-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ __('admin/staff/index.add_button') }}
                </a>

                <div class="flex gap-3">
                    <button id="btnActivate" 
                        class="inline-flex items-center justify-center bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-medium py-3 px-5 rounded-xl opacity-50 cursor-not-allowed transition-all duration-200 flex-1 sm:flex-none" 
                        disabled>
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ __('admin/staff/index.activate') }}
                    </button>
                    <button id="btnDeactivate" 
                        class="inline-flex items-center justify-center bg-gradient-to-r from-amber-500 to-amber-600 text-white font-medium py-3 px-5 rounded-xl opacity-50 cursor-not-allowed transition-all duration-200 flex-1 sm:flex-none" 
                        disabled>
                        <svg class="w-4 h-4 mr-2" fill="currentColor" stroke="none" viewBox="0 0 24 24">
                            <path d="M12 2C6.49 2 2 6.49 2 12s4.49 10 10 10 10-4.49 10-10S17.51 2 12 2M4 12c0-1.85.63-3.54 1.69-4.9L16.9 18.31A8 8 0 0 1 12 20c-4.41 0-8-3.59-8-8m14.31 4.9L7.1 5.69A8 8 0 0 1 12 4c4.41 0 8 3.59 8 8 0 1.85-.63 3.54-1.69 4.9"></path>
                        </svg>
                        {{ __('admin/staff/index.deactivate') }}
                    </button>
                </div>
            </div>
        </div>

        {{-- Staff List Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <form id="bulkActionForm" method="POST">
                @csrf
                
                @if($staff->isEmpty())
                    <div class="text-center py-16 px-6">
                        <div class="w-20 h-20 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-700 mb-2">{{ __('admin/staff/index.empty.title') }}</h3>
                        <p class="text-slate-500 max-w-sm mx-auto">{{ __('admin/staff/index.empty.description') }}</p>
                    </div>
                @else
                    {{-- Header Row --}}
                    <div class="bg-slate-50 border-b border-slate-200 p-4 lg:p-6">
                        <div class="flex items-center">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" id="selectAll" class="w-5 h-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500 focus:ring-2">
                                <span class="ml-3 text-sm font-medium text-slate-700">{{ __('admin/staff/index.select_all') }}</span>
                            </label>
                        </div>
                    </div>

                    {{-- Staff Items --}}
                    <div class="divide-y divide-slate-100">
                        @foreach($staff as $item)
                            <div class="p-4 lg:p-6 hover:bg-slate-50 transition-colors duration-150 {{ $item->status === 'inactive' ? 'bg-red-50/50' : '' }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4 flex-1 min-w-0">
                                        <input type="checkbox" 
                                               class="item-checkbox w-5 h-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500 focus:ring-2" 
                                               data-status="{{ $item->status }}" 
                                               value="{{ $item->id }}" 
                                               name="item_ids[]">
                                        
                                        <div class="flex-1 min-w-0">
                                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                                <div class="flex-1 min-w-0">
                                                    <h3 class="text-base lg:text-lg font-semibold text-slate-900 truncate">
                                                        {{ $item->user->name }}
                                                    </h3>
                                                    <p class="text-sm text-slate-600 flex items-center mt-1">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                        </svg>
                                                        {{ $item->phone }}
                                                    </p>
                                                </div>
                                                
                                                <div class="flex items-center gap-3">
                                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium 
                                                        {{ $item->status === 'active' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-red-100 text-red-700 border border-red-200' }}">
                                                        <div class="w-2 h-2 rounded-full mr-2 
                                                            {{ $item->status === 'active' ? 'bg-emerald-500' : 'bg-red-500' }}">
                                                        </div>
                                                        {{ $item->status === 'active' ? __('admin/staff/index.status.active') : __('admin/staff/index.status.inactive') }}
                                                    </span>
                                                    
<div class="flex items-center gap-2">
    <a href="{{ route('admin.staff.edit', $item->id) }}" 
       class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors duration-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
        </svg>
    </a>
    <a href="{{ route('admin.staff.show', $item->id) }}" 
       class="inline-flex items-center justify-center w-8 h-8 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors duration-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        </svg>
    </a>
    <a href="{{ route('admin.staff.destroy', $item->id) }}" 
       onclick="event.preventDefault(); 
                if(confirm('Apakah Anda yakin ingin menghapus staff {{ $item->user->name }}?')) {
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = this.href;
                    var csrfField = document.createElement('input');
                    csrfField.type = 'hidden';
                    csrfField.name = '_token';
                    csrfField.value = '{{ csrf_token() }}';
                    var methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    form.appendChild(csrfField);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }"
       class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:bg-red-100 rounded-lg transition-colors duration-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
        </svg>
    </a>
</div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

<script>
    const checkboxes = document.querySelectorAll('.item-checkbox');
    const selectAll = document.getElementById('selectAll');
    const btnActivate = document.getElementById('btnActivate');
    const btnDeactivate = document.getElementById('btnDeactivate');
    const form = document.getElementById('bulkActionForm');

    function updateButtons() {
        let checked = document.querySelectorAll('.item-checkbox:checked');
        if (checked.length === 0) {
            btnActivate.disabled = true;
            btnDeactivate.disabled = true;
            btnActivate.classList.add('opacity-50', 'cursor-not-allowed');
            btnDeactivate.classList.add('opacity-50', 'cursor-not-allowed');
            btnActivate.classList.remove('hover:shadow-md');
            btnDeactivate.classList.remove('hover:shadow-md');
            return;
        }

        let statuses = Array.from(checked).map(cb => cb.dataset.status);

        if (statuses.every(s => s === 'active')) {
            btnActivate.disabled = true;
            btnDeactivate.disabled = false;
            btnActivate.classList.add('opacity-50', 'cursor-not-allowed');
            btnDeactivate.classList.remove('opacity-50', 'cursor-not-allowed');
            btnActivate.classList.remove('hover:shadow-md');
            btnDeactivate.classList.add('hover:shadow-md');
        } else if (statuses.every(s => s === 'inactive')) {
            btnActivate.disabled = false;
            btnDeactivate.disabled = true;
            btnActivate.classList.remove('opacity-50', 'cursor-not-allowed');
            btnDeactivate.classList.add('opacity-50', 'cursor-not-allowed');
            btnActivate.classList.add('hover:shadow-md');
            btnDeactivate.classList.remove('hover:shadow-md');
        } else {
            btnActivate.disabled = true;
            btnDeactivate.disabled = true;
            btnActivate.classList.add('opacity-50', 'cursor-not-allowed');
            btnDeactivate.classList.add('opacity-50', 'cursor-not-allowed');
            btnActivate.classList.remove('hover:shadow-md');
            btnDeactivate.classList.remove('hover:shadow-md');
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
        let checked = document.querySelectorAll('.item-checkbox:checked');
        if (checked.length === 0) {
            alert(@json(__('admin/staff/index.js.select_none_deactivate')));
        } else {
            form.action = "{{ route('admin.staff.bulkDeactivate') }}";
            form.submit();
        }
    });

    btnActivate.addEventListener('click', () => {
        let checked = document.querySelectorAll('.item-checkbox:checked');
        if (checked.length === 0) {
            alert(@json(__('admin/staff/index.js.select_none_activate')));
        } else {
            form.action = "{{ route('admin.staff.bulkActivate') }}";
            form.submit();
        }
    });
</script>
@endsection
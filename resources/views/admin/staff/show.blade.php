{{-- resources/views/admin/staff/show.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-900">{{ __('admin/staff/show.title') }}</h1>
            <div class="flex space-x-2">
                <a href="{{ route('admin.staff.edit', $staff) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    {{ __('admin/staff/show.buttons.edit') }}
                </a>
                <a href="{{ route('admin.staff.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    {{ __('admin/staff/show.buttons.back') }}
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2">
            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('admin/staff/show.sections.personal') }}</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('admin/staff/show.labels.full_name') }}</label>
                            <div class="mt-1 text-lg font-medium text-gray-900">{{ $staff->user->name }}</div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('admin/staff/show.labels.email') }}</label>
                            <div class="mt-1 text-sm text-gray-900">
                                <a href="mailto:{{ $staff->user->email }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $staff->user->email }}
                                </a>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('admin/staff/show.labels.ic_number') }}</label>
                            <div class="mt-1 text-sm text-gray-900 font-mono">{{ $staff->ic_number }}</div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('admin/staff/show.labels.phone') }}</label>
                            <div class="mt-1 text-sm text-gray-900">
                                <a href="tel:{{ $staff->phone }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $staff->phone }}
                                </a>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('admin/staff/show.labels.role') }}</label>
                            <div class="mt-1">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ ucfirst($staff->user->role) }}
                                </span>
                            </div>
                        </div>


                    </div>
                </div>
            </div>

            <!-- Employment & Allowance Information -->
            <div class="mt-6 bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('admin/staff/show.sections.allowance') }}</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('admin/staff/show.labels.allowance_amount') }}</label>
                            <div class="mt-1 text-xl font-bold text-green-600">
                                RM {{ number_format($staff->allowance, 0, ',', '.') }}
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('admin/staff/show.labels.allowance_type') }}</label>
                            <div class="mt-1">
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                    {{ __('admin/staff/show.jenis_elaun.' . $staff->allowance_type) }}
                                </span>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-sm text-gray-600">
                                    {{ __('admin/staff/show.labels.estimated_income') }}
                                </div>
                                <div class="text-lg font-medium text-gray-900">
                                    @switch($staff->allowance_type)
                                        @case('per_hour')
                                            RM {{ number_format($staff->allowance * 40 * 4, 0, ',', '.') }} / bulan (40 jam/minggu)
                                            @break
                                        @case('per_session')
                                            RM {{ number_format($staff->allowance * 20, 0, ',', '.') }} / bulan (20 sesi/bulan)
                                            @break
                                        @case('per_month')
                                            RM {{ number_format($staff->allowance, 0, ',', '.') }} / bulan
                                            @break
                                    @endswitch
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bank Information -->
            <div class="mt-6 bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('admin/staff/show.sections.bank') }}</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('admin/staff/show.labels.bank_name') }}</label>
                            <div class="mt-1 text-sm text-gray-900 font-medium">{{ $staff->bank_name }}</div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('admin/staff/show.labels.bank_account') }}</label>
                            <div class="mt-1 text-sm text-gray-900 font-mono bg-gray-50 px-3 py-2 rounded">
                                {{ $staff->bank_account_number }}
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('admin/staff/show.labels.bank_holder') }}</label>
                            <div class="mt-1 text-sm text-gray-900 font-medium">{{ $staff->bank_account_holder }}</div>
                        </div>
                        
                        @if($staff->account_holders_ic_number)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('admin/staff/show.labels.bank_holder_ic') }}</label>
                            <div class="mt-1 text-sm text-gray-900 font-mono">{{ $staff->account_holders_ic_number }}</div>
                        </div>
                        @endif
                    </div>

                    @if($staff->bank_account_holder !== $staff->user->name)
                        <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div class="flex">
                                <svg class="w-5 h-5 text-yellow-400 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <div class="text-sm font-medium text-yellow-800">{{ __('admin/staff/show.warning.title') }}</div>
                                    <div class="text-sm text-yellow-700">
                                        {{ __('admin/staff/show.warning.bank_holder_mismatch') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Status and Actions -->
        <div class="lg:col-span-1">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('admin/staff/show.sections.status') }}</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('admin/staff/show.labels.status') }}</label>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full 
                                    {{ $staff->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    @if($staff->status == 'active')
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ __('admin/staff/show.status.active') }}
                                    @else
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ __('admin/staff/show.status.inactive') }}
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('admin/staff/show.labels.joined_at') }}</label>
                            <div class="mt-1 text-sm text-gray-900">{{ $staff->created_at->format('d F Y H:i') }}</div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('admin/staff/show.labels.updated_at') }}</label>
                            <div class="mt-1 text-sm text-gray-900">{{ $staff->updated_at->format('d F Y H:i') }}</div>
                        </div>

                        <hr class="my-4">

                        <!-- Action Buttons -->
                        <div class="space-y-2">
                          

                            <form action="{{ route('admin.staff.destroy', $staff) }}" method="POST" class="w-full"
                                onsubmit="return confirm(@json(__('admin/staff/show.confirm_delete')))">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    {{ __('admin/staff/show.buttons.delete') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Account Information -->
    <div class="mt-6 bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">{{ __('admin/staff/show.sections.additional') }}</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('admin/staff/show.labels.account_created') }}</label>
                    <div class="mt-1 text-sm text-gray-900">{{ $staff->user->created_at->format('d F Y H:i') }}</div>
                </div>
                
            </div>

            @if($staff->user->remember_token)
            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="text-sm text-blue-800">
                    <strong>{{ __('admin/staff/show.labels.info') }}</strong>
                    {{ __('admin/staff/show.remember_token') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Custom styles for better UX */
.font-mono {
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
}

/* Hover effect for clickable elements */
a:hover, button:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Smooth transitions */
* {
    transition: all 0.2s ease-in-out;
}
</style>

@endsection
@extends('layouts.superadmin')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">{{ __('super_admin/staff.show.title') }}</h1>

    <div class="bg-white rounded-xl shadow p-6">
        {{-- Info User --}}
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-3">{{ __('super_admin/staff.show.user_info') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="text-gray-500">{{ __('super_admin/staff.show.name') }}:</span>
                    <p class="font-medium">{{ $staff->user->name ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-gray-500">{{ __('super_admin/staff.create.labels.email') }}:</span>
                    <p class="font-medium">{{ $staff->user->email ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-gray-500">{{ __('super_admin/staff.show.phone') }}:</span>
                    <p class="font-medium">{{ $staff->phone ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-gray-500">{{ __('super_admin/staff.create.labels.ic_number') }}:</span>
                    <p class="font-medium">{{ $staff->ic_number ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- Info Allowance --}}
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-3">{{ __('super_admin/staff.show.allowance_info') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="text-gray-500">{{ __('super_admin/staff.show.allowance') }}:</span>
                    <p class="font-medium">Rp {{ number_format($staff->allowance ?? 0, 0, ',', '.') }}</p>
                </div>
                <div>
                    <span class="text-gray-500">{{ __('super_admin/staff.show.allowance_type') }}:</span>
                    <p class="font-medium capitalize">{{ $staff->allowance_type ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- Info Bank --}}
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-3">{{ __('super_admin/staff.show.bank_info') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="text-gray-500">{{ __('super_admin/staff.show.bank_name') }}:</span>
                    <p class="font-medium">{{ $staff->bank_name ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-gray-500">{{ __('super_admin/staff.show.bank_account_number') }}:</span>
                    <p class="font-medium">{{ $staff->bank_account_number ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-gray-500">{{ __('super_admin/staff.show.bank_account_holder') }}:</span>
                    <p class="font-medium">{{ $staff->bank_account_holder ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-gray-500">{{ __('super_admin/staff.show.account_holders_ic_number') }}:</span>
                    <p class="font-medium">{{ $staff->account_holders_ic_number ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- Status --}}
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-3">{{ __('super_admin/staff.show.status') }}</h2>
            <p class="font-medium">
                @if($staff->status === 'active')
                    <span class="px-3 py-1 rounded-lg bg-green-100 text-green-700">{{ __('super_admin/staff.show.status_active.active') }}</span>
                @else
                    <span class="px-3 py-1 rounded-lg bg-red-100 text-red-700">{{ __('super_admin/staff.show.status_active.inactive') }}</span>
                @endif
            </p>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex space-x-3">

                <a href="{{ route('superadmin.staff.index') }}" 
                    class="px-4 py-2 rounded-lg bg-gray-500 text-white hover:bg-gray-600">{{ __('super_admin/staff.show.buttons.back') }}</a>

                <a href="{{ route('superadmin.staff.edit', $staff->id) }}" 
                    class="px-4 py-2 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600">{{ __('super_admin/staff.show.buttons.edit') }}</a>

            <form action="{{ route('superadmin.staff.destroy', $staff->id) }}" method="POST" 
                  onsubmit="return confirm('Yakin ingin menghapus staff ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-4 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600">
                    {{ __('super_admin/staff.show.buttons.delete') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
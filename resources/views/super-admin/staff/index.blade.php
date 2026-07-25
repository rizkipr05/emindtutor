@extends('layouts.superadmin')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">{{ __('super_admin/staff.heading') }}</h1>
        <a href="{{ route('superadmin.staff.create') }}" 
           class="px-4 py-2 rounded-lg bg-green-500 text-white hover:bg-green-600 text-sm font-medium">
            {{ __('super_admin/staff.add_button') }}
        </a>
    </div>

    <div class="overflow-x-auto bg-white rounded-xl shadow">
        <table class="w-full text-sm text-left text-gray-700 border border-gray-200">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs font-semibold">
                <tr>
                    <th class="px-6 py-3">{{ __('super_admin/staff.table.no') }}</th>
                    <th class="px-6 py-3">{{ __('super_admin/staff.table.name') }}</th>
                    <th class="px-6 py-3">{{ __('super_admin/staff.table.email') }}</th>
                    <th class="px-6 py-3">{{ __('super_admin/staff.table.ic') }}</th>
                    <th class="px-6 py-3 text-center">{{ __('super_admin/staff.table.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($staff as $index => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3">{{ $index + 1 }}</td>
                        <td class="px-6 py-3">{{ $item->user->name ?? '-' }}</td>
                        <td class="px-6 py-3">{{ $item->user->email ?? '-' }}</td>
                        <td class="px-6 py-3">{{ $item->ic_number ?? '-' }}</td>
                        <td class="px-6 py-3 text-center space-x-2">
                            <a href="{{ route('superadmin.staff.show', $item->id) }}" 
                               class="px-3 py-1 text-xs rounded-lg bg-blue-500 text-white hover:bg-blue-600">
                                {{ __('super_admin/staff.show_button') }}
                            </a>
                            <a href="{{ route('superadmin.staff.edit', $item->id) }}" 
                               class="px-3 py-1 text-xs rounded-lg bg-yellow-500 text-white hover:bg-yellow-600">
                                {{ __('super_admin/staff.edit_button') }}
                            </a>
                            <form action="{{ route('superadmin.staff.destroy', $item->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('{{ __('super_admin/staff.confirm_delete') }}')"
                                        class="px-3 py-1 text-xs rounded-lg bg-red-500 text-white hover:bg-red-600">
                                    {{ __('super_admin/staff.delete') }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center px-6 py-4 text-gray-500">
                            {{ __('super_admin/staff.table.empty') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>


</div>
@endsection

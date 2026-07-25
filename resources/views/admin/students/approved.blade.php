@extends('layouts.admin')

@section('content')
<div class="min-h-screen py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ activeTab: 'approved' }" x-cloak>
        
        <div class="mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ __('admin/students/approved.title') }}</h1>
                        <p class="text-gray-600 mt-2">{{ __('admin/students/approved.subtitle') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <form method="GET" action="{{ route('admin.students.approved') }}">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" name="search" 
                                placeholder="{{ __('admin/students/approved.search.placeholder') }}" 
                                value="{{ request('search') }}"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-gray-50 focus:bg-white">
                        </div>
                        <button type="submit"
                                class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-sm flex items-center justify-center space-x-2">
                            <span>Cari Mahasiswa</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @php
            // Filter koleksi di memori (Blade) agar 3 tab terisi dengan benar
            $approvedStudents = $students->where('status', 'active');
            $pendingStudents = $students->where('status', 'pending');
            $expiredStudents = $students->where('status', 'expired');
        @endphp

        <div class="mb-6 flex p-1 bg-gray-200/50 rounded-xl w-fit border border-gray-200 overflow-x-auto">
            <button @click="activeTab = 'approved'" 
                :class="activeTab === 'approved' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                class="px-6 py-2 rounded-lg font-semibold transition-all duration-200 flex items-center space-x-2 whitespace-nowrap">
                <span>Approved</span>
                <span class="bg-blue-100 text-blue-600 text-xs px-2 py-0.5 rounded-full">{{ $approvedStudents->count() }}</span>
            </button>
            <button @click="activeTab = 'pending'" 
                :class="activeTab === 'pending' ? 'bg-white text-amber-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                class="px-6 py-2 rounded-lg font-semibold transition-all duration-200 flex items-center space-x-2 whitespace-nowrap">
                <span>Pending</span>
                <span class="bg-amber-100 text-amber-600 text-xs px-2 py-0.5 rounded-full">{{ $pendingStudents->count() }}</span>
            </button>
            <button @click="activeTab = 'expired'" 
                :class="activeTab === 'expired' ? 'bg-white text-red-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                class="px-6 py-2 rounded-lg font-semibold transition-all duration-200 flex items-center space-x-2 whitespace-nowrap">
                <span>Expired</span>
                <span class="bg-red-100 text-red-600 text-xs px-2 py-0.5 rounded-full">{{ $expiredStudents->count() }}</span>
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-4 font-semibold text-gray-600 uppercase tracking-wider w-16 text-center">#</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 uppercase tracking-wider">Nama Mahasiswa</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 uppercase tracking-wider">No. IC</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($students as $student)
                            {{-- Kita buat semua baris ada, tapi disembunyikan pakai x-show sesuai tab aktif --}}
                            <tr class="hover:bg-gray-50 transition-colors" 
                                x-show="activeTab === '{{ $student->status == 'active' ? 'approved' : $student->status }}'"
                                x-transition>
                                
                                <td class="px-6 py-4 text-center text-gray-500">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">{{ $student->user->name }}</div>
                                    @if($student->status === 'expired')
                                        <div class="text-xs text-red-600 font-bold uppercase">Expired</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-mono text-gray-600">{{ $student->ic_number }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $student->user->email }}</td>
                                
                                <td class="px-6 py-4 text-center">
                                    @if($student->status === 'pending')
                                        <a href="{{ route('admin.students.approval', $student->id) }}" class="bg-green-600 text-white px-3 py-1 rounded text-xs font-bold">APPROVE</a>
                                    @elseif($student->status === 'expired')
                                        <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-full border border-red-200">EXPIRED</span>
                                    @else
                                        <a href="{{ route('admin.students.show', $student->id) }}" class="text-blue-600 font-bold hover:underline">DETAIL</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">Tidak ada data mahasiswa.</td>
                            </tr>
                        @endforelse

                        {{-- Baris tambahan jika filter tab menghasilkan kosong --}}
                        <tr x-show="activeTab === 'approved' && {{ $approvedStudents->count() }} === 0">
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">Tidak ada mahasiswa aktif.</td>
                        </tr>
                        <tr x-show="activeTab === 'pending' && {{ $pendingStudents->count() }} === 0">
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">Tidak ada pending.</td>
                        </tr>
                        <tr x-show="activeTab === 'expired' && {{ $expiredStudents->count() }} === 0">
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">Tidak ada data expired.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
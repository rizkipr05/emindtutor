@extends('layouts.superadmin')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Reminder WhatsApp</h1>
            <p class="text-gray-500">Atur pesan terjadwal untuk guru, murid, atau perusahaan.</p>
        </div>
        <a href="{{ route('superadmin.whatsapp-reminders.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg">+ Buat Reminder</a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded bg-green-100 border border-green-300 text-green-800 px-4 py-3">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 border-b text-gray-600">
                <tr><th class="px-5 py-3">Reminder</th><th class="px-5 py-3">Target</th><th class="px-5 py-3">Jadwal Berikutnya</th><th class="px-5 py-3">Status</th><th class="px-5 py-3 text-right">Aksi</th></tr>
            </thead>
            <tbody class="divide-y">
                @forelse($reminders as $reminder)
                    <tr>
                        <td class="px-5 py-4"><p class="font-semibold">{{ $reminder->title }}</p><p class="text-gray-500 text-xs">{{ $reminder->company?->nama_wakil_syarikat }}</p><p class="text-gray-500 truncate max-w-sm">{{ $reminder->message }}</p></td>
                        <td class="px-5 py-4 capitalize">{{ $reminder->audience === 'company' ? 'Perusahaan' : ($reminder->audience === 'teachers' ? 'Guru' : 'Murid') }}<br><span class="text-gray-500">{{ $reminder->frequency }}</span></td>
                        <td class="px-5 py-4">{{ $reminder->next_run_at?->format('d M Y H:i') }}</td>
                        <td class="px-5 py-4"><span class="px-2 py-1 rounded text-xs {{ $reminder->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ $reminder->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td class="px-5 py-4 text-right whitespace-nowrap"><a class="text-blue-600 hover:underline mr-3" href="{{ route('superadmin.whatsapp-reminders.edit', $reminder) }}">Edit</a><form class="inline" method="POST" action="{{ route('superadmin.whatsapp-reminders.destroy', $reminder) }}" onsubmit="return confirm('Hapus reminder ini?')">@csrf @method('DELETE')<button class="text-red-600 hover:underline">Hapus</button></form></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-12 text-center text-gray-500">Belum ada reminder. Buat reminder pertama Anda.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
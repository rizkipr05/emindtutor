@extends('layouts.admin')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="mb-6 flex flex-col justify-between gap-4 rounded-xl border border-gray-200 bg-white p-6 shadow-sm md:flex-row md:items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">All Students</h1>
            <p class="mt-1 text-sm text-gray-600">Manage every student account in your company.</p>
        </div>
        <a href="{{ route('admin.students.create') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-center text-sm font-semibold text-white hover:bg-blue-700">+ Add Student</a>
    </div>

    @if(session('success'))<div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>@endif

    <form method="GET" class="mb-6 grid gap-3 rounded-xl border border-gray-200 bg-white p-4 shadow-sm md:grid-cols-4">
        <input name="search" value="{{ request('search') }}" placeholder="Search name, email, IC, phone" class="rounded-lg border-gray-300 text-sm">
        <select name="approval" class="rounded-lg border-gray-300 text-sm"><option value="">All approvals</option><option value="yes" @selected(request('approval') === 'yes')>Approved</option><option value="no" @selected(request('approval') === 'no')>Not approved</option></select>
        <select name="status" class="rounded-lg border-gray-300 text-sm"><option value="">All subscription statuses</option><option value="active" @selected(request('status') === 'active')>Active</option><option value="pending" @selected(request('status') === 'pending')>Pending</option><option value="expired" @selected(request('status') === 'expired')>Expired</option></select>
        <button class="rounded-lg bg-slate-700 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Filter</button>
    </form>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm"><div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200 text-sm"><thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-600"><tr><th class="px-4 py-3">Student</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Latest invoice</th><th class="px-4 py-3 text-right">Actions</th></tr></thead><tbody class="divide-y divide-gray-100">
        @forelse($students as $student)
            @php($dueInvoice = $student->invoices->first(fn ($invoice) => in_array($invoice->status_bayaran, ['upcoming', 'overdue'])))
            <tr><td class="px-4 py-4"><p class="font-semibold text-gray-900">{{ $student->user?->name }}</p><p class="text-gray-500">{{ $student->user?->email }} · {{ $student->ic_number }}</p></td><td class="px-4 py-4"><span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">{{ ucfirst($student->status ?: 'pending') }}</span></td><td class="px-4 py-4">@if($dueInvoice)<p class="font-semibold">{{ $dueInvoice->nomor_invoice }}</p><p class="text-gray-500">RM {{ number_format($dueInvoice->jumlah, 2) }} · {{ ucfirst($dueInvoice->status_bayaran) }}</p>@else <span class="text-gray-400">No outstanding invoice</span>@endif</td><td class="px-4 py-4 text-right"><a href="{{ route('admin.students.show', $student->id) }}" class="mr-3 text-sm font-semibold text-blue-600 hover:underline">Details</a>@if($dueInvoice)<form method="POST" action="{{ route('admin.students.subscription-notifications.send', [$student, $dueInvoice]) }}" class="inline" onsubmit="return confirm('Send subscription notification to this student?')">@csrf<button class="rounded-lg bg-amber-500 px-3 py-2 text-xs font-bold text-white hover:bg-amber-600">Send notification</button></form>@endif</td></tr>
        @empty
            <tr><td colspan="4" class="px-4 py-10 text-center text-gray-500">No students found.</td></tr>
        @endforelse
    </tbody></table></div></div>
    <div class="mt-5">{{ $students->links() }}</div>
</div>
@endsection
@extends('layouts.admin')

@section('title', 'eMindTutor | ' . __('admin/students/account.title'))

@section('content')
@php($credit = $accountBalance < 0)
<div class="mx-auto max-w-6xl px-4 py-7">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <a href="{{ route('admin.students.show', $student->id) }}" class="text-sm text-slate-500 hover:text-slate-800">← {{ __('admin/students/account.back') }}</a>
            <h1 class="mt-2 text-2xl font-bold text-slate-900">{{ __('admin/students/account.title') }}</h1>
            <p class="text-sm text-slate-500">{{ $student->user?->name }} · {{ __('admin/students/account.subtitle') }}</p>
        </div>
        <div class="rounded-xl bg-emerald-50 px-7 py-3 text-center">
            <p class="text-xs font-medium text-slate-600">{{ __('admin/students/account.summary.current_balance') }}</p>
            <p class="text-2xl font-bold {{ $credit ? 'text-emerald-600' : 'text-red-600' }}">{{ $accountBalance < 0 ? '-' : '' }}RM {{ number_format(abs($accountBalance), 2) }}</p>
            <p class="text-xs {{ $credit ? 'text-emerald-700' : 'text-slate-600' }}">{{ $credit ? __('admin/students/account.summary.student_credit') : __('admin/students/account.summary.balance_due') }}</p>
        </div>
    </div>

    @if(session('success'))<div class="mb-5 rounded-lg bg-emerald-50 p-3 text-sm text-emerald-800">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="mb-5 rounded-lg bg-red-50 p-3 text-sm text-red-800">{{ $errors->first() }}</div>@endif

    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-xl border border-blue-100 bg-blue-50 p-5"><p class="text-sm text-slate-500">{{ __('admin/students/account.summary.total_invoices') }}</p><p class="mt-1 text-xl font-bold">RM {{ number_format($totalInvoiceAmount, 2) }}</p></div>
        <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-5"><p class="text-sm text-slate-500">{{ __('admin/students/account.summary.total_payments') }}</p><p class="mt-1 text-xl font-bold">RM {{ number_format($totalPaymentAmount, 2) }}</p></div>
        <div class="rounded-xl border border-purple-100 bg-purple-50 p-5"><p class="text-sm text-slate-500">{{ __('admin/students/account.summary.account_balance') }}</p><p class="mt-1 text-xl font-bold {{ $credit ? 'text-emerald-600' : 'text-red-600' }}">{{ $accountBalance < 0 ? '-' : '' }}RM {{ number_format(abs($accountBalance), 2) }}</p></div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <section class="relative overflow-visible rounded-xl border bg-white">
                <div class="flex items-center justify-between border-b p-4"><h2 class="font-bold">{{ __('admin/students/account.invoices.title') }}</h2><details class="relative z-20"><summary class="cursor-pointer rounded border border-blue-300 px-3 py-2 text-sm text-blue-700 hover:bg-blue-50">+ {{ __('admin/students/account.actions.create_manual_invoice') }}</summary><form method="POST" action="{{ route('admin.students.invoices.store', $student) }}" class="absolute right-0 z-30 mt-2 w-80 rounded-lg border bg-white p-4 shadow-xl">@csrf<label class="text-xs font-medium text-slate-700">{{ __('admin/students/account.form.course') }}</label><select required name="course_id" class="mb-2 mt-1 w-full rounded border-slate-300 text-sm"><option value="">{{ __('admin/students/account.form.select_course') }}</option>@foreach($courses as $course)<option value="{{ $course->id }}">{{ $course->localized_name }}</option>@endforeach</select><label class="text-xs font-medium text-slate-700">{{ __('admin/students/account.form.amount') }}</label><input required type="number" step="0.01" min="0" name="jumlah" class="mb-2 mt-1 w-full rounded border-slate-300 text-sm" placeholder="0.00"><label class="text-xs font-medium text-slate-700">{{ __('admin/students/account.form.start_date') }}</label><input required type="date" name="tarikh_terbit" value="{{ now('Asia/Kuala_Lumpur')->toDateString() }}" class="mb-2 mt-1 w-full rounded border-slate-300 text-sm"><label class="text-xs font-medium text-slate-700">{{ __('admin/students/account.form.end_date') }}</label><input required type="date" name="tarikh_tamat" value="{{ now('Asia/Kuala_Lumpur')->addMonth()->toDateString() }}" class="mb-2 mt-1 w-full rounded border-slate-300 text-sm"><label class="text-xs font-medium text-slate-700">{{ __('admin/students/account.form.status') }}</label><select name="status_bayaran" class="mb-3 mt-1 w-full rounded border-slate-300 text-sm"><option value="upcoming">{{ __('admin/students/account.invoice_status.upcoming') }}</option><option value="paid">{{ __('admin/students/account.invoice_status.paid') }}</option><option value="overdue">{{ __('admin/students/account.invoice_status.overdue') }}</option></select><button class="w-full rounded bg-blue-600 py-2 text-sm font-semibold text-white hover:bg-blue-700">{{ __('admin/students/account.actions.save') }}</button></form></details></div>
                <div class="overflow-x-auto"><table class="w-full text-sm"><thead class="bg-slate-50 text-left text-xs text-slate-500"><tr><th class="p-3">{{ __('admin/students/account.table.date') }}</th><th class="p-3">{{ __('admin/students/account.table.invoice_number') }}</th><th class="p-3">{{ __('admin/students/account.table.course') }}</th><th class="p-3 text-right">{{ __('admin/students/account.table.amount') }}</th><th class="p-3">{{ __('admin/students/account.table.status') }}</th></tr></thead><tbody>
                    @forelse($accountInvoices as $invoice)
                    <tr class="border-t"><td class="p-3">{{ optional($invoice->tarikh_terbit)->format('d M Y') ?? $invoice->created_at->format('d M Y') }}</td><td class="p-3 font-medium">{{ $invoice->nomor_invoice }} @if($invoice->status_bayaran === 'paid')<a href="{{ route('admin.students.invoices.receipt', [$student->id, $invoice->id]) }}" class="ml-1 text-xs text-blue-600">PDF</a>@endif</td><td class="p-3">{{ $invoice->course?->nama_kos ?? '-' }}</td><td class="p-3 text-right">RM {{ number_format($invoice->jumlah, 2) }}</td><td class="p-3"><span class="rounded-full px-2 py-1 text-xs {{ $invoice->status_bayaran === 'paid' ? 'bg-emerald-100 text-emerald-700' : ($invoice->status_bayaran === 'overdue' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">{{ __('admin/students/account.invoice_status.' . $invoice->status_bayaran) }}</span>@if($invoice->payment?->status === 'pending')<span class="ml-1 rounded-full bg-blue-100 px-2 py-1 text-xs text-blue-700">{{ __('admin/students/account.payment_status.pending') }}</span>@endif</td></tr>
                    @empty<tr><td colspan="5" class="p-5 text-center text-slate-500">{{ __('admin/students/account.empty.invoices') }}</td></tr>@endforelse
                </tbody></table></div>
            </section>
            <section class="overflow-hidden rounded-xl border bg-white">
                <div class="border-b p-4"><h2 class="font-bold">{{ __('admin/students/account.payment_history.title') }}</h2></div>
                <div class="overflow-x-auto"><table class="w-full text-sm"><thead class="bg-slate-50 text-left text-xs text-slate-500"><tr><th class="p-3">{{ __('admin/students/account.table.date') }}</th><th class="p-3">{{ __('admin/students/account.table.reference') }}</th><th class="p-3">{{ __('admin/students/account.table.method_proof') }}</th><th class="p-3 text-right">{{ __('admin/students/account.table.amount') }}</th></tr></thead><tbody>
                    @forelse($accountInvoices->where('status_bayaran', 'paid') as $invoice)
                    <tr class="border-t"><td class="p-3">{{ optional($invoice->payment?->dibayar_pada)->format('d M Y') ?? optional($invoice->tarikh_terbit)->format('d M Y') }}</td><td class="p-3 font-medium">{{ $invoice->nomor_invoice }}</td><td class="p-3">@if($invoice->payment?->bukti_bayar)<a href="{{ asset('storage/' . $invoice->payment->bukti_bayar) }}" target="_blank" class="text-blue-600 hover:underline">{{ __('admin/students/account.payment_history.proof') }}</a>@else<span class="text-slate-500">{{ __('admin/students/account.payment_history.manual_note') }}</span>@endif</td><td class="p-3 text-right font-medium text-emerald-700">RM {{ number_format($invoice->jumlah, 2) }}</td></tr>
                    @empty<tr><td colspan="4" class="p-5 text-center text-slate-500">{{ __('admin/students/account.empty.payments') }}</td></tr>@endforelse
                </tbody></table></div>
            </section>
        </div>
        <aside class="rounded-xl bg-slate-50 p-5"><h2 class="font-bold">{{ __('admin/students/account.timeline.title') }}</h2><div class="mt-4 space-y-4 border-l-2 border-slate-200 pl-4">
            @forelse($accountInvoices->take(10) as $invoice)
            <div><p class="text-xs text-slate-500">{{ optional($invoice->tarikh_terbit)->format('d M Y') }} · {{ __('admin/students/account.timeline.invoice') }}</p><p class="font-medium">{{ $invoice->nomor_invoice }}</p><p>+ RM {{ number_format($invoice->jumlah, 2) }}</p>@if($invoice->status_bayaran === 'paid')<p class="mt-1 text-sm text-emerald-700">− RM {{ number_format($invoice->jumlah, 2) }} · {{ __('admin/students/account.invoice_status.paid') }}</p>@endif</div>
            @empty<p class="text-sm text-slate-500">{{ __('admin/students/account.empty.transactions') }}</p>@endforelse
        </div></aside>
    </div>
</div>
@endsection
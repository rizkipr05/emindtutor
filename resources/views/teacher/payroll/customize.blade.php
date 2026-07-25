@extends('layouts.teacher')

@section('title', __('teacher/payroll.customize'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">{{ __('teacher/payroll.customize') }}</h2>
                <p class="mt-2 text-sm text-gray-600">
                    {{ __('teacher/payroll.customize_desc', ['month' => $month, 'year' => $year]) }}
                </p>
            </div>
            <a href="{{ route('teacher.sallaries.index', ['month' => $selectedMonth, 'year' => $selectedYear]) }}"
                class="inline-flex items-center justify-center rounded-lg px-5 py-3 text-sm font-semibold transition"
                style="background-color:#2563eb !important; color:#ffffff !important; border:1px solid #1d4ed8 !important;">
                <span style="color:#ffffff !important;">{{ __('teacher/payroll.customize_back') }}</span>
            </a>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <div class="flex flex-col gap-3 sm:flex-row">
            <button type="button"
                data-pdf-action="preview"
                class="inline-flex min-w-[220px] items-center justify-center rounded-lg px-6 py-3 text-sm font-semibold shadow-md transition"
                style="background-color:#2563eb !important; color:#ffffff !important; border:1px solid #1d4ed8 !important;">
                <span style="color:#ffffff !important;">{{ __('teacher/payroll.preview') }}</span>
            </button>
            <button type="button"
                data-pdf-action="download"
                class="inline-flex min-w-[220px] items-center justify-center rounded-lg bg-red-600 px-6 py-3 text-sm font-semibold text-white shadow-md transition hover:bg-red-700">
                {{ __('teacher/payroll.download') }}
            </button>
        </div>
    </div>
untuk tombol login biometric
    <div class="bg-white shadow-md rounded-lg p-6">
        <form id="pdf-customization-form" class="space-y-6">
            <input type="hidden" name="month" value="{{ $selectedMonth }}">
            <input type="hidden" name="year" value="{{ $selectedYear }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('teacher/payroll.customize_fields.company_name') }}</label>
                    <input type="text" name="company_name" value="{{ $pdfFields['company_name'] }}" class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('teacher/payroll.customize_fields.company_registration') }}</label>
                    <input type="text" name="company_registration" value="{{ $pdfFields['company_registration'] }}" class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('teacher/payroll.customize_fields.company_address') }}</label>
                    <textarea name="company_address" rows="2" class="w-full border-gray-300 rounded-md shadow-sm">{{ $pdfFields['company_address'] }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('teacher/payroll.customize_fields.employee_name') }}</label>
                    <input type="text" name="employee_name" value="{{ $pdfFields['employee_name'] }}" class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('teacher/payroll.customize_fields.employee_nric') }}</label>
                    <input type="text" name="employee_nric" value="{{ $pdfFields['employee_nric'] }}" class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('teacher/payroll.customize_fields.bank_name') }}</label>
                    <input type="text" name="bank_name" value="{{ $pdfFields['bank_name'] }}" class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('teacher/payroll.customize_fields.bank_account_number') }}</label>
                    <input type="text" name="bank_account_number" value="{{ $pdfFields['bank_account_number'] }}" class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('teacher/payroll.customize_fields.outlet') }}</label>
                    <input type="text" name="outlet" value="{{ $pdfFields['outlet'] }}" class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('teacher/payroll.customize_fields.payslip_date') }}</label>
                    <input type="text" name="payslip_date" value="{{ $pdfFields['payslip_date'] }}" class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('teacher/payroll.customize_fields.payroll_period') }}</label>
                    <input type="text" name="payroll_period" value="{{ $pdfFields['payroll_period'] }}" class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('teacher/payroll.customize_fields.department') }}</label>
                    <input type="text" name="department" value="{{ $pdfFields['department'] }}" class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('teacher/payroll.customize_fields.position') }}</label>
                    <input type="text" name="position" value="{{ $pdfFields['position'] }}" class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('teacher/payroll.customize_fields.employee_ref') }}</label>
                    <input type="text" name="employee_ref" value="{{ $pdfFields['employee_ref'] }}" class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div>
                    <h4 class="font-semibold text-slate-800 mb-3">{{ __('teacher/payroll.customize_sections.annual_leave') }}</h4>
                    <div class="space-y-3">
                        <input type="text" name="annual_leave_entitlement" value="{{ $pdfFields['annual_leave_entitlement'] }}" placeholder="{{ __('teacher/payroll.customize_placeholders.entitlement') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                        <input type="text" name="annual_leave_earned" value="{{ $pdfFields['annual_leave_earned'] }}" placeholder="{{ __('teacher/payroll.customize_placeholders.earned_leave') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                        <input type="text" name="annual_leave_cf" value="{{ $pdfFields['annual_leave_cf'] }}" placeholder="{{ __('teacher/payroll.customize_placeholders.cf') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                        <input type="text" name="annual_leave_taken" value="{{ $pdfFields['annual_leave_taken'] }}" placeholder="{{ __('teacher/payroll.customize_placeholders.taken') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                        <input type="text" name="annual_leave_balance" value="{{ $pdfFields['annual_leave_balance'] }}" placeholder="{{ __('teacher/payroll.customize_placeholders.balance') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>
                <div>
                    <h4 class="font-semibold text-slate-800 mb-3">{{ __('teacher/payroll.customize_sections.medical_leave') }}</h4>
                    <div class="space-y-3">
                        <input type="text" name="medical_leave_entitlement" value="{{ $pdfFields['medical_leave_entitlement'] }}" placeholder="{{ __('teacher/payroll.customize_placeholders.entitlement') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                        <input type="text" name="medical_leave_taken" value="{{ $pdfFields['medical_leave_taken'] }}" placeholder="{{ __('teacher/payroll.customize_placeholders.taken') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                        <input type="text" name="medical_leave_balance" value="{{ $pdfFields['medical_leave_balance'] }}" placeholder="{{ __('teacher/payroll.customize_placeholders.balance') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>
                <div>
                    <h4 class="font-semibold text-slate-800 mb-3">{{ __('teacher/payroll.customize_sections.employer_contribution') }}</h4>
                    <div class="space-y-3">
                        <input type="text" name="employer_epf" value="{{ $pdfFields['employer_epf'] }}" placeholder="{{ __('teacher/payroll.customize_placeholders.epf') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                        <input type="text" name="employer_socso" value="{{ $pdfFields['employer_socso'] }}" placeholder="{{ __('teacher/payroll.customize_placeholders.socso') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                        <input type="text" name="employer_eis" value="{{ $pdfFields['employer_eis'] }}" placeholder="{{ __('teacher/payroll.customize_placeholders.eis') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                        <input type="text" name="employer_pcb" value="{{ $pdfFields['employer_pcb'] }}" placeholder="{{ __('teacher/payroll.customize_placeholders.pcb') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                        <input type="text" name="employer_hrd" value="{{ $pdfFields['employer_hrd'] }}" placeholder="{{ __('teacher/payroll.customize_placeholders.hrd') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('pdf-customization-form');
    if (!form) return;

    const previewBase = @json(route('teacher.sallaries.preview', ['month' => '__MONTH__', 'year' => '__YEAR__']));
    const downloadBase = @json(route('teacher.sallaries.download', ['month' => '__MONTH__', 'year' => '__YEAR__']));

    document.querySelectorAll('[data-pdf-action]').forEach((button) => {
        button.addEventListener('click', function () {
            const params = new URLSearchParams(new FormData(form));
            const month = params.get('month');
            const year = params.get('year');
            const base = this.dataset.pdfAction === 'preview' ? previewBase : downloadBase;
            const url = base.replace('__MONTH__', encodeURIComponent(month)).replace('__YEAR__', encodeURIComponent(year)) + '?' + params.toString();

            if (this.dataset.pdfAction === 'preview') {
                window.open(url, '_blank');
            } else {
                window.location.href = url;
            }
        });
    });
});
</script>
@endsection

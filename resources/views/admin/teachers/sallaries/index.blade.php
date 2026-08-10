@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-semibold mb-4">{{ __('admin/teachers/sallaries/index.title') }}</h2>

    {{-- Filter Bulan dan Tahun --}}
    <form method="GET" action="{{ route('admin.teachers.sallaries.index') }}" class="flex items-center gap-4 mb-6">
        <div>
            <label for="month" class="block text-sm font-medium text-gray-700">{{ __('admin/teachers/sallaries/index.filter.month') }}</label>
            <select name="month" id="month" class="mt-1 block w-full border rounded-md px-3 py-2">
                @for ($m = 1; $m <= 12; $m++)
                    @php $monthKey = (string) $m; @endphp
                    <option value="{{ $monthKey }}" {{ (string)$selectedMonth === $monthKey ? 'selected' : '' }}>{{ __('admin/teachers/sallaries/index.months.' . $monthKey) }}</option>
                @endfor
            </select>
        </div>
        <div>
            <label for="year" class="block text-sm font-medium text-gray-700">{{ __('admin/teachers/sallaries/index.filter.year') }}</label>
            <select name="year" id="year" class="mt-1 block w-full border rounded-md px-3 py-2">
                @for ($i = now()->year; $i >= now()->year - 5; $i--)
                    <option value="{{ $i }}" {{ $selectedYear == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="mt-6">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">{{ __('admin/teachers/sallaries/index.filter.button') }}</button>
        </div>
    </form>

    {{-- Statistik Ringkas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white shadow rounded p-4">
            <div class="text-gray-600 text-sm">{{ __('admin/teachers/sallaries/index.stats.to_pay') }}</div>
            <div class="text-xl font-bold">{{ $totalTeachers }}</div>
        </div>
        <div class="bg-white shadow rounded p-4">
            <div class="text-gray-600 text-sm">{{ __('admin/teachers/sallaries/index.stats.total_month') }}</div>
            <div class="text-xl font-bold">RM {{ number_format($totalAmount, 2, '.', ',') }}</div>
        </div>
        <div class="bg-white shadow rounded p-4">
            <div class="text-gray-600 text-sm">Guru Sudah Dibayar</div>
            <div class="text-xl font-bold">{{ $paidTeachers }}</div>
        </div>
        <div class="bg-white shadow rounded p-4">
            <div class="text-gray-600 text-sm">Uang Sudah Dibayar</div>
            <div class="text-xl font-bold">RM {{ number_format($paidAmount, 2, '.', ',') }}</div>
        </div>
    </div>
    
    {{-- Tabel Gaji --}}
    <form method="POST" action="{{ route('admin.teachers.sallaries.createPaid') }}" class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4 shadow-sm md:p-6">
        @csrf
        <input type="hidden" name="month" value="{{ $selectedMonth }}">
        <input type="hidden" name="year" value="{{ $selectedYear }}">
        
        {{-- Tombol Mark as Paid --}}
        <div class="mb-4 flex flex-wrap gap-3">
            <button type="submit"
                    form="sync-attendance-form"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded"
                    onclick="return confirm('Masukkan gaji dari attendance check-out bulan ini ke tabel salary?')">
                <i class="fas fa-sync mr-2"></i>Masukkan dari Attendance
            </button>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded" onclick="return confirm('{{ __('admin/teachers/sallaries/index.actions.confirm_mark_paid') }}')">
                <i class="fas fa-check mr-2"></i>{{ __('admin/teachers/sallaries/index.actions.mark_paid') }}
            </button>
            <!-- <span class="text-sm text-gray-600 ml-4">{{ __('admin/teachers/sallaries/index.actions.mark_paid') }}</span> -->
        </div>
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-gradient-to-r from-teal-600 to-cyan-600 text-white">
                    <tr>
                        <th class="w-12 px-4 py-3.5"><input type="checkbox" id="selectAll" class="rounded border-white/50 text-teal-600 focus:ring-teal-300"></th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">{{ __('admin/teachers/sallaries/index.table.type') }}</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">{{ __('admin/teachers/sallaries/index.table.name') }}</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">{{ __('admin/teachers/sallaries/index.table.attendance') }}</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">{{ __('admin/teachers/sallaries/index.table.total_minutes') }}</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">{{ __('admin/teachers/sallaries/index.table.amount') }}</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">{{ __('admin/teachers/sallaries/index.table.status') }}</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">{{ __('admin/teachers/sallaries/index.table.actions') }}</th>
                    </tr>
                </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse ($sallaries as $index => $sallary)
                    <tr class="transition-colors hover:bg-slate-50 {{ $sallary['type'] == 'Teacher' ? 'border-l-4 border-l-blue-400' : 'border-l-4 border-l-emerald-400' }}">
                        <td class="px-4 py-3.5">
                            @if($sallary['status'] == 'pending')
                                <input type="checkbox" 
                                       name="selected_items[]" 
                                       value="{{ json_encode([
                                           'type' => $sallary['type'],
                                           'name' => $sallary['name'],
                                           'salary' => $sallary['salary'],
                                           'attendance_count' => $sallary['attendance_count'] ?? 0,
                                           'total_minutes' => $sallary['total_minutes'] ?? 0,
                                           'teacher_id' => $sallary['teacher_id'] ?? null,
                                           'staff_id' => $sallary['staff_id'] ?? null
                                       ]) }}"
                                       class="salary-checkbox">
                            @else
                                <span class="text-green-600">✓</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $sallary['type'] == 'Teacher' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                {{ __('admin/teachers/sallaries/index.type_labels.' . $sallary['type']) }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5 font-semibold text-slate-800">{{ $sallary['name'] }}</td>
                        <td class="px-4 py-3.5 text-slate-600">
                            @if($sallary['type'] == 'Teacher')
                                {{ $sallary['attendance_count'] }} {{ __('admin/teachers/sallaries/index.attendance_unit') }}
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-slate-600">
                            @if($sallary['type'] == 'Teacher' && $sallary['total_minutes'] !== '-')
                                {{ number_format($sallary['total_minutes'] / 60, 1) }} {{ __('admin/teachers/sallaries/index.hours_unit') }}
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 font-bold text-slate-800">
                            RM {{ number_format($sallary['salary'], 2, '.', ',') }}
                        </td>
                        <td class="px-4 py-3.5">
                            @if($sallary['status'] == 'paid')
                                <span class="text-green-600 font-semibold">{{ __('admin/teachers/sallaries/index.status.paid') }}</span>
                            @else
                                <span class="text-yellow-600 font-semibold">{{ __('admin/teachers/sallaries/index.status.pending') }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            <button type="button" class="text-blue-600 hover:underline text-sm" onclick="showDetail('{{ $sallary['name'] }}', '{{ $sallary['type'] }}', '{{ number_format($sallary['salary'], 2) }}', '{{ $sallary['attendance_count'] ?? '-' }}')">{{ __('admin/teachers/sallaries/index.table.actions') }}</button>
                            @if($sallary['type'] == 'Teacher')
                                <a href="{{ route('admin.bonus.create', ['user' => $sallary['teacher_id']]) }}" class="text-purple-600 hover:underline text-sm">{{ __('admin/teachers/sallaries/index.table.add_bonus') }}</a>
                            @elseif($sallary['type'] == 'Staff')
                                <a href="{{ route('admin.bonus.create', ['user' => $sallary['staff_id']]) }}" class="text-purple-600 hover:underline text-sm">{{ __('admin/teachers/sallaries/index.table.add_bonus') }}</a>
                            @else
                                <span class="text-gray-500 text-sm">-</span>
                            @endif
                        </td>
                        <!-- <td class="px-4 py-2">

                        </td> -->
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-10 text-center text-slate-500">
                            {{ __('admin/teachers/sallaries/index.empty', ['month' => $months[$selectedMonth] ?? $selectedMonth, 'year' => $selectedYear]) }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if($sallaries->count() > 0)
            <tfoot class="border-t-2 border-teal-100 bg-teal-50">
                <tr class="font-bold text-slate-800">
                    <td class="px-4 py-4" colspan="5">{{ __('admin/teachers/sallaries/index.footer.total') }}</td>
                    <td class="px-4 py-4 text-teal-700">RM {{ number_format($totalAmount, 2, '.', ',') }}</td>
                    <td class="px-4 py-4" colspan="2"></td>
                </tr>
            </tfoot>
            @endif
        </table>
            </div>
    </div>
    </form>
    <form id="sync-attendance-form" method="POST" action="{{ route('admin.teachers.sallaries.syncAttendance') }}" class="hidden">
        @csrf
        <input type="hidden" name="month" value="{{ $selectedMonth }}">
        <input type="hidden" name="year" value="{{ $selectedYear }}">
    </form>

    {{-- Modal Detail (Simple Alert) --}}
    <script>
    function showDetail(name, type, salary, attendance) {
        let message = `{{ __('admin/teachers/sallaries/index.detail.title') }}\n\n` +
            `{{ __('admin/teachers/sallaries/index.detail.name') }}: ${name}\n` +
            `{{ __('admin/teachers/sallaries/index.detail.type') }}: ${type}\n` +
            `{{ __('admin/teachers/sallaries/index.detail.salary') }}: RM ${salary}`;
        if (type === 'Teacher') {
            message += `\n{{ __('admin/teachers/sallaries/index.detail.attendance') }}: ${attendance} {{ __('admin/teachers/sallaries/index.attendance_unit') }}`;
        }
        alert(message);
    }
    
    // Script Pilih Semua
    document.getElementById('selectAll').addEventListener('click', function () {
        const checkboxes = document.querySelectorAll('.salary-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    // Update counter ketika checkbox berubah
    function updateSelectedCounter() {
        const checkedBoxes = document.querySelectorAll('.salary-checkbox:checked');
        const submitButton = document.querySelector('button[type="submit"]');
        const counter = checkedBoxes.length;
        
        if (counter > 0) {
            submitButton.disabled = false;
        } else {
            submitButton.innerHTML = `<i class="fas fa-check mr-2"></i>Tandai Sudah Dibayar`;
            submitButton.disabled = false;
        }
    }

    // Event listener untuk semua checkbox
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('salary-checkbox')) {
            updateSelectedCounter();
        }
    });

    // Event listener untuk select all
    document.getElementById('selectAll').addEventListener('change', function() {
        updateSelectedCounter();
    });
    </script>

    {{-- Info Summary --}}
    @if($sallaries->count() > 0)
    <div class="mt-6 bg-gray-50 rounded-lg p-4">
        <h3 class="text-lg font-medium mb-2">{{ __('admin/teachers/sallaries/index.summary.title') }}</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div>
                <span class="text-gray-600">{{ __('admin/teachers/sallaries/index.summary.total_teachers') }}</span>
                <span class="font-semibold ml-1">{{ $sallaries->where('type', 'Teacher')->count() }}</span>
            </div>
            <div>
                <span class="text-gray-600">{{ __('admin/teachers/sallaries/index.summary.total_staff') }}</span>
                <span class="font-semibold ml-1">{{ $sallaries->where('type', 'Staff')->count() }}</span>
            </div>
            <div>
                <span class="text-gray-600">{{ __('admin/teachers/sallaries/index.summary.teachers_amount') }}</span>
                <span class="font-semibold ml-1">RM {{ number_format($sallaries->where('type', 'Teacher')->sum('salary'), 2, '.', ',') }}</span>
            </div>
            <div>
                <span class="text-gray-600">{{ __('admin/teachers/sallaries/index.summary.staff_amount') }}</span>
                <span class="font-semibold ml-1">RM {{ number_format($sallaries->where('type', 'Staff')->sum('salary'), 2, '.', ',') }}</span>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

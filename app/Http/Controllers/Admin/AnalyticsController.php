<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Company;
use App\Models\Staff;
use App\Models\StudentInvoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function new_students(Request $request)
    {
        $user = auth()->user();

        // 1️⃣ Dapatkan company_id dari Company atau Staff
        $company = Company::where('user_id', $user->id)->first();
        $companyId = $company?->id ?? Staff::where('user_id', $user->id)->value('company_id');

        if (!$companyId) {
            return view('admin.charts.new_students', [
                'labels' => [],
                'data' => [],
                'type' => $request->get('type', 'monthly')
            ]);
        }

        // 2️⃣ Tentukan jenis statistik (default: monthly)
        $type = $request->get('type', 'monthly');

        if ($type === 'weekly') {
            // Statistik siswa baru per minggu (8 minggu terakhir)
            $students = Student::where('company_id', $companyId)
                ->where('created_at', '>=', Carbon::now()->subWeeks(8))
                ->get()
                ->groupBy(function ($student) {
                    return Carbon::parse($student->created_at)->format('W'); // Week number
                });

            $labels = $students->keys()->map(fn($w) => 'Week ' . $w);
            $data = $students->map(fn($s) => $s->count())->values();
        } else {
            // Statistik siswa baru per bulan (12 bulan terakhir)
            $students = Student::where('company_id', $companyId)
                ->where('created_at', '>=', Carbon::now()->subMonths(12))
                ->get()
                ->groupBy(function ($student) {
                    return Carbon::parse($student->created_at)->format('Y-m');
                });

            $labels = $students->keys();
            $data = $students->map(fn($s) => $s->count())->values();
        }

        // 3️⃣ Kirim ke view
        return view('admin.charts.new_students', compact('labels', 'data', 'type'));
    }

    public function invoices_payments()
    {
        $user = Auth::user();

        // 1️⃣ Dapatkan company_id dari Company atau Staff
        $company = Company::where('user_id', $user->id)->first();
        $companyId = $company?->id ?? Staff::where('user_id', $user->id)->value('company_id');

        // Jika tidak ada company_id, tampilkan kosong
        if (!$companyId) {
            return view('admin.charts.invoices_payments', [
                'labels' => [],
                'data' => [],
                'total' => 0
            ]);
        }

        // 2️⃣ Ambil semua student ID di company ini
        $studentIds = Student::where('company_id', $companyId)->pluck('id');

        // 3️⃣ Hitung jumlah invoice berdasarkan status pembayaran
        $statuses = ['paid', 'overdue', 'upcoming'];
        $data = [];

        foreach ($statuses as $status) {
            $count = StudentInvoice::whereIn('student_id', $studentIds)
                ->where('status_bayaran', $status)
                ->count();

            $data[$status] = $count;
        }

        // 4️⃣ Kirim ke view
        return view('admin.charts.invoices_payments', [
            'labels' => ['Paid', 'Overdue', 'Upcoming'],
            'data' => array_values($data),
            'total' => array_sum($data)
        ]);
    }

    public function sales()
    {
        $user = auth()->user();

        // 🔍 Cari company_id dari user login
        $company = Company::where('user_id', $user->id)->first();

        if (!$company) {
            $staff = Staff::where('user_id', $user->id)->first();
            $companyId = $staff ? $staff->company_id : null;
        } else {
            $companyId = $company->id;
        }

        if (!$companyId) {
            return back()->with('error', 'Company not found for this user.');
        }

        // 🎯 Ambil semua invoice dari student yang milik company ini
        $invoices = StudentInvoice::whereHas('student', function ($q) use ($companyId) {
            $q->where('company_id', $companyId);
        })->get();

        // =============================
        // 📊 1️⃣ REVENUE PER BULAN
        // =============================
        $monthlyRevenue = $invoices
            ->where('status_bayaran', 'paid')
            ->groupBy(function ($invoice) {
                return Carbon::parse($invoice->created_at)->format('Y-m'); // contoh: 2025-11
            })
            ->map(function ($group) {
                return $group->sum('jumlah');
            });

        // Format untuk Chart.js
        $months = $monthlyRevenue->keys()->map(function ($key) {
            return Carbon::createFromFormat('Y-m', $key)->format('M Y');
        })->values();

        $revenues = $monthlyRevenue->values();

        // =============================
        // 📊 2️⃣ PAYMENT ANALYTICS
        // =============================
        $paymentData = [
            'paid' => $invoices->where('status_bayaran', 'paid')->sum('jumlah'),
            'overdue' => $invoices->where('status_bayaran', 'overdue')->sum('jumlah'),
            'upcoming' => $invoices->where('status_bayaran', 'upcoming')->sum('jumlah'),
        ];

        return view('admin.charts.sales', compact('months', 'revenues', 'paymentData'));
    }
}

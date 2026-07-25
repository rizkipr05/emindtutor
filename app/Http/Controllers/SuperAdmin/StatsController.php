<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Course;
use App\Models\StudentInvoice;
use App\Models\CompanyInvoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exports\StatisticsExport;
use Maatwebsite\Excel\Facades\Excel;

class StatsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'daily'); // daily, weekly, monthly
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Ambil company yang invoicenya masih berlaku dan status bayaran paid
        $activeCompanies = Company::whereHas('invoices', function($query) {
            $query->where('status_bayaran', 'paid')
                  ->where('tarikh_tamat', '>=', Carbon::now());
        })->with(['user', 'courses', 'students'])->get();

        // 1. Statistik Course per Company (tidak menggunakan filter tanggal)
        $courseStats = $this->getCourseStatistics($activeCompanies);

        // 2. Statistik Student per Company berdasarkan tarikh_terbit StudentInvoice
        $studentStats = $this->getStudentStatistics($activeCompanies, $startDate, $endDate, $period);

        // 3. Statistik Revenue per Company berdasarkan tarikh_terbit StudentInvoice
        $revenueStats = $this->getRevenueStatistics($activeCompanies, $startDate, $endDate, $period);

        // Data untuk chart
        $chartData = [
            'course' => $courseStats,
            'student' => $studentStats,
            'revenue' => $revenueStats
        ];

        return view('super-admin.statistics.index', compact(
            'chartData', 
            'period', 
            'startDate', 
            'endDate',
            'activeCompanies'
        ));
    }

    private function getCourseStatistics($companies)
    {
        $stats = [];
        foreach ($companies as $company) {
            $stats[] = [
                'company_name' => $company->user->name ?? 'Company ' . $company->id,
                'total' => $company->courses->count()
            ];
        }
        return $stats;
    }

    private function getStudentStatistics($companies, $startDate, $endDate, $period)
    {
        $stats = [];
        
        foreach ($companies as $company) {
            $studentIds = $company->students->pluck('id');
            
            if ($period === 'daily') {
                $data = $this->getStudentDataByDay($studentIds, $startDate, $endDate);
            } elseif ($period === 'weekly') {
                $data = $this->getStudentDataByWeek($studentIds, $startDate, $endDate);
            } else {
                $data = $this->getStudentDataByMonth($studentIds, $startDate, $endDate);
            }
            
            $stats[] = [
                'company_name' => $company->user->name ?? 'Company ' . $company->id,
                'data' => $data,
                'total' => collect($data)->sum('count')
            ];
        }
        
        return $stats;
    }

    private function getRevenueStatistics($companies, $startDate, $endDate, $period)
    {
        $stats = [];
        
        foreach ($companies as $company) {
            $studentIds = $company->students->pluck('id');
            
            if ($period === 'daily') {
                $data = $this->getRevenueDataByDay($studentIds, $startDate, $endDate);
            } elseif ($period === 'weekly') {
                $data = $this->getRevenueDataByWeek($studentIds, $startDate, $endDate);
            } else {
                $data = $this->getRevenueDataByMonth($studentIds, $startDate, $endDate);
            }
            
            $stats[] = [
                'company_name' => $company->user->name ?? 'Company ' . $company->id,
                'data' => $data,
                'total' => collect($data)->sum('amount')
            ];
        }
        
        return $stats;
    }

    private function getStudentDataByDay($studentIds, $startDate, $endDate)
    {
        return StudentInvoice::selectRaw('DATE(tarikh_terbit) as date, COUNT(DISTINCT student_id) as count')
            ->whereIn('student_id', $studentIds)
            ->where('status_bayaran', 'paid')
            ->whereBetween('tarikh_terbit', [$startDate, $endDate])
            ->where('tarikh_tamat', '>=', Carbon::now())
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'period' => Carbon::parse($item->date)->format('d M Y'),
                    'count' => $item->count
                ];
            })->toArray();
    }

    private function getStudentDataByWeek($studentIds, $startDate, $endDate)
    {
        return StudentInvoice::selectRaw('YEARWEEK(tarikh_terbit, 1) as week, COUNT(DISTINCT student_id) as count')
            ->whereIn('student_id', $studentIds)
            ->where('status_bayaran', 'paid')
            ->whereBetween('tarikh_terbit', [$startDate, $endDate])
            ->where('tarikh_tamat', '>=', Carbon::now())
            ->groupBy('week')
            ->orderBy('week')
            ->get()
            ->map(function ($item) {
                $year = substr($item->week, 0, 4);
                $week = substr($item->week, 4, 2);
                return [
                    'period' => "Week {$week}, {$year}",
                    'count' => $item->count
                ];
            })->toArray();
    }

    private function getStudentDataByMonth($studentIds, $startDate, $endDate)
    {
        return StudentInvoice::selectRaw('YEAR(tarikh_terbit) as year, MONTH(tarikh_terbit) as month, COUNT(DISTINCT student_id) as count')
            ->whereIn('student_id', $studentIds)
            ->where('status_bayaran', 'paid')
            ->whereBetween('tarikh_terbit', [$startDate, $endDate])
            ->where('tarikh_tamat', '>=', Carbon::now())
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'period' => Carbon::create($item->year, $item->month)->format('M Y'),
                    'count' => $item->count
                ];
            })->toArray();
    }

    private function getRevenueDataByDay($studentIds, $startDate, $endDate)
    {
        return StudentInvoice::selectRaw('DATE(tarikh_terbit) as date, SUM(jumlah) as amount')
            ->whereIn('student_id', $studentIds)
            ->where('status_bayaran', 'paid')
            ->whereBetween('tarikh_terbit', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'period' => Carbon::parse($item->date)->format('d M Y'),
                    'amount' => $item->amount
                ];
            })->toArray();
    }

    private function getRevenueDataByWeek($studentIds, $startDate, $endDate)
    {
        return StudentInvoice::selectRaw('YEARWEEK(tarikh_terbit, 1) as week, SUM(jumlah) as amount')
            ->whereIn('student_id', $studentIds)
            ->where('status_bayaran', 'paid')
            ->whereBetween('tarikh_terbit', [$startDate, $endDate])
            ->groupBy('week')
            ->orderBy('week')
            ->get()
            ->map(function ($item) {
                $year = substr($item->week, 0, 4);
                $week = substr($item->week, 4, 2);
                return [
                    'period' => "Week {$week}, {$year}",
                    'amount' => $item->amount
                ];
            })->toArray();
    }

    private function getRevenueDataByMonth($studentIds, $startDate, $endDate)
    {
        return StudentInvoice::selectRaw('YEAR(tarikh_terbit) as year, MONTH(tarikh_terbit) as month, SUM(jumlah) as amount')
            ->whereIn('student_id', $studentIds)
            ->where('status_bayaran', 'paid')
            ->whereBetween('tarikh_terbit', [$startDate, $endDate])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'period' => Carbon::create($item->year, $item->month)->format('M Y'),
                    'amount' => $item->amount
                ];
            })->toArray();
    }

    // public function exportPdf(Request $request)
    // {
    //     // TODO: Implement PDF export
    //     return response()->json(['message' => 'PDF export will be implemented']);
    // }

    public function exportPdf($period, $startDate, $endDate)
    {
        try {

            // Get data using same logic as index method
            $activeCompanies = Company::whereHas('invoices', function($query) {
                $query->where('status_bayaran', 'paid')
                      ->where('tarikh_tamat', '>=', Carbon::now());
            })->with(['user', 'courses', 'students'])->get();

            $courseStats = $this->getCourseStatistics($activeCompanies);
            $studentStats = $this->getStudentStatistics($activeCompanies, $startDate, $endDate, $period);
            $revenueStats = $this->getRevenueStatistics($activeCompanies, $startDate, $endDate, $period);

            $chartData = [
                'course' => $courseStats,
                'student' => $studentStats,
                'revenue' => $revenueStats
            ];

            // Calculate summary data
            $totalCourses = collect($courseStats)->sum('total');
            $totalStudents = collect($studentStats)->sum('total');
            $totalRevenue = collect($revenueStats)->sum('total');

            $data = [
                'chartData' => $chartData,
                'period' => $period,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'totalCourses' => $totalCourses,
                'totalStudents' => $totalStudents,
                'totalRevenue' => $totalRevenue,
                'generatedAt' => Carbon::now()->format('d M Y H:i:s')
            ];

            $pdf = Pdf::loadView('super-admin.statistics.pdf', $data);
            $pdf->setPaper('A4', 'portrait');
            
            $filename = 'Laporan_Statistik_' . Carbon::now()->format('Y-m-d_H-i-s') . '.pdf';
            
            return $pdf->download($filename);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error generating PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    // public function exportExcel(Request $request)
    // {
    //     // TODO: Implement Excel export
    //     return response()->json(['message' => 'Excel export will be implemented']);
    // }

    public function exportExcel($period, $startDate, $endDate)
    {
        try {
            // Get data using same logic as index method
            $activeCompanies = Company::whereHas('invoices', function($query) {
                $query->where('status_bayaran', 'paid')
                      ->where('tarikh_tamat', '>=', Carbon::now());
            })->with(['user', 'courses', 'students'])->get();

            $courseStats = $this->getCourseStatistics($activeCompanies);
            $studentStats = $this->getStudentStatistics($activeCompanies, $startDate, $endDate, $period);
            $revenueStats = $this->getRevenueStatistics($activeCompanies, $startDate, $endDate, $period);

            $chartData = [
                'course' => $courseStats,
                'student' => $studentStats,
                'revenue' => $revenueStats,
                'period' => $period,
                'startDate' => $startDate,
                'endDate' => $endDate
            ];

            $filename = 'Laporan_Statistik_' . Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';
            
            return Excel::download(new StatisticsExport($chartData), $filename);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error generating Excel: ' . $e->getMessage()
            ], 500);
        }
    }
}
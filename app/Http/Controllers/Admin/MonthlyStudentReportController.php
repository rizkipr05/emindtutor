<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonthlyStudentReport;
use App\Models\Staff;
use App\Services\MonthlyLearningStatementPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MonthlyStudentReportController extends Controller
{
    public function index(Request $request)
    {
        $company = Auth::user()->company
            ?? Staff::where('user_id', Auth::id())->value('company_id');
        $companyId = is_object($company) ? $company->id : $company;
        abort_unless($companyId, 403);

        $reports = MonthlyStudentReport::with('student.user')
            ->where('company_id', $companyId)
            ->when($request->filled('month'), fn ($query) => $query->where('report_month', $request->integer('month')))
            ->when($request->filled('year'), fn ($query) => $query->where('report_year', $request->integer('year')))
            ->latest('report_year')->latest('report_month')->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.monthly_reports.index', compact('reports'));
    }

    public function download(MonthlyStudentReport $report)
    {
        $companyId = Auth::user()->company?->id
            ?? Staff::where('user_id', Auth::id())->value('company_id');
        abort_unless($report->company_id === $companyId, 403);
        abort_unless($report->pdf_path && Storage::disk('public')->exists($report->pdf_path), 404);

        app(MonthlyLearningStatementPdfService::class)->generate($report);

        return Storage::disk('public')->download($report->pdf_path, "monthly-learning-statement-{$report->report_year}-{$report->report_month}.pdf");
    }
}

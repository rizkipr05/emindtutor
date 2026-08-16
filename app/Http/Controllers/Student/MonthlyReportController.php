<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\MonthlyStudentReport;
use App\Services\MonthlyLearningStatementPdfService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MonthlyReportController extends Controller
{
    public function index()
    {
        $student = Auth::user()->students;
        $reports = MonthlyStudentReport::where('student_id', $student->id)
            ->latest('report_year')->latest('report_month')->get();

        return view('student.monthly_reports.index', compact('reports'));
    }

    public function download(MonthlyStudentReport $report)
    {
        abort_unless($report->student_id === Auth::user()->students?->id, 403);
        abort_unless($report->pdf_path && Storage::disk('public')->exists($report->pdf_path), 404);

        app(MonthlyLearningStatementPdfService::class)->generate($report);

        return Storage::disk('public')->download($report->pdf_path, $this->filename($report));
    }

    public function publicDownload(string $token)
    {
        $report = MonthlyStudentReport::where('public_token', $token)->firstOrFail();
        abort_unless($report->pdf_path && Storage::disk('public')->exists($report->pdf_path), 404);

        app(MonthlyLearningStatementPdfService::class)->generate($report);

        return Storage::disk('public')->download($report->pdf_path, $this->filename($report));
    }

    private function filename(MonthlyStudentReport $report): string
    {
        return "monthly-learning-statement-{$report->report_year}-{$report->report_month}.pdf";
    }
}

<?php

namespace App\Services;

use App\Models\MonthlyStudentReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MonthlyLearningStatementPdfService
{
    public function generate(MonthlyStudentReport $report): MonthlyStudentReport
    {
        $report->loadMissing(['student.user', 'company.user']);
        $report->public_token ??= (string) Str::uuid();
        $filename = "monthly-learning-statements/{$report->company_id}/{$report->student_id}/{$report->report_year}-{$report->report_month}.pdf";

        $pdf = Pdf::loadView('pdf.monthly-learning-statement', ['report' => $report])
            ->setPaper('a4');

        Storage::disk('public')->put($filename, $pdf->output());
        $report->pdf_path = $filename;
        $report->save();

        return $report;
    }
}

<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class StatisticsExport implements WithMultipleSheets
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        return [
            'Ringkasan' => new SummarySheet($this->data),
            'Statistik Kelas' => new CourseSheet($this->data),
            'Statistik Pelajar' => new StudentSheet($this->data),
            'Statistik Pendapatan' => new RevenueSheet($this->data),
        ];
    }
}

class SummarySheet implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Ringkasan';
    }

    public function headings(): array
    {
        return [
            'Jenis Statistik',
            'Jumlah',
            'Periode',
            'Tanggal Mulai',
            'Tanggal Akhir',
            'Generated At'
        ];
    }

    public function collection()
    {
        $totalCourses = collect($this->data['course'])->sum('total');
        $totalStudents = collect($this->data['student'])->sum('total');
        $totalRevenue = collect($this->data['revenue'])->sum('total');

        return collect([
            [
                'Total Kelas',
                $totalCourses,
                ucfirst($this->data['period']),
                $this->data['startDate'],
                $this->data['endDate'],
                Carbon::now()->format('d M Y H:i:s')
            ],
            [
                'Total Pelajar Baru',
                $totalStudents,
                ucfirst($this->data['period']),
                $this->data['startDate'],
                $this->data['endDate'],
                Carbon::now()->format('d M Y H:i:s')
            ],
            [
                'Total Pendapatan (RM)',
                number_format($totalRevenue, 2),
                ucfirst($this->data['period']),
                $this->data['startDate'],
                $this->data['endDate'],
                Carbon::now()->format('d M Y H:i:s')
            ]
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:F1')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '3B82F6']
            ],
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ]);

        foreach (range('A', 'F') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return $sheet;
    }
}

class CourseSheet implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Statistik Kelas';
    }

    public function headings(): array
    {
        return [
            'Nama Company',
            'Jumlah Kelas'
        ];
    }

    public function collection()
    {
        return collect($this->data['course'])->map(function ($item) {
            return [
                $item['company_name'],
                $item['total']
            ];
        });
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:B1')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '3B82F6']
            ],
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ]);

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);

        return $sheet;
    }
}

class StudentSheet implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Statistik Pelajar';
    }

    public function headings(): array
    {
        $headings = ['Company'];
        
        // Get all periods from the first company that has data
        $firstCompanyWithData = collect($this->data['student'])->first(function($company) {
            return !empty($company['data']);
        });

        if ($firstCompanyWithData && !empty($firstCompanyWithData['data'])) {
            foreach ($firstCompanyWithData['data'] as $period) {
                $headings[] = $period['period'];
            }
        }
        
        $headings[] = 'Total';
        
        return $headings;
    }

    public function collection()
    {
        return collect($this->data['student'])->map(function ($company) {
            $row = [$company['company_name']];
            
            if (!empty($company['data'])) {
                foreach ($company['data'] as $period) {
                    $row[] = $period['count'];
                }
            }
            
            $row[] = $company['total'];
            
            return $row;
        });
    }

    public function styles(Worksheet $sheet)
    {
        $lastColumn = $sheet->getHighestColumn();
        $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '10B981']
            ],
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ]);

        foreach (range('A', $lastColumn) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return $sheet;
    }
}

class RevenueSheet implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Statistik Pendapatan';
    }

    public function headings(): array
    {
        $headings = ['Company'];
        
        // Get all periods from the first company that has data
        $firstCompanyWithData = collect($this->data['revenue'])->first(function($company) {
            return !empty($company['data']);
        });

        if ($firstCompanyWithData && !empty($firstCompanyWithData['data'])) {
            foreach ($firstCompanyWithData['data'] as $period) {
                $headings[] = $period['period'] . ' (RM)';
            }
        }
        
        $headings[] = 'Total (RM)';
        
        return $headings;
    }

    public function collection()
    {
        return collect($this->data['revenue'])->map(function ($company) {
            $row = [$company['company_name']];
            
            if (!empty($company['data'])) {
                foreach ($company['data'] as $period) {
                    $row[] = number_format($period['amount'], 2);
                }
            }
            
            $row[] = number_format($company['total'], 2);
            
            return $row;
        });
    }

    public function styles(Worksheet $sheet)
    {
        $lastColumn = $sheet->getHighestColumn();
        $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F59E0B']
            ],
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ]);

        foreach (range('A', $lastColumn) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return $sheet;
    }
}
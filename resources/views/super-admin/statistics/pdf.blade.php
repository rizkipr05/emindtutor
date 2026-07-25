<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Statistik & Sistem</title>
    <style>
        @page {
            margin: 2cm;
            size: A4 portrait;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #3B82F6;
        }
        
        .header h1 {
            color: #1E40AF;
            font-size: 24px;
            margin: 0;
            margin-bottom: 5px;
        }
        
        .header p {
            color: #6B7280;
            margin: 0;
        }
        
        .report-info {
            background-color: #F9FAFB;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            border-left: 4px solid #3B82F6;
        }
        
        .report-info table {
            width: 100%;
        }
        
        .report-info td {
            padding: 3px 0;
        }
        
        .report-info .label {
            font-weight: bold;
            width: 120px;
        }
        
        .summary-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            gap: 15px;
        }
        
        .summary-card {
            flex: 1;
            background-color: #F8FAFC;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #E2E8F0;
        }
        
        .summary-card h3 {
            font-size: 14px;
            color: #64748B;
            margin: 0 0 8px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .summary-card .value {
            font-size: 28px;
            font-weight: bold;
            margin: 0;
        }
        
        .summary-card.courses .value { color: #3B82F6; }
        .summary-card.students .value { color: #10B981; }
        .summary-card.revenue .value { color: #F59E0B; }
        
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #1E40AF;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #E2E8F0;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .table th,
        .table td {
            border: 1px solid #E2E8F0;
            padding: 10px;
            text-align: left;
        }
        
        .table th {
            background-color: #F1F5F9;
            font-weight: bold;
            color: #475569;
        }
        
        .table tbody tr:nth-child(even) {
            background-color: #F9FAFB;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #6B7280;
            font-size: 10px;
            border-top: 1px solid #E2E8F0;
            padding-top: 10px;
        }
        
        .no-data {
            text-align: center;
            color: #6B7280;
            font-style: italic;
            padding: 20px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Laporan Statistik & Sistem</h1>
        <p>Laporan komprehensif penggunaan dan statistik sistem</p>
    </div>

    <!-- Report Information -->
    <div class="report-info">
        <table>
            <tr>
                <td class="label">Periode:</td>
                <td>{{ ucfirst($period) }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Mulai:</td>
                <td>{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Akhir:</td>
                <td>{{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</td>
            </tr>
            <tr>
                <td class="label">Dibuat Pada:</td>
                <td>{{ $generatedAt }}</td>
            </tr>
        </table>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card courses">
            <h3>Total Kelas</h3>
            <p class="value">{{ number_format($totalCourses) }}</p>
        </div>
        <div class="summary-card students">
            <h3>Total Pelajar Baru</h3>
            <p class="value">{{ number_format($totalStudents) }}</p>
        </div>
        <div class="summary-card revenue">
            <h3>Total Pendapatan</h3>
            <p class="value">RM {{ number_format($totalRevenue, 2) }}</p>
        </div>
    </div>

    <!-- Course Statistics -->
    <div class="section">
        <h2 class="section-title">Statistik Kelas per Company</h2>
        @if(!empty($chartData['course']))
            <table class="table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Company</th>
                        <th class="text-right">Jumlah Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($chartData['course'] as $index => $course)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $course['company_name'] }}</td>
                        <td class="text-right">{{ number_format($course['total']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="font-weight: bold; background-color: #F1F5F9;">
                        <td colspan="2">JUMLAH KESELURUHAN</td>
                        <td class="text-right">{{ number_format($totalCourses) }}</td>
                    </tr>
                </tfoot>
            </table>
        @else
            <div class="no-data">Tiada data kelas tersedia</div>
        @endif
    </div>

    <!-- Student Statistics -->
    <div class="section page-break">
        <h2 class="section-title">Statistik Pelajar Baru Didaftarkan</h2>
        @if(!empty($chartData['student']))
            @foreach($chartData['student'] as $company)
                <div style="margin-bottom: 20px;">
                    <h3 style="color: #10B981; font-size: 16px; margin-bottom: 10px;">
                        {{ $company['company_name'] }} (Total: {{ number_format($company['total']) }} pelajar)
                    </h3>
                    @if(!empty($company['data']))
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Periode</th>
                                    <th class="text-right">Jumlah Pelajar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($company['data'] as $data)
                                <tr>
                                    <td>{{ $data['period'] }}</td>
                                    <td class="text-right">{{ number_format($data['count']) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="no-data">Tiada data pelajar untuk periode ini</div>
                    @endif
                </div>
            @endforeach
            
            <!-- Summary Table -->
            <div style="margin-top: 30px;">
                <h3 style="color: #10B981; font-size: 16px; margin-bottom: 10px;">Ringkasan Pelajar per Company</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Company</th>
                            <th class="text-right">Total Pelajar Baru</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($chartData['student'] as $index => $company)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $company['company_name'] }}</td>
                            <td class="text-right">{{ number_format($company['total']) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="font-weight: bold; background-color: #F1F5F9;">
                            <td colspan="2">JUMLAH KESELURUHAN</td>
                            <td class="text-right">{{ number_format($totalStudents) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <div class="no-data">Tiada data pelajar tersedia</div>
        @endif
    </div>

    <!-- Revenue Statistics -->
    <div class="section page-break">
        <h2 class="section-title">Statistik Pendapatan</h2>
        @if(!empty($chartData['revenue']))
            @foreach($chartData['revenue'] as $company)
                <div style="margin-bottom: 20px;">
                    <h3 style="color: #F59E0B; font-size: 16px; margin-bottom: 10px;">
                        {{ $company['company_name'] }} (Total: RM {{ number_format($company['total'], 2) }})
                    </h3>
                    @if(!empty($company['data']))
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Periode</th>
                                    <th class="text-right">Jumlah Pendapatan (RM)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($company['data'] as $data)
                                <tr>
                                    <td>{{ $data['period'] }}</td>
                                    <td class="text-right">{{ number_format($data['amount'], 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="no-data">Tiada data pendapatan untuk periode ini</div>
                    @endif
                </div>
            @endforeach
            
            <!-- Summary Table -->
            <div style="margin-top: 30px;">
                <h3 style="color: #F59E0B; font-size: 16px; margin-bottom: 10px;">Ringkasan Pendapatan per Company</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Company</th>
                            <th class="text-right">Total Pendapatan (RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($chartData['revenue'] as $index => $company)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $company['company_name'] }}</td>
                            <td class="text-right">{{ number_format($company['total'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="font-weight: bold; background-color: #F1F5F9;">
                            <td colspan="2">JUMLAH KESELURUHAN</td>
                            <td class="text-right">RM {{ number_format($totalRevenue, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <div class="no-data">Tiada data pendapatan tersedia</div>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini dijana secara automatik oleh sistem pada {{ $generatedAt }}</p>
        <p>© {{ date('Y') }} Sistem Statistik & Laporan. Semua hak terpelihara.</p>
    </div>
</body>
</html>
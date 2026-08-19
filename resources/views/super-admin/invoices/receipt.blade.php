<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $invoice->nomor_invoice }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
            background: white;
        }

        .receipt-container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }

        .company-logo {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .receipt-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .receipt-info {
            margin-bottom: 40px;
        }

        .info-row {
            display: flex;
            gap: 40px;
            margin-bottom: 30px;
        }

        .info-section {
            flex: 1;
        }

        .info-section h3 {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }

        .info-item {
            margin-bottom: 10px;
            display: flex;
        }

        .info-label {
            font-weight: 600;
            color: #555;
            width: 120px;
            flex-shrink: 0;
        }

        .info-value {
            color: #333;
            flex: 1;
        }

        .amount-section {
            text-align: center;
            margin: 40px 0;
            padding: 30px;
            background: #f8f8f8;
            border: 2px solid #333;
        }

        .amount-label {
            font-size: 14px;
            margin-bottom: 10px;
            color: #666;
            font-weight: 600;
        }

        .amount-value {
            font-size: 36px;
            font-weight: bold;
            color: #333;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            background: #28a745;
            color: white;
            font-size: 12px;
            font-weight: bold;
            border-radius: 4px;
        }

        .verification-section {
            text-align: center;
            margin: 40px 0;
            padding: 20px;
            background: #f9f9f9;
            border: 1px solid #ddd;
        }

        .verification-code {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            font-size: 16px;
            color: #333;
            margin: 10px 0;
            letter-spacing: 2px;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }

        .footer p {
            margin: 8px 0;
        }

        /* PDF styles */
        @page {
            margin: 20mm;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="header">
            <div class="company-logo">{{ config('app.name', 'PT. Your Company') }}</div>
            <div class="receipt-title">PAYMENT RECEIPT</div>
        </div>

        <div class="receipt-info">
            <div class="info-row">
                <div class="info-section">
                    <h3>Invoice Details</h3>
                    <div class="info-item">
                        <span class="info-label">Invoice No:</span>
                        <span class="info-value">{{ $invoice->nomor_invoice }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Issue Date:</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($invoice->tarikh_terbit)->format('d M Y') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Due Date:</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($invoice->tarikh_tamat)->format('d M Y') }}</span>
                    </div>
                    @if($invoice->package)
                    <div class="info-item">
                        <span class="info-label">Package:</span>
                        <span class="info-value">{{ $invoice->package->nama_pakej }}</span>
                    </div>
                    @endif
                    <div class="info-item">
                        <span class="info-label">Status:</span>
                        <span class="info-value">
                            <span class="status-badge" style="background: {{ strtolower($invoice->status_bayaran) === 'paid' ? '#28a745' : (strtolower($invoice->status_bayaran) === 'expired' ? '#dc3545' : '#ffc107') }}; color: {{ strtolower($invoice->status_bayaran) === 'pending' ? '#000' : '#fff' }};">{{ strtoupper($invoice->status_bayaran) }}</span>
                        </span>
                    </div>
                </div>

                <div class="info-section">
                    <h3>Company Information</h3>
                    <div class="info-item">
                        <span class="info-label">Company:</span>
                        <span class="info-value">{{ $invoice->company->user->name }}</span>
                    </div>
                    @if($invoice->company->user->email)
                    <div class="info-item">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ $invoice->company->user->email }}</span>
                    </div>
                    @endif
                    <div class="info-item">
                        <span class="info-label">Payment Date:</span>
                        <span class="info-value">{{ \Carbon\Carbon::now()->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="amount-section">
            <div class="amount-label">Total Amount Paid</div>
            <div class="amount-value">RM {{ number_format($invoice->jumlah, 2) }}</div>
        </div>
    </div>
</body>
</html>
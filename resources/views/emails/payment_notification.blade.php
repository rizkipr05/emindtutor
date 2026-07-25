<div style="font-family: sans-serif; max-width: 600px; margin: auto; border: 1px solid #e0e0e0; padding: 30px; border-radius: 15px;">
    <div style="text-align: center; margin-bottom: 20px;">
        <h2 style="color: #1e3a8a; margin: 0;">{{ $senderName }}</h2>
    </div>

    <hr style="border: 0; border-top: 1px solid #eee;">

    <h3 style="color: #333;">Payment Successful</h3>
    <p>Hi {{ $payment->user->name }},</p>
    <p>Thank you for your payment. Your transaction has been successfully processed. Below are the details of your payment:</p>

    <table style="width: 100%; margin-top: 20px; border-collapse: collapse;">
        <tr>
            <td style="padding: 8px 0; color: #777;">Invoice ID</td>
            <td style="padding: 8px 0; text-align: right; font-weight: bold;">#{{ $payment->invoice_number }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 0; color: #777;">Description</td>
            <td style="padding: 8px 0; text-align: right;">{{ $payment->description }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 0; color: #777;">Date</td>
            <td style="padding: 8px 0; text-align: right;">{{ $payment->created_at->format('d M Y, H:i') }}</td>
        </tr>
        <tr style="border-top: 2px solid #eee;">
            <td style="padding: 15px 0; font-size: 1.2em; font-weight: bold;">Total Amount</td>
            <td style="padding: 15px 0; text-align: right; font-size: 1.2em; font-weight: bold; color: #1e3a8a;">
                RM {{ number_format($payment->amount, 2) }}
            </td>
        </tr>
    </table>

    <div style="margin-top: 30px; padding: 15px; background: #f9f9f9; border-radius: 10px; text-align: center;">
        <p style="margin: 0; font-size: 0.9em; color: #555;">Payment Method: <strong>{{ strtoupper($payment->method) }}</strong></p>
    </div>

    <p style="margin-top: 30px; font-size: 0.8em; color: #999; text-align: center;">
        This is an automated email. Please do not reply to this address. <br>
        &copy; {{ date('Y') }} eMindTutor. All rights reserved.
    </p>
</div>
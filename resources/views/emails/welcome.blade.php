<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #1f2937; }
        .container { max-width: 640px; margin: 0 auto; padding: 24px; border: 1px solid #e5e7eb; border-radius: 8px; }
        .brand { margin-bottom: 20px; color: #0891b2; font-size: 22px; font-weight: 700; }
        .content { white-space: normal; font-size: 15px; }
        .footer { margin-top: 28px; color: #6b7280; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="brand">{{ config('app.name', 'eMindTutor') }}</div>
        <div class="content">
            {!! app(\App\Services\EmailTemplateRenderer::class)->render($templateBody, $templateVariables) !!}
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name', 'eMindTutor') }}.
        </div>
    </div>
</body>
</html>

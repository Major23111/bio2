<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Proforma Invoice {{ $proforma->pi_number }}</title>
</head>
<body style="margin:0;padding:0;background:#f8fafc;font-family:Arial,sans-serif;color:#0f172a;">
    <div style="max-width:640px;margin:0 auto;padding:24px;">
        <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;padding:24px;">
            <p style="margin:0 0 16px;font-size:14px;">Dear {{ $customerName }},</p>

            <p style="margin:0 0 12px;font-size:14px;line-height:1.6;">
                Please find attached Proforma Invoice <strong>{{ $proforma->pi_number }}</strong>.
            </p>

            <p style="margin:0 0 12px;font-size:14px;line-height:1.6;">
                PI Date: <strong>{{ $proforma->pi_date ?: now()->format('Y-m-d') }}</strong>
            </p>

            <p style="margin:0 0 12px;font-size:14px;line-height:1.6;">
                Total Amount: <strong>{{ $proforma->currency ?: 'INR' }} {{ number_format((float) ($proforma->total_amount ?? 0), 2) }}</strong>
            </p>

            <p style="margin:0 0 12px;font-size:14px;line-height:1.6;">
                Please review the attached PI and get back to us for the next step.
            </p>

            <p style="margin:20px 0 0;font-size:14px;line-height:1.6;">
                Regards,<br>
                Biogenix Team
            </p>
        </div>
    </div>
</body>
</html>

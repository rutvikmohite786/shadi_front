<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f6f6f6; margin: 0; padding: 20px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
        <tr>
            <td style="padding: 24px; background: #4f46e5; color: #ffffff; text-align: center;">
                <h1 style="margin: 0; font-size: 22px;">Verify your email</h1>
            </td>
        </tr>
        <tr>
            <td style="padding: 24px; color: #111827; font-size: 15px; line-height: 1.6;">
                <p style="margin: 0 0 12px;">Hi {{ $name }},</p>
                <p style="margin: 0 0 16px;">Use the one-time verification code below to complete your registration.</p>
                <div style="text-align: center; margin: 20px 0;">
                    <div style="display: inline-block; padding: 14px 24px; background: #111827; color: #ffffff; border-radius: 10px; font-size: 24px; letter-spacing: 6px; font-weight: 700;">
                        {{ $otp }}
                    </div>
                </div>
                <p style="margin: 0 0 12px;">This code expires in {{ $expiresInMinutes }} minutes. If you didn’t request this, you can safely ignore this email.</p>
                <p style="margin: 0;">Thanks,<br>{{ config('app.name') }} Team</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 16px; text-align: center; font-size: 12px; color: #6b7280; background: #f9fafb;">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </td>
        </tr>
    </table>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9fafb;">
    <!-- Email Header with Logo as Profile Photo -->
    <div style="text-align: center; margin-bottom: 30px; padding: 30px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px 10px 0 0;">
        <div style="display: inline-block; margin-bottom: 15px;">
            <img src="https://gkv.ac.in/logo.png" alt="GKV Logo" style="height: 100px; width: 100px; border-radius: 50%; border: 4px solid #ffffff; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); background-color: #ffffff; padding: 5px; object-fit: contain;">
        </div>
        <h1 style="color: #ffffff; margin: 10px 0 5px 0; font-size: 28px; font-weight: bold;">Alumni Portal</h1>
        <p style="color: #e0e7ff; font-size: 14px; margin: 0;">Gurukula Kangri (Deemed to be University)</p>
    </div>

    <div style="background-color: #ffffff; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #e5e7eb; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2 style="color: #111827; margin: 0 0 10px 0; font-size: 24px; font-weight: bold;">Email Verification</h2>
            <p style="color: #6b7280; font-size: 14px; margin: 0;">Thank you for registering with the Alumni Portal</p>
        </div>
        <p style="color: #374151; margin-bottom: 20px; text-align: center;">To complete your registration, please verify your email address using the verification code below:</p>

        <div style="background-color: #ffffff; border: 2px dashed #4F46E5; padding: 20px; text-align: center; margin: 30px 0; border-radius: 8px;">
            <p style="font-size: 12px; color: #6b7280; margin: 0 0 10px 0; text-transform: uppercase; letter-spacing: 1px;">Your Verification Code</p>
            <p style="font-size: 36px; font-weight: bold; color: #4F46E5; margin: 0; letter-spacing: 8px; font-family: 'Courier New', monospace;">{{ $otp }}</p>
        </div>

        <p style="color: #374151; margin-bottom: 10px;"><strong>Important:</strong></p>
        <ul style="color: #374151; padding-left: 20px; margin-bottom: 20px;">
            <li>This code will expire in 10 minutes</li>
            <li>Do not share this code with anyone</li>
            <li>If you didn't request this code, please ignore this email</li>
        </ul>

        <p style="color: #6b7280; font-size: 14px; margin-top: 30px; margin-bottom: 0;">If you have any questions, please contact the alumni support team.</p>
    </div>

    <div style="text-align: center; margin-top: 30px; padding: 20px; background-color: #f9fafb; border-radius: 8px;">
        <div style="margin-bottom: 15px;">
            <img src="https://gkv.ac.in/logo.png" alt="GKV Logo" style="height: 40px; width: auto; opacity: 0.7;">
        </div>
        <p style="color: #9ca3af; font-size: 12px; margin: 5px 0;">© {{ date('Y') }} Alumni Portal</p>
        <p style="color: #9ca3af; font-size: 11px; margin: 5px 0;">Gurukula Kangri (Deemed to be University). All rights reserved.</p>
    </div>
</body>
</html>


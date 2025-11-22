<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $emailSubject ?? 'Alumni Portal' }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">Alumni Portal</h1>
    </div>
    
    <div style="background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px;">
        <p>Hello {{ $user->name }},</p>
        
        <div style="background: #fff; padding: 20px; border-radius: 5px; margin: 20px 0;">
            {!! nl2br(e($emailBody ?? '')) !!}
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="https://alumniportal.gkv.ac.in/login" style="display: inline-block; padding: 12px 30px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;">Login to Alumni Portal</a>
        </div>
        
        <p style="text-align: center; margin-top: 20px; color: #666; font-size: 14px;">
            Or copy and paste this link in your browser:<br>
            <a href="https://alumniportal.gkv.ac.in/login" style="color: #667eea; word-break: break-all;">https://alumniportal.gkv.ac.in/login</a>
        </p>
        
        <p>Best regards,<br>The Alumni Portal Team</p>
    </div>
</body>
</html>


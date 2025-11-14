<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Alumni Portal</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">Welcome to Alumni Portal!</h1>
    </div>
    
    <div style="background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px;">
        <p>Hello {{ $user->name }},</p>
        
        <p>Welcome to our Alumni Directory & Management System! We're excited to have you join our community.</p>
        
        <p>Your account has been successfully created. You can now:</p>
        
        <ul style="list-style: none; padding: 0;">
            <li style="padding: 10px; margin: 5px 0; background: #fff; border-left: 4px solid #667eea;">✓ Browse the alumni directory</li>
            <li style="padding: 10px; margin: 5px 0; background: #fff; border-left: 4px solid #667eea;">✓ Connect with batchmates</li>
            <li style="padding: 10px; margin: 5px 0; background: #fff; border-left: 4px solid #667eea;">✓ Register for events</li>
            <li style="padding: 10px; margin: 5px 0; background: #fff; border-left: 4px solid #667eea;">✓ Send messages to other alumni</li>
            <li style="padding: 10px; margin: 5px 0; background: #fff; border-left: 4px solid #667eea;">✓ Update your profile</li>
        </ul>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/dashboard') }}" style="display: inline-block; padding: 12px 30px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;">Go to Dashboard</a>
        </div>
        
        <p>If you have any questions, feel free to reach out to us.</p>
        
        <p>Best regards,<br>The Alumni Portal Team</p>
    </div>
</body>
</html>







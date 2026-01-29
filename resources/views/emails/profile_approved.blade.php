<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #4f46e5; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background-color: #ffffff; padding: 20px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 8px 8px; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #6b7280; }
        .button { display: inline-block; background-color: #4f46e5; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Profile Approved!</h1>
        </div>
        <div class="content">
            <p>Dear {{ $user->name }},</p>
            
            <p>Great news! Your profile on the <strong>Alumni Portal</strong> has been verified and approved by the administrator.</p>
            
            <p>You now have full access to all features, including:</p>
            <ul>
                <li>Validating your professional details</li>
                <li>Connecting with batchmates</li>
                <li>Accessing exclusive events</li>
            </ul>

            <p>Click the button below to log in and update your employment details if you haven't already:</p>
            
            <center>
                <a href="{{ route('login') }}" class="button">Go to Dashboard</a>
            </center>

            <p>Welcome to the community!</p>
            
            <p>Best regards,<br>Alumni Portal Team</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Alumni Portal. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to Alumni Meet!</h1>
    </div>
    <div class="content">
        <p>Dear {{ $user->name }},</p>
        
        <p>Welcome to the <strong>Alumni Meet</strong> platform! We're thrilled to have you join our vibrant community of alumni.</p>
        
        <p>Your account has been successfully created. You can now:</p>
        <ul>
            <li>Connect with fellow alumni</li>
            <li>Browse and search the alumni directory</li>
            <li>Stay updated with campaigns and events</li>
            <li>Update your profile and share your journey</li>
        </ul>
        
        <p>To get started, please complete your profile by adding your graduation details, current position, and other information.</p>
        
        <div style="text-align: center;">
            <a href="{{ route('alumni.edit', $user) }}" class="button">Complete Your Profile</a>
        </div>
        
        <p>If you have any questions or need assistance, feel free to reach out to us.</p>
        
        <p>Best regards,<br>
        Alumni Meet Team</p>
    </div>
    <div class="footer">
        <p>© {{ date('Y') }} Alumni Meet. All rights reserved.</p>
    </div>
</body>
</html>



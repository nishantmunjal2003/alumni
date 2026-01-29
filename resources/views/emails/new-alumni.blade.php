<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .content { margin-bottom: 20px; }
        .details { background-color: #fff; border: 1px solid #ddd; padding: 15px; border-radius: 5px; }
        .detail-row { margin-bottom: 10px; }
        .label { font-weight: bold; color: #555; }
        .footer { font-size: 12px; color: #777; margin-top: 30px; border-top: 1px solid #eee; padding-top: 10px; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #4f46e5; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>New Alumni Registration</h2>
        </div>
        
        <div class="content">
            <p>Hello Admin,</p>
            <p>A new alumni has completed their profile registration and is pending approval.</p>
        </div>

        <div class="details">
            <div class="detail-row">
                <span class="label">Name:</span> {{ $user->name }}
            </div>
            <div class="detail-row">
                <span class="label">Email:</span> {{ $user->email }}
            </div>
            <div class="detail-row">
                <span class="label">Course:</span> {{ $user->course }}
            </div>
            <div class="detail-row">
                <span class="label">Passing Year:</span> {{ $user->passing_year }}
            </div>
            <div class="detail-row">
                <span class="label">Phone:</span> {{ $user->phone }}
            </div>
        </div>

        <div class="content" style="margin-top: 20px;">
            <a href="{{ route('admin.profiles.view', $user->id) }}" class="btn">View Profile</a>
        </div>

        <div class="footer">
            <p>This is an automated notification from the Alumni Portal.</p>
        </div>
    </div>
</body>
</html>

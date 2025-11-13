<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Invitation</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">You're Invited!</h1>
    </div>
    
    <div style="background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px;">
        <p>Hello {{ $user->name }},</p>
        
        <p>You're invited to attend:</p>
        
        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #667eea;">
            <h2 style="margin-top: 0; color: #667eea;">{{ $event->title }}</h2>
            <p><strong>Start Date:</strong> {{ ($event->event_start_date ?? $event->event_date ?? null)?->format('F d, Y h:i A') }}</p>
            @if($event->event_end_date)
                <p><strong>End Date:</strong> {{ $event->event_end_date->format('F d, Y h:i A') }}</p>
            @endif
            <p><strong>Venue:</strong> {{ $event->venue }}</p>
            @if($event->google_maps_link)
                <p><strong>Location:</strong> <a href="{{ $event->google_maps_link }}" style="color: #667eea;">View on Google Maps</a></p>
            @elseif(isset($event->location))
                <p><strong>Location:</strong> {{ $event->location }}</p>
            @endif
            <p>{{ $event->description }}</p>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/events/' . $event->id) }}" style="display: inline-block; padding: 12px 30px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;">View Event Details</a>
        </div>
        
        <p>We hope to see you there!</p>
        
        <p>Best regards,<br>The Alumni Portal Team</p>
    </div>
</body>
</html>





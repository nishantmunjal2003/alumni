<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Invitation</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f7fafc;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 40px 20px; text-align: center;">
                <table role="presentation" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); overflow: hidden;">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: bold;">Event Invitation</h1>
                            <p style="color: #ffffff; margin: 10px 0 0; font-size: 16px; opacity: 0.9;">You're Invited!</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="color: #1a202c; margin: 0 0 20px; font-size: 24px; font-weight: 600;">Hello {{ $user->name }},</h2>
                            
                            <p style="color: #4a5568; font-size: 16px; line-height: 1.6; margin: 0 0 20px;">
                                You have been invited to attend an upcoming event:
                            </p>
                            
                            <!-- Event Details Card -->
                            <div style="background-color: #f7fafc; border-left: 4px solid #667eea; padding: 20px; margin: 25px 0; border-radius: 5px;">
                                <h3 style="color: #667eea; margin: 0 0 15px; font-size: 20px; font-weight: 600;">{{ $event->title }}</h3>
                                
                                <p style="color: #4a5568; font-size: 15px; line-height: 1.6; margin: 10px 0;">
                                    {{ $event->description }}
                                </p>
                                
                                <table role="presentation" style="width: 100%; margin-top: 20px;">
                                    @if($event->event_date)
                                    <tr>
                                        <td style="padding: 8px 0; color: #718096; font-size: 14px; width: 120px;">📅 Date & Time:</td>
                                        <td style="padding: 8px 0; color: #2d3748; font-size: 14px; font-weight: 500;">
                                            {{ $event->event_date->format('F d, Y \a\t g:i A') }}
                                        </td>
                                    </tr>
                                    @endif
                                    
                                    @if($event->venue)
                                    <tr>
                                        <td style="padding: 8px 0; color: #718096; font-size: 14px;">📍 Venue:</td>
                                        <td style="padding: 8px 0; color: #2d3748; font-size: 14px; font-weight: 500;">{{ $event->venue }}</td>
                                    </tr>
                                    @endif
                                    
                                    @if($event->location)
                                    <tr>
                                        <td style="padding: 8px 0; color: #718096; font-size: 14px;">🌍 Location:</td>
                                        <td style="padding: 8px 0; color: #2d3748; font-size: 14px; font-weight: 500;">{{ $event->location }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                            
                            <p style="color: #4a5568; font-size: 16px; line-height: 1.6; margin: 25px 0;">
                                We hope to see you there! This is a great opportunity to connect with fellow alumni and network with your batch.
                            </p>
                            
                            <!-- CTA Button -->
                            <table role="presentation" style="width: 100%; margin: 30px 0;">
                                <tr>
                                    <td style="text-align: center;">
                                        <a href="{{ url('/events/' . $event->id) }}" style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 6px; font-weight: 600; font-size: 16px;">
                                            View Event Details
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #edf2f7; padding: 30px; text-align: center;">
                            <p style="color: #718096; font-size: 14px; margin: 0 0 10px;">
                                This invitation was sent by <strong>Gurukula Kangri Deemed to be University</strong>
                            </p>
                            <p style="color: #a0aec0; font-size: 12px; margin: 0;">
                                Alumni Directory System
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>



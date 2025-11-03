# Email Templates Guide

This document explains all the email templates used in the Alumni Directory application and when they are sent.

## Available Email Templates

### 1. Welcome Email (`resources/views/emails/welcome.blade.php`)

**When it's sent:**
- ✅ When a new user registers via standard registration form
- ✅ When a new user logs in for the first time via Google OAuth

**What it contains:**
- Welcome message
- User's name
- Instructions to complete profile
- Link to edit profile

**Mailable Class:** `App\Mail\WelcomeMail`
**Sent from:** `AuthController@register` and `AuthController@handleGoogleCallback`

---

### 2. Event Invitation Email (`resources/views/emails/event-invitation.blade.php`)

**When it's sent:**
- ✅ When an admin creates a new event and selects graduation year batches
- ✅ When an admin clicks "Resend Invitations to All" on an event page
- ✅ Automatically sent to all alumni in selected batches when event is created

**What it contains:**
- Event title and description
- Event date and time
- Event venue (if provided)
- Event location (if provided)
- Call-to-action button to view event details
- Link to event page

**Mailable Class:** `App\Mail\EventInvitationMail`
**Sent from:** 
- `EventController@store` (when creating event)
- `EventController@update` (when updating event with new batches)
- `EventController@resendInvites` (manual resend)

**Recipients:**
- All active alumni whose `graduation_year` matches any of the selected batches in `target_graduation_years`
- Only if they have a valid email address
- Emails are not duplicated if already sent (tracked via `EventInvitation` model)

---

## Email Sending Process

### Step-by-Step: Event Invitation Flow

1. **Admin creates/edits event:**
   - Admin goes to `/admin/events/create` or `/admin/events/{id}/edit`
   - Fills in event details
   - Selects graduation year batches (checkboxes)
   - Saves the event

2. **System finds recipients:**
   - Queries `User` table for all users where:
     - `graduation_year` is in the selected batches
     - `status` = 'active'
     - `email` is not null

3. **System sends emails:**
   - Creates `EventInvitation` record for each recipient
   - Sends `EventInvitationMail` to each user's email
   - Marks `invites_sent = true` on the event
   - Logs success/failure for each email

4. **Email tracking:**
   - `EventInvitation` model tracks:
     - `sent_at`: When email was sent
     - `viewed_at`: When user views the event (optional)
     - `status`: sent, viewed, accepted, declined

---

## Email Configuration

### Current Mail Driver

Check your mail driver:
```bash
php artisan tinker
config('mail.default')
```

**Common drivers:**
- `log` - Emails saved to `storage/logs/laravel.log` (development)
- `smtp` - Actual email sending (production)

### View Logged Emails

If using `log` driver, check:
```
storage/logs/laravel.log
```

Search for:
- "Event invitation sent successfully"
- "Event Invitation: [Event Title]"
- HTML email content

---

## Testing Email Templates

### Test Welcome Email

1. Register a new user
2. Check email inbox or `storage/logs/laravel.log`

### Test Event Invitation Email

1. Create an event as admin
2. Select at least one graduation year batch
3. Save the event
4. Check:
   - Email inbox (if SMTP configured)
   - `storage/logs/laravel.log` (if using log driver)
   - `event_invitations` table in database

### Test Resend Invitations

1. Go to event page: `/events/{id}`
2. Click "Resend Invitations to All" (admin only)
3. Emails will be resent to all batches

---

## Email Template Customization

### Modify Welcome Email

Edit: `resources/views/emails/welcome.blade.php`

Variables available:
- `$user` - User model instance

### Modify Event Invitation Email

Edit: `resources/views/emails/event-invitation.blade.php`

Variables available:
- `$event` - Event model instance
- `$user` - User model instance (recipient)

---

## Email Status Tracking

### Database Tables

**`event_invitations` table:**
- Tracks which users received invitations
- `sent_at`: Timestamp when sent
- `status`: sent, viewed, accepted, declined

### Query Email Status

```php
// Get all invitations for an event
$event->invitations;

// Check if user received invitation
EventInvitation::where('event_id', $eventId)
    ->where('user_id', $userId)
    ->exists();

// Get sent count
EventInvitation::where('event_id', $eventId)->count();
```

---

## Troubleshooting

### Emails Not Sending

1. **Check mail driver:**
   ```bash
   php artisan tinker
   config('mail.default')
   ```

2. **Check logs:**
   ```
   storage/logs/laravel.log
   ```

3. **Verify SMTP settings** (if using SMTP):
   - Check `.env` file
   - Verify credentials
   - Test connection

4. **Check database:**
   - Are users in selected batches?
   - Do users have email addresses?
   - Are users active (`status = 'active'`)?

### Email Template Not Found

- Ensure template exists: `resources/views/emails/{template-name}.blade.php`
- Clear view cache: `php artisan view:clear`

### Duplicate Emails

- System prevents duplicates via `EventInvitation` model
- Uses `firstOrCreate` to ensure one invitation per user per event

---

## Future Email Templates (Planned)

1. **Event Reminder** - Sent 24 hours before event
2. **Registration Confirmation** - When user registers for event
3. **Event Updates** - When event details change
4. **Campaign Announcement** - When new campaign is published
5. **Admin Communication** - Messages from admin to alumni

---

## Best Practices

1. **Always use Mailables** - Don't send emails directly
2. **Handle failures gracefully** - Wrap in try-catch
3. **Log email actions** - Track success/failure
4. **Test templates** - Preview before sending
5. **Use queues** - For large email batches (future enhancement)

---

## Quick Reference

| Email Type | Template | Mailable | Trigger |
|------------|----------|----------|---------|
| Welcome | `emails/welcome` | `WelcomeMail` | User registration |
| Event Invitation | `emails/event-invitation` | `EventInvitationMail` | Event created/updated/resend |

---

**Last Updated:** November 2025
**Version:** 1.0



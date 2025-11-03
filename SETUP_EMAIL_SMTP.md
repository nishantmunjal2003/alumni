# Email SMTP Setup Guide

## Current Status
Your mail driver is set to `log`, which means emails are saved to log files only and not actually sent.

## Option 1: Mailtrap (Recommended for Development/Testing)

Mailtrap is a free email testing service that catches all emails so you can test without sending real emails.

### Steps:
1. **Sign up for Mailtrap (Free)**: Go to https://mailtrap.io and create a free account
2. **Get SMTP Credentials**: 
   - Login to Mailtrap
   - Go to "Email Testing" → "Inboxes" → Select your inbox
   - Click on "SMTP Settings"
   - Select "Laravel" from the dropdown
   - Copy the credentials shown

3. **Update your `.env` file** with these values:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=your_mailtrap_username
   MAIL_PASSWORD=your_mailtrap_password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=noreply@alumni.gkv.ac.in
   MAIL_FROM_NAME="Gurukula Kangri Alumni"
   ```

4. **Clear config cache**:
   ```bash
   php artisan config:clear
   ```

5. **Test**: Create an event and send invitations - emails will appear in your Mailtrap inbox!

---

## Option 2: Gmail SMTP (For Production)

If you want to send real emails using Gmail:

### Steps:
1. **Enable 2-Step Verification** on your Google Account
2. **Generate App Password**:
   - Go to https://myaccount.google.com/apppasswords
   - Select "Mail" and "Other (Custom name)"
   - Enter "Laravel Alumni App"
   - Copy the generated 16-character password

3. **Update your `.env` file**:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=your-email@gmail.com
   MAIL_PASSWORD=your-16-character-app-password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=your-email@gmail.com
   MAIL_FROM_NAME="Gurukula Kangri Alumni"
   ```

4. **Clear config cache**:
   ```bash
   php artisan config:clear
   ```

---

## Option 3: Other Email Providers

### Outlook/Hotmail
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-mail.outlook.com
MAIL_PORT=587
MAIL_USERNAME=your-email@outlook.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
```

### Yahoo
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mail.yahoo.com
MAIL_PORT=587
MAIL_USERNAME=your-email@yahoo.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

---

## Quick Test After Setup

After configuring SMTP, test it by:
1. Going to an event page
2. Clicking "Resend Invitations to All" (as admin)
3. Check your Mailtrap inbox (if using Mailtrap) or email inbox (if using real SMTP)

---

## View Logged Emails (Current Setup)

If you want to see the emails that are currently being logged:
- Check `storage/logs/laravel.log`
- Search for "Event invitation" or the email content

---

## Troubleshooting

- **Connection timeout**: Check your firewall settings
- **Authentication failed**: Verify username and password are correct
- **For Gmail**: Make sure you're using an App Password, not your regular password
- **Still seeing "log" messages**: Run `php artisan config:clear` after updating `.env`



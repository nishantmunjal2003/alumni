# Email Configuration Guide

## Current Status
Your mail driver is currently set to **`log`**, which means emails are being written to log files instead of actually being sent.

## How to Actually Send Emails

### Option 1: Configure SMTP (Recommended for Production)

Add these settings to your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Gurukula Kangri Alumni"
```

**For Gmail:**
1. Enable 2-Step Verification
2. Generate an App Password: https://myaccount.google.com/apppasswords
3. Use the app password in `MAIL_PASSWORD`

**For Other Providers:**
- Outlook/Hotmail: `smtp-mail.outlook.com`, Port: 587
- Yahoo: `smtp.mail.yahoo.com`, Port: 587
- Custom SMTP: Use your provider's SMTP settings

### Option 2: Use Mailtrap (For Testing/Development)

1. Sign up at https://mailtrap.io (free tier available)
2. Copy the SMTP credentials from Mailtrap
3. Add to `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@alumni.gkv.ac.in
MAIL_FROM_NAME="Gurukula Kangri Alumni"
```

### Option 3: View Logged Emails (Current Setup)

If you're using the `log` driver, you can view the emails in:
```
storage/logs/laravel.log
```

The emails are logged as HTML and you can see their content there.

## Verify Configuration

After updating `.env`, clear the config cache:
```bash
php artisan config:clear
```

## Test Email Sending

You can test if emails are working by creating a new event and selecting graduation year batches.

## Troubleshooting

1. **Emails not sending**: Check `storage/logs/laravel.log` for error messages
2. **Gmail blocking**: Make sure you're using an App Password, not your regular password
3. **Connection timeouts**: Check your firewall and port settings
4. **Authentication errors**: Verify your username and password are correct



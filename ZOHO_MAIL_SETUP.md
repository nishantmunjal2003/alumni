# Zoho Mail SMTP Setup Guide

## Zoho Mail SMTP Settings

Zoho Mail provides two options for SMTP:

### Option 1: SSL (Port 465) - Recommended
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.zoho.com
MAIL_PORT=465
MAIL_USERNAME=your-email@yourdomain.com
MAIL_PASSWORD=your-password-or-app-password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=your-email@yourdomain.com
MAIL_FROM_NAME="Gurukula Kangri Alumni"
```

### Option 2: TLS (Port 587)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.zoho.com
MAIL_PORT=587
MAIL_USERNAME=your-email@yourdomain.com
MAIL_PASSWORD=your-password-or-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@yourdomain.com
MAIL_FROM_NAME="Gurukula Kangri Alumni"
```

## Important Notes:

1. **Two-Factor Authentication (2FA):**
   - If you have 2FA enabled on your Zoho account, you MUST use an App Password
   - Go to: Zoho Account → Security → App Passwords
   - Generate a new app password for "Laravel" or "Alumni App"
   - Use this app password in `MAIL_PASSWORD` (NOT your regular password)

2. **Email Address:**
   - `MAIL_USERNAME` and `MAIL_FROM_ADDRESS` must be your Zoho Mail email address
   - Format: `yourname@yourdomain.com` or `yourname@zoho.com`

3. **Domain vs Zoho.com:**
   - If you use a custom domain: `yourname@yourdomain.com`
   - If you use Zoho's free domain: `yourname@zoho.com` or `yourname@zoho.in`

## Steps to Configure:

1. **Get your Zoho Mail credentials:**
   - Your Zoho Mail email address
   - Your password (or App Password if 2FA is enabled)

2. **Update `.env` file:**
   - Replace the MAIL_* settings with your Zoho Mail details
   - Choose either SSL (port 465) or TLS (port 587)

3. **Clear config cache:**
   ```bash
   php artisan config:clear
   ```

4. **Test:**
   - Try resending event invitations
   - Check if emails are received

## Troubleshooting:

- **Authentication Failed:** 
  - Check username and password are correct
  - If 2FA enabled, use App Password, not regular password
  
- **Connection Timeout:**
  - Check if port 465 or 587 is blocked by firewall
  - Try switching between SSL (465) and TLS (587)
  
- **Emails Not Sent:**
  - Verify `MAIL_FROM_ADDRESS` matches your Zoho email exactly
  - Check Zoho Mail account settings allow SMTP access

## Need Help?

If you want me to update your `.env` file automatically, please provide:
1. Your Zoho Mail email address
2. Whether you want to use SSL (port 465) or TLS (port 587)
3. Confirm if you have 2FA enabled (so you know to generate an App Password)



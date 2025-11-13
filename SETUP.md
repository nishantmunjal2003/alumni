# Alumni Portal Setup Guide

## Prerequisites
- PHP 8.2+
- Composer
- Node.js 18.x+
- MySQL/PostgreSQL/SQLite database
- XAMPP (for Windows) or similar local server

## Installation Steps

1. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

2. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Configure Database**
   Edit `.env` file and set your database credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=alumni_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Run Migrations**
   ```bash
   php artisan migrate
   php artisan db:seed --class=RoleSeeder
   ```

5. **Create Storage Link**
   ```bash
   php artisan storage:link
   ```

6. **Configure Google OAuth (Optional)**
   Add to `.env`:
   ```
   GOOGLE_CLIENT_ID=your_client_id
   GOOGLE_CLIENT_SECRET=your_client_secret
   GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
   ```

7. **Configure Email (Optional)**
   Add to `.env`:
   ```
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.example.com
   MAIL_PORT=587
   MAIL_USERNAME=your_email
   MAIL_PASSWORD=your_password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=noreply@example.com
   MAIL_FROM_NAME="Alumni Portal"
   ```

8. **Build Assets**
   ```bash
   npm run build
   # Or for development:
   npm run dev
   ```

9. **Start Server**
   ```bash
   php artisan serve
   ```

10. **Create Admin User**
    ```bash
    php artisan tinker
    ```
    Then run:
    ```php
    $user = App\Models\User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
    ]);
    $user->assignRole('admin');
    ```

## Features Implemented

✅ User Authentication (Email/Password & Google OAuth)
✅ Role-Based Access Control (Admin/User)
✅ Alumni Directory with Search
✅ Event Management
✅ Event Registration System
✅ Campaign Management
✅ Messaging System
✅ Profile Management
✅ File Uploads (Profile Images, Event Images, etc.)
✅ Email Notifications

## Default Routes

- `/` - Welcome page
- `/login` - Login page
- `/register` - Registration page
- `/dashboard` - User dashboard
- `/alumni` - Alumni directory
- `/events` - Events listing
- `/campaigns` - Campaigns listing
- `/messages` - Messages
- `/admin` - Admin dashboard (admin only)

## Notes

- Make sure to run `php artisan storage:link` to enable file uploads
- Configure your email settings if you want email notifications
- Google OAuth requires Google Cloud Console setup
- All file uploads are stored in `storage/app/public/`





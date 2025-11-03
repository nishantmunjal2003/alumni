# Alumni Directory & Management System

A comprehensive Laravel-based web application for managing alumni networks, facilitating connections, organizing events, and running fundraising campaigns.

## 📋 Table of Contents

- [About](#about)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Project Structure](#project-structure)
- [Key Features in Detail](#key-features-in-detail)
- [Additional Documentation](#additional-documentation)
- [Contributing](#contributing)
- [License](#license)

## 🎯 About

The Alumni Directory & Management System is a full-featured platform designed to help educational institutions maintain and strengthen their alumni network. It enables alumni to connect with each other, stay updated on events, participate in fundraising campaigns, and engage with their alma mater.

## ✨ Features

### For Alumni
- **User Authentication**
  - Standard email/password registration and login
  - Google OAuth integration for quick sign-in
  - Secure password management

- **Alumni Directory**
  - Browse and search alumni database
  - Advanced search by name, email, major, company, position, or graduation year
  - View detailed alumni profiles
  - Edit personal profile information
  - Upload profile pictures
  - Connect with batchmates (same graduation year)

- **Events**
  - Browse upcoming and past events
  - Receive event invitations via email
  - Register for events
  - Upload photos during event registration
  - View registered attendees (fellows)
  - Edit or cancel event registrations

- **Campaigns**
  - View active fundraising campaigns
  - Support institutional initiatives

- **Messaging**
  - Send and receive messages with other alumni
  - Real-time unread message count
  - Organized message threads

- **Dashboard**
  - Personalized alumni dashboard
  - View batchmates (same graduation year)
  - Search and connect with all alumni
  - See upcoming events and registration status
  - Quick access to profile management

### For Administrators
- **Admin Dashboard**
  - Overview of system statistics
  - User and role management
  - Event management
  - Campaign management

- **User Management**
  - View all registered users
  - Assign roles and permissions
  - Manage user status

- **Role Management**
  - Create custom roles
  - Assign permissions
  - Role-based access control

- **Event Management**
  - Create, edit, and delete events
  - Target specific graduation year batches
  - Send event invitations automatically
  - Resend invitations
  - View event registrations
  - GPS coordinates for event locations

- **Campaign Management**
  - Create fundraising campaigns
  - Edit campaign details
  - Manage campaign visibility

## 🛠 Tech Stack

- **Backend Framework:** Laravel 12.x
- **Frontend:** Blade Templates with Tailwind CSS 4.x
- **JavaScript:** Vanilla JS with Vite
- **Database:** MySQL/PostgreSQL/SQLite (configurable)
- **Authentication:** Laravel Auth + Laravel Socialite (Google OAuth)
- **Authorization:** Spatie Laravel Permission Package
- **PHP Version:** 8.2+

## 📦 Requirements

- PHP >= 8.2
- Composer
- Node.js >= 18.x and npm
- MySQL 5.7+ / PostgreSQL 10+ / SQLite 3.8.8+
- Web Server (Apache/Nginx) or PHP Built-in Server

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd alumni
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install JavaScript Dependencies

```bash
npm install
```

### 4. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Database Setup

Update your `.env` file with database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=alumni_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Run migrations:

```bash
php artisan migrate
```

Seed initial roles:

```bash
php artisan db:seed --class=RoleSeeder
```

### 6. Storage Link

```bash
php artisan storage:link
```

### 7. Build Assets

For development:

```bash
npm run dev
```

For production:

```bash
npm run build
```

### 8. Start the Server

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## ⚙️ Configuration

### Google OAuth Setup

1. Create a project in [Google Cloud Console](https://console.cloud.google.com/)
2. Enable Google+ API
3. Create OAuth 2.0 credentials
4. Add to `.env`:

```env
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

### Email Configuration

The application uses Laravel's mail system. Configure your email settings in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

For detailed email setup guides, see:
- `SETUP_EMAIL_SMTP.md`
- `ZOHO_MAIL_SETUP.md`
- `EMAIL_CONFIGURATION.md`

### Admin Role Assignment

After creating your account, assign admin role using the command:

```bash
php artisan tinker
```

Then run:

```php
$user = User::where('email', 'your-email@example.com')->first();
$user->assignRole('admin');
```

Or refer to `ASSIGN_ADMIN_ROLE.md` for detailed instructions.

## 📖 Usage

### First-Time Setup

1. Register a new account or login with Google
2. Complete your profile with graduation year, major, and professional details
3. (For admins) Access admin panel at `/admin` to manage the system

### For Alumni Users

1. **Profile Management**
   - Navigate to your profile from the dashboard
   - Click "Edit Profile" to update information
   - Upload a profile picture

2. **Connecting with Alumni**
   - Use the Alumni Directory to search and browse
   - Filter by graduation year, major, or company
   - View detailed profiles and send messages

3. **Event Participation**
   - Browse events from the dashboard or events page
   - Click "Register" to join an event
   - Upload photos during registration
   - View other registered attendees

4. **Messaging**
   - Go to Messages from the navigation
   - Select an alumnus to start a conversation
   - Messages are stored and organized by thread

### For Administrators

1. **Creating Events**
   - Navigate to Admin → Events → Create
   - Fill in event details including date, venue, and description
   - Select target graduation year batches
   - Event invitations are sent automatically

2. **Managing Campaigns**
   - Create campaigns from Admin → Campaigns
   - Set campaign goals and descriptions
   - Make campaigns visible to alumni

3. **User Management**
   - View all users in Admin → Users
   - Assign roles and manage permissions
   - Monitor user activity

## 📁 Project Structure

```
alumni/
├── app/
│   ├── Console/          # Artisan commands
│   ├── Http/
│   │   ├── Controllers/   # Application controllers
│   │   └── Middleware/    # Custom middleware
│   ├── Mail/              # Email classes
│   ├── Models/            # Eloquent models
│   ├── Notifications/     # Notification classes
│   └── Providers/        # Service providers
├── config/                # Configuration files
├── database/
│   ├── migrations/        # Database migrations
│   └── seeders/           # Database seeders
├── public/                # Public assets
├── resources/
│   ├── css/              # Stylesheets
│   ├── js/               # JavaScript files
│   └── views/            # Blade templates
├── routes/                # Route definitions
├── storage/               # Storage for files
└── tests/                 # Test files
```

## 🔑 Key Features in Detail

### Event Invitation System
- Events can target specific graduation year batches
- Automatic email invitations sent to matching alumni
- Invitation tracking with sent/viewed status
- Resend invitation functionality for admins

### Role-Based Access Control
- Uses Spatie Laravel Permission package
- Admin role for system management
- User role for regular alumni access
- Custom roles can be created

### File Management
- Profile image uploads
- Event registration photos
- Secure file storage with public access links

### Search Functionality
- Real-time AJAX-powered search
- Multi-field search (name, email, major, company, position, graduation year)
- Pagination support

### Email Notifications
- Welcome emails for new users
- Event invitation emails
- Configurable email templates

## 📚 Additional Documentation

- `ASSIGN_ADMIN_ROLE.md` - How to assign admin role to users
- `EMAIL_CONFIGURATION.md` - Email setup guide
- `EMAIL_TEMPLATES_GUIDE.md` - Email template documentation
- `SETUP_EMAIL_SMTP.md` - SMTP configuration
- `ZOHO_MAIL_SETUP.md` - Zoho Mail specific setup
- `TROUBLESHOOTING.md` - Common issues and solutions
- `CHANGELOG.md` - Version history
- `GITHUB_REPO_SETUP.md` - GitHub repository setup guide

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🆘 Support

For issues, questions, or contributions, please refer to:
- `TROUBLESHOOTING.md` for common issues
- GitHub Issues for bug reports
- Contact the development team for additional support

---

**Built with ❤️ using Laravel**

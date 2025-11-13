# Alumni Directory & Management System - Regeneration Prompt

## Quick Start Prompt

Build a **Laravel 12.x Alumni Directory & Management System** with the following specifications:

### Tech Stack
- Laravel 12.x, PHP 8.2+, Tailwind CSS 4.x, Vanilla JS with Vite
- MySQL/PostgreSQL database
- Laravel Socialite for Google OAuth
- Spatie Laravel Permission for RBAC

### Core Features

#### 1. Authentication
- Email/password registration and login
- Google OAuth integration
- Secure password hashing

#### 2. User/Alumni Profiles
- Fields: name, email, phone, graduation_year, major, bio, current_position, company, linkedin_url, profile_image, status
- Profile editing
- Profile image uploads
- Browse and search alumni directory
- AJAX-powered multi-field search (name, email, major, company, position, graduation year)
- View batchmates (same graduation year)

#### 3. Role-Based Access Control
- Spatie Laravel Permission package
- Default roles: `admin`, `user`
- Admin middleware
- Admin panel for user/role management
- Custom role creation

#### 4. Event Management
- **Event fields:** title, description, image, event_date, location, latitude, longitude, venue, status, target_graduation_years (JSON array), invites_sent
- Admin can create/edit/delete events
- Public viewing of published events
- **Event Invitation System:**
  - Automatic email invitations to alumni matching target graduation years
  - Track invitation status (sent, viewed)
  - Resend invitations
- **Event Registration:**
  - Users register for events
  - Fields: arrival_date, coming_from_city, arrival_time, needs_stay, coming_with_family, travel_mode, return_journey_details, memories_description
  - Upload photos during registration
  - Edit/cancel registrations
  - View registered attendees (fellows)
  - Friends system (many-to-many: users can mark friends attending same event)

#### 5. Campaign Management
- **Campaign fields:** title, description, image, start_date, end_date, status
- Admin can create/edit/delete campaigns
- Public viewing of published campaigns

#### 6. Messaging System
- Send/receive messages between alumni
- Message threads organized by user
- Real-time unread message count (AJAX endpoint)
- Mark messages as read
- Message model: from_user_id, to_user_id, message, is_read, read_at

#### 7. Email System
- Welcome emails for new users
- Event invitation emails
- Blade email templates
- SMTP configuration support

#### 8. Dashboard
- **Alumni Dashboard:** batchmates, upcoming events, registration status, quick profile access, unread message count
- **Admin Dashboard:** system statistics, user/event/campaign counts, quick actions

### Database Tables

1. **users** (Laravel default + alumni fields)
2. **roles, permissions** (Spatie package tables)
3. **campaigns** (id, created_by, title, description, image, start_date, end_date, status, timestamps)
4. **events** (id, created_by, title, description, image, event_date, location, latitude, longitude, venue, status, target_graduation_years, invites_sent, timestamps)
5. **event_invitations** (id, event_id, user_id, sent_at, viewed_at, status, timestamps)
6. **event_registrations** (id, event_id, user_id, arrival_date, coming_from_city, arrival_time, needs_stay, coming_with_family, travel_mode, return_journey_details, memories_description, timestamps, unique: event_id+user_id)
7. **event_registration_photos** (id, event_registration_id, photo_path, timestamps)
8. **event_registration_friends** (event_registration_id, friend_user_id, timestamps)
9. **messages** (id, from_user_id, to_user_id, message, is_read, read_at, timestamps)

### Routes Structure

**Public:**
- `/` → redirect to login or dashboard
- `/login`, `/register` → authentication
- `/auth/google`, `/auth/google/callback` → OAuth
- `/events`, `/events/{id}` → view events
- `/campaigns`, `/campaigns/{id}` → view campaigns

**Authenticated (Alumni):**
- `/dashboard` → alumni dashboard
- `/alumni` → browse directory
- `/alumni/{id}`, `/alumni/{id}/edit` → view/edit profile
- `/events/{event}/register` → register for event
- `/events/{event}/registrations/{id}/edit` → edit registration
- `/events/{event}/fellows` → view attendees
- `/messages`, `/messages/{user}` → messaging

**Admin Only:**
- `/admin` → admin dashboard
- `/admin/users` → user management
- `/admin/roles` → role management
- `/admin/events/*` → event CRUD
- `/admin/campaigns/*` → campaign CRUD
- `/admin/events/{id}/resend-invites` → resend invitations

### Key Controllers

- `AuthController` - login, register, Google OAuth
- `AlumniController` - directory, profiles, dashboard, search
- `EventController` - event management, invitations
- `EventRegistrationController` - registrations, fellows
- `CampaignController` - campaign management
- `MessageController` - messaging, unread count
- `AdminController` - admin dashboard, user/role management

### Key Features Implementation

1. **Search:** AJAX endpoint in AlumniController, real-time results, multi-field search
2. **File Uploads:** Profile images, event images, campaign images, registration photos (store in `storage/app/public/*`, use `php artisan storage:link`)
3. **Email Notifications:** Queue support, Blade templates, SMTP configurable
4. **GPS Coordinates:** Store latitude/longitude for events, display on maps
5. **Batch Targeting:** JSON array of graduation years, filter users when sending invitations
6. **Unread Messages:** AJAX endpoint `/messages/unread/count`, update badge in real-time
7. **Form Persistence:** Use localStorage to save form data (JavaScript)

### UI Requirements

- Modern, responsive design with Tailwind CSS
- Navigation bar with user menu
- Badge notifications for unread messages
- Image upload with preview
- Search with instant results
- Pagination for lists
- Loading states and feedback messages
- Mobile-friendly layout

### Security

- CSRF protection
- Authentication middleware
- Admin middleware (check for 'admin' role)
- Input validation
- File upload validation
- XSS protection
- SQL injection prevention (Eloquent)

### Installation Checklist

1. `composer create-project laravel/laravel alumni`
2. `composer require laravel/socialite spatie/laravel-permission`
3. `npm install`
4. Configure `.env` (database, mail, Google OAuth)
5. `php artisan key:generate`
6. `php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"`
7. Create and run migrations
8. `php artisan db:seed --class=RoleSeeder` (create roles seeder)
9. `php artisan storage:link`
10. `npm run build`
11. Assign admin role to first user via tinker

### Email Configuration

Support SMTP with these env variables:
```
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME=
```

### Google OAuth Configuration

```
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

### Key Models & Relationships

- `User` → hasMany Campaigns, Events, EventRegistrations, Messages
- `Event` → hasMany Invitations, Registrations; belongsTo User (creator)
- `EventRegistration` → belongsTo Event, User; hasMany Photos; belongsToMany Users (friends)
- `Campaign` → belongsTo User (creator)
- `Message` → belongsTo User (from_user, to_user)
- `EventInvitation` → belongsTo Event, User

### Additional Features

- Welcome notification on registration
- Form data persistence (localStorage)
- Badge updater for notifications
- Event image uploads
- Campaign image uploads
- Profile image uploads
- Registration photo uploads
- GPS coordinates for event locations
- Batch-based event targeting
- Friends system for event registrations
- Comprehensive search with pagination
- Real-time unread message count

### Documentation Files

Create these markdown files:
- README.md (setup guide)
- ASSIGN_ADMIN_ROLE.md
- EMAIL_CONFIGURATION.md
- SETUP_EMAIL_SMTP.md
- ZOHO_MAIL_SETUP.md
- EMAIL_TEMPLATES_GUIDE.md
- TROUBLESHOOTING.md
- CHANGELOG.md

---

**This prompt contains all essential information to regenerate the Alumni Directory & Management System. Follow Laravel best practices, implement proper security, and ensure responsive design.**


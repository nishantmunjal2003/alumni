# Alumni Directory & Management System - Concept Note & Regeneration Prompt

## Project Overview

Build a comprehensive **Alumni Directory & Management System** using Laravel 12.x that enables educational institutions to maintain and strengthen their alumni network. The system facilitates connections between alumni, organizes events, manages fundraising campaigns, and provides a messaging platform for alumni communication.

---

## Technical Stack

- **Backend Framework:** Laravel 12.x
- **Frontend:** Blade Templates with Tailwind CSS 4.x
- **JavaScript:** Vanilla JS with Vite
- **Database:** MySQL/PostgreSQL/SQLite (configurable)
- **Authentication:** Laravel Auth + Laravel Socialite (Google OAuth)
- **Authorization:** Spatie Laravel Permission Package (v6.22+)
- **PHP Version:** 8.2+
- **Node.js:** 18.x+

---

## Core Features & Requirements

### 1. User Authentication System

**Requirements:**
- Standard email/password registration and login
- Google OAuth integration using Laravel Socialite
- Secure password hashing
- Remember me functionality
- Session-based authentication

**User Model Fields:**
- `name` (string)
- `email` (string, unique)
- `password` (hashed)
- `phone` (nullable string)
- `graduation_year` (nullable string)
- `major` (nullable string)
- `bio` (nullable text)
- `current_position` (nullable string)
- `company` (nullable string)
- `linkedin_url` (nullable string)
- `profile_image` (nullable string, stored in storage)
- `status` (enum: 'active', 'inactive', default: 'active')
- Standard Laravel timestamps and email verification fields

**Routes:**
- `GET /login` - Show login form
- `POST /login` - Process login
- `GET /register` - Show registration form
- `POST /register` - Process registration
- `POST /logout` - Logout user
- `GET /auth/google` - Redirect to Google OAuth
- `GET /auth/google/callback` - Handle Google OAuth callback

---

### 2. Role-Based Access Control (RBAC)

**Requirements:**
- Use Spatie Laravel Permission package
- Default roles: `admin`, `user`
- Admin middleware to protect admin routes
- Ability to create custom roles
- Assign roles to users
- Role management interface for admins

**Database Tables:**
- `roles` (id, name, guard_name, timestamps)
- `permissions` (id, name, guard_name, timestamps)
- `model_has_roles` (role_id, model_type, model_id)
- `model_has_permissions` (permission_id, model_type, model_id)
- `role_has_permissions` (permission_id, role_id)

**Admin Routes:**
- `GET /admin` - Admin dashboard
- `GET /admin/users` - List all users
- `POST /admin/users/{user}/roles` - Update user roles
- `GET /admin/roles` - List all roles
- `GET /admin/roles/create` - Create role form
- `POST /admin/roles` - Store new role
- `DELETE /admin/roles/{role}` - Delete role

**Middleware:**
- `AdminMiddleware` - Check if user has 'admin' role

---

### 3. Alumni Directory

**Features:**
- Browse all registered alumni
- Advanced search functionality (AJAX-powered)
- Search by: name, email, major, company, position, graduation year
- View detailed alumni profiles
- Edit own profile
- Upload profile pictures
- Filter by graduation year (batchmates)
- Pagination support
- Grid/list view options

**Alumni Routes (Authenticated):**
- `GET /dashboard` - Alumni dashboard (shows batchmates, upcoming events)
- `GET /alumni` - Browse all alumni (with search)
- `GET /alumni/{id}` - View alumni profile
- `GET /alumni/{id}/edit` - Edit own profile
- `PUT /alumni/{id}` - Update profile
- `DELETE /alumni/{id}` - Delete own account (optional)

**Search Functionality:**
- Real-time AJAX search
- Multi-field search capability
- Results update without page reload
- Pagination for search results

---

### 4. Event Management System

**Event Model Fields:**
- `created_by` (foreign key to users)
- `title` (string)
- `description` (text)
- `image` (nullable string, stored in storage)
- `event_date` (datetime)
- `location` (string)
- `latitude` (decimal:8)
- `longitude` (decimal:8)
- `venue` (string)
- `status` (enum: 'draft', 'published')
- `target_graduation_years` (JSON array of years)
- `invites_sent` (boolean, default: false)
- Timestamps

**Event Features:**
- Create, edit, delete events (admin only)
- Target specific graduation year batches
- Automatic email invitations to matching alumni
- GPS coordinates for event locations
- Event image uploads
- View upcoming and past events
- Public event viewing (published events)
- Event registration system

**Event Routes:**
- `GET /events` - List all published events (public)
- `GET /events/{id}` - View event details (public)
- `GET /admin/events/create` - Create event (admin)
- `POST /admin/events` - Store event (admin)
- `GET /admin/events/{id}/edit` - Edit event (admin)
- `PUT /admin/events/{id}` - Update event (admin)
- `DELETE /admin/events/{id}` - Delete event (admin)
- `POST /admin/events/{id}/resend-invites` - Resend invitations (admin)

**Event Invitation System:**
- Automatic invitations sent when event is created
- Invitations stored in `event_invitations` table
- Track invitation status (sent, viewed)
- Email notifications with event details
- Resend invitation functionality

**Event Invitation Model:**
- `event_id` (foreign key)
- `user_id` (foreign key)
- `sent_at` (datetime)
- `viewed_at` (nullable datetime)
- `status` (enum: 'pending', 'sent', 'viewed')
- Timestamps

---

### 5. Event Registration System

**Event Registration Model Fields:**
- `event_id` (foreign key)
- `user_id` (foreign key)
- `arrival_date` (nullable date)
- `coming_from_city` (nullable string)
- `arrival_time` (nullable time)
- `needs_stay` (boolean, default: false)
- `coming_with_family` (boolean, default: false)
- `travel_mode` (enum: 'car', 'train', 'flight', 'bus', 'other')
- `return_journey_details` (nullable text)
- `memories_description` (nullable text)
- Timestamps
- Unique constraint: one registration per user per event

**Event Registration Features:**
- Register for events (authenticated users)
- Upload photos during registration
- Edit registration details
- Cancel registration
- View registered attendees (fellows)
- Track arrival details, travel mode, accommodation needs

**Event Registration Photo Model:**
- `event_registration_id` (foreign key)
- `photo_path` (string)
- Timestamps

**Event Registration Routes (Authenticated):**
- `GET /events/{event}/register` - Registration form
- `POST /events/{event}/register` - Store registration
- `GET /events/{event}/registrations/{id}/edit` - Edit registration
- `PUT /events/{event}/registrations/{id}` - Update registration
- `DELETE /events/{event}/registrations/{id}` - Cancel registration
- `GET /events/{event}/fellows` - View registered attendees

**Friends System:**
- Many-to-many relationship between event registrations and users
- Pivot table: `event_registration_friends`
- Track friends attending same event

---

### 6. Campaign Management System

**Campaign Model Fields:**
- `created_by` (foreign key to users)
- `title` (string)
- `description` (text)
- `image` (nullable string)
- `start_date` (date)
- `end_date` (date)
- `status` (enum: 'draft', 'published')
- Timestamps

**Campaign Features:**
- Create, edit, delete campaigns (admin only)
- Public viewing of published campaigns
- Campaign image uploads
- Date range for campaigns
- Fundraising initiative support

**Campaign Routes:**
- `GET /campaigns` - List all published campaigns (public)
- `GET /campaigns/{id}` - View campaign details (public)
- `GET /admin/campaigns/create` - Create campaign (admin)
- `POST /admin/campaigns` - Store campaign (admin)
- `GET /admin/campaigns/{id}/edit` - Edit campaign (admin)
- `PUT /admin/campaigns/{id}` - Update campaign (admin)
- `DELETE /admin/campaigns/{id}` - Delete campaign (admin)

---

### 7. Messaging System

**Message Model Fields:**
- `from_user_id` (foreign key)
- `to_user_id` (foreign key)
- `message` (text)
- `is_read` (boolean, default: false)
- `read_at` (nullable datetime)
- Timestamps

**Messaging Features:**
- Send messages between alumni
- View message threads
- Real-time unread message count (AJAX)
- Mark messages as read
- Organized conversation view
- Message history

**Message Routes (Authenticated):**
- `GET /messages` - List all conversations
- `GET /messages/{user}` - View conversation with specific user
- `POST /messages/{user}` - Send message to user
- `GET /messages/unread/count` - Get unread message count (AJAX endpoint)

---

### 8. Email System

**Email Features:**
- Welcome emails for new users
- Event invitation emails
- Email templates using Blade
- SMTP configuration support
- Zoho Mail setup support

**Email Classes:**
- `WelcomeMail` - Sent after registration
- `EventInvitationMail` - Sent for event invitations

**Email Templates:**
- `resources/views/emails/welcome.blade.php`
- `resources/views/emails/event-invitation.blade.php`

**Configuration:**
- Support for multiple SMTP providers
- Environment-based email configuration
- Queue support for email sending

---

### 9. File Management

**File Storage:**
- Profile images stored in `storage/app/public/profiles`
- Event images stored in `storage/app/public/events`
- Campaign images stored in `storage/app/public/campaigns`
- Event registration photos stored in `storage/app/public/event-photos`
- Public symlink: `php artisan storage:link`

**File Upload Features:**
- Image validation
- File size limits
- Secure file storage
- Public access via symlink

---

### 10. Dashboard Features

**Alumni Dashboard:**
- Personalized view for logged-in alumni
- Display batchmates (same graduation year)
- Show upcoming events
- Event registration status
- Quick access to profile management
- Unread message count badge
- Recent activity

**Admin Dashboard:**
- System statistics
- Total users count
- Total events count
- Total campaigns count
- Recent registrations
- Quick actions for common tasks

---

## Database Schema

### Core Tables

1. **users** (Laravel default + custom fields)
2. **roles** (Spatie package)
3. **permissions** (Spatie package)
4. **model_has_roles** (Spatie package)
5. **model_has_permissions** (Spatie package)
6. **role_has_permissions** (Spatie package)
7. **campaigns**
8. **events**
9. **event_invitations**
10. **event_registrations**
11. **event_registration_photos**
12. **event_registration_friends** (pivot table)
13. **messages**

### Key Relationships

- User hasMany Campaigns (created_by)
- User hasMany Events (created_by)
- User hasMany EventRegistrations
- User hasMany Messages (as sender and receiver)
- Event hasMany EventInvitations
- Event hasMany EventRegistrations
- EventRegistration belongsTo Event
- EventRegistration belongsTo User
- EventRegistration hasMany Photos
- EventRegistration belongsToMany Users (friends)
- Message belongsTo User (from_user, to_user)
- Campaign belongsTo User (created_by)

---

## Frontend Requirements

### UI/UX Design
- Modern, clean interface using Tailwind CSS 4.x
- Responsive design (mobile-friendly)
- Consistent navigation bar
- User-friendly forms with validation
- Loading states and feedback messages
- Image upload with preview
- Search with real-time results
- Pagination for lists
- Badge notifications for unread messages

### JavaScript Features
- AJAX search functionality
- Form persistence (localStorage)
- Badge updater for notifications
- Image preview on upload
- Dynamic form validation

### Key Views
- Welcome/Landing page
- Login/Register pages
- Alumni dashboard
- Admin dashboard
- Alumni directory (list/grid view)
- Alumni profile view/edit
- Events list and detail pages
- Event registration form
- Campaigns list and detail pages
- Messages interface
- Admin user management
- Admin role management
- Admin event management
- Admin campaign management

---

## Security Requirements

- CSRF protection on all forms
- XSS protection
- SQL injection prevention (Eloquent ORM)
- Password hashing (bcrypt)
- File upload validation
- Role-based route protection
- Authentication middleware
- Admin middleware
- Secure file storage
- Input validation and sanitization

---

## Configuration Requirements

### Environment Variables (.env)
```
APP_NAME="Alumni Directory"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=alumni_db
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

---

## Installation Steps

1. **Create Laravel Project**
   ```bash
   composer create-project laravel/laravel alumni
   cd alumni
   ```

2. **Install Dependencies**
   ```bash
   composer require laravel/socialite spatie/laravel-permission
   npm install
   ```

3. **Configure Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup**
   - Configure database in `.env`
   - Run migrations: `php artisan migrate`
   - Seed roles: `php artisan db:seed --class=RoleSeeder`

5. **Storage Link**
   ```bash
   php artisan storage:link
   ```

6. **Publish Spatie Permissions**
   ```bash
   php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
   php artisan migrate
   ```

7. **Build Assets**
   ```bash
   npm run build
   # or for development: npm run dev
   ```

8. **Assign Admin Role**
   ```bash
   php artisan tinker
   # Then: $user = User::where('email', 'admin@example.com')->first(); $user->assignRole('admin');
   ```

---

## Key Controllers

1. **AuthController** - Authentication, Google OAuth
2. **AlumniController** - Alumni directory, profiles, dashboard
3. **EventController** - Event management, invitations
4. **EventRegistrationController** - Event registrations
5. **CampaignController** - Campaign management
6. **MessageController** - Messaging system
7. **AdminController** - Admin dashboard, user/role management

---

## Key Middleware

- `auth` - Laravel default authentication
- `AdminMiddleware` - Check for admin role

---

## Additional Features

- Form persistence using localStorage
- Real-time unread message count updates
- Badge notifications
- GPS coordinates for event locations
- Batch-based event targeting
- Photo uploads for event registrations
- Friends system for event registrations
- Comprehensive search functionality
- Email notifications
- Welcome notifications

---

## Testing Considerations

- Feature tests for authentication
- Feature tests for event management
- Feature tests for messaging
- Unit tests for models
- Admin access tests
- Role permission tests

---

## Documentation Files to Include

- README.md (comprehensive setup guide)
- ASSIGN_ADMIN_ROLE.md (admin role assignment)
- EMAIL_CONFIGURATION.md (email setup)
- SETUP_EMAIL_SMTP.md (SMTP configuration)
- ZOHO_MAIL_SETUP.md (Zoho-specific setup)
- EMAIL_TEMPLATES_GUIDE.md (email template documentation)
- TROUBLESHOOTING.md (common issues and solutions)
- CHANGELOG.md (version history)
- GITHUB_REPO_SETUP.md (repository setup)

---

## Success Criteria

The application should:
1. Allow alumni to register and login (email/password or Google OAuth)
2. Enable alumni to browse and search the directory
3. Allow alumni to manage their profiles
4. Enable admins to create and manage events
5. Automatically send event invitations to targeted batches
6. Allow alumni to register for events with detailed information
7. Enable messaging between alumni
8. Display campaigns to alumni
9. Provide admin dashboard for system management
10. Support role-based access control
11. Handle file uploads securely
12. Send email notifications
13. Provide responsive, modern UI

---

## Notes for Implementation

- Use Laravel best practices
- Follow PSR coding standards
- Implement proper error handling
- Add validation for all inputs
- Use Eloquent relationships efficiently
- Optimize database queries
- Implement proper caching where needed
- Use queues for email sending
- Ensure mobile responsiveness
- Add proper logging
- Implement proper error pages (404, 500, etc.)
- Add CSRF tokens to all forms
- Sanitize all user inputs
- Use prepared statements (Eloquent handles this)
- Implement rate limiting for API endpoints
- Add proper SEO meta tags
- Implement proper pagination
- Use eager loading to prevent N+1 queries

---

This concept note provides a complete specification to regenerate the Alumni Directory & Management System from scratch. Follow the requirements, implement the features, and ensure all security and best practices are followed.


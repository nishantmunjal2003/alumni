# How to Access Admin Panel

## Quick Steps:

1. **Admin Panel URL**: `http://localhost:8000/admin`

2. **To assign admin role to your user**, run this in your terminal:

```bash
php artisan tinker
```

Then in the tinker console, run:
```php
$user = App\Models\User::where('email', 'YOUR_EMAIL@example.com')->first();
$user->assignRole('admin');
echo "Admin role assigned to: " . $user->email;
```

3. **Or assign to first user:**
```php
$user = App\Models\User::first();
$user->assignRole('admin');
```

4. **Check if role is assigned:**
```php
$user->hasRole('admin'); // Should return true
```

5. **Exit tinker:** Type `exit` or press Ctrl+C

## After assigning the role:
- Logout and login again (to refresh session)
- Click "Admin Panel" button in the navbar (visible when you have admin role)
- Or directly visit: `http://localhost:8000/admin`

## Admin Panel Features:
- View all users
- Manage user roles (assign admin/alumnus roles)
- Create Campaigns
- Create Events with batch selection
- Access to Alumni Directory



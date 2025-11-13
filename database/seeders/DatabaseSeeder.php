<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles first
        $this->call(RoleSeeder::class);

        // Create default admin user
        $admin = User::updateOrCreate(
            ['email' => 'nishant@gkv.ac.in'],
            [
                'name' => 'Nishant',
                'email' => 'nishant@gkv.ac.in',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'active',
                'profile_completed' => true,
                'profile_status' => 'approved',
            ]
        );

        // Ensure admin role is assigned (syncRoles ensures it's set correctly)
        $admin->syncRoles(['admin']);

        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}

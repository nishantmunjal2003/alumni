<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class EnsureAdminRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:ensure-role {email=nishant@gkv.ac.in}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ensure admin role is assigned to a user';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');

        // Ensure admin role exists
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("User with email {$email} not found!");

            return 1;
        }

        // Sync roles to ensure admin role is assigned
        $user->syncRoles(['admin']);
        $user->refresh();

        if ($user->hasRole('admin')) {
            $this->info("✓ Admin role successfully assigned to {$user->name} ({$email})");
            $this->call('permission:cache-reset');

            return 0;
        }

        $this->error('Failed to assign admin role!');

        return 1;
    }
}

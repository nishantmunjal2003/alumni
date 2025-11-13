<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class DeactivateAccountsWithoutProof extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accounts:deactivate-without-proof';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate accounts that have not uploaded proof document within 7 days of profile submission';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $users = User::whereNull('proof_document')
            ->whereNotNull('profile_submitted_at')
            ->where('status', 'active')
            ->get();

        $deactivatedCount = 0;

        foreach ($users as $user) {
            if ($user->shouldBeDeactivatedForMissingProof()) {
                $user->update([
                    'status' => 'inactive',
                    'profile_status' => 'blocked',
                ]);
                $deactivatedCount++;
                $this->info("Deactivated account: {$user->email} (ID: {$user->id})");
            }
        }

        if ($deactivatedCount > 0) {
            $this->info("Successfully deactivated {$deactivatedCount} account(s) without proof documents.");
        } else {
            $this->info('No accounts found that need to be deactivated.');
        }

        return Command::SUCCESS;
    }
}

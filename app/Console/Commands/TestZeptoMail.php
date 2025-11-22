<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestZeptoMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test-zeptomail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test email via ZeptoMail';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Sending test email via ZeptoMail...');

        try {
            Mail::raw('Hi from Alumni Portal', function ($message) {
                $message->to('nishantmunjal2003@gmail.com')
                    ->subject('Test Email from Alumni Portal');
            });

            $this->info('✓ Email sent successfully to nishantmunjal2003@gmail.com');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('✗ Failed to send email: '.$e->getMessage());
            $this->error('Stack trace: '.$e->getTraceAsString());

            return Command::FAILURE;
        }
    }
}

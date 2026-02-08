<?php

namespace App\Providers;

use App\Mail\Transports\ZeptoMailTransport;
use App\Services\ZeptoMailService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('userInitials', function ($expression) {
            return "<?php echo getUserInitials($expression); ?>";
        });

        
        Mail::extend('zeptomail', function (array $config = []) {
            return new ZeptoMailTransport(
                app(ZeptoMailService::class)
            );
        });
        
        // Log all sent emails
        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Mail\Events\MessageSent::class,
            function ($event) {
                try {
                    $message = $event->message;
                    $recipientEmail = null;
                    
                    // Get recipient email
                    $to = $message->getTo();
                    if (is_array($to) && !empty($to)) {
                        /* @var \Symfony\Component\Mime\Address $address */
                         foreach ($to as $address) {
                             $recipientEmail = $address->getAddress();
                             break; // Just get the first one for logging
                         }
                    } 
                    
                    if (!$recipientEmail) {
                        return;
                    }

                    // Try to find user
                    $user = \App\Models\User::where('email', $recipientEmail)->first();
                    
                    \App\Models\EmailLog::create([
                        'recipient_email' => $recipientEmail,
                        'user_id' => $user ? $user->id : null,
                        'subject' => $message->getSubject(),
                        'body' => $message->getHtmlBody() ?? $message->getTextBody(),
                        'status' => 'sent',
                    ]);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Failed to log email: ' . $e->getMessage());
                }
            }
        );
    }
}

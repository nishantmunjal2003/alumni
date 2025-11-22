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
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use App\Notifications\Channels\WhatsAppChannel;
use Illuminate\Support\Facades\Notification;

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
        //decommenter si : "Notification channel [whatsapp] does not exist"
        //  Notification::extend('whatsapp', function ($app) {
        //         return $app->make(WhatsAppChannel::class);
        //     });

        // if (app()->environment('local')) {
        //     URL::forceScheme('https');
        // }
    }
}

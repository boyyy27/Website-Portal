<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Force HTTPS for all URLs in production
        if (env('APP_ENV') === 'production' || env('FORCE_HTTPS', false)) {
            \URL::forceScheme('https');
        }

        // Register Resend Mail Transport
        $this->app->make('mail.manager')->extend('resend', function ($app) {
            return new \App\Mail\ResendTransport();
        });
    }
}

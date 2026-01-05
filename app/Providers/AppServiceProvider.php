<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Mail\Transports\BrevoTransport;

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

        // Register Brevo API transport if API key is set
        if (config('mail.mailers.brevo.api_key')) {
            Mail::extend('brevo', function (array $config) {
                return new BrevoTransport($config['api_key']);
            });
        }
    }
}

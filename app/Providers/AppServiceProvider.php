<?php

namespace App\Providers;

use App\Events\EmailVerificationRequested;
use App\Listeners\QueueEmailVerification;
use App\Events\PasswordResetRequested;
use App\Listeners\QueuePasswordReset;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);
        Event::listen(EmailVerificationRequested::class, QueueEmailVerification::class);
        Event::listen(PasswordResetRequested::class, QueuePasswordReset::class);

        RateLimiter::for('otp-email', function ($request) {
            $email = (string) ($request->email ?? 'guest');
            return [
                Limit::perMinute(3)->by($request->ip()),
                Limit::perMinute(5)->by($email),
            ];
        });

        RateLimiter::for('password-email', function ($request) {
            $email = (string) ($request->email ?? 'guest');
            return [
                Limit::perMinute(5)->by($request->ip()),
                Limit::perMinute(5)->by($email),
            ];
        });
    }
}

<?php

namespace App\Providers;

use App\Events\EmailVerificationRequested;
use App\Listeners\QueueEmailVerification;
use App\Events\PasswordResetRequested;
use App\Listeners\QueuePasswordReset;
use Illuminate\Support\Facades\Event;
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
    }
}

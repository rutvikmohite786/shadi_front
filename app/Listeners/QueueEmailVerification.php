<?php

namespace App\Listeners;

use App\Events\EmailVerificationRequested;
use App\Jobs\SendEmailVerificationJob;
use Illuminate\Contracts\Queue\ShouldQueue;

class QueueEmailVerification implements ShouldQueue
{
    public function handle(EmailVerificationRequested $event): void
    {
        SendEmailVerificationJob::dispatch($event->email, $event->otp);
    }
}


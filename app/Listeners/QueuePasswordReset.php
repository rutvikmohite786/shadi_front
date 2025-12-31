<?php

namespace App\Listeners;

use App\Events\PasswordResetRequested;
use App\Jobs\SendPasswordResetJob;
use Illuminate\Contracts\Queue\ShouldQueue;

class QueuePasswordReset implements ShouldQueue
{
    public function handle(PasswordResetRequested $event): void
    {
        SendPasswordResetJob::dispatch($event->email, $event->name, $event->token);
    }
}


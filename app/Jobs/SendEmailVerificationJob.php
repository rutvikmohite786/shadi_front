<?php

namespace App\Jobs;

use App\Mail\EmailVerificationOtp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailVerificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected string $email,
        protected string $otp,
        protected ?string $name = null
    ) {}

    public function handle(): void
    {
        $name = $this->name ?: $this->email;
        Mail::to($this->email)->send(new EmailVerificationOtp($name, $this->otp));
    }
}


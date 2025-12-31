<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerificationOtp extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        protected string $name,
        protected string $otp
    ) {}

    public function build(): self
    {
        return $this->subject('Verify your email')
            ->view('emails.verify-otp')
            ->with([
                'name' => $this->name,
                'otp' => $this->otp,
                'expiresInMinutes' => 15,
            ]);
    }
}


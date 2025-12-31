<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        protected string $name,
        protected string $token,
        protected string $email
    ) {}

    public function build(): self
    {
        $resetUrl = route('password.reset.form', ['token' => $this->token, 'email' => $this->email]);

        return $this->subject('Reset your password')
            ->view('emails.password-reset')
            ->with([
                'name' => $this->name,
                'resetUrl' => $resetUrl,
            ]);
    }
}


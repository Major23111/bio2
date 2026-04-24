<?php

namespace App\Mail\Auth;

use App\Models\Authorization\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

// Email sent when a customer requests to reset their forgotten password.
class PasswordResetEmail extends Mailable
{
    // Store the user and reset URL for the email template.
    public function __construct( public User $user, public string $resetUrl,  ) {
    }

    // Define the email subject and sender details.
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset your Biogenix account password',
        );
    }

    // Define which template file to use and pass the data to it.
    public function content(): Content
    {
        // Get the password reset link expiry time from Laravel config.
        $expiryMinutes = (int) config('auth.passwords.'.config('fortify.passwords').'.expire', 60);

        return new Content(
            view: 'email-template.auth.forgot-password-reset',
            with: [
                'user' => $this->user,
                'resetUrl' => $this->resetUrl,
                'expiryMinutes' => $expiryMinutes,
            ],
        );
    }
}

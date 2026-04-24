<?php

namespace App\Mail\Auth;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

// Email sent when a customer signs up and needs to verify their email with OTP.
class SignupOtpEmail extends Mailable
{
    // Store the OTP code and expiry time for the email template.
    public function __construct(
        public string $otpCode,
        public int $expiryMinutes,
    ) {
    }

    // Define the email subject and sender details.
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verify your email for Biogenix signup',
        );
    }

    // Define which template file to use and pass the data to it.
    public function content(): Content
    {
        return new Content(
            view: 'email-template.auth.signup-email-otp',
            with: [
                'otpCode' => $this->otpCode,
                'expiryMinutes' => $this->expiryMinutes,
            ],
        );
    }
}

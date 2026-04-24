<?php

namespace App\Contracts;

use Illuminate\Mail\Mailable;

// Interface that every email provider must follow.
// This makes it easy to add new providers like SendGrid or Mailgun later.
interface EmailProviderContract
{
    // Send an email using this provider.
    public function send(Mailable $email): void;
}

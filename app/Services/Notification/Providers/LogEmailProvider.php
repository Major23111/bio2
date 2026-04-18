<?php

namespace App\Services\Notification\Providers;

use App\Contracts\EmailProviderContract;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;

// Provider that logs emails instead of sending them.
// Use this in development or testing environments.
class LogEmailProvider implements EmailProviderContract
{
    // Log the email instead of sending it.
    public function send(Mailable $email): void
    {
        $envelope = $email->envelope();
        $recipient = $email->to[0] ?? [];
        $recipientEmail = is_array($recipient)
            ? ($recipient['address'] ?? 'unknown')
            : ($recipient->address ?? $recipient->email ?? 'unknown');

        // Log the email details for viewing in storage/logs.
        Log::channel('single')->info('Email notification logged for development.', [
            'provider' => 'log',
            'recipient' => $recipientEmail,
            'subject' => $envelope->subject,
        ]);
    }
}

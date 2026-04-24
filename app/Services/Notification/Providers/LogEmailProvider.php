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
        // Render the email first so Laravel prepares the body and attachments.
        $email->render();
        $envelope = $email->envelope();
        $recipient = $email->to[0] ?? [];
        $recipientEmail = is_array($recipient)
            ? ($recipient['address'] ?? 'unknown')
            : ($recipient->address ?? $recipient->email ?? 'unknown');
        $attachmentNames = collect($email->rawAttachments)
            ->pluck('name')
            ->filter()
            ->values()
            ->all();

        // Log the email details for viewing in storage/logs.
        Log::channel('single')->info('Email notification logged for development.', [
            'provider' => 'log',
            'recipient' => $recipientEmail,
            'subject' => $envelope->subject,
            'attachments' => $attachmentNames,
        ]);
    }
}

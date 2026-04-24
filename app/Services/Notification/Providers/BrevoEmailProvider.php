<?php

namespace App\Services\Notification\Providers;

use App\Contracts\EmailProviderContract;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

// Provider that sends emails through Brevo's email service.
class BrevoEmailProvider implements EmailProviderContract
{
    // Send an email using Brevo's API.
    public function send(Mailable $email): void
    {
        // Check that Brevo API key is configured before attempting to send.
        $apiKey = trim((string) config('common.email_notifications.brevo.api_key', ''));

        if ($apiKey === '') {
            throw new RuntimeException('Brevo API key is not configured for email notifications.');
        }

        // Build the email payload from the Mailable object.
        $payload = $this->buildBrevoPayload($email);

        // Call Brevo API to send the email.
        $response = $this->callBrevoApi($payload, $apiKey);

        // Check if Brevo accepted the email request.
        if (! $response->successful()) {
            Log::error('Brevo rejected the email notification request.', [
                'status_code' => $response->status(),
                'response_body' => $response->body(),
                'recipient' => $payload['to'][0]['email'] ?? 'unknown',
            ]);

            throw new RuntimeException('Brevo could not accept the email request.');
        }

        Log::info('Email sent successfully via Brevo.', [
            'recipient' => $payload['to'][0]['email'] ?? 'unknown',
            'subject' => $payload['subject'] ?? 'unknown',
        ]);
    }

    // Build a payload that Brevo API expects.
    private function buildBrevoPayload(Mailable $email): array
    {
        // Render the email first so Laravel prepares the body and attachments.
        $htmlContent = $email->render();
        $envelope = $email->envelope();

        // Get the recipient email and name from the Mailable.
        $recipient = $email->to[0] ?? [];
        $recipientEmail = is_array($recipient)
            ? ($recipient['address'] ?? null)
            : ($recipient->address ?? $recipient->email ?? null);
        $recipientName = is_array($recipient)
            ? ($recipient['name'] ?? '')
            : ($recipient->name ?? '');

        // Create plain text version by removing HTML tags.
        $textContent = trim(strip_tags($htmlContent));

        // Build the Brevo SMTP API payload.
        $payload = [
            'sender' => [
                'name' => (string) config('common.email_notifications.from_name', config('app.name')),
                'email' => (string) config('common.email_notifications.from_email', 'noreply@example.com'),
            ],
            'to' => [[
                'email' => $recipientEmail,
                'name' => $recipientName,
            ]],
            'subject' => $envelope->subject,
            'htmlContent' => $htmlContent,
            'textContent' => $textContent,
        ];

        // Add prepared raw attachments when the email includes them.
        if ($email->rawAttachments !== []) {
            $payload['attachment'] = $this->buildBrevoAttachments($email);
        }

        return $payload;
    }

    // Convert Laravel raw attachments into the Brevo attachment format.
    private function buildBrevoAttachments(Mailable $email): array
    {
        $attachments = [];

        foreach ($email->rawAttachments as $attachment) {
            $attachments[] = [
                'name' => $attachment['name'] ?? 'attachment.pdf',
                'content' => base64_encode((string) ($attachment['data'] ?? '')),
            ];
        }

        return $attachments;
    }

    // Call the Brevo SMTP email API.
    private function callBrevoApi(array $payload, string $apiKey): \Illuminate\Http\Client\Response
    {
        $baseUrl = rtrim((string) config('common.email_notifications.brevo.base_url', 'https://api.brevo.com/v3'), '/');
        $timeoutSeconds = (int) config('common.email_notifications.brevo.timeout_seconds', 15);

        // Create HTTP client with timeout and Brevo API key.
        $httpClient = Http::timeout($timeoutSeconds)
            ->withHeaders([
                'accept' => 'application/json',
                'api-key' => $apiKey,
                'content-type' => 'application/json',
            ]);

        // Apply SSL verification settings for local or remote environments.
        $httpClient = $this->applySslConfiguration($httpClient);

        // Make the request to Brevo's SMTP email endpoint.
        $response = $httpClient->post($baseUrl.'/smtp/email', $payload);

        return $response;
    }

    // Apply SSL verification settings based on configuration.
    private function applySslConfiguration($httpClient)
    {
        $verifySsl = filter_var(config('common.email_notifications.brevo.verify_ssl', true), FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
        $caBundlePath = trim((string) config('common.email_notifications.brevo.ca_bundle_path', ''));

        // Use a specific CA bundle file if configured.
        if ($caBundlePath !== '') {
            if (! is_file($caBundlePath)) {
                throw new RuntimeException("Brevo CA bundle file was not found at [{$caBundlePath}].");
            }

            Log::info('Brevo email client is using a configured CA bundle.', [
                'ca_bundle_path' => $caBundlePath,
            ]);

            return $httpClient->withOptions([
                'verify' => $caBundlePath,
            ]);
        }

        // Disable SSL verification if explicitly configured 
        if ($verifySsl === false) {
            Log::warning('Brevo email client SSL verification is disabled. Use only for local troubleshooting.', [
                'provider' => 'brevo',
            ]);

            return $httpClient->withOptions([
                'verify' => false,
            ]);
        }

        // Use normal SSL verification by default.
        return $httpClient;
    }
}

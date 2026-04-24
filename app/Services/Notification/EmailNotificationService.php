<?php

namespace App\Services\Notification;

use App\Contracts\EmailProviderContract;
use App\Mail\Auth\PasswordResetEmail;
use App\Mail\Auth\SignupOtpEmail;
use App\Mail\Order\OrderConfirmationEmail;
use App\Models\Authorization\User;
use App\Models\Order\Order;
use App\Services\Notification\Providers\BrevoEmailProvider;
use App\Services\Notification\Providers\LogEmailProvider;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

// Service that sends all email notifications using Laravel's Mail system.
// This is the central place where all emails are sent, making it easy to switch email providers.
class EmailNotificationService
{
    // Send signup OTP email to verify the customer's email address.
    public function sendSignupEmailOtp(string $email, string $otpCode, int $expiryMinutes): void
    {
        // Create the signup OTP email object.
        $emailMessage = new SignupOtpEmail($otpCode, $expiryMinutes);

        // Send the email to the customer.
        $this->sendEmail($email, 'Biogenix Customer', $emailMessage);
    }

    // Send password reset email with a secure link to reset the account password.
    public function sendForgotPasswordResetLink(User $user, string $resetUrl): void
    {
        // Create the password reset email object.
        $emailMessage = new PasswordResetEmail($user, $resetUrl);

        // Send the email to the customer.
        $this->sendEmail($user->email, $user->name, $emailMessage);
    }

    // Send order confirmation email after the customer successfully submits an order.
    public function sendOrderSubmittedConfirmation(User $user, Order $order): void
    {
        // Create the order confirmation email object.
        $emailMessage = new OrderConfirmationEmail($user, $order);

        // Send the email to the customer.
        $this->sendEmail($user->email, $user->name, $emailMessage);
    }

    // Send any email using the configured provider.
    // This is the main method that all notification methods use.
    private function sendEmail(string $toEmail, string $toName, $emailMessage): void
    {
        // Get the email message and add the recipient.
        $email = $emailMessage->to($toEmail, $toName);

        // Get the configured email provider (brevo or log).
        $provider = $this->getEmailProvider();

        // Send the email using the provider.
        $provider->send($email);
    }

    // Get the email provider instance based on configuration.
    private function getEmailProvider(): EmailProviderContract
    {
        $providerName = strtolower(trim((string) config('common.email_notifications.provider', 'log')));

        // Return the Brevo provider if configured.
        if ($providerName === 'brevo') {
            return new BrevoEmailProvider();
        }

        // Return the Log provider for development or as fallback.
        if ($providerName === 'log') {
            return new LogEmailProvider();
        }

        // Fail clearly if an unknown provider is configured.
        throw new RuntimeException("Unsupported email provider [{$providerName}] configured for notifications.");
    }
}

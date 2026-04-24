<?php

namespace App\Mail\Order;

use App\Models\Authorization\User;
use App\Models\Order\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

// Email sent when a customer successfully submits an order.
class OrderConfirmationEmail extends Mailable
{
    // Store the customer and their order for the email template.
    public function __construct(  public User $user,  public Order $order, ) {
    }

    // Define the email subject and sender details.
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Biogenix order #'.$this->order->id.' has been submitted',
        );
    }

    // Define which template file to use and pass the data to it.
    public function content(): Content
    {
        // Load the order items so they appear in the email.
        $orderWithItems = $this->order->loadMissing([
            'items' => fn ($builder) => $builder->orderBy('sort_order')->orderBy('id'),
        ]);

        return new Content(
            view: 'email-template.order.order-submitted',
            with: [
                'user' => $this->user,
                'order' => $orderWithItems,
            ],
        );
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'New Order Placed',
            'message' => 'Order #' . $this->order->id . ' has been placed by ' . ($this->order->placedByUser->name ?? 'Customer'),
            'url' => route('admin.orders.view', ['orderId' => $this->order->id]),
            'type' => 'order'
        ];
    }
}

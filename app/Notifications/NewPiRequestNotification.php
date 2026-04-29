<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewPiRequestNotification extends Notification
{
    use Queueable;

    public $proforma;

    public function __construct($proforma)
    {
        $this->proforma = $proforma;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'New PI Request',
            'message' => 'PI Request ' . $this->proforma->pi_number . ' has been submitted.',
            'url' => route('admin.pi-quotation.edit', ['proformaId' => $this->proforma->id]),
            'type' => 'pi_request'
        ];
    }
}

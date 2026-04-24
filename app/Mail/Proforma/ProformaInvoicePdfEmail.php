<?php

namespace App\Mail\Proforma;

use App\Models\Proforma\ProformaInvoice;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ProformaInvoicePdfEmail extends Mailable
{
    public function __construct(
        public ProformaInvoice $proforma,
        public string $pdfContent,
        public string $pdfFileName,
    ) {
    }

    // Define the PI email subject.
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Proforma Invoice '.$this->proforma->pi_number.' from Biogenix',
        );
    }

    // Load the PI email body template.
    public function content(): Content
    {
        return new Content(
            view: 'email-template.proforma.proforma-invoice-pdf',
            with: [
                'proforma' => $this->proforma,
                'customerName' => $this->proforma->target_name ?: 'Customer',
            ],
        );
    }

    // Attach the PI PDF file to the email.
    public function attachments(): array
    {
        return [
            Attachment::fromData(
                fn (): string => $this->pdfContent,
                $this->pdfFileName,
            )->withMime('application/pdf'),
        ];
    }
}

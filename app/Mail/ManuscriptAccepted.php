<?php

namespace App\Mail;

use App\Models\Invoice;
use App\Models\Prospect;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class ManuscriptAccepted extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $invoice;
    public $prospect;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->prospect = $invoice->prospect;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Congratulations! Manuscript Accepted - Invoice ' . $this->invoice->invoice_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.manuscript_accepted',
            with: [
                'checkoutLink' => route('payments.checkout', ['invoice' => $this->invoice->id]),
            ]
        );
    }

    public function attachments(): array
    {
        $pdf = Pdf::loadView('admin.invoices.pdf', ['invoice' => $this->invoice]);
        
        return [
            Attachment::fromData(fn () => $pdf->output(), 'Invoice-' . $this->invoice->invoice_number . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}

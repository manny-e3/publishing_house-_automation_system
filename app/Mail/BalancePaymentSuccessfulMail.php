<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Mail\Mailables\Attachment;

class BalancePaymentSuccessfulMail extends Mailable implements ShouldQueue
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
            subject: 'Payment Confirmation & Receipt',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.balance_payment_success',
        );
    }

    public function attachments(): array
    {
        $pdf = Pdf::loadView('admin.receipts.pdf', ['invoice' => $this->invoice]);
        
        return [
            Attachment::fromData(fn () => $pdf->output(), 'RECEIPT-' . $this->invoice->payment_reference . '.pdf')
                    ->withMime('application/pdf'),
        ];
    }
}

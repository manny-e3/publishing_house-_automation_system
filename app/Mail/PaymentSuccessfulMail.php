<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentSuccessfulMail extends Mailable
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
            subject: 'Payment Confirmed - Project Activated!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment_success',
        );
    }
}

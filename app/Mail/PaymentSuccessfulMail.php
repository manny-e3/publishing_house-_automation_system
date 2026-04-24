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
use Illuminate\Support\Facades\Storage;

class PaymentSuccessfulMail extends Mailable implements ShouldQueue
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

    public function attachments(): array
    {
        $attachments = [];

        // 1. Attach Receipt
        $receiptPdf = Pdf::loadView('admin.receipts.pdf', ['invoice' => $this->invoice]);
        $attachments[] = Attachment::fromData(fn () => $receiptPdf->output(), 'RECEIPT-' . $this->invoice->payment_reference . '.pdf')
                    ->withMime('application/pdf');
        
        // 2. Attach Signed Contract (if exists)
        $contract = $this->prospect->contracts()->where('status', 'signed')->latest()->first();
        
        if (!$contract && $this->prospect->project) {
            $contract = $this->prospect->project->latestContract;
        }

        if ($contract && Storage::disk('local')->exists($contract->document_path)) {
            $attachments[] = Attachment::fromStorage($contract->document_path)
                ->as('SIGNED_AGREEMENT.pdf')
                ->withMime('application/pdf');
        }

        return $attachments;
    }
}

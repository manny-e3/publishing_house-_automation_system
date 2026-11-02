<?php

namespace App\Mail;

use App\Models\Prospect;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeadRecoveryFinalWarningMail extends Mailable
{
    use SerializesModels;

    public $prospect;

    public function __construct(Prospect $prospect)
    {
        $this->prospect = $prospect;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Action Required: Editorial production reservation expiring for '{$this->prospect->book_title}'",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.lead_recovery_final_warning',
            with: [
                'prospect' => $this->prospect,
                'resumeUrl' => url('/enquiry?resume=' . $this->prospect->id),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}


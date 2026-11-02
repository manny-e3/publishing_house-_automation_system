<?php

namespace App\Mail;

use App\Models\Prospect;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeadRecoveryNudgeMail extends Mailable
{
    use SerializesModels;

    public $prospect;
    public $tone;

    public function __construct(Prospect $prospect, string $tone = 'casual')
    {
        $this->prospect = $prospect;
        $this->tone = $tone;
    }

    public function envelope(): Envelope
    {
        $firstName = explode(' ', trim($this->prospect->name))[0] ?? 'Author';

        if ($this->tone === 'casual') {
            $subject = "Hey {$firstName}, your story is waiting to be told! 📖 Let's bring it to life";
        } else {
            $subject = "The Curated Archive — Follow-up on your publishing proposal for '{$this->prospect->book_title}'";
        }

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.lead_recovery_nudge',
            with: [
                'prospect' => $this->prospect,
                'tone' => $this->tone,
                'resumeUrl' => url('/enquiry?resume=' . $this->prospect->id),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}


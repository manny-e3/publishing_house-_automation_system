<?php

namespace App\Mail;

use App\Models\Prospect;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AuthorAcknowledgment extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $prospect;

    public function __construct(Prospect $prospect)
    {
        $this->prospect = $prospect;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Manuscript Received: ' . $this->prospect->book_title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.author_acknowledgment',
        );
    }
}

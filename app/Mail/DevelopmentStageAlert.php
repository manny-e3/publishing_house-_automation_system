<?php

namespace App\Mail;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DevelopmentStageAlert extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $project;
    public $stage;
    public $actionRequired;

    public function __construct(Project $project, $stage)
    {
        $this->project = $project;
        $this->stage = $stage;
        
        $actions = [
            'editing' => 'Please assign an editor to review this manuscript.',
            'formatting' => 'Internal typesetting and layout verification required.',
            'cover_design' => 'Coordinate with the design team for cover options.',
            'printing' => 'Generate print files and coordinate with suppliers.',
            'distribution' => 'Verify stock delivery to retail channels.',
        ];
        
        $this->actionRequired = $actions[$stage] ?? 'Please review the project status in the dashboard.';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'PIPELINE ALERT: ' . ucwords(str_replace('_', ' ', $this->stage)) . ' - ' . $this->project->prospect->book_title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.internal_stage_alert',
        );
    }
}

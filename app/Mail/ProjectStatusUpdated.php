<?php

namespace App\Mail;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProjectStatusUpdated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $project;
    public $newStatus;
    public $statusMessages = [
        'editing' => 'Your manuscript is now in the **Editing** phase. Our editors are carefully reviewing and polishing your work to ensure it meets the highest standards.',
        'cover_design' => 'Exciting news! Your book has moved to **Cover Design**. Our creative team is now working on a stunning visual identity for your work.',
        'printing' => 'We are heading to the presses! Your book is now in the **Printing** stage, moving from digital files to physical copies.',
        'distribution' => 'Your book is reaching the world! It has moved to the **Distribution** phase and will be available across our retail networks shortly.',
        'completed' => 'Congratulations! Your publishing journey with The Curated Archive is **Complete**. Your book is now live and distributed.'
    ];

    public function __construct(Project $project, $newStatus)
    {
        $this->project = $project;
        $this->newStatus = $newStatus;
    }

    public function build()
    {
        $friendlyStatus = ucwords(str_replace('_', ' ', $this->newStatus));
        $messageBody = $this->statusMessages[$this->newStatus] ?? 'Your project status has been updated to ' . $friendlyStatus;

        return $this->subject('Update on your book: ' . $this->project->prospect->book_title)
                    ->view('emails.project_status_updated')
                    ->with([
                        'statusName' => $friendlyStatus,
                        'messageBody' => $messageBody
                    ]);
    }
}

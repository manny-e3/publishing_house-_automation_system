<?php

namespace App\Events;

use App\Models\Project;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProjectStageUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $project;
    public $stage;

    public function __construct(Project $project, string $stage)
    {
        $this->project = $project;
        $this->stage = $stage;
    }
}

<?php

namespace App\Events;

use App\Models\Prospect;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProspectSubmitted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $prospect;

    public function __construct(Prospect $prospect)
    {
        $this->prospect = $prospect;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\Invoice;
use App\Mail\ProjectStatusUpdated;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AuditProjects extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:audit-projects';

    /**
     * The console command description.
     */
    protected $description = 'Audit projects for overdue stages and stalled payments.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->checkOverdueStages();
        $this->info('Project audit completed.');
    }

    private function checkOverdueStages()
    {
        // Find projects in the same stage for more than 7 days (stalled)
        $stalledProjects = Project::where('updated_at', '<', now()->subDays(7))
            ->where('status', '!=', 'completed')
            ->get();

        foreach ($stalledProjects as $project) {
            $this->warn("Project #{$project->id} is stalled in '{$project->status}' stage.");
            // We could notify Admin here
            Log::warning("Automation: Project #{$project->id} ({$project->prospect->book_title}) has been stalled for 7+ days.");
        }
    }
}

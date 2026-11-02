<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AbandonedLeadRecoveryService;

class ProcessAbandonedLeads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:recovery-scheduler {--dry-run : Simulate run without sending emails or updating records}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process abandoned Osisi Hybrid leads for 2-hour nudges and 24-hour editorial slot final warnings';

    protected $recoveryService;

    public function __construct(AbandonedLeadRecoveryService $recoveryService)
    {
        parent::__construct();
        $this->recoveryService = $recoveryService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $this->info("------------------------------------------------------------");
        $this->info(" Running Osisi Abandoned Cart Recovery Scheduler" . ($dryRun ? " [DRY RUN]" : ""));
        $this->info("------------------------------------------------------------");

        $results = $this->recoveryService->processAbandonedLeads($dryRun);

        $this->line("Total leads scanned:       <fg=yellow>{$results['total_scanned']}</>");
        $this->line("2-Hour Nudges dispatched:  <fg=green>{$results['nudges_dispatched']}</>");
        $this->line("24-Hour Warnings flagged:  <fg=red>{$results['final_warnings_dispatched']}</>");
        $this->line("Skipped / Not eligible:    <fg=gray>{$results['skipped_count']}</>");

        if (!empty($results['actions'])) {
            $this->newLine();
            $this->table(
                ['ID', 'Author Name', 'Email', 'Book Title', 'Source', 'Action Taken', 'Hours Elapsed'],
                array_map(function ($item) {
                    return [
                        $item['prospect_id'],
                        $item['name'],
                        $item['email'],
                        $item['title'],
                        $item['source'] ?? 'direct',
                        $item['action'],
                        $item['hours_elapsed'] . 'h',
                    ];
                }, $results['actions'])
            );
        } else {
            $this->comment("\nNo pending abandoned leads required recovery actions.");
        }

        $this->info("\nRecovery scheduler completed successfully.");
        return Command::SUCCESS;
    }
}


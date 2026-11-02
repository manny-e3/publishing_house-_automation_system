<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AbandonedLeadRecoveryService;
use Illuminate\Support\Facades\Log;

class LeadRecoveryController extends Controller
{
    protected $recoveryService;

    public function __construct(AbandonedLeadRecoveryService $recoveryService)
    {
        $this->recoveryService = $recoveryService;
    }

    /**
     * Hourly Cron Endpoint for Abandoned Lead Recovery
     * POST /api/cron/recovery-scheduler
     * Protected by X-Cron-Key header
     */
    public function runScheduler(Request $request)
    {
        $configuredKey = config('services.cron.key') ?: env('CRON_SECRET_KEY', 'osisi-secret-recovery-key');
        $providedKey = $request->header('X-Cron-Key') ?? ($request->query('cron_key') ?? $request->input('cron_key'));

        if (empty($providedKey) || !hash_equals((string)$configuredKey, (string)$providedKey)) {
            Log::warning('Unauthorized Cron Attempt on /api/cron/recovery-scheduler', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized cron request. Valid X-Cron-Key required.',
            ], 401);
        }

        $dryRun = $request->boolean('dry_run', false);

        try {
            $summary = $this->recoveryService->processAbandonedLeads($dryRun);

            return response()->json([
                'status' => 'success',
                'message' => 'Abandoned cart recovery scheduler executed successfully.',
                'data' => $summary,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Recovery scheduler error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Internal error during recovery execution: ' . $e->getMessage(),
            ], 500);
        }
    }
}


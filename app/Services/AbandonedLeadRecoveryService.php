<?php

namespace App\Services;

use App\Models\Prospect;
use App\Models\AuditLog;
use App\Enums\WorkflowStatus;
use App\Enums\PublishingModel;
use App\Mail\LeadRecoveryNudgeMail;
use App\Mail\LeadRecoveryFinalWarningMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AbandonedLeadRecoveryService
{
    /**
     * Process all abandoned Osisi Hybrid leads for 2-hour nudges and 24-hour final warnings.
     *
     * @param bool $dryRun If true, simulate without sending emails or writing changes.
     * @return array Summary of operations performed.
     */
    public function processAbandonedLeads(bool $dryRun = false): array
    {
        $now = Carbon::now();

        // 1. Fetch eligible abandoned leads
        $leads = Prospect::where(function ($q) {
                $q->where('determined_model', PublishingModel::HybridOsisi->value)
                  ->orWhere('determined_model', 'hybrid_osisi');
            })
            ->where('payment_status', 'unpaid')
            ->where(function ($q) {
                $q->where('status', WorkflowStatus::LeadCaptured->value)
                  ->orWhere('status', 'lead_captured');
            })
            ->get();

        $summary = [
            'total_scanned' => $leads->count(),
            'nudges_dispatched' => 0,
            'final_warnings_dispatched' => 0,
            'skipped_count' => 0,
            'dry_run' => $dryRun,
            'processed_at' => $now->toIso8601String(),
            'actions' => [],
        ];

        foreach ($leads as $lead) {
            $metadata = $lead->metadata_file ?? [];
            $createdAt = Carbon::parse($lead->created_at);
            $hoursElapsed = $createdAt->diffInHours($now);

            $actionTaken = 'none';
            $tone = $this->determineTone($lead->marketing_source);

            // =========================================================================
            // Stage 2: 24-Hour Final Warning (Slot Expiry Alert)
            // =========================================================================
            if ($hoursElapsed >= 24 && empty($metadata['recovery_final_warning_sent'])) {
                $actionTaken = '24hr_final_warning';

                if (!$dryRun) {
                    // Send Email
                    try {
                        Mail::to($lead->email)->send(new LeadRecoveryFinalWarningMail($lead));
                    } catch (\Exception $e) {
                        Log::warning("Could not send 24hr final warning to {$lead->email}: " . $e->getMessage());
                    }

                    // Update JSON state & bright-red alert flags for Back-Office boards
                    $metadata['recovery_final_warning_sent'] = true;
                    $metadata['final_warning_sent_at'] = $now->toIso8601String();
                    $metadata['pending_followup'] = true;
                    $metadata['flag_red'] = true;
                    $metadata['abandoned_critical'] = true;
                    $metadata['recovery_stage'] = '24hr_warning';
                    $metadata['last_recovery_attempt'] = $now->toIso8601String();

                    $lead->update([
                        'metadata_file' => $metadata,
                    ]);

                    // Audit trail
                    AuditLog::create([
                        'user_id' => $lead->user_id ?? 1,
                        'action' => 'recovery_24hr_warning_sent',
                        'model_type' => Prospect::class,
                        'model_id' => $lead->id,
                        'changes' => [
                            'email' => $lead->email,
                            'hours_elapsed' => $hoursElapsed,
                            'flag_red' => true,
                            'pending_followup' => true,
                        ],
                        'ip_address' => '127.0.0.1',
                    ]);
                }

                $summary['final_warnings_dispatched']++;
                $summary['actions'][] = [
                    'prospect_id' => $lead->id,
                    'name' => $lead->name,
                    'email' => $lead->email,
                    'title' => $lead->book_title,
                    'source' => $lead->marketing_source,
                    'action' => '24hr_final_warning',
                    'hours_elapsed' => $hoursElapsed,
                    'flagged_red' => true,
                ];
                continue;
            }

            // =========================================================================
            // Stage 1: 2-Hour Nudge (Adaptive Channel Tone)
            // =========================================================================
            if ($hoursElapsed >= 2 && empty($metadata['recovery_nudge_sent'])) {
                $actionTaken = '2hr_nudge';

                if (!$dryRun) {
                    // Send Email
                    try {
                        Mail::to($lead->email)->send(new LeadRecoveryNudgeMail($lead, $tone));
                    } catch (\Exception $e) {
                        Log::warning("Could not send 2hr nudge to {$lead->email}: " . $e->getMessage());
                    }

                    // Update JSON state
                    $metadata['recovery_nudge_sent'] = true;
                    $metadata['nudge_sent_at'] = $now->toIso8601String();
                    $metadata['nudge_tone'] = $tone;
                    $metadata['recovery_stage'] = '2hr_nudge';
                    $metadata['last_recovery_attempt'] = $now->toIso8601String();

                    $lead->update([
                        'metadata_file' => $metadata,
                    ]);

                    // Audit trail
                    AuditLog::create([
                        'user_id' => $lead->user_id ?? 1,
                        'action' => 'recovery_2hr_nudge_sent',
                        'model_type' => Prospect::class,
                        'model_id' => $lead->id,
                        'changes' => [
                            'email' => $lead->email,
                            'hours_elapsed' => $hoursElapsed,
                            'tone' => $tone,
                            'source' => $lead->marketing_source,
                        ],
                        'ip_address' => '127.0.0.1',
                    ]);
                }

                $summary['nudges_dispatched']++;
                $summary['actions'][] = [
                    'prospect_id' => $lead->id,
                    'name' => $lead->name,
                    'email' => $lead->email,
                    'title' => $lead->book_title,
                    'source' => $lead->marketing_source,
                    'tone' => $tone,
                    'action' => '2hr_nudge',
                    'hours_elapsed' => $hoursElapsed,
                ];
                continue;
            }

            $summary['skipped_count']++;
        }

        return $summary;
    }

    /**
     * Determine adaptive email tone based on marketing attribution source.
     * Instagram & TikTok: Casual, encouraging, creator-centric.
     * LinkedIn & Google: Formal, structured, institutional.
     */
    public function determineTone(?string $marketingSource): string
    {
        $source = strtolower(trim((string)$marketingSource));
        
        $casualSources = ['instagram', 'tiktok', 'social', 'facebook', 'youtube', 'threads', 'x'];

        if (in_array($source, $casualSources)) {
            return 'casual';
        }

        return 'formal';
    }
}


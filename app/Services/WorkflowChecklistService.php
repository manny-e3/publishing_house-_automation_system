<?php

namespace App\Services;

use App\Models\Prospect;
use App\Models\AuditLog;
use App\Enums\ManuscriptRating;
use App\Enums\DesignStatus;
use App\Enums\PublishingModel;
use App\Enums\WorkflowStatus;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class WorkflowChecklistService
{
    /**
     * Acquisition Checklist Definitions
     */
    public const ACQUISITION_TASKS = [
        'basic_content_vetting' => 'Basic Content Vetting',
        'author_background_check' => 'Author Background Check',
        'application_for_isbn' => 'Application for ISBN',
        'log_acquired_isbn' => 'Log Acquired ISBN into Metadata Registry',
    ];

    /**
     * Editorial Checklist Definitions
     */
    public const EDITORIAL_TASKS = [
        'first_compulsory_editing' => '1st Compulsory Editing',
        'second_compulsory_editing' => '2nd Compulsory Editing',
        'professional_proofreading' => 'Professional Proofreading',
        'author_final_reading' => 'Author Final Reading',
        'dummy_copy_editing_1' => 'Dummy Copy Editing 1',
        'dummy_copy_editing_2' => 'Dummy Copy Editing 2',
        'dummy_copy_editing_3' => 'Dummy Copy Editing 3',
    ];

    /**
     * Toggle or set the state of a checklist task with audit trail.
     * Enforces Gate 2 Regression Logic if dummy copy edits are modified during or after design.
     */
    public function setTaskStatus(Prospect $prospect, string $taskKey, bool $completed, ?int $userId = null): array
    {
        $checklist = $prospect->checklist_progress ?? [];
        $wasCompleted = !empty($checklist[$taskKey]['completed']);

        $checklist[$taskKey] = [
            'completed' => $completed,
            'timestamp' => now()->toIso8601String(),
            'user_id' => $userId ?? auth()->id(),
        ];

        $metadata = $prospect->metadata_file ?? [];
        $regressionTriggered = false;

        // =========================================================================
        // Gate 2: Regression Guard
        // If Dummy Copy Editing 2 or 3 is modified/unticked or re-opened after design
        // has started, automatically drop design_phase to 'in_progress', append
        // _EDIT_REVISION to metadata, and set regression alert banner flag.
        // =========================================================================
        if (in_array($taskKey, ['dummy_copy_editing_2', 'dummy_copy_editing_3']) && !$completed) {
            if ($prospect->design_phase && $prospect->design_phase !== DesignStatus::Queued) {
                $prospect->design_phase = DesignStatus::InProgress;
                $metadata['regression_alert'] = true;
                $metadata['regression_reason'] = "Editorial revision reopened during {$taskKey}";
                $metadata['regression_timestamp'] = now()->toIso8601String();
                $metadata['revision_tag'] = '_EDIT_REVISION';
                $regressionTriggered = true;

                AuditLog::create([
                    'user_id' => $userId ?? auth()->id() ?? 1,
                    'action' => 'gate_2_regression_guard_triggered',
                    'model_type' => Prospect::class,
                    'model_id' => $prospect->id,
                    'changes' => [
                        'task' => $taskKey,
                        'design_phase' => DesignStatus::InProgress->value,
                        'revision_tag' => '_EDIT_REVISION',
                    ],
                    'ip_address' => request()->ip() ?? '127.0.0.1',
                ]);
            }
        }

        $prospect->checklist_progress = $checklist;
        $prospect->metadata_file = $metadata;
        $prospect->save();

        AuditLog::create([
            'user_id' => $userId ?? auth()->id() ?? 1,
            'action' => 'checklist_task_updated',
            'model_type' => Prospect::class,
            'model_id' => $prospect->id,
            'changes' => [
                'task_key' => $taskKey,
                'completed' => $completed,
                'regression_triggered' => $regressionTriggered,
            ],
            'ip_address' => request()->ip() ?? '127.0.0.1',
        ]);

        return [
            'task_key' => $taskKey,
            'completed' => $completed,
            'gate_1_unlocked' => $this->isGate1Cleared($prospect),
            'regression_triggered' => $regressionTriggered,
        ];
    }

    /**
     * Gate 1 Check:
     * Design tools & asset dropzones are LOCKED until Editorial completes 100% of Dummy Copy Editing 3.
     */
    public function isGate1Cleared(Prospect $prospect): bool
    {
        $checklist = $prospect->checklist_progress ?? [];
        return !empty($checklist['dummy_copy_editing_3']['completed']);
    }

    /**
     * Rate manuscript quality and handle Osisi Hybrid -> Traditional escalation trigger.
     */
    public function updateRating(Prospect $prospect, string $ratingValue, ?string $notes = null, ?int $userId = null): void
    {
        $rating = ManuscriptRating::tryFrom($ratingValue) ?? ManuscriptRating::Promising;
        $prospect->manuscript_rating = $rating;

        $metadata = $prospect->metadata_file ?? [];
        $isOsisi = $prospect->determined_model === PublishingModel::HybridOsisi || $prospect->determined_model === 'hybrid_osisi';

        // Escalation Trigger: If Osisi Hybrid project is scored 'Excellent', notify Executive Board
        if ($isOsisi && $rating === ManuscriptRating::Excellent) {
            $metadata['recommended_for_traditional'] = true;
            $metadata['traditional_recommendation_date'] = now()->toIso8601String();
            $metadata['escalation_notes'] = $notes ?: 'Manuscript scored EXCELLENT by Editorial. Qualified for full Traditional publishing contract switch.';

            AuditLog::create([
                'user_id' => $userId ?? auth()->id() ?? 1,
                'action' => 'osisi_excellent_escalated_to_board',
                'model_type' => Prospect::class,
                'model_id' => $prospect->id,
                'changes' => [
                    'title' => $prospect->book_title,
                    'author' => $prospect->name,
                    'rating' => 'excellent',
                    'offer_traditional_contract' => true,
                ],
                'ip_address' => request()->ip() ?? '127.0.0.1',
            ]);
        }

        if ($notes) {
            $metadata['editorial_review_notes'] = $notes;
        }

        $prospect->metadata_file = $metadata;
        $prospect->save();

        AuditLog::create([
            'user_id' => $userId ?? auth()->id() ?? 1,
            'action' => 'manuscript_rating_updated',
            'model_type' => Prospect::class,
            'model_id' => $prospect->id,
            'changes' => [
                'rating' => $rating->value,
                'notes' => $notes,
            ],
            'ip_address' => request()->ip() ?? '127.0.0.1',
        ]);
    }

    /**
     * Compute progress percentage for a given task definition array.
     */
    public function calculateProgress(Prospect $prospect, array $taskDefinitions): array
    {
        $checklist = $prospect->checklist_progress ?? [];
        $total = count($taskDefinitions);
        $completedCount = 0;

        foreach (array_keys($taskDefinitions) as $key) {
            if (!empty($checklist[$key]['completed'])) {
                $completedCount++;
            }
        }

        $percentage = $total > 0 ? round(($completedCount / $total) * 100) : 0;

        return [
            'total' => $total,
            'completed' => $completedCount,
            'percentage' => $percentage,
            'is_complete' => $completedCount === $total,
        ];
    }
}


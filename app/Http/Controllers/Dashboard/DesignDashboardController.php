<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Prospect;
use App\Services\WorkflowChecklistService;
use App\Enums\DesignStatus;
use App\Enums\WorkflowStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DesignDashboardController extends Controller
{
    protected $checklistService;

    public function __construct(WorkflowChecklistService $checklistService)
    {
        $this->checklistService = $checklistService;
    }

    public function index(Request $request)
    {
        $projects = Prospect::whereIn('status', [
                WorkflowStatus::InEditing->value,
                WorkflowStatus::InDesign->value,
                WorkflowStatus::ReadyForPrint->value,
                'in_editing', 'in_design', 'ready_for_print', 'project_active'
            ])
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('dashboards.design', compact('projects'));
    }

    public function show(Prospect $prospect)
    {
        $gate1Cleared = $this->checklistService->isGate1Cleared($prospect);
        $metadata = $prospect->metadata_file ?? [];
        $regressionAlert = !empty($metadata['regression_alert']);
        $annotations = $metadata['proof_annotations'] ?? [];

        return view('dashboards.design_detail', compact(
            'prospect',
            'gate1Cleared',
            'regressionAlert',
            'annotations'
        ));
    }

    /**
     * Upload one of the 4 target production formats.
     * Enforces Gate 1: Cannot upload unless Gate 1 cleared.
     */
    public function uploadAsset(Request $request, Prospect $prospect)
    {
        if (!$this->checklistService->isGate1Cleared($prospect)) {
            return redirect()->back()->with('error', 'Gate 1 Violation: Cannot upload production assets until Editorial completes 100% of Dummy Copy Editing 3.');
        }

        $request->validate([
            'asset_type' => 'required|in:epub,ebook_pdf,print_ready_pdf,amazon_kdp,cover_image',
            'file' => 'required|file|max:102400', // 100MB max
        ]);

        $file = $request->file('file');
        $assetType = $request->asset_type;
        $path = $file->store("production/{$prospect->id}", 'public');

        $url = Storage::url($path);

        switch ($assetType) {
            case 'epub':
                $prospect->epub_file_url = $url;
                break;
            case 'ebook_pdf':
                $prospect->ebook_pdf_url = $url;
                break;
            case 'print_ready_pdf':
                $prospect->print_ready_pdf_url = $url;
                break;
            case 'amazon_kdp':
                $prospect->amazon_kdp_ready_url = $url;
                break;
            case 'cover_image':
                $prospect->cover_design_path = $url;
                break;
        }

        // If all 4 key digital assets are present, advance to ready_for_print
        if ($prospect->epub_file_url && $prospect->ebook_pdf_url && $prospect->print_ready_pdf_url && $prospect->amazon_kdp_ready_url) {
            $prospect->design_phase = DesignStatus::Approved;
            $prospect->status = WorkflowStatus::ReadyForPrint->value;
        } else {
            $prospect->design_phase = DesignStatus::InProgress;
        }

        $prospect->save();

        \App\Models\AuditLog::record('design_asset_uploaded', $prospect, [
            'asset_type' => $assetType,
            'book_title' => $prospect->book_title,
            'url' => $url,
        ]);

        return redirect()->back()->with('success', "Production asset '{$assetType}' successfully uploaded and registered.");
    }

    /**
     * Add point-by-point timestamped annotation to design proofs
     */
    public function addAnnotation(Request $request, Prospect $prospect)
    {
        $request->validate([
            'note' => 'required|string|max:1000',
            'page_number' => 'nullable|integer|min:1',
            'format' => 'nullable|string',
        ]);

        $metadata = $prospect->metadata_file ?? [];
        $annotations = $metadata['proof_annotations'] ?? [];

        $annotations[] = [
            'id' => uniqid('note_'),
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'role' => auth()->user()->getRoleNames()->first() ?? 'design',
            'note' => $request->note,
            'page_number' => $request->page_number,
            'format' => $request->format ?? 'general',
            'created_at' => now()->toIso8601String(),
        ];

        $metadata['proof_annotations'] = $annotations;
        $prospect->metadata_file = $metadata;
        $prospect->save();

        \App\Models\AuditLog::record('proof_annotation_pinned', $prospect, [
            'book_title' => $prospect->book_title,
            'format' => $request->format ?? 'general',
            'page_number' => $request->page_number,
            'note_excerpt' => \Illuminate\Support\Str::limit($request->note, 100),
        ]);

        return redirect()->back()->with('success', 'Design proof annotation recorded.');
    }

    /**
     * Update the active design production phase manually.
     */
    public function updatePhase(Request $request, Prospect $prospect)
    {
        $request->validate([
            'design_phase' => 'required|string|in:queued,in_progress,cover_pending_review,interior_pending_review,approved',
        ]);

        $prospect->design_phase = $request->design_phase;

        if ($request->design_phase === 'approved') {
            $prospect->status = WorkflowStatus::ReadyForPrint->value;
        } elseif ($prospect->status !== WorkflowStatus::InDesign->value && $request->design_phase !== 'approved') {
            $prospect->status = WorkflowStatus::InDesign->value;
        }

        $prospect->save();

        return redirect()->back()->with('success', 'Design phase successfully updated to ' . ucfirst(str_replace('_', ' ', $request->design_phase)) . '.');
    }

    /**
     * Acknowledge Gate 2 regression and clear active alert
     */
    public function acknowledgeRegression(Prospect $prospect)
    {
        $metadata = $prospect->metadata_file ?? [];
        $metadata['regression_alert'] = false;
        $metadata['regression_acknowledged_at'] = now()->toIso8601String();
        $metadata['regression_acknowledged_by'] = auth()->id();

        $prospect->metadata_file = $metadata;
        $prospect->save();

        return redirect()->back()->with('success', 'Regression notice acknowledged. Workspace active.');
    }
}


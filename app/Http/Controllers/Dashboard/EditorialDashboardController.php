<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Prospect;
use App\Services\WorkflowChecklistService;
use App\Enums\WorkflowStatus;
use App\Enums\ManuscriptRating;
use App\Enums\DesignStatus;
use Illuminate\Http\Request;

class EditorialDashboardController extends Controller
{
    protected $checklistService;

    public function __construct(WorkflowChecklistService $checklistService)
    {
        $this->checklistService = $checklistService;
    }

    public function index(Request $request)
    {
        $manuscripts = Prospect::whereIn('status', [
                WorkflowStatus::Submitted->value,
                WorkflowStatus::UnderReview->value,
                WorkflowStatus::InEditing->value,
                'submitted', 'under_review', 'in_editing', 'contract_pending', 'project_active'
            ])
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        $editorialTasks = WorkflowChecklistService::EDITORIAL_TASKS;

        return view('dashboards.editorial', compact('manuscripts', 'editorialTasks'));
    }

    public function show(Prospect $prospect)
    {
        $editorialTasks = WorkflowChecklistService::EDITORIAL_TASKS;
        $progress = $this->checklistService->calculateProgress($prospect, $editorialTasks);
        $gate1Cleared = $this->checklistService->isGate1Cleared($prospect);
        $regressionAlert = !empty($prospect->metadata_file['regression_alert']);

        return view('dashboards.editorial_detail', compact(
            'prospect',
            'editorialTasks',
            'progress',
            'gate1Cleared',
            'regressionAlert'
        ));
    }

    public function toggleTask(Request $request, Prospect $prospect)
    {
        $taskKey = $request->input('task_key');
        $completed = $request->boolean('completed');

        if (!array_key_exists($taskKey, WorkflowChecklistService::EDITORIAL_TASKS)) {
            return response()->json(['error' => 'Invalid editorial task key'], 400);
        }

        $result = $this->checklistService->setTaskStatus($prospect, $taskKey, $completed);
        
        // If dummy copy editing 3 is checked, promote status to in_design
        if ($taskKey === 'dummy_copy_editing_3' && $completed) {
            $prospect->status = WorkflowStatus::InDesign->value;
            $prospect->design_phase = DesignStatus::Queued;
            $prospect->save();
        }

        return response()->json($result);
    }

    public function updateRating(Request $request, Prospect $prospect)
    {
        $request->validate([
            'rating' => 'required|string|in:excellent,promising,some_work_required,poor',
            'notes' => 'nullable|string|max:2000',
        ]);

        $this->checklistService->updateRating($prospect, $request->rating, $request->notes);

        $message = "Manuscript rating updated to: " . ucfirst($request->rating);
        if ($request->rating === 'excellent' && ($prospect->determined_model?->value === 'hybrid_osisi' || $prospect->determined_model === 'hybrid_osisi')) {
            $message .= " — ⚠️ Executive Alert Dispatched: Recommended for Traditional Publishing Contract offer!";
        }

        return redirect()->back()->with('success', $message);
    }
}


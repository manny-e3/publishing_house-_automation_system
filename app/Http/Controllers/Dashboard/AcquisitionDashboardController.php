<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Prospect;
use App\Services\WorkflowChecklistService;
use App\Enums\WorkflowStatus;
use App\Enums\PublishingModel;
use Illuminate\Http\Request;

class AcquisitionDashboardController extends Controller
{
    protected $checklistService;

    public function __construct(WorkflowChecklistService $checklistService)
    {
        $this->checklistService = $checklistService;
    }

    public function index(Request $request)
    {
        // 1. Pending Acquisition Vetting Queue (ordered with paid/escalated at top)
        $vettingQueue = Prospect::whereIn('status', [
                WorkflowStatus::Submitted->value, 
                'submitted', 
                'under_review',
                'project_active',
                WorkflowStatus::InEditing->value,
                'in_editing'
            ])
            ->orderByDesc('metadata_file->escalated_acquisition')
            ->orderBy('updated_at', 'desc')
            ->paginate(15, ['*'], 'vetting_page');

        // 2. Pending Follow-Up Pipeline (Bright Red Alerts from 24hr abandonment)
        $abandonedFollowups = Prospect::where(function ($q) {
                $q->where('metadata_file->flag_red', true)
                  ->orWhere('metadata_file->pending_followup', true);
            })
            ->where('payment_status', 'unpaid')
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. Traditional Candidates vs Osisi Hybrid stats
        $stats = [
            'total_prospects' => Prospect::count(),
            'traditional_candidates' => Prospect::where('determined_model', PublishingModel::Traditional->value)->count(),
            'osisi_hybrid' => Prospect::where('determined_model', PublishingModel::HybridOsisi->value)->count(),
            'pending_review' => Prospect::whereIn('status', [
                WorkflowStatus::Submitted->value, 
                'submitted', 
                'project_active', 
                'under_review'
            ])->count(),
            'critical_followups' => $abandonedFollowups->count(),
        ];

        return view('dashboards.acquisition', compact('vettingQueue', 'abandonedFollowups', 'stats'));
    }

    public function show(Prospect $prospect)
    {
        $acquisitionTasks = WorkflowChecklistService::ACQUISITION_TASKS;
        $progress = $this->checklistService->calculateProgress($prospect, $acquisitionTasks);

        return view('dashboards.acquisition_detail', compact('prospect', 'acquisitionTasks', 'progress'));
    }

    public function toggleTask(Request $request, Prospect $prospect)
    {
        $taskKey = $request->input('task_key');
        $completed = $request->boolean('completed');

        if (!array_key_exists($taskKey, WorkflowChecklistService::ACQUISITION_TASKS)) {
            return response()->json(['error' => 'Invalid acquisition task key'], 400);
        }

        $result = $this->checklistService->setTaskStatus($prospect, $taskKey, $completed);
        return response()->json($result);
    }

    public function assignIsbn(Request $request, Prospect $prospect)
    {
        $request->validate([
            'isbn' => 'required|string|max:50',
        ]);

        $metadata = $prospect->metadata_file ?? [];
        $metadata['isbn'] = $request->isbn;
        $metadata['isbn_assigned_at'] = now()->toIso8601String();
        $metadata['isbn_assigned_by'] = auth()->id();

        $prospect->metadata_file = $metadata;
        $prospect->save();

        // Mark 'application_for_isbn' and 'log_acquired_isbn' as completed
        $this->checklistService->setTaskStatus($prospect, 'application_for_isbn', true);
        $this->checklistService->setTaskStatus($prospect, 'log_acquired_isbn', true);

        return redirect()->back()->with('success', "ISBN {$request->isbn} successfully registered for {$prospect->book_title}.");
    }
}


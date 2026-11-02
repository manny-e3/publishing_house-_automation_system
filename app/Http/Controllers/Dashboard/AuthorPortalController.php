<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Prospect;
use App\Enums\WorkflowStatus;
use Illuminate\Http\Request;

class AuthorPortalController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Get author's manuscripts
        if ($user->hasRole('admin')) {
            $prospects = Prospect::with(['channelListings', 'invoices'])
                ->latest()
                ->get();
        } else {
            $prospects = Prospect::where('user_id', $user->id)
                ->orWhere('email', $user->email)
                ->with(['channelListings', 'invoices'])
                ->latest()
                ->get();
        }

        // Determine activeProspect for Digital Bookshelf
        $activeProspect = null;
        if ($request->has('book')) {
            $activeProspect = $prospects->firstWhere('id', (int) $request->query('book'));
            if ($activeProspect) {
                session(['author_active_book' => $activeProspect->id]);
            }
        } elseif (session()->has('author_active_book')) {
            $activeProspect = $prospects->firstWhere('id', session('author_active_book'));
        }

        // If not explicitly requested, prioritize manuscript with compiled files (e.g. Ancient Trade Routes)
        if (!$activeProspect) {
            $activeProspect = $prospects->first(function ($p) {
                return !empty($p->epub_file_url) || !empty($p->ebook_pdf_url) || !empty($p->print_ready_pdf_url) || !empty($p->amazon_kdp_ready_url);
            });
        }

        // Fallback to active production stages
        if (!$activeProspect) {
            $activeProspect = $prospects->first(function ($p) {
                $status = $p->status?->value ?? $p->status;
                return in_array($status, [
                    'ready_for_print',
                    WorkflowStatus::ReadyForPrint->value,
                    'in_design',
                    WorkflowStatus::InDesign->value,
                    'published',
                    WorkflowStatus::Published->value,
                ]);
            });
        }

        if (!$activeProspect) {
            $activeProspect = $prospects->first();
        }

        // Stepper calculation & Gate 4 status for active prospect
        $currentStep = 1;
        $isGate4Cleared = false;

        if ($activeProspect) {
            $status = $activeProspect->status?->value ?? $activeProspect->status;
            $currentStep = match ($status) {
                'lead_captured', 'submitted', WorkflowStatus::LeadCaptured->value, WorkflowStatus::Submitted->value => 1,
                'under_review', 'in_editing', WorkflowStatus::UnderReview->value, WorkflowStatus::InEditing->value => 2,
                'in_design', WorkflowStatus::InDesign->value => 3,
                'ready_for_print', WorkflowStatus::ReadyForPrint->value => 4,
                'published', WorkflowStatus::Published->value => 5,
                default => 1,
            };

            $metadata = $activeProspect->metadata_file ?? [];
            $isGate4Cleared = !empty($metadata['distribution_validated']) || $status === WorkflowStatus::Published->value;
        }

        return view('dashboards.author_portal', compact('prospects', 'activeProspect', 'currentStep', 'isGate4Cleared'));
    }

    public function show(Prospect $prospect)
    {
        $user = auth()->user();

        // Ensure user owns this manuscript or is admin
        if (!$user->hasRole('admin') && $prospect->user_id !== $user->id && $prospect->email !== $user->email) {
            abort(403, 'Unauthorized access to this manuscript.');
        }

        // Determine current 5-stage stepper step
        $status = $prospect->status?->value ?? $prospect->status;
        $currentStep = match ($status) {
            'lead_captured', 'submitted', WorkflowStatus::LeadCaptured->value, WorkflowStatus::Submitted->value => 1,
            'under_review', 'in_editing', WorkflowStatus::UnderReview->value, WorkflowStatus::InEditing->value => 2,
            'in_design', WorkflowStatus::InDesign->value => 3,
            'ready_for_print', WorkflowStatus::ReadyForPrint->value => 4,
            'published', WorkflowStatus::Published->value => 5,
            default => 1,
        };

        // Gate 4: Check if downloads are unlocked
        $metadata = $prospect->metadata_file ?? [];
        $isGate4Cleared = !empty($metadata['distribution_validated']) || $status === WorkflowStatus::Published->value;

        return view('dashboards.author_tracking', compact('prospect', 'currentStep', 'isGate4Cleared'));
    }

    public function postFeedback(Request $request, Prospect $prospect)
    {
        $request->validate([
            'note' => 'required|string|max:1000',
            'page_reference' => 'nullable|string|max:50',
        ]);

        $metadata = $prospect->metadata_file ?? [];
        $annotations = $metadata['proof_annotations'] ?? [];

        $annotations[] = [
            'id' => uniqid('author_note_'),
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'role' => 'author',
            'note' => $request->note,
            'page_number' => $request->page_reference,
            'format' => 'author_proof_feedback',
            'created_at' => now()->toIso8601String(),
        ];

        $metadata['proof_annotations'] = $annotations;
        $prospect->metadata_file = $metadata;
        $prospect->save();

        return redirect()->back()->with('success', 'Your feedback has been sent to our design and editorial teams.');
    }
}

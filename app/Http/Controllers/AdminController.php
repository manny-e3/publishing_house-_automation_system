<?php

namespace App\Http\Controllers;

use App\Models\Prospect;
use App\Events\ManuscriptAccepted;
use App\Events\ManuscriptRejected;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function prospects()
    {
        $prospects = Prospect::latest()->get();
        return view('admin.prospects.index', compact('prospects'));
    }

    public function show(Prospect $prospect)
    {
        $criteria = \App\Models\ReviewCriterion::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.prospects.show', compact('prospect', 'criteria'));
    }

    public function updateStatus(Request $request, Prospect $prospect)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected',
            'evaluation' => 'nullable|array'
        ]);

        // Save Evaluations
        if ($request->has('evaluation')) {
            foreach ($request->evaluation as $criterionId => $data) {
                \App\Models\ProspectEvaluation::updateOrCreate(
                    ['prospect_id' => $prospect->id, 'review_criterion_id' => $criterionId],
                    ['passed' => isset($data['passed']), 'notes' => $data['notes'] ?? null]
                );
            }
        }

        $prospect->status = $request->status;
        $prospect->save();

        if ($request->status === 'accepted') {
            event(new ManuscriptAccepted($prospect));
            return redirect()->route('admin.invoices.create', ['prospect_id' => $prospect->id])->with('success', 'Manuscript accepted! Generate the invoice to proceed.');
        }

        event(new ManuscriptRejected($prospect));
        return redirect()->route('admin.prospects.index')->with('success', 'Manuscript rejected and archived.');
    }
}

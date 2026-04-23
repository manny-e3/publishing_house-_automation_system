<?php

namespace App\Http\Controllers;

use App\Models\Prospect;
use App\Models\PricingRate;
use App\Services\AdminService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function dashboard()
    {
        $data = $this->adminService->getDashboardData();
        return view('admin.dashboard', $data);
    }

    public function prospects()
    {
        $prospects = Prospect::latest()->paginate(10);
        return view('admin.prospects.index', compact('prospects'));
    }

    public function show(Prospect $prospect)
    {
        $criteria = \App\Models\ReviewCriterion::where('is_active', true)->orderBy('sort_order')->get();
        $rates = PricingRate::all()->pluck('value', 'key');
        return view('admin.prospects.show', compact('prospect', 'criteria', 'rates'));
    }

    public function updateStatus(Request $request, Prospect $prospect)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected',
            'evaluation' => 'nullable|array'
        ]);

        $this->adminService->updateProspectStatus($prospect, $request->status, $request->evaluation);

        if ($request->status === 'accepted') {
            return redirect()->route('admin.invoices.create', ['prospect_id' => $prospect->id])->with('success', 'Manuscript accepted! Generate the invoice to proceed.');
        }

        return redirect()->route('admin.prospects.index')->with('success', 'Manuscript rejected and archived.');
    }

    public function updateEstimate(Request $request, Prospect $prospect)
    {
        $request->validate([
            'estimated_cost' => 'required|numeric|min:0'
        ]);

        $this->adminService->updateProspectEstimate($prospect, (float) $request->estimated_cost);

        return response()->json(['success' => true, 'message' => 'Estimate updated successfully.']);
    }
}

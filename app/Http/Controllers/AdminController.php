<?php

namespace App\Http\Controllers;

use App\Models\Prospect;
use App\Models\PricingRate;
use App\Events\ManuscriptAccepted;
use App\Events\ManuscriptRejected;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Project;
use App\Models\Invoice;
use App\Models\AuditLog;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();
        
        // Revenue Metrics
        $totalRevenue = Invoice::sum('total_paid');
        $revenueThisMonth = Invoice::where('updated_at', '>=', $startOfMonth)->sum('total_paid');
        $revenueLastMonth = Invoice::whereBetween('updated_at', [$startOfLastMonth, $endOfLastMonth])->sum('total_paid');
        
        $revenueChange = $revenueLastMonth > 0 ? (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100 : 0;

        // Active Dashboard Stats
        $activeProjectsCount = Project::count();
        $newProspectsCount = Prospect::where('created_at', '>=', $startOfMonth)->count();
        $allUsersCount = User::count();
        
        // Recent Transactions (Paid Invoices)
        $recentTransactions = Invoice::with('prospect')
            ->where('total_paid', '>', 0)
            ->latest('updated_at')
            ->limit(5)
            ->get();
            
        // Recent Activities (Audit Logs)
        $recentActivities = AuditLog::with('user')->latest()->limit(8)->get();
        
        // Project Stage Distribution (Doughnut Chart)
        $projectStages = Project::selectRaw('current_stage, count(*) as count')
            ->groupBy('current_stage')
            ->get()
            ->pluck('count', 'current_stage');
            
        $stageLabels = [];
        $stageCounts = [];
        foreach(Project::STAGES as $key => $label) {
            if (isset($projectStages[$key]) && $projectStages[$key] > 0) {
                $stageLabels[] = $label;
                $stageCounts[] = $projectStages[$key];
            }
        }

        // New Users Weekly
        $newUsers = User::latest()->limit(5)->get();

        return view('admin.dashboard', compact(
            'totalRevenue', 
            'revenueThisMonth', 
            'revenueChange', 
            'activeProjectsCount', 
            'newProspectsCount',
            'allUsersCount',
            'recentTransactions',
            'recentActivities',
            'stageLabels',
            'stageCounts',
            'newUsers'
        ));
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

    public function updateEstimate(Request $request, Prospect $prospect)
    {
        $request->validate([
            'estimated_cost' => 'required|numeric|min:0'
        ]);

        $prospect->update([
            'estimated_cost' => $request->estimated_cost
        ]);

        return response()->json(['success' => true, 'message' => 'Estimate updated successfully.']);
    }
}

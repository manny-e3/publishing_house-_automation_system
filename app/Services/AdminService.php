<?php

namespace App\Services;

use App\Models\Prospect;
use App\Models\Project;
use App\Models\Invoice;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\ProspectEvaluation;
use App\Events\ManuscriptAccepted;
use App\Events\ManuscriptRejected;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminService
{
    /**
     * Get all metrics and data for the admin dashboard.
     */
    public function getDashboardData(): array
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

        return [
            'totalRevenue' => $totalRevenue,
            'revenueThisMonth' => $revenueThisMonth,
            'revenueChange' => $revenueChange,
            'activeProjectsCount' => $activeProjectsCount,
            'newProspectsCount' => $newProspectsCount,
            'allUsersCount' => $allUsersCount,
            'recentTransactions' => $recentTransactions,
            'recentActivities' => $recentActivities,
            'stageLabels' => $stageLabels,
            'stageCounts' => $stageCounts,
            'newUsers' => $newUsers,
        ];
    }

    /**
     * Update the status of a prospect (Accept/Reject).
     */
    public function updateProspectStatus(Prospect $prospect, string $status, ?array $evaluations = []): void
    {
        DB::transaction(function () use ($prospect, $status, $evaluations) {
            // Save Evaluations
            if (!empty($evaluations)) {
                foreach ($evaluations as $criterionId => $data) {
                    ProspectEvaluation::updateOrCreate(
                        ['prospect_id' => $prospect->id, 'review_criterion_id' => $criterionId],
                        ['passed' => isset($data['passed']), 'notes' => $data['notes'] ?? null]
                    );
                }
            }

            $prospect->status = $status;
            $prospect->save();

            if ($status === 'accepted') {
                event(new ManuscriptAccepted($prospect));
            } else {
                event(new ManuscriptRejected($prospect));
            }
        });
    }

    /**
     * Update the estimated cost for a prospect.
     */
    public function updateProspectEstimate(Prospect $prospect, float $estimatedCost): void
    {
        $prospect->update([
            'estimated_cost' => $estimatedCost
        ]);
    }
}

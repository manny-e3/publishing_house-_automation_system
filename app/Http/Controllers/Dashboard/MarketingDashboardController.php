<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Prospect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarketingDashboardController extends Controller
{
    public const CAMPAIGN_TASKS = [
        'target_audience_analysis' => 'Target Audience Analysis',
        'arc_distribution' => 'Advance Reader Copies (ARC) Distribution',
        'store_blurb_signoff' => 'Store Metadata & Blurb Sign-Off',
        'preorder_setup' => 'Pre-Order / Retail Promotion Setup',
    ];

    public function index(Request $request)
    {
        // 1. UTM Attribution ROI Matrix
        $sources = ['instagram', 'linkedin', 'tiktok', 'google', 'direct'];
        $matrix = [];

        foreach ($sources as $source) {
            $totalLeads = Prospect::where('marketing_source', $source)->count();
            $paidLeads = Prospect::where('marketing_source', $source)->where('payment_status', 'paid')->count();
            $conversionRate = $totalLeads > 0 ? round(($paidLeads / $totalLeads) * 100, 1) : 0;
            
            $revenueNGN = Prospect::where('marketing_source', $source)
                ->where('payment_status', 'paid')
                ->where('payment_merchant', '!=', 'stripe')
                ->sum('total_calculated_fee');

            $revenueUSD = Prospect::where('marketing_source', $source)
                ->where('payment_status', 'paid')
                ->where('payment_merchant', 'stripe')
                ->sum('total_calculated_fee');

            $avgRevenue = $totalLeads > 0 ? round(($revenueNGN ?: ($revenueUSD * 1500)) / $totalLeads) : 0;

            $matrix[$source] = [
                'leads' => $totalLeads,
                'paid' => $paidLeads,
                'conversion_rate' => $conversionRate,
                'revenue_ngn' => $revenueNGN,
                'revenue_usd' => $revenueUSD,
                'avg_revenue_ngn' => $avgRevenue,
            ];
        }

        // 2. Pending Follow-Up Pipeline (24hr Abandonment alerts)
        $followupLeads = Prospect::where(function ($q) {
                $q->where('metadata_file->flag_red', true)
                  ->orWhere('metadata_file->pending_followup', true);
            })
            ->where('payment_status', 'unpaid')
            ->get();

        // 3. Active Marketing Manuscripts
        $campaignManuscripts = Prospect::where('payment_status', 'paid')
            ->latest()
            ->paginate(15);

        return view('dashboards.marketing', compact('matrix', 'followupLeads', 'campaignManuscripts'));
    }

    public function toggleCampaignTask(Request $request, Prospect $prospect)
    {
        $taskKey = $request->input('task_key');
        $completed = $request->boolean('completed');

        $metadata = $prospect->metadata_file ?? [];
        $campaignProgress = $metadata['campaign_progress'] ?? [];
        
        $campaignProgress[$taskKey] = [
            'completed' => $completed,
            'timestamp' => now()->toIso8601String(),
            'user_id' => auth()->id(),
        ];

        $metadata['campaign_progress'] = $campaignProgress;
        $prospect->metadata_file = $metadata;
        $prospect->save();

        return response()->json([
            'status' => 'success',
            'task_key' => $taskKey,
            'completed' => $completed,
        ]);
    }
}


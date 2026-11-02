<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SystemFeatureFlag;
use App\Models\AuditLog;
use App\Models\Prospect;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItDashboardController extends Controller
{
    public function index(Request $request)
    {
        $featureFlags = SystemFeatureFlag::all();
        $auditLogs = AuditLog::with('user')->latest()->paginate(20);

        // Scan for Ghost Files (>60 days unpaid leads with raw uploads)
        $ghostLeads = Prospect::where('payment_status', 'unpaid')
            ->whereNotNull('manuscript_file_path')
            ->where('created_at', '<', Carbon::now()->subDays(60))
            ->get();

        $systemStats = [
            'php_version' => phpversion(),
            'laravel_version' => app()->version(),
            'database_connection' => config('database.default'),
            'total_audit_logs' => AuditLog::count(),
            'ghost_files_count' => $ghostLeads->count(),
        ];

        return view('dashboards.it', compact('featureFlags', 'auditLogs', 'ghostLeads', 'systemStats'));
    }

    public function toggleFlag(Request $request, SystemFeatureFlag $flag)
    {
        $flag->is_enabled = !$flag->is_enabled;
        $flag->save();

        AuditLog::create([
            'user_id' => auth()->id() ?? 1,
            'action' => 'feature_flag_toggled',
            'model_type' => SystemFeatureFlag::class,
            'model_id' => $flag->id,
            'changes' => [
                'flag_name' => $flag->name,
                'is_enabled' => $flag->is_enabled,
            ],
            'ip_address' => $request->ip() ?? '127.0.0.1',
        ]);

        return redirect()->back()->with('success', "Feature flag '{$flag->name}' updated to " . ($flag->is_enabled ? 'ENABLED' : 'DISABLED'));
    }

    /**
     * Batch Cleaner: Flush Unpaid Ghost Files (>60 days)
     */
    public function flushGhostFiles(Request $request)
    {
        $ghostLeads = Prospect::where('payment_status', 'unpaid')
            ->whereNotNull('manuscript_file_path')
            ->where('created_at', '<', Carbon::now()->subDays(60))
            ->get();

        $flushedCount = 0;

        foreach ($ghostLeads as $lead) {
            if ($lead->manuscript_file_path && Storage::disk('public')->exists($lead->manuscript_file_path)) {
                Storage::disk('public')->delete($lead->manuscript_file_path);
            }

            $metadata = $lead->metadata_file ?? [];
            $metadata['ghost_file_flushed_at'] = now()->toIso8601String();
            $lead->metadata_file = $metadata;
            $lead->manuscript_file_path = null;
            $lead->save();

            $flushedCount++;
        }

        AuditLog::create([
            'user_id' => auth()->id() ?? 1,
            'action' => 'ghost_files_flushed',
            'model_type' => Prospect::class,
            'model_id' => 0,
            'changes' => ['flushed_count' => $flushedCount],
            'ip_address' => $request->ip() ?? '127.0.0.1',
        ]);

        return redirect()->back()->with('success', "Batch Cleaner: Successfully flushed {$flushedCount} unpaid ghost file(s) (>60 days old).");
    }
}


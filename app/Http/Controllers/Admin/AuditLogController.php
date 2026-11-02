<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AuditLogController extends Controller
{
    /**
     * Display a paginated, filterable, and searchable list of audit logs.
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        // 1. Text Search across Action, IP, Model, or User details
        if ($search = trim($request->input('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('action', 'LIKE', "%{$search}%")
                  ->orWhere('ip_address', 'LIKE', "%{$search}%")
                  ->orWhere('model_type', 'LIKE', "%{$search}%")
                  ->orWhere('model_id', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        // 2. Category Filter
        if ($category = $request->input('category')) {
            if ($category === 'security') {
                $query->where(function ($q) {
                    $q->where('action', 'LIKE', 'warning_%')
                      ->orWhere('action', 'LIKE', '%unauthorized%')
                      ->orWhere('action', 'LIKE', '%denied%')
                      ->orWhere('action', 'LIKE', '%regression%');
                });
            } elseif ($category === 'auth') {
                $query->where(function ($q) {
                    $q->where('action', 'LIKE', 'auth_%')
                      ->orWhere('action', 'LIKE', '%login%')
                      ->orWhere('action', 'LIKE', '%logout%')
                      ->orWhere('action', 'LIKE', '%otp%');
                });
            } elseif ($category === 'publishing') {
                $query->where(function ($q) {
                    $q->where('action', 'LIKE', '%gate%')
                      ->orWhere('action', 'LIKE', '%rating%')
                      ->orWhere('action', 'LIKE', '%manuscript%')
                      ->orWhere('action', 'LIKE', '%editorial%')
                      ->orWhere('action', 'LIKE', '%design%')
                      ->orWhere('action', 'LIKE', '%prospect%')
                      ->orWhere('action', 'LIKE', '%checklist%')
                      ->orWhere('action', 'LIKE', '%distribution%');
                });
            } elseif ($category === 'financial') {
                $query->where(function ($q) {
                    $q->where('action', 'LIKE', '%invoice%')
                      ->orWhere('action', 'LIKE', '%payment%')
                      ->orWhere('action', 'LIKE', '%receipt%')
                      ->orWhere('action', 'LIKE', '%checkout%');
                });
            } elseif ($category === 'user_management') {
                $query->where(function ($q) {
                    $q->where('action', 'LIKE', 'user_%')
                      ->orWhere('action', 'LIKE', '%role%')
                      ->orWhere('action', 'LIKE', '%user');
                });
            }
        }

        // 3. User Filter
        if ($userId = $request->input('user_id')) {
            $query->where('user_id', $userId);
        }

        // 4. Period / Date Filter
        $period = $request->input('period', 'all');
        if ($period === 'today') {
            $query->whereDate('created_at', today());
        } elseif ($period === 'yesterday') {
            $query->whereDate('created_at', today()->subDay());
        } elseif ($period === '7days') {
            $query->where('created_at', '>=', now()->subDays(7));
        } elseif ($period === '30days') {
            $query->where('created_at', '>=', now()->subDays(30));
        }

        $logs = $query->paginate(25)->withQueryString();

        // High-level KPI metrics
        $totalLogsCount = AuditLog::count();
        $todayLogsCount = AuditLog::whereDate('created_at', today())->count();
        $activeUsersTodayCount = AuditLog::whereDate('created_at', today())->whereNotNull('user_id')->distinct('user_id')->count('user_id');
        $securityAlertsCount = AuditLog::where(function ($q) {
            $q->where('action', 'LIKE', 'warning_%')
              ->orWhere('action', 'LIKE', '%unauthorized%')
              ->orWhere('action', 'LIKE', '%regression%');
        })->count();
        $publishingEventsCount = AuditLog::where(function ($q) {
            $q->where('action', 'LIKE', '%gate%')
              ->orWhere('action', 'LIKE', '%distribution%')
              ->orWhere('action', 'LIKE', '%design%');
        })->count();

        $allUsers = User::orderBy('name')->get();

        return view('admin.audit_logs.index', compact(
            'logs',
            'totalLogsCount',
            'todayLogsCount',
            'activeUsersTodayCount',
            'securityAlertsCount',
            'publishingEventsCount',
            'allUsers',
            'category',
            'period'
        ));
    }

    /**
     * Retrieve single audit log details as JSON for detail modal inspection.
     */
    public function show(AuditLog $auditLog)
    {
        $auditLog->load('user');

        return response()->json([
            'id' => $auditLog->id,
            'action' => $auditLog->action,
            'human_action' => $auditLog->human_action,
            'category' => $auditLog->category,
            'badge_class' => $auditLog->badge_class,
            'user' => $auditLog->user ? [
                'id' => $auditLog->user->id,
                'name' => $auditLog->user->name,
                'email' => $auditLog->user->email,
                'role' => $auditLog->user->roles->first()?->name ?? 'User',
            ] : null,
            'model_type' => $auditLog->model_type,
            'model_id' => $auditLog->model_id,
            'target_title' => $auditLog->target_title,
            'changes' => $auditLog->changes,
            'ip_address' => $auditLog->ip_address,
            'user_agent' => $auditLog->user_agent,
            'created_at' => $auditLog->created_at->format('Y-m-d H:i:s'),
            'time_ago' => $auditLog->created_at->diffForHumans(),
        ]);
    }

    /**
     * Export filtered audit logs as a downloadable CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $query = AuditLog::with('user')->latest();

        if ($search = trim($request->input('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('action', 'LIKE', "%{$search}%")
                  ->orWhere('ip_address', 'LIKE', "%{$search}%")
                  ->orWhere('model_type', 'LIKE', "%{$search}%")
                  ->orWhere('model_id', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($category = $request->input('category')) {
            if ($category === 'security') {
                $query->where('action', 'LIKE', 'warning_%');
            } elseif ($category === 'auth') {
                $query->where('action', 'LIKE', 'auth_%');
            }
        }

        if ($userId = $request->input('user_id')) {
            $query->where('user_id', $userId);
        }

        $filename = 'audit_logs_' . date('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Log ID',
                'Timestamp',
                'User Name',
                'User Email',
                'Action Code',
                'Action Description',
                'Category',
                'Target Resource',
                'Model ID',
                'IP Address',
                'User Agent',
                'Changes / Payload (JSON)',
            ]);

            $query->chunk(200, function ($logs) use ($handle) {
                foreach ($logs as $log) {
                    fputcsv($handle, [
                        $log->id,
                        $log->created_at->toIso8601String(),
                        $log->user?->name ?? 'System/Guest',
                        $log->user?->email ?? 'N/A',
                        $log->action,
                        $log->human_action,
                        $log->category,
                        $log->target_title,
                        $log->model_id ?? 'N/A',
                        $log->ip_address,
                        $log->user_agent,
                        json_encode($log->changes),
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}


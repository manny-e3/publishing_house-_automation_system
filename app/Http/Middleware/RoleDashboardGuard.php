<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\AuditLog;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;

class RoleDashboardGuard
{
    /**
     * Handle an incoming request.
     * Enforces Prompt 3 RBAC Route Guards across the 8 departmental dashboards.
     */
    public function handle(Request $request, Closure $next, ?string $requiredRole = null): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please authenticate to access the publishing dashboard.');
        }

        /** @var User $user */
        $user = Auth::user();

        // System Admin has universal clearance
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        $path = $request->path();
        $targetRole = $requiredRole;

        // Auto-detect target role from URI path if not explicitly passed in route middleware
        if (!$targetRole) {
            if (str_starts_with($path, 'dashboard/it')) {
                $targetRole = 'it';
            } elseif (str_starts_with($path, 'dashboard/finance')) {
                $targetRole = 'finance';
            } elseif (str_starts_with($path, 'dashboard/acquisition')) {
                $targetRole = 'acquisition_admin';
            } elseif (str_starts_with($path, 'dashboard/editorial')) {
                $targetRole = 'editorial';
            } elseif (str_starts_with($path, 'dashboard/design')) {
                $targetRole = 'design';
            } elseif (str_starts_with($path, 'dashboard/marketing')) {
                $targetRole = 'marketing';
            } elseif (str_starts_with($path, 'dashboard/distribution')) {
                $targetRole = 'print_distribution';
            } elseif (str_starts_with($path, 'portal/author')) {
                $targetRole = 'author_agent';
            }
        }

        // Check if user has permission
        $hasAccess = false;
        if ($targetRole) {
            if ($user->hasRole($targetRole)) {
                $hasAccess = true;
            } elseif ($targetRole === 'acquisition_admin' && $user->hasRole('acquisitions')) {
                $hasAccess = true;
            } elseif ($targetRole === 'author_agent' && ($user->hasRole('author_agent') || $user->hasRole('prospect'))) {
                $hasAccess = true;
            }
        } else {
            $hasAccess = true;
        }

        if (!$hasAccess) {
            // Log security warning to audit logs with IP and attempted route
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'warning_unauthorized_dashboard_access',
                'model_type' => User::class,
                'model_id' => $user->id,
                'changes' => [
                    'attempted_path' => '/' . $path,
                    'required_role' => $targetRole,
                    'user_roles' => $user->getRoleNames()->toArray(),
                    'method' => $request->method(),
                ],
                'ip_address' => $request->ip() ?? '127.0.0.1',
            ]);

            $authService = app(AuthService::class);
            $redirectUrl = $authService->getRedirectRoute($user);

            // If the redirect route happens to be the same path, send to login with error
            if ($redirectUrl === url($path) || str_contains($redirectUrl, $path)) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Access Denied: You do not have permissions for that departmental dashboard.');
            }

            return redirect($redirectUrl)->with('error', 'Access Denied: You do not have permission to access that department dashboard.');
        }

        return $next($request);
    }
}


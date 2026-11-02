<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'changes',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper to reliably record audit log entries with request context.
     */
    public static function record(
        string $action,
        ?Model $model = null,
        array $changes = [],
        ?int $userId = null,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): self {
        $resolvedUserId = $userId;
        if ($resolvedUserId === null && Auth::check()) {
            $resolvedUserId = Auth::id();
        }

        return static::create([
            'user_id' => $resolvedUserId,
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? (string)$model->getKey() : null,
            'changes' => $changes,
            'ip_address' => $ipAddress ?? (request() ? request()->ip() : '127.0.0.1'),
            'user_agent' => $userAgent ?? (request() ? request()->userAgent() : null),
        ]);
    }

    /**
     * Activity category classifier for filtering and badges.
     */
    public function getCategoryAttribute(): string
    {
        $action = strtolower($this->action);

        if (str_starts_with($action, 'auth_') || str_contains($action, 'login') || str_contains($action, 'logout') || str_contains($action, 'otp') || str_contains($action, 'password')) {
            return 'auth';
        }

        if (str_starts_with($action, 'warning_') || str_contains($action, 'unauthorized') || str_contains($action, 'denied') || str_contains($action, 'regression')) {
            return 'security';
        }

        if (str_contains($action, 'gate') || str_contains($action, 'rating') || str_contains($action, 'manuscript') || str_contains($action, 'editorial') || str_contains($action, 'design') || str_contains($action, 'prospect') || str_contains($action, 'checklist') || str_contains($action, 'distribution') || str_contains($action, 'inventory') || str_contains($action, 'channel')) {
            return 'publishing';
        }

        if (str_contains($action, 'invoice') || str_contains($action, 'payment') || str_contains($action, 'receipt') || str_contains($action, 'transaction') || str_contains($action, 'checkout') || str_contains($action, 'price')) {
            return 'financial';
        }

        if (str_starts_with($action, 'user_') || str_contains($action, 'role') || str_contains($action, 'user')) {
            return 'user_management';
        }

        return 'system';
    }

    /**
     * Visual badge styling classes based on category and action severity.
     */
    public function getBadgeClassAttribute(): string
    {
        return match ($this->category) {
            'security' => 'badge-dim bg-danger text-danger border-danger',
            'auth' => 'badge-dim bg-info text-info border-info',
            'publishing' => 'badge-dim bg-success text-success border-success',
            'financial' => 'badge-dim bg-warning text-warning border-warning',
            'user_management' => 'badge-dim bg-primary text-primary border-primary',
            default => 'badge-dim bg-secondary text-secondary border-secondary',
        };
    }

    /**
     * DashLite Icon mapping.
     */
    public function getIconAttribute(): string
    {
        $action = strtolower($this->action);

        if (str_contains($action, 'warning') || str_contains($action, 'unauthorized') || str_contains($action, 'regression')) {
            return 'ni-shield-alert';
        }
        if (str_contains($action, 'login')) {
            return 'ni-signin';
        }
        if (str_contains($action, 'logout')) {
            return 'ni-signout';
        }
        if (str_contains($action, 'payment') || str_contains($action, 'invoice') || str_contains($action, 'receipt')) {
            return 'ni-money';
        }
        if (str_contains($action, 'distribution') || str_contains($action, 'channel') || str_contains($action, 'release')) {
            return 'ni-truck';
        }
        if (str_contains($action, 'design') || str_contains($action, 'upload') || str_contains($action, 'proof')) {
            return 'ni-color-palette';
        }
        if (str_contains($action, 'user') || str_contains($action, 'role')) {
            return 'ni-user-list';
        }
        if (str_contains($action, 'rating') || str_contains($action, 'board')) {
            return 'ni-star-fill';
        }
        if (str_contains($action, 'gate')) {
            return 'ni-shield-check';
        }

        return 'ni-activity-round';
    }

    /**
     * Human-readable formatted action title.
     */
    public function getHumanActionAttribute(): string
    {
        $known = [
            'warning_unauthorized_dashboard_access' => 'Unauthorized Access Blocked (RBAC)',
            'gate4_distribution_release_validated' => 'Gate 4 Release Validated & Bookshelf Unlocked',
            'gate4_distribution_release_revoked' => 'Gate 4 Release Revoked & Bookshelf Locked',
            'gate_2_regression_guard_triggered' => 'Gate 2 Regression Guard Triggered',
            'gate1_design_cleared' => 'Gate 1 Design Clearance Approved',
            'gate3_channel_updated' => 'Gate 3 Distribution Channel Updated',
            'osisi_excellent_escalated_to_board' => 'Osisi Excellence Escalated to Executive Board',
            'manuscript_rating_updated' => 'Manuscript Editorial Rating Updated',
            'checklist_task_updated' => 'Checklist Gate Progress Updated',
            'recovery_2hr_nudge_sent' => '2-Hour Abandoned Lead Nudge Dispatched',
            'recovery_24hr_final_warning' => '24-Hour Final Lead Warning Dispatched',
            'auth_login' => 'User Login Successful',
            'auth_logout' => 'User Logout',
            'auth_failed_login' => 'Failed Login Attempt',
            'auth_otp_sent' => 'Two-Factor OTP Dispatched',
            'payment_confirmed_manual' => 'Manual Bank Payment Confirmed by Admin',
            'payment_completed_webhook' => 'Online Payment Completed (Webhook)',
            'created invoice' => 'Invoice Generated',
            'created prospect' => 'New Manuscript Prospect Registered',
            'updated prospect' => 'Manuscript Prospect Updated',
            'created user' => 'User Account Created',
            'updated user' => 'User Account Updated',
            'deleted user' => 'User Account Deleted',
            'contract_signed_by_author' => 'Publishing Contract Executed by Author',
            'design_asset_uploaded' => 'Production Format Asset Uploaded',
            'proof_annotation_pinned' => 'Proof Annotation Pin Added',
        ];

        return $known[$this->action] ?? ucwords(str_replace(['_', '-'], ' ', $this->action));
    }

    /**
     * Resolve target resource label.
     */
    public function getTargetTitleAttribute(): string
    {
        if (!$this->model_type || !$this->model_id) {
            return 'System Platform';
        }

        $base = class_basename($this->model_type);

        try {
            if ($this->model_type === Prospect::class) {
                $prospect = Prospect::find($this->model_id);
                return $prospect ? "Book: {$prospect->book_title} (#{$this->model_id})" : "Prospect #{$this->model_id}";
            }

            if ($this->model_type === Invoice::class) {
                $inv = Invoice::find($this->model_id);
                return $inv ? "Invoice #{$inv->invoice_number}" : "Invoice #{$this->model_id}";
            }

            if ($this->model_type === User::class) {
                $u = User::find($this->model_id);
                return $u ? "User: {$u->name} ({$u->email})" : "User #{$this->model_id}";
            }
        } catch (\Throwable $e) {
            // Ignore DB lookup error and fallback
        }

        return "{$base} #{$this->model_id}";
    }
}

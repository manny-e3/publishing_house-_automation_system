<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait LogsAudit
{
    protected static function bootLogsAudit()
    {
        static::updated(function ($model) {
            static::logAction($model, 'updated');
        });

        static::created(function ($model) {
            static::logAction($model, 'created');
        });

        static::deleted(function ($model) {
            static::logAction($model, 'deleted');
        });
    }

    protected static function logAction($model, $action)
    {
        $sensitive = ['password', 'remember_token', 'otp', 'otp_expires_at'];

        $changes = [];
        if ($action === 'updated') {
            $dirty = $model->getDirty();
            
            // Mask or omit sensitive fields
            foreach ($sensitive as $s) {
                if (isset($dirty[$s])) {
                    $dirty[$s] = '***REDACTED***';
                }
            }

            // Don't log if only timestamps changed
            if (empty(array_diff(array_keys($dirty), ['updated_at']))) {
                return;
            }

            $original = $model->getOriginal();
            foreach ($sensitive as $s) {
                if (isset($original[$s])) {
                    $original[$s] = '***REDACTED***';
                }
            }

            $changes = [
                'before' => array_intersect_key($original, $dirty),
                'after' => $dirty,
            ];
        } elseif ($action === 'created') {
            $attrs = $model->getAttributes();
            foreach ($sensitive as $s) {
                if (isset($attrs[$s])) {
                    $attrs[$s] = '***REDACTED***';
                }
            }
            $changes = ['attributes' => $attrs];
        }

        AuditLog::record(
            action: $action . ' ' . strtolower(class_basename($model)),
            model: $model,
            changes: $changes,
            userId: Auth::id()
        );
    }
}

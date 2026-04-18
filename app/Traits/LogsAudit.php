<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

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
        $changes = [];
        if ($action === 'updated') {
            $changes = [
                'before' => array_intersect_key($model->getOriginal(), $model->getDirty()),
                'after' => $model->getDirty(),
            ];
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action . ' ' . strtolower(class_basename($model)),
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'changes' => $changes,
            'ip_address' => Request::ip(),
        ]);
    }
}

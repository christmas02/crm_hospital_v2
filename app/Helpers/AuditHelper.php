<?php

namespace App\Helpers;

use App\Models\AuditLog;

class AuditHelper
{
    public static function log(string $action, string $description, $model = null, array $changes = null)
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $model ? class_basename($model) : null,
            'model_id' => $model?->id,
            'description' => $description,
            'changes' => $changes,
            'ip_address' => request()->ip(),
        ]);
    }
}

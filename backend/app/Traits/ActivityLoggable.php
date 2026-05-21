<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait ActivityLoggable
{
    /**
     * Log an activity to the activity_logs table.
     */
    protected function logActivity(
        string $action,
        string $entityType,
        string $entityId,
        ?array $oldValues = null,
        ?array $newValues = null,
    ): void {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'created_at' => now(),
        ]);
    }
}

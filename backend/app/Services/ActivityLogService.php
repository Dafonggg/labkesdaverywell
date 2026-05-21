<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    /**
     * Log an activity.
     */
    public function log(
        string $action,
        string $entityType,
        string $entityId,
        ?array $oldValues = null,
        ?array $newValues = null,
    ): ActivityLog {
        return ActivityLog::create([
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

    /**
     * Get all activity logs with filters and pagination.
     */
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $query = ActivityLog::with('user');

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['action'])) {
            $query->where('action', $filters['action']);
        }

        if (!empty($filters['entity_type'])) {
            $query->where('entity_type', $filters['entity_type']);
        }

        return $query->orderByDesc('created_at')
            ->paginate($filters['per_page'] ?? 20);
    }
}

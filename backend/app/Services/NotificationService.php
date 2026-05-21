<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NotificationService
{
    /**
     * Get notifications for a user.
     */
    public function getForUser(string $userId): LengthAwarePaginator
    {
        return Notification::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->paginate(20);
    }

    /**
     * Mark notifications as read.
     */
    public function markAsRead(string $userId, ?array $ids = null): int
    {
        $query = Notification::where('user_id', $userId)
            ->where('is_read', false);

        if ($ids) {
            $query->whereIn('id', $ids);
        }

        return $query->update(['is_read' => true]);
    }
}

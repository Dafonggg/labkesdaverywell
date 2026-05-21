<?php

namespace App\Enums;

enum PermohonanStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
}

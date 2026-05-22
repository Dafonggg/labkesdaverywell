<?php

namespace App\Enums;

enum PermohonanStatus: string
{
    case PENDING = 'pending';
    case WAITING_PAYMENT = 'waiting_payment';
    case PAID = 'paid';
    case SCHEDULED = 'scheduled';
    case COMPLETED = 'completed';
}

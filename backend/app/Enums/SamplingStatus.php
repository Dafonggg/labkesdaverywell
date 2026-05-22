<?php

namespace App\Enums;

enum SamplingStatus: string
{
    case DRAFT = 'draft';
    case PENDING_SYNC = 'pending_sync';
    case SYNCED = 'synced';
    case FAILED = 'failed';
}

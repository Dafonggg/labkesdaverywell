<?php

namespace App\Enums;

enum LaporanStatus: string
{
    case DRAFT = 'draft';
    case PENDING_APPROVAL = 'pending_approval';
    case APPROVED = 'approved';
    case FINAL = 'final';
    case ARCHIVED = 'archived';
}

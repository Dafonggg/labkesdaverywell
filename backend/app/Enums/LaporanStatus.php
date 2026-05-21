<?php

namespace App\Enums;

enum LaporanStatus: string
{
    case DRAFT = 'draft';
    case REVIEW = 'review';
    case PENDING_APPROVAL = 'pending_approval';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case REVISION = 'revision';
    case FINAL = 'final';
}

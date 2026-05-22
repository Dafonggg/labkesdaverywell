<?php

namespace App\Enums;

enum PengujianStatus: string
{
    case DRAFT = 'draft';
    case PENDING_QC = 'pending_qc';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}

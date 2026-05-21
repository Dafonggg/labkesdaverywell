<?php

namespace App\Enums;

enum SampleStatus: string
{
    case COLLECTED = 'collected';
    case REGISTERED = 'registered';
    case TESTING = 'testing';
    case COMPLETED = 'completed';
}

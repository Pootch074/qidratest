<?php

namespace App\Enums;

enum QueueStatus: string
{
    case WAITING = 'waiting';
    case PENDING = 'pending';
    case DEFERRED = 'deferred';
    case SERVING = 'serving';
    case COMPLETED = 'completed';
}

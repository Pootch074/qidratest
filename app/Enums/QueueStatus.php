<?php

namespace App\Enums;

enum QueueStatus: string
{
    case WAITING = 'waiting';
    case PENDING = 'pending';
    case SERVING = 'serving';
    case COMPLETED = 'completed';
}

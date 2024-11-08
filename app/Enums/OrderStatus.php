<?php

namespace App\Enums;

enum OrderStatus: string
{
    case REQUESTED = 'Requested';
    case APPROVED = 'Approved';
    case CLOSED = 'Closed';
}

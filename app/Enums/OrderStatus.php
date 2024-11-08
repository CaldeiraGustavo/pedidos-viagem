<?php

namespace App\Enums;

enum OrderStatus: string
{
    case R = "Requested";
    case A = "Approved";
    case C = "Closed";
}
<?php

namespace App\Enums;

enum TicketStatus: string
{
    case Pending = 'pending';
    case Active = 'active';
    case Used = 'used';
    case Cancelled = 'cancelled';
}

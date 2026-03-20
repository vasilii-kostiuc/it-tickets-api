<?php

namespace App\Domain\Ticket\Enums;

enum TicketMessageAuthorType: string
{
    case User = 'user';
    case Client = 'client';
    case System = 'system';
}

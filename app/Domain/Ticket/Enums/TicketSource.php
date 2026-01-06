<?php

namespace App\Domain\Ticket\Enums;

enum TicketSource: string
{
    case Web = 'Web';
    case Email = 'Email';
    case Phone = 'Phone';
    case Api = 'Api';
    case Other = 'Other';
}

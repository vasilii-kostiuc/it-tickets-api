<?php

namespace App\Domain\Ticket\Enums;

enum CategoryStatus : string
{
    case Active = 'Active';
    case Inactive = 'Inactive';
    case Archived = 'Archived';
}

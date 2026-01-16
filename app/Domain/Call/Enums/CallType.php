<?php

namespace App\Domain\Call\Enums;

enum CallType: string
{
    case In = 'in';
    case Out = 'out';
}

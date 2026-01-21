<?php

namespace App\Domain\Ticket\Services\SlaCalculators;

use App\Domain\Ticket\Enums\TicketSource;
use Illuminate\Support\Carbon;

interface SlaCalculatorInterface
{
    public function calculateDueDate(
        ?int $categoryId = null,
        ?int $departmentId = null,
        ?int $clientId = null,
        ?TicketSource $source = null,
        array $extra = []
    ): ?Carbon;
    
    public function getSlaId(
        ?int $categoryId = null,
        ?int $departmentId = null,
        ?int $clientId = null,
        ?TicketSource $source = null,
        array $extra = []
    ): ?int;
}

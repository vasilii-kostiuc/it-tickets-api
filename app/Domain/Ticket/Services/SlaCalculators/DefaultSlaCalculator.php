<?php

namespace App\Domain\Ticket\Services\SlaCalculators;

use App\Domain\Ticket\Enums\TicketSource;
use Illuminate\Support\Carbon;

/**
 * Простой калькулятор SLA с хардкодом
 * В будущем можно заменить на более сложную логику
 */
class DefaultSlaCalculator implements SlaCalculatorInterface
{
    private const DEFAULT_RESOLUTION_HOURS = 8;
    
    public function calculateDueDate(
        ?int $categoryId = null,
        ?int $departmentId = null,
        ?int $clientId = null,
        ?TicketSource $source = null,
        array $extra = []
    ): ?Carbon {
        return now()->addHours(self::DEFAULT_RESOLUTION_HOURS);
    }
    
    public function getSlaId(
        ?int $categoryId = null,
        ?int $departmentId = null,
        ?int $clientId = null,
        ?TicketSource $source = null,
        array $extra = []
    ): ?int {
        return null;
    }
}

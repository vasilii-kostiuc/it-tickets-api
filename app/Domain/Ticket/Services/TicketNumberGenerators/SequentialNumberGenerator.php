<?php

namespace App\Domain\Ticket\Services\TicketNumberGenerators;

use App\Domain\Ticket\Enums\TicketSource;
use App\Domain\Ticket\Models\Ticket;

/**
 * Простая последовательная нумерация: T-0001, T-0002, ...
 */
class SequentialNumberGenerator implements TicketNumberGeneratorInterface
{

    
    public function generate(
        ?int $categoryId,
        ?int $departmentId,
        ?int $clientId,
        TicketSource $source,
        array $extra = []
    ): string {
        $lastTicket = Ticket::query()
            ->orderByDesc('id')
            ->first();

        $nextNumber = $lastTicket ? ($lastTicket->id + 1) : 1;

        return sprintf('T-%04d', $nextNumber);
    }
}

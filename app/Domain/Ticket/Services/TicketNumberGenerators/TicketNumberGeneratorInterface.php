<?php

namespace App\Domain\Ticket\Services\TicketNumberGenerators;

use App\Domain\Ticket\Enums\TicketSource;

interface TicketNumberGeneratorInterface
{
    /**
     * Генерирует уникальный номер тикета на основе бизнес-правил
     *
     * @param int|null $categoryId ID категории тикета
     * @param int|null $departmentId ID отдела
     * @param int|null $clientId ID клиента
     * @param TicketSource $source Источник создания тикета
     * @param array $extra Дополнительные параметры для генерации
     * @return string Сгенерированный номер тикета
     */
    public function generate(
        ?int $categoryId,
        ?int $departmentId,
        ?int $clientId,
        TicketSource $source,
        array $extra = []
    ): string;
}

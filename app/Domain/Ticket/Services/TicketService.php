<?php

namespace App\Domain\Ticket\Services;

use App\Domain\Ticket\Data\TicketCreateData;
use App\Domain\Ticket\Events\TicketCreatedEvent;
use App\Domain\Ticket\Models\Ticket;
use App\Domain\Ticket\Services\SlaCalculators\SlaCalculatorInterface;

class TicketService
{
    public function __construct(
        private readonly SlaCalculatorInterface $slaCalculator
    ) {}
    
    public function createTicket(TicketCreateData $data): Ticket
    {
        $ticketData = $this->prepareSlaData($data);
        
        $ticket = Ticket::query()->create($ticketData);

        event(new TicketCreatedEvent($ticket));

        return $ticket;
    }
    
    private function prepareSlaData(TicketCreateData $data): array
    {
        $ticketData = $data->toArray();
        
        if (!$data->dueDate) {
            $ticketData['due_date'] = $this->slaCalculator->calculateDueDate(
                $data->categoryId,
                $data->departmentId,
                $data->clientId,
                $data->source
            );
        }
        
        if (!$data->slaId) {
            $ticketData['sla_id'] = $this->slaCalculator->getSlaId(
                $data->categoryId,
                $data->departmentId,
                $data->clientId,
                $data->source
            );
        }
        
        return $ticketData;
    }
}

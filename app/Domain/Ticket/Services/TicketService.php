<?php

namespace App\Domain\Ticket\Services;

use App\Domain\Ticket\DTO\TicketCreateData;
use App\Domain\Ticket\Events\TicketCreatedEvent;
use App\Domain\Ticket\Models\Ticket;
use App\Domain\Ticket\Services\SlaCalculators\SlaCalculatorInterface;
use App\Domain\Ticket\Services\TicketNumberGenerators\TicketNumberGeneratorInterface;

class TicketService
{
    public function __construct(
        private readonly SlaCalculatorInterface $slaCalculator,
        private readonly TicketNumberGeneratorInterface $ticketNumberGenerator
    ) {}

    public function createTicket(TicketCreateData $data): Ticket
    {
        $enrichedData = $this->prepareSlaData($data);
        $enrichedData = $this->prepareTicketNumber($enrichedData);

        $ticket = Ticket::query()->create($enrichedData->toArray());

        event(new TicketCreatedEvent($ticket));

        return $ticket;
    }

    private function prepareSlaData(TicketCreateData $data): TicketCreateData
    {
        $dueDate = $data->dueDate;
        $slaId = $data->slaId;

        if (!$dueDate) {
            $dueDate = $this->slaCalculator->calculateDueDate(
                $data->categoryId,
                $data->departmentId,
                $data->clientId,
                $data->source
            );
        }

        if (!$slaId) {
            $slaId = $this->slaCalculator->getSlaId(
                $data->categoryId,
                $data->departmentId,
                $data->clientId,
                $data->source
            );
        }

        return new TicketCreateData(
            clientId: $data->clientId,
            userId: $data->userId,
            source: $data->source,
            ticketStatusId: $data->ticketStatusId,
            departmentId: $data->departmentId,
            categoryId: $data->categoryId,
            slaId: $slaId,
            dueDate: $dueDate,
            ticketNumber: $data->ticketNumber,
        );
    }

    private function prepareTicketNumber(TicketCreateData $data): TicketCreateData
    {
        if ($data->ticketNumber) {
            return $data;
        }



        $ticketNumber = $this->ticketNumberGenerator->generate(
            $data->categoryId,
            $data->departmentId,
            $data->clientId,
            $data->source
        );

        return new TicketCreateData(
            clientId: $data->clientId,
            userId: $data->userId,
            source: $data->source,
            ticketStatusId: $data->ticketStatusId,
            departmentId: $data->departmentId,
            categoryId: $data->categoryId,
            slaId: $data->slaId,
            dueDate: $data->dueDate,
            ticketNumber: $ticketNumber,
        );
    }
}


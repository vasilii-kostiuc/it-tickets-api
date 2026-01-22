<?php

namespace App\Domain\Ticket\DTO;

use App\Domain\Client\Models\Client;
use App\Domain\Ticket\Enums\TicketSource;
use App\Domain\User\Models\User;
use Illuminate\Support\Carbon;

readonly class TicketCreateData
{
    public function __construct(
        public int          $clientId,
        public int          $userId,
        public TicketSource $source,
        public ?int          $ticketStatusId,
        public ?int         $departmentId = null,
        public ?int         $categoryId = null,
        public ?int         $slaId = null,
        public ?Carbon      $dueDate = null,
        public ?string      $ticketNumber = null,
    )
    {
    }

    public static function collect(
        Client       $client,
        User         $user,
        TicketSource $source,
        int          $statusId,
        array        $params = []
    ): self
    {
        return new self(
            clientId: $client->id,
            userId: $user->id,
            source: $source,
            ticketStatusId: $statusId,
            departmentId: $params['department_id'] ?? null,
            categoryId: $params['category_id'] ?? null,
            slaId: $params['sla_id'] ?? null,
            dueDate: isset($params['due_date']) ? Carbon::parse($params['due_date']) : null,
            ticketNumber: $params['ticket_number'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'client_id' => $this->clientId,
            'user_id' => $this->userId,
            'source' => $this->source->value,
            'ticket_status_id' => $this->ticketStatusId,
            'department_id' => $this->departmentId,
            'category_id' => $this->categoryId,
            'sla_id' => $this->slaId,
            'due_date' => $this->dueDate,
            'ticket_number' => $this->ticketNumber,
        ];
    }
}

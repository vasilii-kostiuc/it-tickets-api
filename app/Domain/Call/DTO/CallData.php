<?php

namespace App\Domain\Call\DTO;

use App\Domain\Call\Enums\CallType;
use App\Domain\Client\Models\Client;
use App\Domain\Ticket\Models\Ticket;
use App\Domain\User\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

readonly class CallData
{
    public function __construct(
        public int $ticketId,
        public int $clientId,
        public int $userId,
        public CallType $type,
        public string $lang,
        public ?string $extension = null,
        public ?string $uniqueId = null,
        public ?Carbon $started = null,
        public ?Carbon $ended = null,
        public ?string $duration = null,
        public ?string $recording = null,
        public string $status = '',
        public bool $redirected = false,
    ) {}

    public static function collect(
        Ticket $ticket,
        Client $client,
        User $user,
        CallType $type,
        string $lang = 'ru',
        ?Carbon $started = null,
        ?string $extension = null,
        ?string $uniqueId = null
    ): self {
        return new self(
            ticketId: $ticket->id,
            clientId: $client->id,
            userId: $user->id,
            type: $type,
            lang: $lang,
            extension: $extension,
            uniqueId: $uniqueId,
            started: $started
        );
    }

    public static function fromTicket(
        Ticket $ticket,
        CallType $type,
        ?string $extension = null,
        ?string $uniqueId = null,
        string $lang = 'ru'
    ): self {
        return new self(
            ticketId: $ticket->id,
            clientId: $ticket->client_id,
            userId: $ticket->user_id,
            type: $type,
            lang: $lang,
            extension: $extension,
            uniqueId: $uniqueId,
            started: now()
        );
    }

    public function toArray(): array
    {
        return [
            'ticket_id' => $this->ticketId,
            'client_id' => $this->clientId,
            'user_id' => $this->userId,
            'type' => $this->type->value,
            'lang' => $this->lang,
            'extension' => $this->extension,
            'unique_id' => $this->uniqueId,
            'started' => $this->started,
            'ended' => $this->ended,
            'duration' => $this->duration,
            'recording' => $this->recording,
            'status' => $this->status,
            'redirected' => $this->redirected,
        ];
    }
}

<?php

namespace App\Http\Resources\Ticket;

use Illuminate\Http\Request;

class TicketResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user->id,
            'client_id' => $this->client->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

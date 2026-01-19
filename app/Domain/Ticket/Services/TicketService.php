<?php

namespace App\Domain\Ticket\Services;

use App\Domain\Ticket\Events\TicketCreatedEvent;
use App\Domain\Ticket\Models\Ticket;

class TicketService
{
    public function createTicket($client, $user, $data){
        $ticketData = [];

        $ticketData['user_id'] = $user->id;
        $ticketData['client_id'] = $client->id;

        $ticketData = array_merge($ticketData, $data);

        $ticket = Ticket::query()->create($ticketData);

        event(new TicketCreatedEvent($ticket));

        return $ticket;
    }




}

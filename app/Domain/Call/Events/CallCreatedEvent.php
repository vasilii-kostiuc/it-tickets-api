<?php

namespace App\Domain\Call\Events;

use App\Domain\Call\DTO\CallData;
use App\Domain\Call\Models\Call;
use App\Domain\Ticket\Models\Ticket;
use Illuminate\Foundation\Events\Dispatchable;

class CallCreatedEvent
{
    use Dispatchable;

    public Call $call;

    public ?CallData $callData;

    public function __construct(Call $call, CallData $callData = null)
    {
        $this->call = $call;
        $this->callData = $callData;
    }
}

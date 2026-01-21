<?php

namespace App\Domain\Call\Services;

use App\Domain\Call\DTO\CallData;
use App\Domain\Call\Events\CallCreatedEvent;
use App\Domain\Call\Models\Call;

class CallService
{
    public function createCall(CallData $callData){
        $call = Call::query()->create($callData->toArray());

        CallCreatedEvent::dispatch($call, $callData);

        return $call;
    }
}

<?php

namespace App\Http\Resources\Sla;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SlaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'duration'       => $this->duration,
            'grace_duration' => $this->grace_duration,
            'schedule_id'    => $this->schedule_id,
            'description'    => $this->description,
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
        ];
    }
}

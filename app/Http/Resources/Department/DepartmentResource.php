<?php

namespace App\Http\Resources\Department;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'ext_mask'   => $this->ext_mask,
            'queue1'     => $this->queue1,
            'queue2'     => $this->queue2,
            'manager_id' => $this->manager_id,
            'sla_id'     => $this->sla_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

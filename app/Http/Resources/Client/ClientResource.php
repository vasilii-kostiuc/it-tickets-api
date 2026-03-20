<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'phone1'     => $this->phone1,
            'phone2'     => $this->phone2,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

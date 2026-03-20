<?php

namespace App\Http\Requests\Sla;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSlaRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'           => 'sometimes|string|max:255|unique:slas,name,' . $this->route('sla')?->id,
            'duration'       => 'sometimes|integer|min:1',
            'grace_duration' => 'nullable|integer|min:0',
            'schedule_id'    => 'nullable|integer|exists:schedules,id',
            'description'    => 'nullable|string',
        ];
    }
}

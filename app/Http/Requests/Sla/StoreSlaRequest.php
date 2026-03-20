<?php

namespace App\Http\Requests\Sla;

use Illuminate\Foundation\Http\FormRequest;

class StoreSlaRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'           => 'required|string|max:255|unique:slas,name',
            'duration'       => 'required|integer|min:1',
            'grace_duration' => 'nullable|integer|min:0',
            'schedule_id'    => 'nullable|integer|exists:schedules,id',
            'description'    => 'nullable|string',
        ];
    }
}

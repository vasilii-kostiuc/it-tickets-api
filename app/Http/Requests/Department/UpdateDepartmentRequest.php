<?php

namespace App\Http\Requests\Department;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDepartmentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'       => 'sometimes|string|max:255|unique:departments,name,' . $this->route('department')?->id,
            'ext_mask'   => 'sometimes|string|max:255',
            'queue1'     => 'sometimes|string|max:255',
            'queue2'     => 'sometimes|string|max:255',
            'manager_id' => 'sometimes|integer|exists:users,id',
            'sla_id'     => 'nullable|integer|exists:slas,id',
        ];
    }
}

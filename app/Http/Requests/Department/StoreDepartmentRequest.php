<?php

namespace App\Http\Requests\Department;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'       => 'required|string|max:255|unique:departments,name',
            'ext_mask'   => 'required|string|max:255',
            'queue1'     => 'required|string|max:255',
            'queue2'     => 'required|string|max:255',
            'manager_id' => 'required|integer|exists:users,id',
            'sla_id'     => 'nullable|integer|exists:slas,id',
        ];
    }
}

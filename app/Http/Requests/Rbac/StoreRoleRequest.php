<?php

namespace App\Http\Requests\Rbac;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "name" => "required|string|unique:roles,name",
            "description" => ['nullable', 'string', 'max:255'],
            "permissions" => "array|min:0",
            "permissions.*" => "integer",
        ];
    }
}

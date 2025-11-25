<?php

namespace App\Http\Requests\Rbac;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "name" => "required|string|max:255|unique:roles,name,{$this->route('role')->id}",
            "description" => ['nullable', 'string', 'max:255'],
        ];
    }
}

<?php

namespace App\Http\Requests\Rbac;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRolePermissionsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "permissions" => "array|min:0"
        ];
    }
}

<?php

namespace App\Http\Requests\Rbac;

use Illuminate\Foundation\Http\FormRequest;

class StorePermissionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "name" => "required|string|unique:permissions,name",
            "display_name" => "required",
        ];
    }
}

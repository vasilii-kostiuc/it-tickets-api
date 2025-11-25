<?php

namespace App\Http\Requests\Rbac;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "name" => "string|unique:permissions,name,{$this->route('permission')->id}",
            "display_name" => "",
        ];
    }
}

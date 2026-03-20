<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'   => 'sometimes|string|max:255',
            'email'  => 'nullable|email|max:255|unique:clients,email,' . $this->route('client')?->id,
            'phone'  => 'sometimes|string|max:50|unique:clients,phone,' . $this->route('client')?->id,
            'phone1' => 'nullable|string|max:50',
            'phone2' => 'nullable|string|max:50',
        ];
    }
}

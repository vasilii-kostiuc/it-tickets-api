<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'   => 'required|string|max:255',
            'email'  => 'nullable|email|max:255|unique:clients,email',
            'phone'  => 'required|string|max:50|unique:clients,phone',
            'phone1' => 'nullable|string|max:50',
            'phone2' => 'nullable|string|max:50',
        ];
    }
}

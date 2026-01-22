<?php

namespace App\Http\Requests\Asterisk;

use Illuminate\Foundation\Http\FormRequest;

class AsteriskCallRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'extension' => ['required', 'string'],
            'phone' => 'required|string',
            'lang' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'lang' => $this->input('lang', 'ro'),
        ]);
    }
}

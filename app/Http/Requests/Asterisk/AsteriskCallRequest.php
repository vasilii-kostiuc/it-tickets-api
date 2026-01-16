<?php

namespace App\Http\Requests\Asterisk;

use Illuminate\Foundation\Http\FormRequest;

class AsteriskCallRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'ext' => ['required', 'string'],
            'phone' => 'required|string',
            'lang' => 'default:ro',
            'codId' => 'default:',
        ];
    }
}

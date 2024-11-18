<?php

namespace App\Http\Requests;

class TransakWebhookRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'data' => ['required', 'string'],
        ];
    }
}

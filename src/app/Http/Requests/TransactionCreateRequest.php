<?php

namespace App\Http\Requests;

use App\Enums\CurrencyEnum;
use Illuminate\Validation\Rule;

class TransactionCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'transaction_id' => ['required', 'string'],
            'payment_method' => ['required', 'string', 'in:credit_card,debit_card'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'country' => ['required', 'string', 'size:3'],
            'currency' => ['required', Rule::enum(CurrencyEnum::class)],
            'description' => ['nullable', 'string', 'max:255'],
            'success_redirect_url' => ['nullable', 'url'],
            'fail_redirect_url' => ['nullable', 'url'],
            'type_of_calculation' => ['required', 'string', 'in:forward_with_fee'],
            'transaction_type' => ['required', 'string', 'in:link']
        ];
    }
}

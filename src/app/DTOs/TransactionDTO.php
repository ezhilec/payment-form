<?php

namespace App\DTOs;

use App\Enums\CurrencyEnum;

class TransactionDTO
{
    public function __construct(
        public string $transactionId,
        public string $paymentMethod,
        public float $amount,
        public string $country,
        public CurrencyEnum $currency,
        public string $typeOfCalculation,
        public string $transactionType,
        public string $status,
        public ?string $description = null,
        public ?string $successRedirectUrl = null,
        public ?string $failRedirectUrl = null,
    ) {
    }
}

<?php

namespace App\Services;

use App\DTOs\TransaskWebhookDTO;
use App\Repositories\BalanceRepository;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class BalanceService
{
    public function __construct(private readonly BalanceRepository $balanceRepository)
    {
    }

    public function increase(float $value, string $currency): float
    {
        $balance = $this->balanceRepository->getByCurrency($currency);
        $newValue = $balance->value + $value;

        $this->balanceRepository->update($balance, [
            'value' => $newValue,
        ]);

        return $newValue;
    }
}

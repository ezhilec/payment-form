<?php

namespace App\Services;

use App\Repositories\BalanceRepository;

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

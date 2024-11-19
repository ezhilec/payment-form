<?php

namespace App\Repositories;

use App\DTOs\TransactionDTO;
use App\Enums\CurrencyEnum;
use App\Models\Balance;
use App\Models\Transaction;

class BalanceRepository
{
    public function getByCurrency(string $currency): Balance
    {
        /* @var Balance $balance */
        $balance = Balance::query()
            ->firstOrCreate(
                ['currency' => $currency],
                ['value' => 0]
            );

        return $balance;
    }

    public function update(Balance $balance, array $fields): Balance
    {
        $balance->update($fields);

        return $balance;
    }
}

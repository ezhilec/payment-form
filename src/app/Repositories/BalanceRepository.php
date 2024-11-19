<?php

namespace App\Repositories;

use App\Models\Balance;

class BalanceRepository
{
    public function getByCurrency(string $currency): Balance
    {
        return Balance::firstOrCreate(
            ['currency' => $currency],
            ['value' => 0]
        );
    }

    public function update(Balance $balance, array $fields): Balance
    {
        $balance->update($fields);

        return $balance;
    }
}

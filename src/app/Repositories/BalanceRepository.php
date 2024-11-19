<?php

namespace App\Repositories;

use App\Models\Balance;

class BalanceRepository
{
    public function getByCurrency(string $currency): Balance
    {
        /** @var Balance $balance */
        $balance = Balance::query()
            ->where('currency', $currency)
            ->firstOrCreate([
                'value' => 0,
                'currency' => $currency,
            ]);

        return $balance;
    }

    public function update(Balance $balance, array $fields): Balance
    {
        $balance->update($fields);

        return $balance;
    }
}

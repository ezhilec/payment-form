<?php

namespace Tests\Unit\Services;

use App\Models\Balance;
use App\Services\BalanceService;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;

class BalanceServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function test_increase_updates_balance_and_returns_new_value(): void
    {
        $currency = 'USD';
        $initialValue = 100.0;
        $increaseValue = 50.0;
        $expectedNewValue = 150.0;

        $balance = Balance::create([
            'currency' => $currency,
            'value' => $initialValue,
        ]);

        $balanceService = app(BalanceService::class);

        $newBalanceValue = $balanceService->increase($increaseValue, $currency);

        $balance->refresh();

        $this->assertEquals($expectedNewValue, $newBalanceValue);
        $this->assertEquals($expectedNewValue, $balance->value);
    }
}

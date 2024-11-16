<?php

namespace App\Services\Commissions;

use App\DTOs\CommissionDetailsDTO;
use App\DTOs\TransactionDTO;
use App\Enums\CryptoCurrencyEnum;
use App\Enums\CryptoCurrencyNetworkEnum;
use App\Enums\CurrencyEnum;

interface CommissionProviderInterface
{
    public function getCommissionDetails(
        CurrencyEnum $fiatCurrency,
        CryptoCurrencyEnum $cryptoCurrency,
        CryptoCurrencyNetworkEnum $cryptoCurrencyNetwork,
        float $fiatAmount,
    ): CommissionDetailsDTO;
}

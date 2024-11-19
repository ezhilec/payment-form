<?php

namespace App\Services\Commissions;

use App\Clients\TransakClient;
use App\DTOs\CommissionDetailsDTO;
use App\Enums\CryptoCurrencyEnum;
use App\Enums\CryptoCurrencyNetworkEnum;
use App\Enums\CurrencyEnum;

class TransakCommissionProvider implements CommissionProviderInterface
{
    public function __construct(private readonly TransakClient $transakClient)
    {
    }

    public function getCommissionDetails(
        CurrencyEnum $fiatCurrency,
        CryptoCurrencyEnum $cryptoCurrency,
        CryptoCurrencyNetworkEnum $cryptoCurrencyNetwork,
        float $fiatAmount,
    ): CommissionDetailsDTO {
        $transakQuoteDTO = $this->transakClient->getQuote(
            fiatCurrency: $fiatCurrency->value,
            cryptoCurrency: $cryptoCurrency->value,
            isBuyOrSell: 'BUY',
            network: $cryptoCurrencyNetwork->value,
            paymentMethod: 'credit_debit_card',
            fiatAmount: $fiatAmount
        );

        return new CommissionDetailsDTO(
            $transakQuoteDTO->getProviderCommission(),
            $transakQuoteDTO->getNetworkCommission()
        );
    }
}

<?php

namespace App\Services\Commissions;

use app\Clients\Transak\TransakClient;
use App\DTOs\TransactionDTO;

class TransakCommissionProvider implements CommissionProviderInterface
{
    public function __construct(private TransakClient $transakClient)
    {
    }

    public function getCommissionDetails(TransactionDTO $transactionDTO): CommissionDetailsDTO
    {
        $transakQuoteDTO = $this->transakClient->getQuote(
            fiatCurrency: $transactionDTO->currency->value,
            cryptoCurrency: 'USDT',
            isBuyOrSell: 'BUY',
            network: 'tron',
            paymentMethod: 'credit_debit_card',
            fiatAmount: $transactionDTO->amount
        );

        return new CommissionDetailsDTO(
            $transakQuoteDTO->getProviderCommission(),
            $transakQuoteDTO->getNetworkCommission()
        );
    }
}

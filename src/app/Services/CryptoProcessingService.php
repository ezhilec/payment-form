<?php

namespace App\Services;

use App\Clients\CryptoProcessing\CryptoProcessingClientInterface;
use App\DTOs\CryptoInvoiceDTO;
use App\Enums\CurrencyEnum;

class CryptoProcessingService
{
    const DEFAULT_CODE = 'usdt_trc20';
    const DEFAULT_TYPE = 'invoice_type_default';

    public function __construct(private readonly CryptoProcessingClientInterface $cryptoProcessingClient)
    {
    }

    public function createInvoice(string $txId, float $amount, CurrencyEnum $currency): CryptoInvoiceDTO
    {
        $response = $this->cryptoProcessingClient->createInvoice(
            txId: $txId,
            code: self::DEFAULT_CODE,
            type: self::DEFAULT_TYPE,
            amount: $amount,
            currency: strtolower($currency->value)
        );

        $amountRequiredUnit = $response['amountRequiredUnit'] ?? '';
        $walletAddress = $response['walletAddress'] ?? '';

        if (empty($amountRequiredUnit) || empty($walletAddress)) {
            throw new \Exception('Invalid response: Missing required fields.');
        }

        return new CryptoInvoiceDTO($amountRequiredUnit, $walletAddress);
    }
}

<?php

namespace App\Services;

use App\Clients\CryptoProcessing\CryptoProcessingClientInterface;
use App\DTOs\CryptoInvoiceDTO;
use App\Enums\CryptoProcessingTransactionStatusEnum;
use App\Enums\CurrencyEnum;
use App\Jobs\GetIncomingTransactionJob;
use Carbon\Carbon;

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

    public function dispatchGetIncomingTransactionJob(string $transactionHash, bool $allowRetry = true, int $delayInSeconds = 0): void
    {
        dispatch(new GetIncomingTransactionJob($transactionHash, $allowRetry))->delay(Carbon::now()->addSeconds($delayInSeconds));
    }

    public function getIncomingTransaction(string $transactionHash): CryptoProcessingTransactionStatusEnum
    {
        $transactionData = $this->cryptoProcessingClient->getIncomingTransaction($transactionHash);

        $status = $transactionData['status'] ?? null;

        return match ($status) {
            'transaction_status_confirmed' => CryptoProcessingTransactionStatusEnum::CONFIRMED,
            'transaction_status_expired' => CryptoProcessingTransactionStatusEnum::EXPIRED,
            'transaction_status_failed' => CryptoProcessingTransactionStatusEnum::FAILED,
            'transaction_status_approve_required' => CryptoProcessingTransactionStatusEnum::APPROVE_REQUIRED,
            default => CryptoProcessingTransactionStatusEnum::OTHER,
        };
    }
}

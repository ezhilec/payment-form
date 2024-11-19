<?php

namespace App\Services;

use App\Clients\CryptoProcessing\CryptoProcessingClientInterface;
use App\DTOs\CryptoInvoiceDTO;
use App\Enums\CryptoProcessingTransactionStatusEnum;
use App\Enums\CurrencyEnum;
use App\Jobs\GetIncomingTransactionJob;
use Carbon\Carbon;
use Illuminate\Support\Facades\Queue;

class CryptoProcessingService
{
    public const DEFAULT_CODE = 'usdt_trc20';
    public const DEFAULT_TYPE = 'invoice_type_default';

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
        Queue::later(Carbon::now()->addSeconds($delayInSeconds), new GetIncomingTransactionJob($transactionHash, $allowRetry));
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

<?php

namespace App\Jobs;

use App\Enums\TransactionStatusEnum;
use App\Services\CryptoProcessingService;
use App\Services\TransactionService;

class GetIncomingTransactionJob extends Job
{
    public const MAX_RETRIES = 10;

    public function __construct(
        private readonly string $transactionHash,
        private readonly bool $allowRetry,
        private int $currentRetry = 1,
    ) {
    }

    public function handle(
        CryptoProcessingService $cryptoProcessingService,
        TransactionService $transactionService,
    ) {
        $status = $cryptoProcessingService->getIncomingTransactionStatus($this->transactionHash);

        if ($this->currentRetry >= self::MAX_RETRIES) {
            $transactionService->processTransactionStatus($this->transactionHash, TransactionStatusEnum::DECLINED);
        } elseif ($status->isFinal() || !$this->allowRetry) {
            $transactionStatus = TransactionStatusEnum::fromCryptoProcessingTransactionStatus($status);
            $transactionService->processTransactionStatus($this->transactionHash, $transactionStatus);
        } elseif ($status->isLastRetry()) {
            $cryptoProcessingService->dispatchGetIncomingTransactionJob($this->transactionHash, false, 5, ++$this->currentRetry);
        } else {
            $cryptoProcessingService->dispatchGetIncomingTransactionJob($this->transactionHash, true, 30, ++$this->currentRetry);
        }
    }
}

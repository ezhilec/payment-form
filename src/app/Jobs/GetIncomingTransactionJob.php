<?php

namespace App\Jobs;

use App\Enums\TransactionStatusEnum;
use App\Services\CryptoProcessingService;
use App\Services\TransactionService;

class GetIncomingTransactionJob extends Job
{
    public function __construct(private readonly string $transactionHash, private readonly bool $allowRetry)
    {
    }

    public function handle(
        CryptoProcessingService $cryptoProcessingService,
        TransactionService $transactionService,
    ) {
        $status = $cryptoProcessingService->getIncomingTransaction($this->transactionHash);

        if ($status->isFinal() || !$this->allowRetry) {
            $transactionStatus = TransactionStatusEnum::fromCryptoProcessingTransactionStatus($status);
            $transactionService->processTransactionStatus($this->transactionHash, $transactionStatus);
        } elseif ($status->isLastRetry()) {
            $cryptoProcessingService->dispatchGetIncomingTransactionJob($this->transactionHash, false, 5);
        } else {
            $cryptoProcessingService->dispatchGetIncomingTransactionJob($this->transactionHash, true, 30);
        }
    }
}

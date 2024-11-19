<?php

namespace App\Jobs;

use App\Enums\TransactionStatusEnum;
use App\Repositories\TransactionRepository;
use App\Services\CryptoProcessingService;
use App\Services\SendCallbackService;
use App\Services\TransactionService;

class GetIncomingTransactionJob extends Job
{
    public function __construct(private readonly string $transactionHash, private readonly bool $allowRetry)
    {
    }

    public function handle(
        CryptoProcessingService $cryptoProcessingService,
        TransactionService $transactionService,
        TransactionRepository $transactionRepository,
        SendCallbackService $sendCallbackService,
    ) {
        $status = $cryptoProcessingService->getIncomingTransaction($this->transactionHash);

        if ($status->isFinal() || !$this->allowRetry) {
            $transactionStatus = TransactionStatusEnum::fromCryptoProcessingTransactionStatus($status);

            $transaction = $transactionRepository->getByTransactionId($this->transactionHash);
            $transactionService->updateTransactionStatus($transaction, $transactionStatus);
            // todo change balance
            $sendCallbackService->sendTransactionStatusCallback(
                callbackUrl: $transaction->success_redirect_url, // todo check redirect url according the status
                transactionId: $transaction->transaction_id, // todo use dto instead
                status: $transactionStatus
            );
        } elseif ($status->isLastRetry()) {
            $cryptoProcessingService->dispatchGetIncomingTransactionJob($this->transactionHash, false, 5);
        } else {
            $cryptoProcessingService->dispatchGetIncomingTransactionJob($this->transactionHash, true, 30);
        }
    }
}

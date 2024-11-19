<?php

namespace App\Services;

use App\DTOs\TransactionDTO;
use App\Enums\TransactionStatusEnum;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use App\Services\Commissions\CommissionProviderInterface;
use App\Services\Commissions\TransakCommissionProvider;
use http\Exception\InvalidArgumentException;
use Illuminate\Contracts\Container\Container;

class TransactionService
{
    public function __construct(
        private readonly TransactionRepository $transactionRepository,
        private readonly Container $container,
        private readonly SendCallbackService $sendCallbackService,
        private readonly BalanceService $balanceService,
    ) {
    }

    public function createTransaction(TransactionDTO $transactionDTO): Transaction
    {
        return $this->transactionRepository->store($transactionDTO);
    }

    public function getCommissionProvider(): CommissionProviderInterface
    {
        return $this->container->make(TransakCommissionProvider::class);
    }

    public function updateTransactionStatus(Transaction $transaction, TransactionStatusEnum $status): Transaction
    {
        return $this->transactionRepository->update($transaction, [
            'status' => $status->value
        ]);
    }

    public function getCallbackUrlByStatus(Transaction $transaction): string
    {
        return match ($transaction->status) {
            TransactionStatusEnum::PENDING->value,
            TransactionStatusEnum::DECLINED->value,
            TransactionStatusEnum::NEED_APPROVE->value => $transaction->fail_redirect_url,
            TransactionStatusEnum::SUCCESS->value => $transaction->success_redirect_url,
            default => throw new InvalidArgumentException('Invalid transaction status: ' . $transaction->status),
        };
    }

    public function processTransactionStatus(
        string $transactionId,
        TransactionStatusEnum $status
    ): void {
        $transaction = $this->transactionRepository->getByTransactionId($transactionId);

        $this->updateTransactionStatus($transaction, $status);

        $this->balanceService->increase($transaction->amount, $transaction->currency);

        $redirectUrl = $this->getCallbackUrlByStatus($transaction);

        $this->sendCallbackService->sendTransactionStatusCallback(
            callbackUrl: $redirectUrl,
            transactionId: $transaction->transaction_id,
            status: $status
        );
    }
}

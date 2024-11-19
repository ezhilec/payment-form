<?php

namespace App\Services;

use App\DTOs\TransactionDTO;
use App\Enums\TransactionStatusEnum;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use App\Services\Commissions\CommissionProviderInterface;
use App\Services\Commissions\TransakCommissionProvider;
use Illuminate\Contracts\Container\Container;

class TransactionService
{
    public function __construct(
        private readonly TransactionRepository $transactionRepository,
        private readonly Container $container
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
        $this->transactionRepository->update($transaction, [
            'status' => $status->value
        ]);
    }
}

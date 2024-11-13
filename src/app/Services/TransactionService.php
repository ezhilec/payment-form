<?php

namespace App\Services;

use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use App\Services\Commissions\CommissionProviderInterface;
use App\Services\Commissions\TransakCommissionProvider;

class TransactionService
{
    protected TransactionRepository $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function createTransaction(array $data): Transaction
    {
        return $this->transactionRepository->store($data);
    }

    public function getCommissionProvider(): CommissionProviderInterface
    {
        return new TransakCommissionProvider();
    }
}

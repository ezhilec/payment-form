<?php

namespace App\Repositories;

use App\DTOs\TransactionDTO;
use App\Models\Transaction;

class TransactionRepository
{
    public function store(TransactionDTO $transactionDTO): Transaction
    {
        return Transaction::create([
            'transaction_id' => $transactionDTO->transactionId,
            'payment_method' => $transactionDTO->paymentMethod,
            'amount' => $transactionDTO->amount,
            'country' => $transactionDTO->country,
            'currency' => $transactionDTO->currency,
            'description' => $transactionDTO->description,
            'success_redirect_url' => $transactionDTO->successRedirectUrl,
            'fail_redirect_url' => $transactionDTO->failRedirectUrl,
            'type_of_calculation' => $transactionDTO->typeOfCalculation,
            'transaction_type' => $transactionDTO->transactionType,
            'status' => $transactionDTO->status
        ]);
    }
}

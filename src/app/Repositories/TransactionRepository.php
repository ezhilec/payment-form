<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Enums\TransactionStatusEnum;

class TransactionRepository
{
    public function store(array $data): Transaction
    {
        $data['status'] = $data['status'] ?? TransactionStatusEnum::PENDING;

        if (!Transaction::isValidStatus($data['status'])) {
            throw new \InvalidArgumentException('Invalid status value');
        }

        return Transaction::create($data);
    }
}

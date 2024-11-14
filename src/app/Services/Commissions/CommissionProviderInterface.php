<?php

namespace App\Services\Commissions;

use App\DTOs\TransactionDTO;

interface CommissionProviderInterface
{
    public function getCommissionDetails(TransactionDTO $transactionDTO): CommissionDetailsDTO;
}

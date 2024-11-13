<?php

namespace App\Services\Commissions;

use App\Models\Transaction;

interface CommissionProviderInterface
{
    public function getCommissionDetails(Transaction $transaction): CommissionDetailsDTO;
}

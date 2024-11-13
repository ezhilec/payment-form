<?php

namespace App\Services\Commissions;

use App\Models\Transaction;

class TransakCommissionProvider implements CommissionProviderInterface
{
    public function getCommissionDetails(Transaction $transaction): CommissionDetailsDTO
    {
        $providerCommission = 5.0;
        $networkCommission = 2.5;

        return new CommissionDetailsDTO($providerCommission, $networkCommission);
    }
}

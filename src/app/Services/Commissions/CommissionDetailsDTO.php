<?php

namespace App\Services\Commissions;

class CommissionDetailsDTO
{
    public function __construct(
        private readonly float $providerCommission,
        private readonly float $networkCommission
    ) {
    }

    public function getProviderCommission(): float
    {
        return $this->providerCommission;
    }

    public function getNetworkCommission(): float
    {
        return $this->networkCommission;
    }
}

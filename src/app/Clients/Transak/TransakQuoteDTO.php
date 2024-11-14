<?php

namespace App\Clients\Transak;

class TransakQuoteDTO
{
    private float $providerCommission;
    private float $networkCommission;

    public function __construct(array $data)
    {
        foreach ($data['feeBreakdown'] as $fee) {
            if ($fee['id'] === 'transak_fee') {
                $this->providerCommission = $fee['value'];
            }
            if ($fee['id'] === 'network_fee') {
                $this->networkCommission = $fee['value'];
            }
        }
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

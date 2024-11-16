<?php

namespace App\DTOs;

class CryptoInvoiceDTO
{
    public function __construct(
        private readonly string $amount,
        private readonly string $walletAddress
    ) {
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getWalletAddress(): string
    {
        return $this->walletAddress;
    }
}

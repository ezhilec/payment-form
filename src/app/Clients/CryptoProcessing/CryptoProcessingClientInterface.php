<?php

namespace App\Clients\CryptoProcessing;

interface CryptoProcessingClientInterface
{
    public function createInvoice(string $txId, string $code, string $type, float $amount, string $currency): array;

    public function getIncomingTransaction(string $idOrTxHash): array;
}

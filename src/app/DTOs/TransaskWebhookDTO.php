<?php

namespace App\DTOs;

class TransaskWebhookDTO
{
    private string $transactionHash;

    public function __construct(array $decodedData)
    {
        if (!isset($decodedData['webhookData']->transactionHash)) {
            throw new \Exception('Decoded data does not contain webhookData.');
        }

        $this->transactionHash = $decodedData['webhookData']->transactionHash;
    }

    public function getTransactionHash(): string
    {
        return $this->transactionHash;
    }
}

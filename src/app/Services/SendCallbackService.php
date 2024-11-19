<?php

namespace App\Services;

use App\Clients\CallbackClient;
use App\Enums\TransactionStatusEnum;

class SendCallbackService
{
    public function __construct(private CallbackClient $callbackClient)
    {
    }

    public function sendTransactionStatusCallback(string $callbackUrl, string $transactionId, TransactionStatusEnum $status): bool
    {
        $data = [
            'transaction_id' => $transactionId,
            'status' => $status->value,
        ];

        return $this->callbackClient->postTransactionCallback($callbackUrl, $data);
    }
}

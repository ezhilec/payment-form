<?php

namespace App\Enums;

enum CryptoProcessingTransactionStatusEnum: string
{
    case CONFIRMED = 'transaction_status_confirmed';
    case EXPIRED = 'transaction_status_expired';
    case FAILED = 'transaction_status_failed';
    case APPROVE_REQUIRED = 'transaction_status_approve_required';
    case OTHER = 'other';

    public function isFinal(): bool
    {
        return in_array($this, [
            self::CONFIRMED,
            self::EXPIRED,
            self::FAILED,
        ]);
    }

    public function isLastRetry(): bool
    {
        return $this == self::APPROVE_REQUIRED;
    }
}

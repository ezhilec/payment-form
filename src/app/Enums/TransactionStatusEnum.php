<?php

namespace App\Enums;

enum TransactionStatusEnum: string
{
    case PENDING = 'pending';
    case SUCCESS = 'success';
    case DECLINED = 'declined';
    case NEED_APPROVE = 'need_approve';

    public static function fromCryptoProcessingTransactionStatus(CryptoProcessingTransactionStatusEnum $status): self
    {
        return match ($status) {
            CryptoProcessingTransactionStatusEnum::CONFIRMED => self::SUCCESS,
            CryptoProcessingTransactionStatusEnum::EXPIRED,
            CryptoProcessingTransactionStatusEnum::FAILED,
            CryptoProcessingTransactionStatusEnum::OTHER => self::DECLINED,
            CryptoProcessingTransactionStatusEnum::APPROVE_REQUIRED => self::NEED_APPROVE,
        };
    }
}

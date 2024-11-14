<?php

namespace App\Enums;

class TransactionStatusEnum
{
    public const PENDING = 'pending';
    public const SUCCESS = 'success';
    public const DECLINED = 'declined';

    public static function getAllStatuses(): array
    {
        return [
            self::PENDING,
            self::SUCCESS,
            self::DECLINED,
        ];
    }
}

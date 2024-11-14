<?php

namespace App\Enums;

enum TransactionStatusEnum: string
{
    case PENDING = 'pending';
    case SUCCESS = 'success';
    case DECLINED = 'declined';
}

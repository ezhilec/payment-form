<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\TransactionStatusEnum;

class Transaction extends Model
{
    protected $fillable = [
        'transaction_id',
        'payment_method',
        'amount',
        'country',
        'currency',
        'description',
        'success_redirect_url',
        'fail_redirect_url',
        'type_of_calculation',
        'transaction_type',
        'status',
    ];

    public static function isValidStatus(string $status): bool
    {
        return in_array($status, TransactionStatusEnum::getAllStatuses(), true);
    }
}

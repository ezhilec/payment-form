<?php

namespace App\Http\Controllers;

use App\DTOs\TransactionDTO;
use App\Enums\TransactionStatusEnum;
use App\Http\Requests\TransactionCreateRequest;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function __construct(private readonly TransactionService $transactionService)
    {
    }

    /**
     * @throws ValidationException
     */
    public function create(TransactionCreateRequest $request): JsonResponse
    {
        $transactionDTO = new TransactionDTO(
            transactionId: $request->get('transaction_id'),
            paymentMethod: $request->get('payment_method'),
            amount: $request->get('amount'),
            country: $request->get('country'),
            currency: $request->get('currency'),
            typeOfCalculation: $request->get('type_of_calculation'),
            transactionType: $request->get('transaction_type'),
            status: TransactionStatusEnum::PENDING->value,
            description: $request->get('description') ?? null,
            successRedirectUrl: $request->get('success_redirect_url') ?? null,
            failRedirectUrl: $request->get('fail_redirect_url') ?? null,
        );

        $transaction = $this->transactionService->createTransaction($transactionDTO);

        $commissionProvider = $this->transactionService->getCommissionProvider();
        $commissionDetails = $commissionProvider->getCommissionDetails($transactionDTO);

        return response()->json($transaction, 201);
    }
}

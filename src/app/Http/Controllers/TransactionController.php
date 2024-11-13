<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function __construct(private readonly TransactionService $transactionService)
    {
    }

    /**
     * @throws ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $validated = $this->validate($request, [
            'transaction_id' => ['required', 'string'],
            'payment_method' => ['required', 'string', 'in:credit_card,debit_card'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'country' => ['required', 'string', 'size:3'],
            'currency' => ['required', 'string', 'size:3'],
            'description' => ['required', 'string', 'max:255'],
            'success_redirect_url' => ['required', 'url'],
            'fail_redirect_url' => ['required', 'url'],
            'type_of_calculation' => ['required', 'string', 'in:forward_with_fee'],
            'transaction_type' => ['required', 'string', 'in:link']
        ]);

        $transaction = $this->transactionService->createTransaction($validated);

        $commissionProvider = $this->transactionService->getCommissionProvider();
        $commissionDetails = $commissionProvider->getCommissionDetails($transaction);

        return response()->json($transaction, 201);
    }
}

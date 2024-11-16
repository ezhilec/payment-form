<?php

namespace App\Http\Controllers;

use App\DTOs\TransactionDTO;
use App\Enums\CountryEnum;
use App\Enums\CryptoCurrencyEnum;
use App\Enums\CryptoCurrencyNetworkEnum;
use App\Enums\CurrencyEnum;
use App\Enums\TransactionStatusEnum;
use App\Http\Requests\TransactionCreateRequest;
use App\Services\CryptoProcessingService;
use App\Services\PaymentFormService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function __construct(
        private readonly TransactionService $transactionService,
        private readonly CryptoProcessingService $cryptoProcessingService,
        private readonly PaymentFormService $paymentFormService,
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function create(TransactionCreateRequest $request): View
    {
        $transactionDTO = new TransactionDTO(
            transactionId: $request->get('transaction_id'),
            paymentMethod: $request->get('payment_method'),
            amount: $request->get('amount'),
            country: CountryEnum::from($request->get('country')),
            currency: CurrencyEnum::from($request->get('currency')),
            typeOfCalculation: $request->get('type_of_calculation'),
            transactionType: $request->get('transaction_type'),
            status: TransactionStatusEnum::PENDING->value,
            description: $request->get('description') ?? null,
            successRedirectUrl: $request->get('success_redirect_url') ?? null,
            failRedirectUrl: $request->get('fail_redirect_url') ?? null,
        );

        $transaction = $this->transactionService->createTransaction($transactionDTO);

        $cryptoCurrency = CryptoCurrencyEnum::USDT;
        $cryptoCurrencyNetwork = CryptoCurrencyNetworkEnum::tron;

        $commissionProvider = $this->transactionService->getCommissionProvider();
        $commissionDetailsDTO = $commissionProvider->getCommissionDetails(
            fiatCurrency: $transactionDTO->currency,
            cryptoCurrency: $cryptoCurrency,
            cryptoCurrencyNetwork: $cryptoCurrencyNetwork,
            fiatAmount: $transactionDTO->amount,
        );

        $transactionTax = $commissionDetailsDTO->getNetworkCommission() +
            $commissionDetailsDTO->getProviderCommission();

        $cryptoInvoiceAmount = $transactionDTO->amount - $transactionTax;

        $cryptoInvoiceDTO = $this->cryptoProcessingService->createInvoice(
            txId: $transactionDTO->transactionId,
            amount: $cryptoInvoiceAmount,
            currency: $transactionDTO->currency
        );

        $paymentFormUrl = $this->paymentFormService->getLink(
            fiatCurrency: $transactionDTO->currency,
            fiatAmount: $transactionDTO->amount,
            cryptoCurrencyCode: $cryptoCurrency,
            network: $cryptoCurrencyNetwork,
            walletAddress: $cryptoInvoiceDTO->getWalletAddress(),
        );

        return view('payment', ['url' => $paymentFormUrl]);
    }
}

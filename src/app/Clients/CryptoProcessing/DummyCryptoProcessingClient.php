<?php

namespace App\Clients\CryptoProcessing;

class DummyCryptoProcessingClient implements CryptoProcessingClientInterface
{
    public function createInvoice(string $txId, string $code, string $type, float $amount, string $currency): array
    {
        return [
            "txId" => "281020243",
            "walletAddress" => "TNWCCipitH9tjtmMeaJi25bGqdkLEpbmog",
            "walletType" => "wallet_type_invoice",
            "codeRequested" => "usdt_trc20",
            "amountRequired" => "143010000",
            "amountRequiredUnit" => "143.01",
            "expiredAt" => null,
            "status" => "transaction_status_pending",
            "txHash" => "",
            "amount" => "",
            "confirmations" => "0",
            "createdAt" => "2024-10-28T09:18:58.663024053Z",
            "processingFeePercent" => 1,
            "processingFee" => "",
            "id" => "",
            "exchangeRate" => [
                "currency" => "usd",
                "exchangeRate" => 0.9990236853577911,
                "currencyAmount" => 142.87037724301769,
                "code" => "usdt_trc20"
            ],
            "link" => [
                "self" => "",
                "related" => [
                    [
                        "href" => "https://pay.sandbox.xmx.link/...",
                        "title" => "Payment link",
                        "name" => "payment_link"
                    ],
                    [
                        "href" => "/v1/payment-link/vendors",
                        "title" => "",
                        "name" => "onramp_link_vendors"
                    ]
                ]
            ],
            "amountUnit" => "",
            "type" => "invoice",
            "comment" => ""
        ];
    }
}

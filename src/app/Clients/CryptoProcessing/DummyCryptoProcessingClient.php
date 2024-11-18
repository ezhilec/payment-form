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

    public function getIncomingTransaction(string $idOrTxHash): array
    {
        return [
            "txId" => "0",
            "walletAddress" => "T000000000000000000000000000000000",
            "codeRequested" => "trx",
            "amountRequired" => "0",
            "amountRequiredUnit" => "0",
            "expiredAt" => "0001-01-01T00:00:00Z",
            "status" => "transaction_status_approve_required",
            "txHash" => "0000000000000000000000000000000000000000000000000000000000000000",
            "amount" => "13960716",
            "confirmations" => "0",
            "createdAt" => "2023-04-13T16:11:08Z",
            "processingFeePercent" => 0,
            "processingFeeUnit" => "0",
            "id" => "Nb5Yxe2SL6DABeGp1MTqkUAncsflJZarnB-sigIpZ-4",
            "exchangeRate" => [
                "currency" => "usd",
                "exchangeRate" => 0.30072662361337,
                "currencyAmount" => 4.19835899,
                "code" => "trx"
            ],
            "link" => [
                "self" => "/v1/transaction/incoming/0000000000000000000000000000000000000000000000000000000000000000",
                "related" => [
                    [
                        "href" => "/v1/wallet/T000000000000000000000000000000000",
                        "title" => "Wallet",
                        "name" => "wallet"
                    ],
                    [
                        "href" => "/v1/transaction/incoming/0000000000000000000000000000000000000000000000000000000000000000/approve",
                        "title" => "Incoming transaction approve",
                        "name" => "incoming_transaction_approve"
                    ],
                    [
                        "href" => "/v1/transaction/incoming/0000000000000000000000000000000000000000000000000000000000000000/refund",
                        "title" => "Incoming transaction refund",
                        "name" => "incoming_transaction_refund"
                    ]
                ]
            ]
        ];
    }
}

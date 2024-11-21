<?php

namespace Tests\Feature\Http\Controllers;

use App\Clients\CryptoProcessing\CryptoProcessingClient;
use App\Clients\TransakClient;
use App\DTOs\TransakQuoteDTO;
use App\Enums\CountryEnum;
use App\Enums\CurrencyEnum;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;

class TransactionControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_invalid_request_returns_validation_errors(): void
    {
        $payload = [
            'transaction_id' => '',
            'payment_method' => 'invalid_method',
            'amount' => -10,
            'country' => 'INVALID_COUNTRY',
            'currency' => 'INVALID_CURRENCY',
            'type_of_calculation' => 'invalid_calculation',
            'transaction_type' => 'invalid_type',
        ];

        $response = $this->json(
            'POST',
            route('transactions.store'),
            $payload
        );

        $response->assertResponseStatus(422);

        $this->seeJsonStructure([
            'transaction_id',
            'payment_method',
            'amount',
            'country',
            'currency',
            'type_of_calculation',
            'transaction_type',
        ]);
    }


    public function test_valid_request_creates_transaction_and_generates_correct_link(): void
    {
        $transakClientMock = \Mockery::mock(TransakClient::class);
        $transakClientMock->shouldReceive('getQuote')
            ->once()
            ->andReturn(
                new TransakQuoteDTO([
                    'feeBreakdown' => [
                        [
                            'name' => 'Transak fee',
                            'value' => 1,
                            'id' => 'transak_fee',
                            'ids' => [
                                'transak_fee'
                            ]
                        ],
                        [
                            'name' => 'Network/Exchange fee',
                            'value' => 2,
                            'id' => 'network_fee',
                            'ids' => [
                                'network_fee'
                            ]
                        ]
                    ]
                ])
            );

        $cryptoProcessingClientMock = \Mockery::mock(CryptoProcessingClient::class);
        $cryptoProcessingClientMock->shouldReceive('createInvoice')
            ->once()
            ->withArgs(
                [
                    '12345',
                    'usdt_trc20',
                    'invoice_type_default',
                    97,
                    'usd',
                ]
            )
            ->andReturn(
                [
                    'amountRequiredUnit' => '97',
                    'walletAddress' => 'wallet-address-123'
                ]
            );

        $this->app->instance(TransakClient::class, $transakClientMock);
        $this->app->instance(CryptoProcessingClient::class, $cryptoProcessingClientMock);

        $payload = [
            'transaction_id' => '12345',
            'payment_method' => 'credit_card',
            'amount' => 100,
            'country' => CountryEnum::USA->value,
            'currency' => CurrencyEnum::USD->value,
            'type_of_calculation' => 'forward_with_fee',
            'transaction_type' => 'link',
            'description' => 'Test transaction',
            'success_redirect_url' => 'https://success.url',
            'fail_redirect_url' => 'https://fail.url',
        ];

        $response = $this->json(
            'POST',
            route('transactions.store'),
            $payload,
        );

        $response->assertResponseStatus(200);

        $this->seeInDatabase('transactions', [
            'transaction_id' => '12345',
            'amount' => 100,
            'currency' => 'USD',
        ]);

        $htmlContent = $response->response->getContent();

        $this->assertStringContainsString('fiatAmount=100', $htmlContent);
        $this->assertStringContainsString('fiatCurrency=USD', $htmlContent);
        $this->assertStringContainsString('walletAddress=wallet-address-123', $htmlContent);
    }
}

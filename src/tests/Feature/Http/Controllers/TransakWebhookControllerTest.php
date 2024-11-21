<?php

namespace Tests\Feature\Http\Controllers;

use App\Clients\CallbackClient;
use App\Clients\CryptoProcessing\CryptoProcessingClient;
use App\DTOs\TransaskWebhookDTO;
use App\Enums\CountryEnum;
use App\Enums\CurrencyEnum;
use App\Enums\TransactionStatusEnum;
use App\Models\Transaction;
use App\Services\TransakWebhookService;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;
use Mockery;

class TransakWebhookControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_temporay_status_dispatches_get_incoming_transaction_job_again(): void
    {
        $transaction = Transaction::create([
            'transaction_id' => 'transaction_hash_123',
            'payment_method' => 'credit_card',
            'amount' => 100,
            'country' => CountryEnum::USA->value,
            'currency' => CurrencyEnum::USD->value,
            'type_of_calculation' => 'forward_with_fee',
            'transaction_type' => 'link',
            'description' => 'Test transaction',
            'success_redirect_url' => 'https://success.url',
            'fail_redirect_url' => 'https://fail.url',
            'status' => TransactionStatusEnum::PENDING->value,
        ]);

        $transakWebhookServiceMock = Mockery::mock(TransakWebhookService::class);
        $cryptoProcessingClientMock = \Mockery::mock(CryptoProcessingClient::class);
        $callbackClientMock = \Mockery::mock(CallbackClient::class);

        $decodedData = [
            'webhookData' => (object)[
                'status' => 'temporary_status',
                'transactionHash' => 'transaction_hash_123',
            ]
        ];

        $transakWebhookServiceMock->shouldReceive('decodeData')
            ->once()
            ->andReturn(new TransaskWebhookDTO($decodedData));

        $cryptoProcessingClientMock->shouldReceive('getIncomingTransaction')
            ->times(10)
            ->andReturn([
                'status' => 'temporary_status',
            ]);

        $callbackClientMock->shouldReceive('postTransactionCallback')
            ->once()
            ->andReturn(true);

        $this->app->instance(TransakWebhookService::class, $transakWebhookServiceMock);
        $this->app->instance(CryptoProcessingClient::class, $cryptoProcessingClientMock);
        $this->app->instance(CallbackClient::class, $callbackClientMock);

        $payload = ['data' => 'valid_encrypted_data'];

        $response = $this->json('POST', route('transak-webhook'), $payload);

        $response->assertResponseStatus(200);

        $transaction->refresh();
        $this->assertEquals(TransactionStatusEnum::DECLINED->value, $transaction->status);
    }

    public function test_transaction_status_confirmed_updates_transaction_status_to_success(): void
    {
        $transaction = Transaction::create([
            'transaction_id' => 'transaction_hash_123',
            'payment_method' => 'credit_card',
            'amount' => 100,
            'country' => CountryEnum::USA->value,
            'currency' => CurrencyEnum::USD->value,
            'type_of_calculation' => 'forward_with_fee',
            'transaction_type' => 'link',
            'description' => 'Test transaction',
            'success_redirect_url' => 'https://success.url',
            'fail_redirect_url' => 'https://fail.url',
            'status' => TransactionStatusEnum::PENDING->value,
        ]);

        $transakWebhookServiceMock = Mockery::mock(TransakWebhookService::class);
        $cryptoProcessingClientMock = \Mockery::mock(CryptoProcessingClient::class);
        $callbackClientMock = \Mockery::mock(CallbackClient::class);

        $decodedData = [
            'webhookData' => (object)[
                'status' => 'transaction_status_confirmed',
                'transactionHash' => 'transaction_hash_123',
            ]
        ];

        $transakWebhookServiceMock->shouldReceive('decodeData')
            ->once()
            ->andReturn(new TransaskWebhookDTO($decodedData));

        $cryptoProcessingClientMock->shouldReceive('getIncomingTransaction')
            ->once()
            ->andReturn([
                'status' => 'transaction_status_confirmed',
            ]);

        $callbackClientMock->shouldReceive('postTransactionCallback')
            ->once()
            ->andReturn(true);

        $this->app->instance(TransakWebhookService::class, $transakWebhookServiceMock);
        $this->app->instance(CryptoProcessingClient::class, $cryptoProcessingClientMock);
        $this->app->instance(CallbackClient::class, $callbackClientMock);

        $payload = ['data' => 'valid_encrypted_data'];

        $response = $this->json('POST', route('transak-webhook'), $payload);

        $response->assertResponseStatus(200);

        $transaction->refresh();
        $this->assertEquals(TransactionStatusEnum::SUCCESS->value, $transaction->status);
    }

    public function test_transaction_status_failed_updates_transaction_status_to_declined(): void
    {
        $transaction = Transaction::create([
            'transaction_id' => 'transaction_hash_123',
            'payment_method' => 'credit_card',
            'amount' => 100,
            'country' => CountryEnum::USA->value,
            'currency' => CurrencyEnum::USD->value,
            'type_of_calculation' => 'forward_with_fee',
            'transaction_type' => 'link',
            'description' => 'Test transaction',
            'success_redirect_url' => 'https://success.url',
            'fail_redirect_url' => 'https://fail.url',
            'status' => TransactionStatusEnum::PENDING->value,
        ]);

        $transakWebhookServiceMock = Mockery::mock(TransakWebhookService::class);
        $cryptoProcessingClientMock = \Mockery::mock(CryptoProcessingClient::class);
        $callbackClientMock = \Mockery::mock(CallbackClient::class);

        $decodedData = [
            'webhookData' => (object)[
                'status' => 'transaction_status_failed',
                'transactionHash' => 'transaction_hash_123',
            ]
        ];

        $transakWebhookServiceMock->shouldReceive('decodeData')
            ->once()
            ->andReturn(new TransaskWebhookDTO($decodedData));

        $cryptoProcessingClientMock->shouldReceive('getIncomingTransaction')
            ->once()
            ->andReturn([
                'status' => 'transaction_status_failed',
            ]);

        $callbackClientMock->shouldReceive('postTransactionCallback')
            ->once()
            ->andReturn(true);

        $this->app->instance(TransakWebhookService::class, $transakWebhookServiceMock);
        $this->app->instance(CryptoProcessingClient::class, $cryptoProcessingClientMock);
        $this->app->instance(CallbackClient::class, $callbackClientMock);

        $payload = ['data' => 'valid_encrypted_data'];

        $response = $this->json('POST', route('transak-webhook'), $payload);

        $response->assertResponseStatus(200);

        $transaction->refresh();
        $this->assertEquals(TransactionStatusEnum::DECLINED->value, $transaction->status);
    }

    public function test_transaction_status_failed_dispatches_get_incoming_transaction_job_again(): void
    {
        $transaction = Transaction::create([
            'transaction_id' => 'transaction_hash_123',
            'payment_method' => 'credit_card',
            'amount' => 100,
            'country' => CountryEnum::USA->value,
            'currency' => CurrencyEnum::USD->value,
            'type_of_calculation' => 'forward_with_fee',
            'transaction_type' => 'link',
            'description' => 'Test transaction',
            'success_redirect_url' => 'https://success.url',
            'fail_redirect_url' => 'https://fail.url',
            'status' => TransactionStatusEnum::PENDING->value,
        ]);

        $transakWebhookServiceMock = Mockery::mock(TransakWebhookService::class);
        $cryptoProcessingClientMock = \Mockery::mock(CryptoProcessingClient::class);
        $callbackClientMock = \Mockery::mock(CallbackClient::class);

        $decodedData = [
            'webhookData' => (object)[
                'status' => 'transaction_status_approve_required',
                'transactionHash' => 'transaction_hash_123',
            ]
        ];

        $transakWebhookServiceMock->shouldReceive('decodeData')
            ->once()
            ->andReturn(new TransaskWebhookDTO($decodedData));

        $cryptoProcessingClientMock->shouldReceive('getIncomingTransaction')
            ->twice()
            ->andReturn([
                'status' => 'transaction_status_approve_required',
            ]);

        $callbackClientMock->shouldReceive('postTransactionCallback')
            ->once()
            ->andReturn(true);

        $this->app->instance(TransakWebhookService::class, $transakWebhookServiceMock);
        $this->app->instance(CryptoProcessingClient::class, $cryptoProcessingClientMock);
        $this->app->instance(CallbackClient::class, $callbackClientMock);

        $payload = ['data' => 'valid_encrypted_data'];

        $response = $this->json('POST', route('transak-webhook'), $payload);

        $response->assertResponseStatus(200);

        $transaction->refresh();
        $this->assertEquals(TransactionStatusEnum::NEED_APPROVE->value, $transaction->status);
    }
}

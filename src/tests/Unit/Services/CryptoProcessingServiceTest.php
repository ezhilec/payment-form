<?php

namespace Tests\Unit\Services;

use App\Clients\CryptoProcessing\CryptoProcessingClientInterface;
use App\DTOs\CryptoInvoiceDTO;
use App\Enums\CryptoProcessingTransactionStatusEnum;
use App\Enums\CurrencyEnum;
use App\Exceptions\InvoiceCreationException;
use App\Services\CryptoProcessingService;
use Mockery;
use Tests\TestCase;

class CryptoProcessingServiceTest extends TestCase
{
    public function test_create_invoice_returns_valid_crypto_invoice_dto(): void
    {
        $cryptoProcessingClientMock = Mockery::mock(CryptoProcessingClientInterface::class);
        $cryptoProcessingClientMock->shouldReceive('createInvoice')
            ->once()
            ->withArgs([
                'tx123',
                'usdt_trc20',
                'invoice_type_default',
                100.0,
                'usd',
            ])
            ->andReturn([
                'amountRequiredUnit' => '100.0',
                'walletAddress' => 'TRX123WalletAddress',
            ]);

        $this->app->instance(CryptoProcessingClientInterface::class, $cryptoProcessingClientMock);

        $service = app(CryptoProcessingService::class);

        $result = $service->createInvoice('tx123', 100.0, CurrencyEnum::USD);

        $this->assertInstanceOf(CryptoInvoiceDTO::class, $result);
        $this->assertEquals('100.0', $result->getAmount());
        $this->assertEquals('TRX123WalletAddress', $result->getWalletAddress());
    }

    public function test_create_invoice_throws_exception_on_invalid_response(): void
    {
        $cryptoProcessingClientMock = Mockery::mock(CryptoProcessingClientInterface::class);
        $cryptoProcessingClientMock->shouldReceive('createInvoice')
            ->once()
            ->andReturn([]);

        $this->app->instance(CryptoProcessingClientInterface::class, $cryptoProcessingClientMock);

        $this->expectException(InvoiceCreationException::class);

        $service = app(CryptoProcessingService::class);
        $service->createInvoice('tx123', 100.0, CurrencyEnum::USD);
    }

    public function test_get_incoming_transaction_status_returns_correct_status(): void
    {
        $cryptoProcessingClientMock = Mockery::mock(CryptoProcessingClientInterface::class);
        $cryptoProcessingClientMock->shouldReceive('getIncomingTransaction')
            ->once()
            ->with('tx_hash_123')
            ->andReturn(['status' => 'transaction_status_confirmed']);

        $this->app->instance(CryptoProcessingClientInterface::class, $cryptoProcessingClientMock);

        $service = app(CryptoProcessingService::class);

        $result = $service->getIncomingTransactionStatus('tx_hash_123');

        $this->assertEquals(CryptoProcessingTransactionStatusEnum::CONFIRMED, $result);
    }

    public function test_get_incoming_transaction_status_returns_default_status_for_unknown(): void
    {
        $cryptoProcessingClientMock = Mockery::mock(CryptoProcessingClientInterface::class);
        $cryptoProcessingClientMock->shouldReceive('getIncomingTransaction')
            ->once()
            ->with('tx_hash_123')
            ->andReturn(['status' => 'unknown_status']);

        $this->app->instance(CryptoProcessingClientInterface::class, $cryptoProcessingClientMock);

        $service = app(CryptoProcessingService::class);

        $result = $service->getIncomingTransactionStatus('tx_hash_123');

        $this->assertEquals(CryptoProcessingTransactionStatusEnum::OTHER, $result);
    }
}

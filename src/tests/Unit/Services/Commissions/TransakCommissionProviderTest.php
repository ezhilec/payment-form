<?php

namespace Tests\Unit\Services\Commissions;

use App\Clients\TransakClient;
use App\DTOs\CommissionDetailsDTO;
use App\DTOs\TransakQuoteDTO;
use App\Enums\CryptoCurrencyEnum;
use App\Enums\CryptoCurrencyNetworkEnum;
use App\Enums\CurrencyEnum;
use App\Exceptions\CommissionRetrievalException;
use App\Services\Commissions\TransakCommissionProvider;
use Mockery;
use Tests\TestCase;

class TransakCommissionProviderTest extends TestCase
{
    public function test_get_commission_details_returns_correct_dto(): void
    {
        $transakClientMock = Mockery::mock(TransakClient::class);
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

        $this->app->instance(TransakClient::class, $transakClientMock);

        $commissionProvider = new TransakCommissionProvider($transakClientMock);

        $fiatCurrency = CurrencyEnum::USD;
        $cryptoCurrency = CryptoCurrencyEnum::USDT;
        $cryptoCurrencyNetwork = CryptoCurrencyNetworkEnum::tron;
        $fiatAmount = 100.0;

        $commissionDetails = $commissionProvider->getCommissionDetails(
            fiatCurrency: $fiatCurrency,
            cryptoCurrency: $cryptoCurrency,
            cryptoCurrencyNetwork: $cryptoCurrencyNetwork,
            fiatAmount: $fiatAmount
        );

        $this->assertInstanceOf(CommissionDetailsDTO::class, $commissionDetails);
        $this->assertEquals(1, $commissionDetails->getProviderCommission());
        $this->assertEquals(2, $commissionDetails->getNetworkCommission());
    }

    public function test_get_commission_details_throws_exception_on_error(): void
    {
        $transakClientMock = Mockery::mock(TransakClient::class);
        $transakClientMock->shouldReceive('getQuote')
            ->once()
            ->andThrow(new \Exception('API Error'));

        $this->app->instance(TransakClient::class, $transakClientMock);

        $commissionProvider = new TransakCommissionProvider($transakClientMock);

        $fiatCurrency = CurrencyEnum::USD;
        $cryptoCurrency = CryptoCurrencyEnum::USDT;
        $cryptoCurrencyNetwork = CryptoCurrencyNetworkEnum::tron;
        $fiatAmount = 100.0;

        $this->expectException(CommissionRetrievalException::class);
        $commissionProvider->getCommissionDetails(
            fiatCurrency: $fiatCurrency,
            cryptoCurrency: $cryptoCurrency,
            cryptoCurrencyNetwork: $cryptoCurrencyNetwork,
            fiatAmount: $fiatAmount
        );
    }
}

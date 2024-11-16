<?php

namespace App\Services;

use App\Enums\CryptoCurrencyEnum;
use App\Enums\CryptoCurrencyNetworkEnum;
use App\Enums\CurrencyEnum;

class PaymentFormService
{
    private string $baseUrl;
    private string $apiKey;
    private string $environment;

    public function __construct()
    {
        $this->baseUrl = 'https://global-stg.transak.com';
        $this->apiKey = env('TRANSAK_PARTNER_API_KEY');
        $this->environment = 'STAGING';
    }

    public function getLink(
        CurrencyEnum $fiatCurrency,
        float $fiatAmount,
        CryptoCurrencyEnum $cryptoCurrencyCode,
        CryptoCurrencyNetworkEnum $network,
        string $walletAddress,
        string $paymentMethod = 'credit_debit_card',
        string $themeColor = '33e680',
        string $exchangeScreenTitle = 'XAMAX',
        bool $isFeeCalculationHidden = false,
        bool $hideExchangeScreen = true
    ): string {
        $queryParams = http_build_query([
            'apiKey' => $this->apiKey,
            'environment' => $this->environment,
            'themeColor' => $themeColor,
            'exchangeScreenTitle' => $exchangeScreenTitle,
            'productsAvailed' => 'BUY',
            'fiatCurrency' => $fiatCurrency->value,
            'defaultFiatCurrency' => $fiatCurrency->value,
            'network' => $network->value,
            'paymentMethod' => $paymentMethod,
            'fiatAmount' => $fiatAmount,
            'cryptoCurrencyCode' => $cryptoCurrencyCode,
            'isFeeCalculationHidden' => $isFeeCalculationHidden ? 'true' : 'false',
            'hideExchangeScreen' => $hideExchangeScreen ? 'true' : 'false',
            'walletAddress' => $walletAddress,
            'disableWalletAddressForm' => 'true',
            'partnerCustomerId' => env('TRANSAK_PARTNER_ID'),
        ]);

        return $this->baseUrl . '?' . $queryParams;
    }
}

<?php

namespace App\Clients;

use App\DTOs\TransakQuoteDTO;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class TransakClient
{
    private string $baseUrl;
    private string $partnerApiKey;

    public function __construct(private readonly Client $httpClient)
    {
        $this->baseUrl = 'https://api-stg.transak.com/api/v1/pricing/public/quotes';
        $this->partnerApiKey = env('TRANSAK_PARTNER_API_KEY');
    }

    public function getQuote(
        string $fiatCurrency,
        string $cryptoCurrency,
        string $isBuyOrSell,
        string $network,
        string $paymentMethod,
        float $fiatAmount
    ): TransakQuoteDTO {
        try {
            $response = $this->httpClient->get($this->baseUrl, [
                'query' => [
                    'partnerApiKey' => $this->partnerApiKey,
                    'fiatCurrency' => $fiatCurrency,
                    'cryptoCurrency' => $cryptoCurrency,
                    'isBuyOrSell' => $isBuyOrSell,
                    'network' => $network,
                    'paymentMethod' => $paymentMethod,
                    'fiatAmount' => $fiatAmount,
                ],
                'headers' => [
                    'accept' => 'application/json',
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
            $quoteData = $body['response'] ?? [];

            return new TransakQuoteDTO($quoteData);
        } catch (RequestException $e) {
            throw new \Exception('Failed to get quote from Transak: ' . $e->getMessage());
        }
    }
}

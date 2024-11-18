<?php

namespace App\Clients\CryptoProcessing;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class CryptoProcessingClient implements CryptoProcessingClientInterface
{
    private string $baseUrl;
    private string $accessToken;

    public function __construct(private readonly Client $httpClient)
    {
        $this->baseUrl = 'https://api.xamax.io/v1';
        $this->accessToken = env('CRYPTO_PROCESSING_ACCESS_TOKEN');
    }

    public function createInvoice(
        string $txId,
        string $code,
        string $type,
        float $amount,
        string $currency
    ): array {
        try {
            $response = $this->httpClient->post(sprintf('%s/transaction/invoice', $this->baseUrl), [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => [
                    'txId' => $txId,
                    'code' => $code,
                    'type' => $type,
                    'fiat' => [
                        'amount' => $amount,
                        'currency' => $currency,
                    ],
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
            return $body;
        } catch (RequestException $e) {
            throw new \Exception('Failed to create invoice: ' . $e->getMessage());
        }
    }

    public function getIncomingTransaction(string $idOrTxHash): array
    {
        try {
            $response = $this->httpClient->get(sprintf('%s/transaction/incoming/%s', $this->baseUrl, $idOrTxHash), [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
            return $body;
        } catch (RequestException $e) {
            throw new \Exception('Failed to get transaction: ' . $e->getMessage());
        }
    }
}

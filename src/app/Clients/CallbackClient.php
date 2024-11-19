<?php

namespace App\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class CallbackClient
{
    public function __construct(private readonly Client $httpClient)
    {
    }

    public function postTransactionCallback(string $url, array $data): bool
    {
        try {
            $response = $this->httpClient->post($url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => $data,
            ]);

            return $response->getStatusCode() === 200;
        } catch (RequestException $e) {
            return false;
        }
    }
}

<?php

namespace App\Services;

use App\DTOs\TransaskWebhookDTO;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TransakWebhookService
{
    public function __construct()
    {
    }

    /**
     * @throws \Exception
     */
    public function decodeData(string $data): TransaskWebhookDTO
    {
        $key = env('TRANSAK_WEBHOOK_ACCESS_TOKEN');

        $decodedData = JWT::decode($data, new Key($key, 'HS256'));

        $decodedArray = (array)$decodedData;

        return new TransaskWebhookDTO($decodedArray);
    }
}

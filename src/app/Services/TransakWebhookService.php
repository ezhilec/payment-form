<?php

namespace App\Services;

use App\DTOs\TransaskWebhookDTO;
use App\Exceptions\TransakWebhookDecodeException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TransakWebhookService
{
    /**
     * @throws \Exception
     */
    public function decodeData(string $data): TransaskWebhookDTO
    {
        try {
            $key = env('TRANSAK_WEBHOOK_ACCESS_TOKEN');

            $decodedData = JWT::decode($data, new Key($key, 'HS256'));

            $decodedArray = (array)$decodedData;

            return new TransaskWebhookDTO($decodedArray);
        } catch (\Throwable $exception) {
            throw new TransakWebhookDecodeException('Failed to decode Transak webhook data.', previous: $exception);
        }
    }
}

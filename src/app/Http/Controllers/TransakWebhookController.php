<?php

namespace App\Http\Controllers;

use App\Exceptions\TransakWebhookDecodeException;
use App\Http\Requests\TransakWebhookRequest;
use App\Services\CryptoProcessingService;
use App\Services\TransakWebhookService;
use Illuminate\Http\JsonResponse;

class TransakWebhookController extends Controller
{
    public function __construct(
        private readonly TransakWebhookService $transakWebhookService,
        private readonly CryptoProcessingService $cryptoProcessingService,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function get(TransakWebhookRequest $request): JsonResponse
    {
        try {
            $data = $request->get('data');

            $transakWebhookDTO = $this->transakWebhookService->decodeData($data);

            $this->cryptoProcessingService->dispatchGetIncomingTransactionJob($transakWebhookDTO->getTransactionHash());

            return response()->json(['status' => 'ok'], 200);
        } catch (TransakWebhookDecodeException $exception) {
            return response()->json(['error' => $exception->getMessage()], 400);
        } catch (\Throwable $exception) {
            return response()->json(['error' => 'Unexpected error occurred.'], 500);
        }
    }
}

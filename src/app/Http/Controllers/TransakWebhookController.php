<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransakWebhookRequest;
use App\Services\CryptoProcessingService;
use App\Services\TransakWebhookService;

class TransakWebhookController extends Controller
{
    public function __construct(
        private readonly TransakWebhookService $transakWebhookService,
        private readonly CryptoProcessingService $cryptoProcessingService,
    )
    {
    }
    public function get(TransakWebhookRequest $request): void
    {
        $data = $request->get('data');

        $transakWebhookDTO = $this->transakWebhookService->decodeData($data);

        $this->cryptoProcessingService->dispatchGetIncomingTransactionJob($transakWebhookDTO->getTransactionHash());
    }
}

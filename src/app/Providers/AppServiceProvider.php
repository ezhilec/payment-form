<?php

namespace App\Providers;

use App\Clients\CryptoProcessing\CryptoProcessingClientInterface;
use App\Clients\CryptoProcessing\DummyCryptoProcessingClient;
use App\Clients\CryptoProcessing\CryptoProcessingClient;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CryptoProcessingClientInterface::class, function ($app) {
            if (env('APP_ENV') === 'local') {
                return $app->make(DummyCryptoProcessingClient::class);
            }
            return $app->make(CryptoProcessingClient::class);
        });
    }
}

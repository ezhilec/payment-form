<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('test_form', function () {
    return view('test_form');
});

$router->get('test_transak_webhook', function () {
    return view('test_transak_webhook');
});

$router->post('transactions', [
    'as' => 'transactions.store',
    'uses' => 'TransactionController@create'
]);

$router->post('transak-webhook', [
    'as' => 'transak-webhook',
    'uses' => 'TransakWebhookController@get'
]);

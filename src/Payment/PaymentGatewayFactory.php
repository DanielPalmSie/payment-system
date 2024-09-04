<?php

namespace Daniel\PaymentSystem\Payment;

use Daniel\PaymentSystem\Http\GuzzleHttpClient;

class PaymentGatewayFactory
{
    public static function create(string $apiKey): PaymentGateway
    {
        $httpClient = new GuzzleHttpClient($apiKey);
        return new PaymentGateway($httpClient);
    }
}
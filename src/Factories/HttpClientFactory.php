<?php

namespace Daniel\PaymentSystem\Factories;

use GuzzleHttp\Client;

class HttpClientFactory
{
    public static function create(): Client
    {
        $baseUrl = getenv('BASE_URL');
        return new Client(['base_uri' => $baseUrl]);
    }
}
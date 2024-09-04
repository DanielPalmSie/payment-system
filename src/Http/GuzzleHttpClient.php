<?php

namespace Daniel\PaymentSystem\Http;

use Daniel\PaymentSystem\Factories\HttpClientFactory;
use Daniel\PaymentSystem\Factories\SignatureGeneratorFactory;

class GuzzleHttpClient implements HttpClientInterface
{
    private $client;
    private $apiKey;
    private $signatureGenerator;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->signatureGenerator = SignatureGeneratorFactory::create();
        $this->client = HttpClientFactory::create();
    }

    public function post(string $endpoint, array $data): array
    {
        $signature = $this->signatureGenerator->generate($data);

        $headers = [
            'APIKEY' => $this->apiKey,
            'Signature' => $signature,
            'Content-Type' => 'application/json',
        ];

        $response = $this->client->post($endpoint, [
            'json' => $data,
            'headers' => $headers,
        ]);

        return json_decode($response->getBody(), true);
    }

    public function get(string $endpoint): array
    {
        $headers = [
            'APIKEY' => $this->apiKey,

            'Signature' => $this->signatureGenerator->generate([]),
        ];

        $response = $this->client->get($endpoint, [
            'headers' => $headers,
        ]);

        return json_decode($response->getBody(), true);
    }
}


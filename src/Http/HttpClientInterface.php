<?php

namespace Daniel\PaymentSystem\Http;

interface HttpClientInterface
{
    public function get(string $endpoint): array;
    public function post(string $endpoint, array $data): array;
}

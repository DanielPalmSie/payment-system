<?php

namespace Daniel\PaymentSystem\Payment;

use Daniel\PaymentSystem\Http\HttpClientInterface;

class PaymentGateway implements PaymentGatewayInterface
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function createDeposit(array $data): array
    {
        return $this->httpClient->post('/payment/deposit', $data);
    }

    public function confirmDeposit(string $billId): array
    {
        $data = ['bill_id' => $billId];
        return $this->httpClient->post('/payment/deposit/confirm', $data);
    }

    public function getPaymentDetails(array $data): array
    {
        return $this->httpClient->post('/payment/details', $data);
    }

    public function getBalance(): array
    {
        return $this->httpClient->get('/payment/balance');
    }
}

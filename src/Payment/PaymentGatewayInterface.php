<?php

namespace Daniel\PaymentSystem\Payment;

interface PaymentGatewayInterface
{
    public function createDeposit(array $data): array;
    public function confirmDeposit(string $billId): array;
    public function getPaymentDetails(array $data): array;
    public function getBalance(): array;
}
<?php

namespace Daniel\PaymentSystem\Domain\Services;

use Money\Money;

interface PaymentGatewayInterface
{
    public function processPayment(Money $amount, string $currency, string $source, string $destination): string;

    public function confirmPayment(string $transactionId): bool;

    public function cancelPayment(string $transactionId): bool;
}

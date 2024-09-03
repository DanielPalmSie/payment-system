<?php

namespace Daniel\PaymentSystem\Infrastructure\Services;

use Daniel\PaymentSystem\Domain\Services\PaymentGatewayInterface;

class StripePaymentGatewayService implements PaymentGatewayInterface
{
    public function processPayment(Money $amount, string $currency, string $source, string $destination): string
    {
        // Логика интеграции с платежным шлюзом Stripe
        return $transactionId;
    }

    public function confirmPayment(string $transactionId): bool
    {
        // Логика подтверждения платежа через Stripe
        return $isConfirmed;
    }

    public function cancelPayment(string $transactionId): bool
    {
        // TODO: Implement cancelPayment() method.
    }
}
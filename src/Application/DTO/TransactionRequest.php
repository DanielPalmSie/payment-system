<?php

namespace Daniel\PaymentSystem\Application\DTO;

class TransactionRequest
{
    private int $userId;
    private string $paymentToken;

    public function __construct(int $userId, string $paymentToken)
    {
        $this->userId = $userId;
        $this->paymentToken = $paymentToken;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getPaymentToken(): string
    {
        return $this->paymentToken;
    }
}
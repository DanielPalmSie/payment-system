<?php

namespace Daniel\PaymentSystem\Application\DTO;

use Money\Money;


class DepositRequest
{
    private int $userId;
    private Money $amount;
    private string $paymentToken;

    public function __construct(int $userId, Money $amount, string $paymentToken)
    {
        $this->userId = $userId;
        $this->amount = $amount;
        $this->paymentToken = $paymentToken;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function getPaymentToken(): string
    {
        return $this->paymentToken;
    }
}
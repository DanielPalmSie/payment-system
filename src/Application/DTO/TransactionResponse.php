<?php

namespace Daniel\PaymentSystem\Application\DTO;

use Money\Money;
use DateTimeImmutable;

class TransactionResponse
{
    private string $paymentToken;
    private string $type;
    private Money $amount;
    private Money $balanceBefore;
    private Money $balanceAfter;
    private DateTimeImmutable $createdAt;

    public function __construct(
        string $paymentToken,
        string $type,
        Money $amount,
        Money $balanceBefore,
        Money $balanceAfter,
        DateTimeImmutable $createdAt
    ) {
        $this->paymentToken = $paymentToken;
        $this->type = $type;
        $this->amount = $amount;
        $this->balanceBefore = $balanceBefore;
        $this->balanceAfter = $balanceAfter;
        $this->createdAt = $createdAt;
    }

    public function getPaymentToken(): string
    {
        return $this->paymentToken;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function getBalanceBefore(): Money
    {
        return $this->balanceBefore;
    }

    public function getBalanceAfter(): Money
    {
        return $this->balanceAfter;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
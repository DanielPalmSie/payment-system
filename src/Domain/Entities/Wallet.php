<?php

namespace Daniel\PaymentSystem\Domain\Entities;

use Daniel\PaymentSystem\Domain\ValueObjects\Currency;
use Money\Money;
use DateTimeImmutable;

class Wallet
{
    private int $id;
    private int $userId;
    private Currency $currency;
    private Money $balance;
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable $updatedAt;

    public function __construct(int $userId, Currency $currency, Money $balance)
    {
        $this->userId = $userId;
        $this->currency = $currency;
        $this->balance = $balance;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getBalance(): Money
    {
        return $this->balance;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function deposit(Money $amount): void
    {
        $this->balance = $this->balance->add($amount);
    }

    public function withdraw(Money $amount): void
    {
        if ($this->balance->lessThan($amount)) {
            throw new \InvalidArgumentException("Insufficient funds.");
        }
        $this->balance = $this->balance->subtract($amount);
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}

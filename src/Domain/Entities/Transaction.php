<?php

namespace Daniel\PaymentSystem\Domain\Entities;

use Daniel\PaymentSystem\Domain\Enums\TransactionTypeEnum;
use Daniel\PaymentSystem\Domain\ValueObjects\TransactionStatus;
use Money\Money;
use DateTimeImmutable;

class Transaction
{
    public function __construct(
        private readonly string              $paymentToken,
        private readonly int                 $userId,
        private readonly TransactionTypeEnum $type,
        private readonly Money               $amount,
        private readonly Money               $balanceBefore,
        private Money                        $balanceAfter,
        private DateTimeImmutable            $updatedAt,
        private readonly DateTimeImmutable   $createdAt,
        private TransactionStatus            $status,
        private readonly string              $clientOrderId,
        private readonly string              $comment,
        private readonly int                 $expire,
        private readonly string              $userIp,
    )
    {
    }

    public
    function getId(): int
    {
        return $this->id;
    }

    public
    function getUserId(): int
    {
        return $this->userId;
    }

    public
    function getPaymentToken(): string
    {
        return $this->paymentToken;
    }

    public
    function getType(): TransactionTypeEnum
    {
        return $this->type;
    }

    public function getClientOrderId(): string
    {
        return $this->clientOrderId;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getExpire(): int
    {
        return $this->expire;
    }

    public
    function getAmount(): Money
    {
        return $this->amount;
    }

    public
    function getBalanceBefore(): Money
    {
        return $this->balanceBefore;
    }

    public
    function getBalanceAfter(): Money
    {
        return $this->balanceAfter;
    }

    public
    function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public
    function getStatus(): TransactionStatus
    {
        return $this->status;
    }

    public function setStatus(TransactionStatus $status): void
    {
        $this->status = $status;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function setBalanceAfter(Money $balanceAfter): void
    {
        $this->balanceAfter = $balanceAfter;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getUserIp(): string
    {
        return $this->userIp;
    }
}


<?php

namespace Daniel\PaymentSystem\Application\DTO\Request;

use Money\Money;

class DepositRequest
{
    private int $userId;
    private Money $amount;
    private string $paymentToken;
    private string $clientOrderId;
    private string $comment;
    private int $expire;
    private string $userIp;

    public function __construct(
        int $userId,
        Money $amount,
        string $paymentToken,
        string $clientOrderId,
        string $comment,
        int $expire,
        string $userIp
    ) {
        $this->userId = $userId;
        $this->amount = $amount;
        $this->paymentToken = $paymentToken;
        $this->clientOrderId = $clientOrderId;
        $this->comment = $comment;
        $this->expire = $expire;
        $this->userIp = $userIp;
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

    public function getClientOrderId(): string
    {
        return $this->clientOrderId;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getExpire(): int
    {
        return $this->expire;
    }

    public function getUserIp(): string
    {
        return $this->userIp;
    }
}

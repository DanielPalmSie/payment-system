<?php

namespace Daniel\PaymentSystem\Application\DTO\Response;

use Money\Money;

class BalanceResponse
{
    private Money $balance;

    public function __construct(Money $balance)
    {
        $this->balance = $balance;
    }

    public function getBalance(): Money
    {
        return $this->balance;
    }

    public function toArray(): array
    {
        return [
            'success' => true,
            'balance' => $this->getBalance(),
        ];
    }
}
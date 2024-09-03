<?php

namespace Daniel\PaymentSystem\Application\DTO;

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
}
<?php

namespace Daniel\PaymentSystem\Domain\ValueObjects;

use InvalidArgumentException;

class Currency
{
    private $code;

    private static $allowedCurrencies = [
        'USD', 'EUR', 'GBP', 'JPY', 'CAD', 'AUD',
    ];

    public function __construct(string $code)
    {
        if (!in_array(strtoupper($code), self::$allowedCurrencies)) {
            throw new InvalidArgumentException("Unsupported currency code: $code");
        }

        $this->code = strtoupper($code);
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function equals(Currency $currency): bool
    {
        return $this->code === $currency->getCode();
    }

    public function __toString(): string
    {
        return $this->code;
    }
}

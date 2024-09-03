<?php

namespace Daniel\PaymentSystem\Domain\Enums;

enum CurrencyEnum: string
{
    case USD = 'USD';
    case EUR = 'EUR';
    case GBP = 'GBP';
    case JPY = 'JPY';
    case CAD = 'CAD';
    case AUD = 'AUD';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function isValid(string $currency): bool
    {
        return in_array($currency, self::values());
    }
}

<?php

namespace Daniel\PaymentSystem\Domain\Enums;

enum TransactionTypeEnum: string
{
    case DEPOSIT = 'deposit';
    case WITHDRAW = 'confirmed';
    case TRANSFER = 'transfer';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function isValid(string $type): bool
    {
        return in_array($type, self::values());
    }
}

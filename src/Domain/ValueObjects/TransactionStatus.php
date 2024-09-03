<?php

namespace Daniel\PaymentSystem\Domain\ValueObjects;

class TransactionStatus
{
    public const PENDING = 'pending';
    public const CONFIRMED = 'confirmed';
    public const CANCELED = 'canceled';

    private string $status;

    public function __construct(string $status)
    {
        if (!in_array($status, $this->getAllowedStatuses())) {
            throw new \InvalidArgumentException('Invalid status value');
        }
        $this->status = $status;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function isPending(): bool
    {
        return $this->status === self::PENDING;
    }

    public static function getAllowedStatuses(): array
    {
        return [
            self::PENDING,
            self::CONFIRMED,
            self::CANCELED,
        ];
    }
}

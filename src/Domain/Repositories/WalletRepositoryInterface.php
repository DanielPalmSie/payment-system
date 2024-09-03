<?php

namespace Daniel\PaymentSystem\Domain\Repositories;

use Daniel\PaymentSystem\Domain\Entities\Wallet;
use Daniel\PaymentSystem\Domain\ValueObjects\Currency;

interface WalletRepositoryInterface
{
    public function save(Wallet $wallet): void;

    public function findById(int $id): ?Wallet;

    public function findByUserId(int $userId): ?Wallet;

    public function findByCurrency(Currency $currency): array;
}
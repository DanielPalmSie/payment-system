<?php

namespace Daniel\PaymentSystem\Domain\Repositories;

use Daniel\PaymentSystem\Domain\Entities\Wallet;

interface WalletRepositoryInterface
{
    public function save(Wallet $wallet): void;

    public function findByUserId(int $userId): ?Wallet;
}
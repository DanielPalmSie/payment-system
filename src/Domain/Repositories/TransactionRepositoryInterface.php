<?php

namespace Daniel\PaymentSystem\Domain\Repositories;

use Daniel\PaymentSystem\Domain\Entities\Transaction;
use Money\Money;

interface TransactionRepositoryInterface
{
    public function save(Transaction $transaction): int;

    public function findById(int $id): ?Transaction;

    public function findByBillId(string $billId): ?Transaction;
}

<?php

namespace Daniel\PaymentSystem\Domain\Repositories;

use Daniel\PaymentSystem\Domain\Entities\Transaction;
use Money\Money;

interface TransactionRepositoryInterface
{
    public function save(Transaction $transaction): int;

    public function findById(int $id): ?Transaction;

    public function findByWalletId(int $walletId): array;

    public function findTransactionsByAmount(Money $amount): array;

    public function findByBillId(string $billId): ?Transaction;
}

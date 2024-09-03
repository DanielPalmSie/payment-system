<?php

namespace Daniel\PaymentSystem\Infrastructure\Repositories;

use Daniel\PaymentSystem\Domain\Entities\Transaction;
use Daniel\PaymentSystem\Domain\Repositories\TransactionRepositoryInterface;
use Daniel\PaymentSystem\Infrastructure\Database\Connection;
use Money\Money;
use Daniel\PaymentSystem\Domain\Enums\TransactionTypeEnum;
use Daniel\PaymentSystem\Domain\ValueObjects\TransactionStatus;

class MySQLTransactionRepository implements TransactionRepositoryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function save(Transaction $transaction): int
    {
        $query = "
            INSERT INTO transactions 
            (payment_token, user_id, type, amount, balance_before, balance_after, created_at, updated_at, status, client_order_id, comment, expire, user_ip)
            VALUES 
            (:payment_token, :user_id, :type, :amount, :balance_before, :balance_after, :created_at, :updated_at, :status, :client_order_id, :comment, :expire, :user_ip)
        ";

        $params = [
            'payment_token' => $transaction->getPaymentToken(),
            'user_id' => $transaction->getUserId(),
            'type' => $transaction->getType()->value,
            'amount' => $transaction->getAmount()->getAmount(),
            'balance_before' => $transaction->getBalanceBefore()->getAmount(),
            'balance_after' => $transaction->getBalanceAfter()->getAmount(),
            'created_at' => $transaction->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $transaction->getUpdatedAt()->format('Y-m-d H:i:s'),
            'status' => $transaction->getStatus()->getValue(),
            'client_order_id' => $transaction->getClientOrderId(),
            'comment' => $transaction->getComment(),
            'expire' => $transaction->getExpire(),
            'user_ip' => $transaction->getUserIp(),
        ];

        $this->connection->executeQuery($query, $params);
        return (int) $this->connection->getPdo()->lastInsertId();
    }

    public function findByBillId(string $billId): ?Transaction
    {
        $query = 'SELECT * FROM transactions WHERE bill_id = :bill_id LIMIT 1';
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':bill_id', $billId, \PDO::PARAM_STR);
        $statement->execute();

        $transactionData = $statement->fetch(\PDO::FETCH_ASSOC);

        if ($transactionData === false) {
            return null;
        }

        return $this->mapToTransaction($transactionData);
    }

    public function findById(int $id): ?Transaction
    {
        $query = "SELECT * FROM transactions WHERE id = :id";
        $params = ['id' => $id];

        $result = $this->connection->fetch($query, $params);

        if (!$result) {
            return null;
        }

        return $this->mapToTransaction($result);
    }

    private function mapToTransaction(array $data): Transaction
    {
        return new Transaction(
            paymentToken: $data['payment_token'],
            userId: $data['user_id'],
            type: TransactionTypeEnum::from($data['type']),
            amount: new Money($data['amount'], new \Money\Currency('USD')),
            balanceBefore: new Money($data['balance_before'], new \Money\Currency('USD')),
            balanceAfter: new Money($data['balance_after'], new \Money\Currency('USD')),
            updatedAt: new \DateTimeImmutable($data['updated_at']),
            createdAt: new \DateTimeImmutable($data['created_at']),
            status: new TransactionStatus($data['status']),
            clientOrderId: $data['client_order_id'],
            comment: $data['comment'],
            expire: $data['expire'],
            userIp: $data['user_ip']
        );
    }
}

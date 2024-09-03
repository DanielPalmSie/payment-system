<?php

namespace Daniel\PaymentSystem\Infrastructure\Repositories;

use Daniel\PaymentSystem\Domain\Entities\Wallet;
use Daniel\PaymentSystem\Domain\Repositories\WalletRepositoryInterface;
use Daniel\PaymentSystem\Domain\ValueObjects\Currency;
use Daniel\PaymentSystem\Infrastructure\Database\Connection;
use Money\Money;

class MySQLWalletRepository implements WalletRepositoryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findById(int $id): ?Wallet
    {
        $query = "SELECT * FROM wallets WHERE id = :id";
        $params = ['id' => $id];

        $result = $this->connection->fetch($query, $params);

        if (!$result) {
            return null;
        }

        return $this->mapToWallet($result);
    }

    public function findByUserId(int $userId): ?Wallet
    {
        $query = "SELECT * FROM wallets WHERE user_id = :user_id";
        $params = ['user_id' => $userId];

        $result = $this->connection->fetch($query, $params);

        if (!$result) {
            return null;
        }

        return $this->mapToWallet($result);
    }

    public function save(Wallet $wallet): int
    {
        if ($wallet->getId() === null) {
            $query = "
                INSERT INTO wallets (user_id, currency, balance, created_at, updated_at) 
                VALUES (:user_id, :currency, :balance, :created_at, :updated_at)
            ";
            $params = [
                'user_id' => $wallet->getUserId(),
                'currency' => $wallet->getCurrency()->getCode(),
                'balance' => $wallet->getBalance()->getAmount(),
                'created_at' => $wallet->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at' => $wallet->getUpdatedAt()->format('Y-m-d H:i:s'),
            ];

            $this->connection->executeQuery($query, $params);
            return (int)$this->connection->getPdo()->lastInsertId();
        } else {
            $query = "
                UPDATE wallets 
                SET balance = :balance, updated_at = :updated_at 
                WHERE id = :id
            ";
            $params = [
                'balance' => $wallet->getBalance()->getAmount(),
                'updated_at' => $wallet->getUpdatedAt()->format('Y-m-d H:i:s'),
                'id' => $wallet->getId(),
            ];

            $this->connection->executeQuery($query, $params);
            return $wallet->getId();
        }
    }

    public function delete(int $id): void
    {
        $query = "DELETE FROM wallets WHERE id = :id";
        $params = ['id' => $id];

        $this->connection->executeQuery($query, $params);
    }

    private function mapToWallet(array $data): Wallet
    {
        return new Wallet(
            userId: $data['user_id'],
            currency: new Currency($data['currency']),
            balance: new Money($data['balance'], new Currency($data['currency'])),
        );
    }


    public function findByCurrency(Currency $currency): array
    {
        $query = "SELECT * FROM wallets WHERE currency = :currency";
        $params = ['currency' => $currency->getCode()];

        $results = $this->connection->fetchAll($query, $params);

        $wallets = [];
        foreach ($results as $data) {
            $wallets[] = $this->mapToWallet($data);
        }

        return $wallets;
    }
}

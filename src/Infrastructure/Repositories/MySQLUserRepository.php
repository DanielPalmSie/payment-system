<?php

namespace Daniel\PaymentSystem\Infrastructure\Repositories;

use Daniel\PaymentSystem\Domain\Entities\User;
use Daniel\PaymentSystem\Domain\Repositories\UserRepositoryInterface;
use Daniel\PaymentSystem\Infrastructure\Database\Connection;

class MySQLUserRepository implements UserRepositoryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findById(int $id): ?User
    {
        $query = "SELECT * FROM users WHERE id = :id";
        $params = ['id' => $id];

        $result = $this->connection->fetch($query, $params);

        if (!$result) {
            return null;
        }

        return $this->mapToUser($result);
    }

    public function findByEmail(string $email): ?User
    {
        $query = "SELECT * FROM users WHERE email = :email";
        $params = ['email' => $email];

        $result = $this->connection->fetch($query, $params);

        if (!$result) {
            return null;
        }

        return $this->mapToUser($result);
    }

    public function save(User $user): int
    {
        if ($user->getId() === null) {
            $query = "
                INSERT INTO users (username, email, created_at, updated_at) 
                VALUES (:username, :email, :created_at, :updated_at)
            ";
            $params = [
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at' => $user->getUpdatedAt()->format('Y-m-d H:i:s'),
            ];

            $this->connection->executeQuery($query, $params);
            return (int) $this->connection->getPdo()->lastInsertId();
        } else {
            $query = "
                UPDATE users 
                SET username = :username, email = :email, updated_at = :updated_at 
                WHERE id = :id
            ";
            $params = [
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'updated_at' => $user->getUpdatedAt()->format('Y-m-d H:i:s'),
                'id' => $user->getId(),
            ];

            $this->connection->executeQuery($query, $params);
            return $user->getId();
        }
    }

    public function delete(int $id): void
    {
        $query = "DELETE FROM users WHERE id = :id";
        $params = ['id' => $id];

        $this->connection->executeQuery($query, $params);
    }

    private function mapToUser(array $data): User
    {
        return new User(
            id: $data['id'],
            username: $data['username'],
            email: $data['email'],
        );
    }

    public function findByUsername(string $username): ?User
    {
        $query = "SELECT * FROM users WHERE username = :username";
        $params = ['username' => $username];

        $result = $this->connection->fetch($query, $params);

        if (!$result) {
            return null;
        }

        return $this->mapToUser($result);
    }

}

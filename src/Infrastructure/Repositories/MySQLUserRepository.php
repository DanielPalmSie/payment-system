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

    public function save(User $user): void
    {
        // TODO: Implement save() method.
    }

    public function findById(int $id): ?User
    {
        // TODO: Implement findById() method.
    }

    public function findByEmail(string $email): ?User
    {
        // TODO: Implement findByEmail() method.
    }

    public function findByUsername(string $username): ?User
    {
        // TODO: Implement findByUsername() method.
    }
}

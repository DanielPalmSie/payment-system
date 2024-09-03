<?php

namespace Daniel\PaymentSystem\Domain\Repositories;

use Daniel\PaymentSystem\Domain\Entities\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function findById(int $id): ?User;

    public function findByEmail(string $email): ?User;

    public function findByUsername(string $username): ?User;
}
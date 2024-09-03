<?php

namespace Daniel\PaymentSystem\Domain\Entities;

use DateTimeImmutable;

class User
{
    private int $id;
    private string $username;
    private string $email;
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable $updatedAt;

    public function __construct(
        int $id,
        string $username,
        string $email,
    )
    {
        $this->username = $username;
        $this->email = $email;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}

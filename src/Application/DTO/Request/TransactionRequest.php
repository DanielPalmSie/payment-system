<?php

namespace Daniel\PaymentSystem\Application\DTO\Request;

class TransactionRequest
{
    private int $id;
    private string $billId;
    private string $orderType;

    public function __construct(int $id, string $billId, string $orderType)
    {
        $this->id = $id;
        $this->billId = $billId;
        $this->orderType = $orderType;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getBillId(): string
    {
        return $this->billId;
    }

    public function getOrderType(): string
    {
        return $this->orderType;
    }
}
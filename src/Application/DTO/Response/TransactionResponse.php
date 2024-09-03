<?php

namespace Daniel\PaymentSystem\Application\DTO\Response;

use DateTimeImmutable;
use Money\Money;

class TransactionResponse
{
    private int $id;
    private DateTimeImmutable $created;
    private DateTimeImmutable $updated;
    private string $billId;
    private Money $fee;
    private string $orderType;
    private string $comment;
    private string $status;
    private Money $amount;
    private string $currency;

    public function __construct(
        int $id,
        DateTimeImmutable $created,
        DateTimeImmutable $updated,
        string $billId,
        Money $fee,
        string $orderType,
        string $comment,
        string $status,
        Money $amount,
        string $currency
    ) {
        $this->id = $id;
        $this->created = $created;
        $this->updated = $updated;
        $this->billId = $billId;
        $this->fee = $fee;
        $this->orderType = $orderType;
        $this->comment = $comment;
        $this->status = $status;
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCreated(): DateTimeImmutable
    {
        return $this->created;
    }

    public function getUpdated(): DateTimeImmutable
    {
        return $this->updated;
    }

    public function getBillId(): string
    {
        return $this->billId;
    }

    public function getFee(): Money
    {
        return $this->fee;
    }

    public function getOrderType(): string
    {
        return $this->orderType;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'created' => $this->created->format(DATE_ATOM),
            'updated' => $this->updated->format(DATE_ATOM),
            'bill_id' => $this->billId,
            'fee' => $this->fee->getAmount(),
            'order_type' => $this->orderType,
            'comment' => $this->comment,
            'status' => $this->status,
            'amount' => $this->amount->getAmount(),
            'currency' => $this->currency,
        ];
    }
}

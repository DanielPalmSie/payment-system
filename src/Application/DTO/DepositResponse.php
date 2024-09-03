<?php

namespace Daniel\PaymentSystem\Application\DTO;

class DepositResponse
{
    private bool $success;
    private string $transactionId;
    private string $billId;
    private float $amount;
    private string $cardNumber;

    public function __construct(
        bool $success,
        string $transactionId,
        string $billId,
        float $amount,
        string $cardNumber
    ) {
        $this->success = $success;
        $this->transactionId = $transactionId;
        $this->billId = $billId;
        $this->amount = $amount;
        $this->cardNumber = $cardNumber;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function getBillId(): string
    {
        return $this->billId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCardNumber(): string
    {
        return $this->cardNumber;
    }

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'id' => $this->transactionId,
            'bill_id' => $this->billId,
            'amount' => $this->amount,
            'card_number' => $this->cardNumber,
        ];
    }
}

<?php

namespace Daniel\PaymentSystem\Application\UseCases;

use Daniel\PaymentSystem\Application\DTO\Request\TransactionRequest;
use Daniel\PaymentSystem\Application\DTO\Response\TransactionResponse;
use Daniel\PaymentSystem\Domain\Repositories\TransactionRepositoryInterface;
use Money\Money;

class TransactionUseCase
{
    private TransactionRepositoryInterface $transactionRepository;

    public function __construct(TransactionRepositoryInterface $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function getTransactionDetails(TransactionRequest $request): TransactionResponse
    {
        $transaction = $this->transactionRepository->findById($request->getId());

        if (!$transaction) {
            throw new \Exception('Transaction not found.');
        }

        return new TransactionResponse(
            id: $transaction->getId(),
            created: $transaction->getCreatedAt(),
            updated: $transaction->getUpdatedAt(),
            billId: $transaction->getClientOrderId(),
            fee: new Money(0, $transaction->getAmount()->getCurrency()), //TODO: Это для примера я написал тут в идеале должна быть логика расчеиа комиссии
            orderType: $transaction->getType()->value,
            comment: $transaction->getComment(),
            status: $transaction->getStatus()->getStatus(),
            amount: $transaction->getAmount(),
            currency: $transaction->getAmount()->getCurrency()->getCode()
        );
    }
}

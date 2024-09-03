<?php

namespace Daniel\PaymentSystem\Application\UseCases;

use Daniel\PaymentSystem\Application\DTO\DepositRequest;
use Daniel\PaymentSystem\Application\DTO\DepositResponse;
use Daniel\PaymentSystem\Domain\Entities\Transaction;
use Daniel\PaymentSystem\Domain\Enums\TransactionTypeEnum;
use Daniel\PaymentSystem\Domain\Repositories\TransactionRepositoryInterface;
use Daniel\PaymentSystem\Domain\Repositories\WalletRepositoryInterface;
use Daniel\PaymentSystem\Domain\Services\PaymentGatewayInterface;
use Daniel\PaymentSystem\Domain\ValueObjects\TransactionStatus;
use DateTimeImmutable;
use Money\Money;
use Exception;

class DepositUseCase
{
    private WalletRepositoryInterface $walletRepository;
    private TransactionRepositoryInterface $transactionRepository;
    private PaymentGatewayInterface $paymentGateway;

    public function __construct(
        WalletRepositoryInterface $walletRepository,
        TransactionRepositoryInterface $transactionRepository,
        PaymentGatewayInterface $paymentGateway
    ) {
        $this->walletRepository = $walletRepository;
        $this->transactionRepository = $transactionRepository;
        $this->paymentGateway = $paymentGateway;
    }

    public function execute(DepositRequest $request): DepositResponse
    {
        try {
            $wallet = $this->walletRepository->findByUserId($request->getUserId());

            if ($wallet === null) {
                throw new Exception('Wallet not found for user ID: ' . $request->getUserId());
            }

            $paymentToken = $this->paymentGateway->processPayment(
                $request->getAmount(),
                $wallet->getCurrency()->getCode(),
                $request->getPaymentToken(),
                $wallet->getUserId()
            );

            $transaction = new Transaction(
                paymentToken: $paymentToken,
                userId: $request->getUserId(),
                type: TransactionTypeEnum::DEPOSIT,
                amount: $request->getAmount(),
                balanceBefore: new Money(0, $request->getAmount()->getCurrency()),
                balanceAfter: new Money(0, $request->getAmount()->getCurrency()),
                updatedAt: new DateTimeImmutable(),
                createdAt: new DateTimeImmutable(),
                status: new TransactionStatus(TransactionStatus::PENDING),
                clientOrderId: $request->getClientOrderId(),
                comment: $request->getComment(),
                expire: $request->getExpire(),
                userIp: $request->getUserIp()
            );


            $transactionId = $this->transactionRepository->save($transaction);

            return new DepositResponse(
                success: true,
                transactionId: (string)$transactionId,
                billId: (string)$transactionId, //TODO: Вроде тоже самое что и id транзакции ?
                amount: $request->getAmount()->getAmount() / 100,
                cardNumber: $paymentToken //TODO: В целях безопасности креды карты храню в виде токена который сгенерировал например страйп на основе данных карты
            );

        } catch (Exception $e) {
            return new DepositResponse(
                success: false,
                transactionId: '',
                billId: '',
                amount: 0.0,
                cardNumber: ''
            );
        }
    }

    public function confirmDeposit(string $billId): void
    {
        $transaction = $this->transactionRepository->findByBillId($billId);

        if ($transaction === null) {
            throw new \Exception('Transaction not found for Bill ID: ' . $billId);
        }

        if (!$transaction->getStatus()->isPending()) {
            throw new \Exception('Transaction is not in a pending state');
        }

        $transaction->setStatus(new TransactionStatus(TransactionStatus::CONFIRMED));
        $transaction->setBalanceAfter(
            new Money($transaction->getAmount()->getAmount(), $transaction->getAmount()->getCurrency())
        );
        $this->transactionRepository->save($transaction);

        $wallet = $this->walletRepository->findByUserId($transaction->getUserId());

        if ($wallet === null) {
            throw new \Exception('Wallet not found for user ID: ' . $transaction->getUserId());
        }

        $newBalance = $wallet->getBalance()->add($transaction->getAmount());
        $wallet->setBalance($newBalance);
        $this->walletRepository->save($wallet);
    }
}

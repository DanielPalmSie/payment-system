<?php

namespace Daniel\PaymentSystem\Application\UseCases;

use Daniel\PaymentSystem\Application\DTO\Request\DepositRequest;
use Daniel\PaymentSystem\Application\DTO\Response\DepositResponse;
use Daniel\PaymentSystem\Domain\Entities\Transaction;
use Daniel\PaymentSystem\Domain\Enums\TransactionTypeEnum;
use Daniel\PaymentSystem\Domain\Repositories\TransactionRepositoryInterface;
use Daniel\PaymentSystem\Domain\Repositories\WalletRepositoryInterface;
use Daniel\PaymentSystem\Domain\Services\PaymentGatewayInterface;
use Daniel\PaymentSystem\Domain\ValueObjects\TransactionStatus;
use Daniel\PaymentSystem\Infrastructure\Database\Connection;
use DateTimeImmutable;
use Exception;
use Money\Money;

class DepositUseCase
{
    private WalletRepositoryInterface $walletRepository;
    private TransactionRepositoryInterface $transactionRepository;
    private PaymentGatewayInterface $paymentGateway;
    private Connection $connection;

    public function __construct(
        WalletRepositoryInterface $walletRepository,
        TransactionRepositoryInterface $transactionRepository,
        PaymentGatewayInterface $paymentGateway,
        Connection $connection
    ) {
        $this->walletRepository = $walletRepository;
        $this->transactionRepository = $transactionRepository;
        $this->paymentGateway = $paymentGateway;
        $this->connection = $connection;
    }

    public function execute(DepositRequest $request): DepositResponse
    {
        $this->connection->beginTransaction();

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

            $this->connection->commit();

            return new DepositResponse(
                success: true,
                transactionId: (string)$transactionId,
                billId: (string)$transactionId, //TODO: Вроде тоже самое что и id транзакции ?
                amount: $request->getAmount()->getAmount() / 100,
                cardNumber: $paymentToken //TODO: В целях безопасности креды карты храню в виде токена
            );

        } catch (Exception $e) {
            $this->connection->rollBack();

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
        $this->connection->beginTransaction();

        try {
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

            $this->connection->commit();

        } catch (\Exception $e) {

            $this->connection->rollBack();

            throw $e;
        }
    }
}


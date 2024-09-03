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

            // 2. Инициировать депозит через платежный шлюз
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
                createdAt: new DateTimeImmutable(),
                status: new TransactionStatus(TransactionStatus::PENDING)
            );

            $transactionId = $this->transactionRepository->save($transaction);

            $response = [
                'success' => true,
                'id' => (string) $transactionId,
                'bill_id' => $transactionId, // Вроде тоже самое что и id транзакции ?
                'amount' => $request->getAmount()->getAmount() / 100,
                'card_number' => $paymentToken // В целях безопасности данные карты храню в виде токена
            ];

            return new DepositResponse(true, json_encode($response));

        } catch (Exception $e) {
            return new DepositResponse(false, 'Deposit failed: ' . $e->getMessage());
        }
    }
}

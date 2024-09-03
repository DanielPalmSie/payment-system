<?php

namespace Daniel\PaymentSystem\Application\UseCases;

use Daniel\PaymentSystem\Application\DTO\Request\BalanceRequest;
use Daniel\PaymentSystem\Application\DTO\Response\BalanceResponse;
use Daniel\PaymentSystem\Domain\Repositories\WalletRepositoryInterface;

class BalanceUseCase
{
    private WalletRepositoryInterface $walletRepository;

    public function __construct(WalletRepositoryInterface $walletRepository)
    {
        $this->walletRepository = $walletRepository;
    }

    public function execute(BalanceRequest $request): BalanceResponse
    {
        $wallet = $this->walletRepository->findByUserId($request->getUserId());

        if ($wallet === null) {
            throw new \InvalidArgumentException('Wallet not found for user ID: ' . $request->getUserId());
        }

        return new BalanceResponse($wallet->getBalance());
    }
}
<?php

namespace Daniel\PaymentSystem\Infrastructure\Controllers;

use Daniel\PaymentSystem\Application\DTO\Request\BalanceRequest;
use Daniel\PaymentSystem\Application\UseCases\BalanceUseCase;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class BalanceController
{
    private BalanceUseCase $balanceUseCase;

    public function __construct(BalanceUseCase $balanceUseCase)
    {
        $this->balanceUseCase = $balanceUseCase;
    }

    public function getBalance(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $userId = (int) $request->getQueryParams()['user_id'] ?? 0;

            if ($userId === 0) {
                throw new \InvalidArgumentException('User ID is required');
            }

            $balanceRequest = new BalanceRequest($userId);
            $balanceResponse = $this->balanceUseCase->execute($balanceRequest);

            return new JsonResponse($balanceResponse->toArray());

        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}

<?php

namespace Daniel\PaymentSystem\Infrastructure\Controllers;

use Daniel\PaymentSystem\Application\UseCases\TransactionUseCase;
use Daniel\PaymentSystem\Application\DTO\TransactionRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\JsonResponse;

class TransactionController
{
    private TransactionUseCase $transactionUseCase;

    public function __construct(TransactionUseCase $transactionUseCase)
    {
        $this->transactionUseCase = $transactionUseCase;
    }

    public function getTransaction(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $queryParams = $request->getQueryParams();

            $transactionId = (int) $queryParams['transaction_id'] ?? 0;
            $billId = $queryParams['bill_id'] ?? '';
            $orderType = $queryParams['order_type'] ?? '';

            if ($transactionId === 0 || empty($billId) || empty($orderType)) {
                throw new \InvalidArgumentException('Transaction ID, Bill ID, and Order Type are required');
            }

            $transactionRequest = new TransactionRequest($transactionId, $billId, $orderType);
            $transactionResponse = $this->transactionUseCase->getTransactionDetails($transactionRequest);

            return new JsonResponse($transactionResponse->toArray());

        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
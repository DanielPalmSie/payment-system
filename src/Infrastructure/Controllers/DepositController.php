<?php

namespace Daniel\PaymentSystem\Infrastructure\Controllers;

use Daniel\PaymentSystem\Application\UseCases\DepositUseCase;
use Daniel\PaymentSystem\Application\DTO\DepositRequest;
use Money\Currency;
use Money\Money;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\JsonResponse;

class DepositController
{
    private DepositUseCase $depositUseCase;

    public function __construct(DepositUseCase $depositUseCase)
    {
        $this->depositUseCase = $depositUseCase;
    }

    public function createDeposit(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $data = $request->getParsedBody();

            $userId = (int) $data['user_id'] ?? 0;
            $amount = new Money($data['amount'], new Currency($data['currency']));
            $paymentToken = $data['payment_token'];
            $clientOrderId = $data['client_order_id'];
            $comment = $data['comment'] ?? '';
            $expire = (int) $data['expire'] ?? 0;
            $userIp = $data['user_ip'] ?? '';

            if ($userId === 0 || empty($paymentToken) || empty($clientOrderId)) {
                throw new \InvalidArgumentException('Required fields are missing');
            }

            $depositRequest = new DepositRequest(
                $userId,
                $amount,
                $paymentToken,
                $clientOrderId,
                $comment,
                $expire,
                $userIp
            );

            $depositResponse = $this->depositUseCase->execute($depositRequest);

            return new JsonResponse($depositResponse->toArray());

        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function confirmDeposit(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $data = $request->getParsedBody();

            $paymentToken = $data['payment_token'] ?? '';
            $transactionId = (int) $data['transaction_id'] ?? 0;

            if (empty($paymentToken) || $transactionId === 0) {
                throw new \InvalidArgumentException('Required fields are missing');
            }

            $this->depositUseCase->confirmDeposit($paymentToken, $transactionId);

            return new JsonResponse(['success' => true, 'message' => 'Deposit confirmed.']);

        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}

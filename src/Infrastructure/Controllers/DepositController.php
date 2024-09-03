<?php

namespace Daniel\PaymentSystem\Infrastructure\Controllers;

use Daniel\PaymentSystem\Application\DTO\Request\DepositRequest;
use Daniel\PaymentSystem\Application\UseCases\DepositUseCase;
use Laminas\Diactoros\Response\JsonResponse;
use Money\Currency;
use Money\Money;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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

            $billId = $data['bill_id'] ?? '';

            if (empty($billId)) {
                throw new \InvalidArgumentException('bill_id is required');
            }

            $this->depositUseCase->confirmDeposit($billId);

            return new JsonResponse([
                'success' => true,
                'msg' => 'Deposit confirmed successfully'
            ]);

        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'msg' => $e->getMessage(),
            ], 400);
        }
    }
}

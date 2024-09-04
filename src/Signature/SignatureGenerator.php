<?php

namespace Daniel\PaymentSystem\Signature;

class SignatureGenerator
{
    private string $secretKey;

    public function __construct(string $secretKey)
    {
        $this->secretKey = $secretKey;
    }

    public function generate(array $data): string
    {
        ksort($data);

        $dataString = http_build_query($data);

        return hash_hmac('sha256', $dataString, $this->secretKey);
    }
}

<?php

namespace Daniel\PaymentSystem\Factories;

use Daniel\PaymentSystem\Signature\SignatureGenerator;

class SignatureGeneratorFactory
{
    public static function create(): SignatureGenerator
    {
        $secretKey = getenv('SECRET_KEY');
        return new SignatureGenerator($secretKey);
    }
}
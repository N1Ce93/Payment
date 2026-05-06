<?php

namespace App\Services\Payments\Contracts;

use App\DTO\CreatePaymentDto;
use App\Services\Payments\DTO\CallbackResultDto;
use App\Services\Payments\DTO\ProviderPaymentResponseDto;

interface PaymentProviderContract
{
    public function createPayment(CreatePaymentDto $dto): ProviderPaymentResponseDto;

    public function handleCallback(array $payload): CallbackResultDto;
}

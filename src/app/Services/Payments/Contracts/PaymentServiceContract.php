<?php

namespace App\Services\Payments\Contracts;

use App\DTO\CreatePaymentDto;
use App\Models\Payment;

interface PaymentServiceContract
{
    public function create(CreatePaymentDto $dto): Payment;

    public function handleCallback(string $providerName, array $payload): Payment;
}

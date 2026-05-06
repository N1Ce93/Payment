<?php

namespace App\Services\Payments\DTO;

use App\Enums\PaymentStatus;

readonly class ProviderPaymentResponseDto
{
    public function __construct(
        public string $externalId,
        public string $paymentUrl,
        public PaymentStatus $status,
        public array $rawResponse = [],
    ) {}
}

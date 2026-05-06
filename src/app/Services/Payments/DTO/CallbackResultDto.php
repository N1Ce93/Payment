<?php

namespace App\Services\Payments\DTO;

use App\Enums\PaymentStatus;

readonly class CallbackResultDto
{
    public function __construct(
        public string $externalId,
        public PaymentStatus $status,
        public array $rawPayload = [],
    ) {}
}

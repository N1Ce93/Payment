<?php

namespace App\DTO;

use App\Enums\PaymentProvider;

readonly class CreatePaymentDto
{
    public function __construct(
        public PaymentProvider $provider,
        public float $amount,
        public string $currency,
        public string $orderId,
        public ?string $description = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            provider: PaymentProvider::from(data_get($data, 'provider')),
            amount: (float) data_get($data, 'amount'),
            currency: data_get($data, 'currency'),
            orderId: data_get($data, 'order_id'),
            description: data_get($data, 'description'),
        );
    }
}

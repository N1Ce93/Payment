<?php

namespace App\Services\Payments\Providers;

use App\DTO\CreatePaymentDto;
use App\Enums\PaymentStatus;
use App\Services\Payments\Contracts\PaymentProviderContract;
use App\Services\Payments\DTO\CallbackResultDto;
use App\Services\Payments\DTO\ProviderPaymentResponseDto;

abstract class AbstractPaymentProvider implements PaymentProviderContract
{
    public function createPayment(CreatePaymentDto $dto): ProviderPaymentResponseDto
    {
        $response = $this->createPaymentRequest($dto);

        return new ProviderPaymentResponseDto(
            externalId: $this->getExternalId($response),
            paymentUrl: $this->getPaymentUrl($response),
            status: $this->mapStatus(
                $this->getStatus($response)
            ),
            rawResponse: $response,
        );
    }

    public function handleCallback(array $payload): CallbackResultDto
    {
        return new CallbackResultDto(
            externalId: $this->getCallbackExternalId($payload),
            status: $this->mapStatus(
                $this->getCallbackStatus($payload)
            ),
            rawPayload: $payload,
        );
    }

    abstract public function callbackRules(): array;

    abstract protected function createPaymentRequest(CreatePaymentDto $dto): array;

    abstract protected function mapStatus(string $status): PaymentStatus;

    abstract protected function getExternalId(array $response): string;

    abstract protected function getPaymentUrl(array $response): string;

    abstract protected function getStatus(array $response): string;

    abstract protected function getCallbackExternalId(array $payload): string;

    abstract protected function getCallbackStatus(array $payload): string;
}

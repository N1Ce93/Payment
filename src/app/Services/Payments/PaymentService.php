<?php

namespace App\Services\Payments;

use App\DTO\CreatePaymentDto;
use App\Models\Payment;
use App\Services\Payments\Contracts\PaymentServiceContract;
use App\Services\Payments\DTO\CallbackResultDto;
use App\Services\Payments\DTO\ProviderPaymentResponseDto;
use App\Services\Payments\Resolver\PaymentProviderResolver;

class PaymentService implements PaymentServiceContract
{
    public function __construct(protected PaymentProviderResolver $resolver) {}

    public function create(CreatePaymentDto $dto): Payment
    {
        $provider = $this->resolver->resolve($dto->provider->value);

        $response = $provider->createPayment($dto);

        return $this->storePayment($dto, $response);
    }

    public function handleCallback(string $providerName, array $payload): Payment
    {
        $provider = $this->resolver->resolve($providerName);

        $callbackResult = $provider->handleCallback($payload);

        return $this->updatePayment($callbackResult);
    }

    private function storePayment(CreatePaymentDto $dto, ProviderPaymentResponseDto $response): Payment
    {
        return Payment::query()->create([
            'provider' => $dto->provider->value,
            'order_id' => $dto->orderId,
            'external_id' => $response->externalId,
            'payment_url' => $response->paymentUrl,
            'amount' => $dto->amount,
            'currency' => $dto->currency,
            'status' => $response->status,
            'description' => $dto->description,
            'provider_payload' => $response->rawResponse,
        ]);
    }

    private function updatePayment(CallbackResultDto $callbackResult): Payment
    {
        $payment = Payment::query()
            ->where('external_id', $callbackResult->externalId)
            ->firstOrFail();

        $payment->update([
            'status' => $callbackResult->status,
            'provider_payload' => $callbackResult->rawPayload,
        ]);

        return $payment->refresh();
    }
}

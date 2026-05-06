<?php

namespace App\Services\Payments\Providers;

use App\DTO\CreatePaymentDto;
use App\Enums\PaymentStatus;
use Illuminate\Support\Facades\Http;

class PayGateAProvider extends AbstractPaymentProvider
{
    protected function createPaymentRequest(CreatePaymentDto $dto): array
    {
        Http::fake([
            'paygate-a.test/*' => Http::response([
                'payment_id' => 'a-100500',
                'payment_url' => 'https://paygate-a.test/pay/a-100500',
                'status' => 'new',
            ]),
        ]);

        /**
         * Fake HTTP request to external provider API.
         */
        $response = Http::post(
            'https://paygate-a.test/create',
            [
                'amount' => $dto->amount,
                'currency' => $dto->currency,
                'merchant_order_id' => $dto->orderId,
            ]
        );

        return $response->json();
    }

    protected function mapStatus(string $status): PaymentStatus
    {
        return match ($status) {
            'new' => PaymentStatus::Pending,
            'paid' => PaymentStatus::Success,
            'rejected' => PaymentStatus::Failed,
        };
    }

    public function callbackRules(): array
    {
        return [
            'payment_id' => ['required', 'string'],
            'merchant_order_id' => ['required', 'string'],

            'status' => [
                'required',
                'in:paid,rejected,new',
            ],
        ];
    }

    protected function getExternalId(array $response): string
    {
        return data_get($response, 'payment_id');
    }

    protected function getPaymentUrl(array $response): string
    {
        return data_get($response, 'payment_url');
    }

    protected function getStatus(array $response): string
    {
        return data_get($response, 'status');
    }

    protected function getCallbackExternalId(array $payload): string
    {
        return data_get($payload, 'payment_id');
    }

    protected function getCallbackStatus(array $payload): string
    {
        return data_get($payload, 'status');
    }
}

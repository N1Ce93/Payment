<?php

namespace App\Services\Payments\Providers;

use App\DTO\CreatePaymentDto;
use App\Enums\PaymentStatus;
use Illuminate\Support\Facades\Http;

class PayGateBProvider extends AbstractPaymentProvider
{
    protected function createPaymentRequest(
        CreatePaymentDto $dto
    ): array {
        Http::fake([
            'paygate-b.test/*' => Http::response([
                'id' => 'b-777',
                'redirect_url' => 'https://paygate-b.test/checkout/b-777',
                'state' => 'created',
            ]),
        ]);

        /**
         * Fake HTTP request to external provider API.
         */
        $response = Http::post(
            'https://paygate-b.test/payments',
            [
                'order' => $dto->orderId,

                // provider expects amount in cents
                'total' => (int) round($dto->amount * 100),

                'currency_code' => $dto->currency,

                'note' => $dto->description,
            ]
        );

        return $response->json();
    }

    protected function mapStatus(
        string $status
    ): PaymentStatus {
        return match ($status) {
            'created' => PaymentStatus::Pending,
            'success' => PaymentStatus::Success,
            'error' => PaymentStatus::Failed,
        };
    }

    public function callbackRules(): array
    {
        return [
            'id' => ['required', 'string'],
            'order' => ['required', 'string'],

            'state' => [
                'required',
                'in:created,success,error',
            ],
        ];
    }

    protected function getExternalId(array $response): string
    {
        return data_get($response, 'id');
    }

    protected function getPaymentUrl(array $response): string
    {
        return data_get($response, 'redirect_url');
    }

    protected function getStatus(array $response): string
    {
        return data_get($response, 'state');
    }

    protected function getCallbackExternalId(array $payload): string
    {
        return data_get($payload, 'id');
    }

    protected function getCallbackStatus(array $payload): string
    {
        return data_get($payload, 'state');
    }
}

<?php

namespace App\Http\Controllers;

use App\DTO\CreatePaymentDto;
use App\Http\Requests\CallbackRequest;
use App\Http\Requests\CreatePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Services\Payments\Contracts\PaymentServiceContract;

class PaymentController extends Controller
{
    public function __construct(protected PaymentServiceContract $paymentService) {}

    public function store(CreatePaymentRequest $request)
    {
        $dto = CreatePaymentDto::fromArray($request->validated());

        $payment = $this->paymentService->create($dto);

        return PaymentResource::make($payment);
    }

    public function callback(string $provider, CallbackRequest $request)
    {
        $payment = $this->paymentService->handleCallback(
            $provider,
            $request->all(),
        );

        return response()->json([
            'success' => true,
            'payment_id' => $payment->id,
            'status' => $payment->status->value,
        ]);
    }
}

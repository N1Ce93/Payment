<?php

namespace App\Http\Requests;

use App\Enums\PaymentProvider;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class CreatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'provider' => [
                'required',
                new Enum(PaymentProvider::class),
            ],
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],
            'currency' => [
                'required',
                'string',
                'size:3',
            ],
            'order_id' => [
                'required',
                'string',
                'max:255',
                Rule::unique('payments', 'order_id'),
            ],
            'description' => [
                'nullable',
                'string',
            ],
        ];
    }
}

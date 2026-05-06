<?php

namespace App\Http\Requests;

use App\Services\Payments\Resolver\PaymentProviderResolver;
use Illuminate\Foundation\Http\FormRequest;

class CallbackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(
        PaymentProviderResolver $resolver,
    ): array {
        $provider = $resolver->resolve($this->route('provider'));

        return $provider->callbackRules();
    }
}

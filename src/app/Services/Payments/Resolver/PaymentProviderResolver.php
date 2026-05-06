<?php

namespace App\Services\Payments\Resolver;

use App\Services\Payments\Contracts\PaymentProviderContract;
use InvalidArgumentException;

class PaymentProviderResolver
{
    public function resolve(string $provider): PaymentProviderContract
    {
        $providerClass = config("payments.providers.$provider");

        if (! $providerClass) {
            throw new InvalidArgumentException(
                "Unsupported provider [$provider]"
            );
        }

        return app($providerClass);
    }
}

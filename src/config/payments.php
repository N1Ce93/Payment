<?php

use App\Services\Payments\Providers\PayGateAProvider;
use App\Services\Payments\Providers\PayGateBProvider;

return [
    'providers' => [
        'paygate_a' => PayGateAProvider::class,
        'paygate_b' => PayGateBProvider::class,
    ],
];

<?php

namespace App\Enums;

enum PaymentProvider: string
{
    case PayGateA = 'paygate_a';
    case PayGateB = 'paygate_b';
}

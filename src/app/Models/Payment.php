<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $provider
 * @property string $order_id
 * @property string|null $external_id
 * @property string $amount
 * @property string $currency
 * @property PaymentStatus $status
 * @property string|null $description
 * @property string|null $payment_url
 * @property array|null $provider_payload
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'order_id',
        'external_id',
        'amount',
        'currency',
        'status',
        'description',
        'provider_payload',
        'payment_url',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'provider_payload' => 'array',
            'status' => PaymentStatus::class,
        ];
    }
}

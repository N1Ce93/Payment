<?php

namespace Database\Factories;

use App\Enums\PaymentProvider;
use App\Enums\PaymentStatus;
use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Model>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'provider' => fake()->randomElement([
                PaymentProvider::PayGateA->value,
                PaymentProvider::PayGateB->value,
            ]),

            'order_id' => 'ORD-'.fake()->unique()->numberBetween(1000, 9999),

            'external_id' => fake()->uuid(),

            'amount' => fake()->randomFloat(2, 10, 1000),

            'currency' => 'USD',

            'status' => fake()->randomElement([
                PaymentStatus::Pending->value,
                PaymentStatus::Success->value,
                PaymentStatus::Failed->value,
            ]),

            'description' => fake()->sentence(),

            'provider_payload' => null,
        ];
    }
}

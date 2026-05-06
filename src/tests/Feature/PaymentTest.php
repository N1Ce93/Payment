<?php

namespace Tests\Feature;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_payment_via_paygate_a(): void
    {
        $response = $this->postJson('/api/payments', [
            'provider' => 'paygate_a',
            'amount' => '100.00',
            'currency' => 'USD',
            'order_id' => 'ORD-10001',
            'description' => 'Test payment',
        ]);

        $response
            ->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'provider',
                    'external_id',
                    'status',
                    'payment_url',
                ],
            ]);

        $this->assertDatabaseHas('payments', [
            'provider' => 'paygate_a',
            'order_id' => 'ORD-10001',
            'status' => PaymentStatus::Pending->value,
        ]);
    }

    public function test_create_payment_via_paygate_b(): void
    {
        $response = $this->postJson('/api/payments', [
            'provider' => 'paygate_b',
            'amount' => '250.50',
            'currency' => 'USD',
            'order_id' => 'ORD-10002',
            'description' => 'Second payment',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath(
                'data.provider',
                'paygate_b'
            );

        $this->assertDatabaseHas('payments', [
            'provider' => 'paygate_b',
            'order_id' => 'ORD-10002',
        ]);
    }

    public function test_paygate_a_callback_updates_payment_status(): void
    {
        $payment = Payment::factory()->create([
            'provider' => 'paygate_a',
            'external_id' => 'a-100500',
            'status' => PaymentStatus::Pending,
        ]);

        $response = $this->postJson(
            '/api/callbacks/paygate_a',
            [
                'payment_id' => 'a-100500',
                'merchant_order_id' => $payment->order_id,
                'status' => 'paid',
            ]
        );

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'status' => 'success',
            ]);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => PaymentStatus::Success->value,
        ]);
    }

    public function test_paygate_b_callback_updates_payment_status(): void
    {
        $payment = Payment::factory()->create([
            'provider' => 'paygate_b',
            'external_id' => 'b-777',
            'status' => PaymentStatus::Pending,
        ]);

        $response = $this->postJson(
            '/api/callbacks/paygate_b',
            [
                'id' => 'b-777',
                'order' => $payment->order_id,
                'state' => 'success',
            ]
        );

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'status' => 'success',
            ]);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => PaymentStatus::Success->value,
        ]);
    }

    public function test_validation_error_is_returned(): void
    {
        $response = $this->postJson('/api/payments', []);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'provider',
                'amount',
                'currency',
                'order_id',
            ]);
    }

    public function test_unsupported_provider_returns_error(): void
    {
        $response = $this->postJson('/api/payments', [
            'provider' => 'unknown_provider',
            'amount' => '100.00',
            'currency' => 'USD',
            'order_id' => 'ORD-99999',
        ]);

        $response->assertUnprocessable();
    }
}

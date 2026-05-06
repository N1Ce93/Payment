<?php

use App\Enums\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->string('provider');
            $table->string('order_id')->unique()->index();
            $table->string('external_id')->nullable()->index();

            $table->decimal('amount', 12, 2);
            $table->string('currency', 3);

            $table->string('status')
                ->default(PaymentStatus::Pending->value)
                ->index();

            $table->string('payment_url')->nullable();
            $table->text('description')->nullable();

            $table->json('provider_payload')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->foreignId('campaign_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained()->nullOnDelete();

            $table->string('reference')->unique();

            $table->string('name')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->boolean('is_anonymous')->default(false);

            $table->decimal('amount', 15, 2);
            $table->integer('unit_qty')->nullable();

            $table->string('payment_method')->nullable();
            $table->string('duitku_reference')->nullable();

            $table->enum('status', ['pending', 'paid', 'failed', 'expired'])->default('pending');

            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();

            $table->json('callback_payload')->nullable();
            $table->text('failure_reason')->nullable();

            $table->boolean('is_visible')->default(true);

            $table->timestamps();

            $table->index(['campaign_id', 'status']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};

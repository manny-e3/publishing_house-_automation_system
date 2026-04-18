<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->string('gateway_slug'); // paystack, flutterwave, bank_transfer
            $table->string('transaction_reference')->unique(); // Our internal ref
            $table->string('external_reference')->nullable()->unique(); // Gateway provider ref
            $table->decimal('amount', 15, 2);
            $table->string('currency')->default('NGN');
            $table->enum('status', ['pending', 'successful', 'failed', 'abandoned'])->default('pending');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

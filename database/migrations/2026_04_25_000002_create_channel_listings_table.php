<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('channel_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prospect_id')->constrained('prospects')->onDelete('cascade');
            $table->string('channel'); // amazon_kdp, selar, african_book_collective, ingram_spark
            $table->string('storefront_url')->nullable();
            $table->boolean('is_live')->default(false);
            $table->foreignId('verified_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('live_at')->nullable();
            $table->timestamps();

            $table->unique(['prospect_id', 'channel']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('channel_listings');
    }
};


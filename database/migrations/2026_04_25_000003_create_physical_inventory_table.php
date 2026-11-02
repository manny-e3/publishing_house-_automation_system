<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('physical_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prospect_id')->constrained('prospects')->onDelete('cascade');
            $table->string('format')->default('paperback'); // paperback, hardcover
            $table->integer('quantity_ordered')->default(500);
            $table->integer('quantity_in_stock')->default(0);
            $table->string('warehouse_location')->nullable();
            $table->integer('reorder_threshold')->default(20);
            $table->timestamps();

            $table->unique(['prospect_id', 'format']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('physical_inventory');
    }
};


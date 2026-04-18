<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Pricing Rates
        Schema::create('pricing_rates', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g., printing_per_page
            $table->string('label');
            $table->decimal('value', 15, 2);
            $table->string('category')->default('general'); // printing, editing, etc.
            $table->timestamps();
        });

        // Review Criteria
        Schema::create('review_criteria', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Global Settings (Key-Value)
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('site'); // site, mail, branding
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pricing_rates');
        Schema::dropIfExists('review_criteria');
        Schema::dropIfExists('settings');
    }
};

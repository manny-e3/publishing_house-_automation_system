<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prospects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone_number');
            
            $table->string('book_title');
            $table->string('genre');
            $table->string('stage_of_manuscript');
            $table->integer('number_of_words');
            
            $table->string('manuscript_file_path')->nullable();
            $table->string('cover_design_path')->nullable();
            
            $table->string('agreement_name');
            $table->boolean('agreement_terms')->default(false);
            $table->ipAddress('ip_address')->nullable();
            $table->decimal('estimated_cost', 10, 2)->default(0);
            
            $table->string('status')->default('prospect');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prospects');
    }
};

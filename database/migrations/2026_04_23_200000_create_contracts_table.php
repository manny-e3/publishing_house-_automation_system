<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function run(): void
    {
        Schema::create('contracts', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('project_id')->constrained()->onDelete('cascade');
            $blueprint->string('status')->default('draft'); // draft, sent, signed, cancelled
            $blueprint->dateTime('signed_at')->nullable();
            $blueprint->json('signature_info')->nullable(); // IP, User Agent, Name
            $blueprint->string('document_path')->nullable();
            $blueprint->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};

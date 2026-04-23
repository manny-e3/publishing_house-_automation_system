<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prospects', function (Blueprint $table) {
            $table->integer('print_quantity')->default(1);
            $table->string('interior_paper')->nullable();
            $table->string('cover_paper')->nullable();
            $table->boolean('is_hard_cover')->default(false);
            $table->boolean('is_embossed')->default(false);
            $table->boolean('is_packaged')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('prospects', function (Blueprint $table) {
            $table->dropColumn([
                'print_quantity',
                'interior_paper',
                'cover_paper',
                'is_hard_cover',
                'is_embossed',
                'is_packaged'
            ]);
        });
    }
};

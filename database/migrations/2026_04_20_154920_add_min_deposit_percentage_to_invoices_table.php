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
        Schema::table('invoices', function (Blueprint $table) {
            $table->integer('min_deposit_percentage')->default(40)->after('payment_plans');
        });

        // Insert global setting if not exists
        \DB::table('settings')->updateOrInsert(
            ['key' => 'min_deposit_percentage'],
            [
                'value' => '40',
                'group' => 'payments',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('min_deposit_percentage');
        });
    }
};

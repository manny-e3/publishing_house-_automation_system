<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prospects', function (Blueprint $table) {
            $table->string('marketing_source')->nullable()->after('status');
            $table->integer('author_age')->nullable()->after('marketing_source');
            $table->boolean('has_social_presence')->default(false)->after('author_age');
            $table->boolean('is_established_author')->default(false)->after('has_social_presence');
            $table->string('determined_model')->default('hybrid_osisi')->after('is_established_author');
            $table->string('manuscript_rating')->nullable()->after('determined_model');
            $table->string('design_phase')->default('queued')->after('manuscript_rating');
            
            // Multi-format file URLs
            $table->string('epub_file_url')->nullable()->after('cover_design_path');
            $table->string('ebook_pdf_url')->nullable()->after('epub_file_url');
            $table->string('print_ready_pdf_url')->nullable()->after('ebook_pdf_url');
            $table->string('amazon_kdp_ready_url')->nullable()->after('print_ready_pdf_url');

            // Financial & Bulk print calculations
            $table->decimal('total_calculated_fee', 12, 2)->default(0)->after('estimated_cost');
            $table->decimal('total_bulk_print_estimate', 12, 2)->default(0)->after('total_calculated_fee');
            $table->integer('requested_print_quantity')->default(500)->after('total_bulk_print_estimate');
            $table->string('payment_merchant')->default('paystack')->after('requested_print_quantity');
            $table->string('payment_status')->default('unpaid')->after('payment_merchant');

            // JSON state columns
            $table->json('checklist_progress')->nullable()->after('payment_status');
            $table->json('metadata_file')->nullable()->after('checklist_progress');
        });
    }

    public function down(): void
    {
        Schema::table('prospects', function (Blueprint $table) {
            $table->dropColumn([
                'marketing_source',
                'author_age',
                'has_social_presence',
                'is_established_author',
                'determined_model',
                'manuscript_rating',
                'design_phase',
                'epub_file_url',
                'ebook_pdf_url',
                'print_ready_pdf_url',
                'amazon_kdp_ready_url',
                'total_calculated_fee',
                'total_bulk_print_estimate',
                'requested_print_quantity',
                'payment_merchant',
                'payment_status',
                'checklist_progress',
                'metadata_file'
            ]);
        });
    }
};


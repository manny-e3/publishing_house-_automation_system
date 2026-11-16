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
        Schema::table('prospects', function (Blueprint $table) {
            // Publishing option chosen by the author
            $table->string('publishing_option')->default('hybrid')->after('determined_model'); // hybrid, traditional, advise_me
            
            // Detailed Book Information
            $table->text('synopsis')->nullable()->after('genre');
            $table->string('target_audience')->nullable()->after('synopsis');
            $table->string('comparable_titles')->nullable()->after('target_audience');
            
            // Detailed Author Information
            $table->text('author_bio')->nullable()->after('name');
            $table->string('pen_name')->nullable()->after('author_bio');
            $table->string('author_location')->nullable()->after('pen_name');
            $table->text('prior_publications')->nullable()->after('author_location');
            $table->json('social_links')->nullable()->after('prior_publications');
            
            // Excerpt & Marketing consent
            $table->string('excerpt_file_path')->nullable()->after('manuscript_file_path');
            $table->boolean('marketing_opt_in')->default(false)->after('agreement_terms');
        });

        // Dedicated Marketing Subscribers Table for Point 23 & 15
        if (!Schema::hasTable('marketing_subscribers')) {
            Schema::create('marketing_subscribers', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('email')->unique();
                $table->string('phone_number')->nullable();
                $table->string('source')->default('enquiry'); // enquiry, website_footer, newsletter
                $table->string('preferred_genre')->nullable();
                $table->string('status')->default('active'); // active, unsubscribed
                $table->ipAddress('ip_address')->nullable();
                $table->timestamp('subscribed_at')->useCurrent();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prospects', function (Blueprint $table) {
            $table->dropColumn([
                'publishing_option',
                'synopsis',
                'target_audience',
                'comparable_titles',
                'author_bio',
                'pen_name',
                'author_location',
                'prior_publications',
                'social_links',
                'excerpt_file_path',
                'marketing_opt_in'
            ]);
        });

        Schema::dropIfExists('marketing_subscribers');
    }
};


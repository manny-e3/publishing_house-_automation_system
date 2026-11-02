<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prospect;
use App\Models\Project;
use App\Models\Invoice;
use App\Models\ChannelListing;
use App\Models\PhysicalInventory;
use App\Models\SystemFeatureFlag;
use App\Models\User;
use App\Enums\WorkflowStatus;
use App\Enums\PublishingModel;
use App\Enums\DesignStatus;
use App\Enums\ManuscriptRating;
use App\Enums\DistributionChannel;
use App\Enums\BindingType;
use Carbon\Carbon;
use Illuminate\Support\Str;

class MultiStageFunnelSeeder extends Seeder
{
    public function run(): void
    {
        $authorUser = User::where('email', 'author@thecuratedarchive.com')->first();
        $authorId = $authorUser ? $authorUser->id : 1;

        // =========================================================================
        // Record 1 (Osisi Hybrid Abandoned Checkout)
        // =========================================================================
        $lead = Prospect::updateOrCreate(
            ['email' => 'dr.kalu@africanheritage.org'],
            [
                'user_id' => $authorId,
                'name' => 'Dr. Emeka Kalu',
                'phone_number' => '+2348011223344',
                'book_title' => 'Echoes of the Niger Delta: An Economic History',
                'genre' => 'Non-Fiction',
                'stage_of_manuscript' => 'completed',
                'number_of_words' => 65000,
                'status' => WorkflowStatus::LeadCaptured->value,
                'marketing_source' => 'instagram',
                'author_age' => 54,
                'has_social_presence' => false,
                'is_established_author' => false,
                'determined_model' => PublishingModel::HybridOsisi->value,
                'manuscript_rating' => null,
                'design_phase' => DesignStatus::Queued->value,
                'estimated_cost' => 1250000.00,
                'total_calculated_fee' => 1250000.00,
                'total_bulk_print_estimate' => 850000.00,
                'requested_print_quantity' => 500,
                'payment_merchant' => 'paystack',
                'payment_status' => 'unpaid',
                'agreement_name' => 'Dr. Emeka Kalu',
                'agreement_terms' => true,
                'created_at' => Carbon::now()->subHours(3),
                'updated_at' => Carbon::now()->subHours(3),
            ]
        );

        // =========================================================================
        // Record 2 (Awaiting Acquisition Vetting)
        // =========================================================================
        $vetting = Prospect::updateOrCreate(
            ['email' => 'chidinma.okafor@literary.ng'],
            [
                'user_id' => $authorId,
                'name' => 'Chidinma Okafor',
                'phone_number' => '+2348099887766',
                'book_title' => 'The Whispering Baobab',
                'genre' => 'Fiction',
                'stage_of_manuscript' => 'completed',
                'number_of_words' => 78000,
                'status' => WorkflowStatus::Submitted->value,
                'marketing_source' => 'linkedin',
                'author_age' => 29,
                'has_social_presence' => true,
                'is_established_author' => false,
                'determined_model' => PublishingModel::Traditional->value,
                'manuscript_rating' => ManuscriptRating::Promising->value,
                'design_phase' => DesignStatus::Queued->value,
                'manuscript_file_path' => 'manuscripts/whispering_baobab_raw.docx',
                'estimated_cost' => 0.00,
                'total_calculated_fee' => 0.00,
                'total_bulk_print_estimate' => 0.00,
                'requested_print_quantity' => 500,
                'payment_merchant' => 'stripe',
                'payment_status' => 'paid',
                'agreement_name' => 'Chidinma Okafor',
                'agreement_terms' => true,
                'checklist_progress' => [
                    'basic_content_vetting' => ['completed' => true, 'timestamp' => now()->subDay()->toISOString(), 'user_id' => 2],
                    'author_background_check' => ['completed' => false],
                    'application_for_isbn' => ['completed' => false],
                    'log_acquired_isbn' => ['completed' => false],
                ],
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ]
        );

        // =========================================================================
        // Record 3 (Active Editorial Review)
        // =========================================================================
        $editing = Prospect::updateOrCreate(
            ['email' => 'tunde.adebayo@futurechronicles.io'],
            [
                'user_id' => $authorId,
                'name' => 'Tunde Adebayo',
                'phone_number' => '+2347033445566',
                'book_title' => 'Lagos 2099: Cybernetic Dawn',
                'genre' => 'Sci-Fi/Fantasy',
                'stage_of_manuscript' => 'completed',
                'number_of_words' => 92000,
                'status' => WorkflowStatus::InEditing->value,
                'marketing_source' => 'tiktok',
                'author_age' => 32,
                'has_social_presence' => true,
                'is_established_author' => true,
                'determined_model' => PublishingModel::Traditional->value,
                'manuscript_rating' => ManuscriptRating::Excellent->value,
                'design_phase' => DesignStatus::Queued->value,
                'manuscript_file_path' => 'manuscripts/lagos_2099_v2.docx',
                'estimated_cost' => 0.00,
                'total_calculated_fee' => 0.00,
                'total_bulk_print_estimate' => 0.00,
                'requested_print_quantity' => 1000,
                'payment_merchant' => 'stripe',
                'payment_status' => 'paid',
                'agreement_name' => 'Tunde Adebayo',
                'agreement_terms' => true,
                'checklist_progress' => [
                    'compulsory_editing_1' => ['completed' => true, 'timestamp' => now()->subDays(5)->toISOString(), 'user_id' => 3],
                    'compulsory_editing_2' => ['completed' => true, 'timestamp' => now()->subDays(2)->toISOString(), 'user_id' => 3],
                    'professional_proofreading' => ['completed' => true, 'timestamp' => now()->subDay()->toISOString(), 'user_id' => 3],
                    'author_final_reading' => ['completed' => false],
                    'dummy_copy_editing_1' => ['completed' => false],
                    'dummy_copy_editing_2' => ['completed' => false],
                    'dummy_copy_editing_3' => ['completed' => false],
                ],
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subHours(6),
            ]
        );

        // =========================================================================
        // Record 4 (Production & Channel Ready)
        // =========================================================================
        $ready = Prospect::updateOrCreate(
            ['email' => 'amina.yusuf@archivalpress.com'],
            [
                'user_id' => $authorId,
                'name' => 'Prof. Amina Yusuf',
                'phone_number' => '+2348055667788',
                'book_title' => 'Ancient Trade Routes of West Africa',
                'genre' => 'Non-Fiction',
                'stage_of_manuscript' => 'completed',
                'number_of_words' => 84000,
                'status' => WorkflowStatus::ReadyForPrint->value,
                'marketing_source' => 'google',
                'author_age' => 61,
                'has_social_presence' => false,
                'is_established_author' => true,
                'determined_model' => PublishingModel::HybridOsisi->value,
                'manuscript_rating' => ManuscriptRating::Excellent->value,
                'design_phase' => DesignStatus::Approved->value,
                'manuscript_file_path' => 'manuscripts/trade_routes_final.docx',
                'cover_design_path' => 'covers/trade_routes_front.jpg',
                
                // 4 Asset Targets
                'epub_file_url' => 'assets/production/trade_routes.epub',
                'ebook_pdf_url' => 'assets/production/trade_routes_ebook.pdf',
                'print_ready_pdf_url' => 'assets/production/trade_routes_press_alignment.pdf',
                'amazon_kdp_ready_url' => 'assets/production/trade_routes_kdp_package.zip',

                'estimated_cost' => 1850000.00,
                'total_calculated_fee' => 1850000.00,
                'total_bulk_print_estimate' => 1200000.00,
                'requested_print_quantity' => 1000,
                'payment_merchant' => 'paystack',
                'payment_status' => 'paid',
                'agreement_name' => 'Prof. Amina Yusuf',
                'agreement_terms' => true,
                'checklist_progress' => [
                    'compulsory_editing_1' => ['completed' => true, 'timestamp' => now()->subDays(20)->toISOString(), 'user_id' => 3],
                    'compulsory_editing_2' => ['completed' => true, 'timestamp' => now()->subDays(15)->toISOString(), 'user_id' => 3],
                    'professional_proofreading' => ['completed' => true, 'timestamp' => now()->subDays(12)->toISOString(), 'user_id' => 3],
                    'author_final_reading' => ['completed' => true, 'timestamp' => now()->subDays(10)->toISOString(), 'user_id' => $authorId],
                    'dummy_copy_editing_1' => ['completed' => true, 'timestamp' => now()->subDays(8)->toISOString(), 'user_id' => 3],
                    'dummy_copy_editing_2' => ['completed' => true, 'timestamp' => now()->subDays(6)->toISOString(), 'user_id' => 3],
                    'dummy_copy_editing_3' => ['completed' => true, 'timestamp' => now()->subDays(4)->toISOString(), 'user_id' => 3],
                ],
                'created_at' => Carbon::now()->subDays(30),
                'updated_at' => Carbon::now()->subDays(1),
            ]
        );

        // Channel Listings for Record 4
        ChannelListing::updateOrCreate(
            ['prospect_id' => $ready->id, 'channel' => DistributionChannel::AmazonKdp],
            [
                'storefront_url' => 'https://www.amazon.com/dp/B09XXSAMPLE',
                'is_live' => true,
                'verified_by_user_id' => 1,
                'live_at' => Carbon::now()->subDays(2),
            ]
        );

        ChannelListing::updateOrCreate(
            ['prospect_id' => $ready->id, 'channel' => DistributionChannel::Selar],
            [
                'storefront_url' => 'https://selar.co/sample-trade-routes',
                'is_live' => true,
                'verified_by_user_id' => 1,
                'live_at' => Carbon::now()->subDays(2),
            ]
        );

        ChannelListing::updateOrCreate(
            ['prospect_id' => $ready->id, 'channel' => DistributionChannel::AfricanBookCollective],
            [
                'storefront_url' => 'https://africanbookscollective.com/books/sample-trade-routes',
                'is_live' => true,
                'verified_by_user_id' => 1,
                'live_at' => Carbon::now()->subDays(1),
            ]
        );

        // Physical Inventory for Record 4
        PhysicalInventory::updateOrCreate(
            ['prospect_id' => $ready->id, 'format' => BindingType::Paperback],
            [
                'quantity_ordered' => 700,
                'quantity_in_stock' => 540,
                'warehouse_location' => 'Lagos Central Depot - Shelf B4',
                'reorder_threshold' => 20,
            ]
        );

        PhysicalInventory::updateOrCreate(
            ['prospect_id' => $ready->id, 'format' => BindingType::Hardcover],
            [
                'quantity_ordered' => 300,
                'quantity_in_stock' => 18, // <20: triggers low-stock alert!
                'warehouse_location' => 'Lagos Central Depot - Shelf B5 (Vault)',
                'reorder_threshold' => 20,
            ]
        );

        // System Feature Flags
        $flags = [
            ['key' => 'allow_public_submissions', 'name' => 'Allow Public Submissions', 'is_enabled' => true, 'description' => 'When disabled, freezes the submission portal for scheduled intake maintenance.'],
            ['key' => 'enable_automated_recovery_cron', 'name' => 'Enable Abandoned Cart Recovery Cron', 'is_enabled' => true, 'description' => 'Executes the 2h and 24h automated email recovery scheduler for Osisi Hybrid unpaid leads.'],
            ['key' => 'enforce_strict_distribution_gate', 'name' => 'Enforce Strict Distribution File Gate', 'is_enabled' => true, 'description' => 'Requires ePub and Amazon Ready packages to be verified prior to storefront URL entry.'],
            ['key' => 'enable_stripe_international_checkout', 'name' => 'Enable Stripe USD Checkout', 'is_enabled' => true, 'description' => 'Enables international USD card processing via Stripe Checkout sessions.'],
        ];

        foreach ($flags as $flag) {
            SystemFeatureFlag::updateOrCreate(['key' => $flag['key']], $flag);
        }
    }
}


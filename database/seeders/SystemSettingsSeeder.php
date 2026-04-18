<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemSettingsSeeder extends Seeder
{
    public function run()
    {
        // 1. Pricing Rates
        $rates = [
            ['key' => 'fixed_setup_fee', 'label' => 'Platform Setup Fee', 'value' => 150000.00, 'category' => 'general'],
            ['key' => 'fixed_editing_fee', 'label' => 'Standard Editing Base Fee', 'value' => 0.00, 'category' => 'editorial'],
            ['key' => 'editing_per_word', 'label' => 'Editing Cost (Per Word)', 'value' => 5.00, 'category' => 'editorial'],
            ['key' => 'fixed_formatting_fee', 'label' => 'Standard Formatting Base Fee', 'value' => 0.00, 'category' => 'editorial'],
            ['key' => 'formatting_per_word', 'label' => 'Formatting Cost (Per Word)', 'value' => 2.00, 'category' => 'editorial'],
            ['key' => 'fixed_cover_design_fee', 'label' => 'Cover Design Fee', 'value' => 50000.00, 'category' => 'design'],
            ['key' => 'fixed_printing_fee', 'label' => 'Base Printing & Distribution Fee', 'value' => 100000.00, 'category' => 'printing'],
        ];

        foreach ($rates as $rate) {
            DB::table('pricing_rates')->updateOrInsert(['key' => $rate['key']], $rate);
        }

        // 2. Review Criteria
        $criteria = [
            ['label' => 'Grammar & Syntax', 'description' => 'Meets basic structural publishing standards.', 'sort_order' => 1],
            ['label' => 'Pacing & Coherence', 'description' => 'The sample chapters read smoothly and logic is sound.', 'sort_order' => 2],
            ['label' => 'Originality Check', 'description' => 'Checked for blatant plagiarism risks or AI-generated patterns.', 'sort_order' => 3],
            ['label' => 'Commercial Viability', 'description' => 'Does this manuscript fit our target market niche?', 'sort_order' => 4],
        ];

        foreach ($criteria as $item) {
            DB::table('review_criteria')->updateOrInsert(['label' => $item['label']], $item);
        }

        // 3. Global Settings
        $settings = [
            ['key' => 'site_name', 'value' => 'The Curated Archive', 'group' => 'site'],
            ['key' => 'support_email', 'value' => 'support@thecuratedarchive.com', 'group' => 'mail'],
            ['key' => 'acquisitions_email', 'value' => 'acquisitions@thecuratedarchive.com', 'group' => 'mail'],
            ['key' => 'finance_email', 'value' => 'finance@thecuratedarchive.com', 'group' => 'mail'],
            ['key' => 'editorial_email', 'value' => 'editorial@thecuratedarchive.com', 'group' => 'mail'],
            ['key' => 'design_email', 'value' => 'design@thecuratedarchive.com', 'group' => 'mail'],
            ['key' => 'logistics_email', 'value' => 'logistics@thecuratedarchive.com', 'group' => 'mail'],
            ['key' => 'site_tagline', 'value' => 'Premium Publishing for the Discerning Author', 'group' => 'site'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(['key' => $setting['key']], $setting);
        }
    }
}

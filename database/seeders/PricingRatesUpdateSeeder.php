<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PricingRatesUpdateSeeder extends Seeder
{
    public function run()
    {
        // 1. First Request Data (Paper types, etc.)
        $interiorPapers = [
            ['key' => 'paper_cream_17x24_80g', 'label' => 'Cream 17x24 80 Gram', 'value' => 70000.00, 'category' => 'interior_paper'],
            ['key' => 'paper_cream_17x24_70g', 'label' => 'Cream 17x24 70 Gram', 'value' => 34000.00, 'category' => 'interior_paper'],
            ['key' => 'paper_cream_17x24_60g', 'label' => 'Cream 17x24 60 Gram', 'value' => 5.00, 'category' => 'interior_paper'],
            ['key' => 'paper_cream_24x36_80g', 'label' => 'Cream 24x36 80 Gram', 'value' => 80000.00, 'category' => 'interior_paper'],
            ['key' => 'paper_cream_24x36_70g', 'label' => 'Cream 24x36 70 Gram', 'value' => 5.00, 'category' => 'interior_paper'],
            ['key' => 'paper_cream_24x36_60g', 'label' => 'Cream 24x36 60 Gram', 'value' => 5.00, 'category' => 'interior_paper'],
            ['key' => 'paper_cream_20x30_80g', 'label' => 'Cream 20x30 80 Gram', 'value' => 5.00, 'category' => 'interior_paper'],
            ['key' => 'paper_cream_20x30_70g', 'label' => 'Cream 20x30 70 Gram', 'value' => 5.00, 'category' => 'interior_paper'],
            ['key' => 'paper_cream_20x30_60g', 'label' => 'Cream 20x30 60 Gram', 'value' => 5.00, 'category' => 'interior_paper'],
            ['key' => 'paper_white_17x24_80g', 'label' => 'White 17x24 80 Gram', 'value' => 5.00, 'category' => 'interior_paper'],
            ['key' => 'paper_white_17x24_70g', 'label' => 'White 17x24 70 Gram', 'value' => 5.00, 'category' => 'interior_paper'],
            ['key' => 'paper_white_17x24_60g', 'label' => 'White 17x24 60 Gram', 'value' => 5.00, 'category' => 'interior_paper'],
            ['key' => 'paper_white_24x36_80g', 'label' => 'White 24x36 80 Gram', 'value' => 5.00, 'category' => 'interior_paper'],
            ['key' => 'paper_white_24x36_70g', 'label' => 'White 24x36 70 Gram', 'value' => 5.00, 'category' => 'interior_paper'],
            ['key' => 'paper_white_24x36_60g', 'label' => 'White 24x36 60 Gram', 'value' => 5.00, 'category' => 'interior_paper'],
            ['key' => 'paper_white_20x30_80g', 'label' => 'White 20x30 80 Gram', 'value' => 5.00, 'category' => 'interior_paper'],
            ['key' => 'paper_white_20x30_70g', 'label' => 'White 20x30 70 Gram', 'value' => 5.00, 'category' => 'interior_paper'],
            ['key' => 'paper_white_20x30_60g', 'label' => 'White 20x30 60 Gram', 'value' => 5.00, 'category' => 'interior_paper'],
            ['key' => 'paper_art_paper', 'label' => 'Art Paper', 'value' => 88000.00, 'category' => 'interior_paper'],
        ];

        $platingPress = [
            ['key' => 'plating_dummy', 'label' => 'Dummy Plating', 'value' => 1000.00, 'category' => 'plating'],
            ['key' => 'plating_standard', 'label' => 'Standard Plating', 'value' => 3500.00, 'category' => 'plating'],
            ['key' => 'press_impression', 'label' => 'Press Impression', 'value' => 2000.00, 'category' => 'main_press'],
            ['key' => 'press_folding', 'label' => 'Folding', 'value' => 50.00, 'category' => 'main_press'],
            ['key' => 'press_lamination', 'label' => 'Lamination', 'value' => 70.00, 'category' => 'main_press'],
            ['key' => 'press_binding', 'label' => 'Binding', 'value' => 50.00, 'category' => 'main_press'],
            ['key' => 'press_cutting', 'label' => 'Cutting', 'value' => 20.00, 'category' => 'main_press'],
            ['key' => 'effect_hard_cover', 'label' => 'Hard Cover Effect', 'value' => 3000.00, 'category' => 'special_effects'],
            ['key' => 'effect_embossing', 'label' => 'Embossing', 'value' => 500.00, 'category' => 'special_effects'],
            ['key' => 'pkg_packaging', 'label' => 'Packaging', 'value' => 50.00, 'category' => 'branding_packaging'],
            ['key' => 'pkg_carton', 'label' => 'Carton', 'value' => 100.00, 'category' => 'branding_packaging'],
            ['key' => 'pkg_branding', 'label' => 'Branding', 'value' => 200.00, 'category' => 'branding_packaging'],
        ];

        $specialCards = [
            ['key' => 'card_art_200g', 'label' => 'Art Card 200 Gram', 'value' => 20000.00, 'category' => 'special_paper'],
            ['key' => 'card_art_250g', 'label' => 'Art Card 250 Gram', 'value' => 3000.00, 'category' => 'special_paper'],
            ['key' => 'card_art_300g', 'label' => 'Art Card 300 Gram', 'value' => 2348.00, 'category' => 'special_paper'],
            ['key' => 'card_art_400g', 'label' => 'Art Card 400 Gram', 'value' => 40000.00, 'category' => 'special_paper'],
            ['key' => 'fbb_200g', 'label' => 'FBB 200 Gram', 'value' => 4556.00, 'category' => 'special_paper'],
            ['key' => 'fbb_250g', 'label' => 'FBB 250 Gram', 'value' => 1000.00, 'category' => 'special_paper'],
            ['key' => 'fbb_300g', 'label' => 'FBB 300 Gram', 'value' => 4567.00, 'category' => 'special_paper'],
            ['key' => 'fbb_400g', 'label' => 'FBB 400 Gram', 'value' => 444.00, 'category' => 'special_paper'],
        ];

        // 2. Second Request Data (Unit Prices for Calculator)
        $calculatorUnits = [
            ['key' => 'calc_interior_paper', 'label' => 'Interior Paper (Unit)', 'value' => 5.00, 'category' => 'costing'],
            ['key' => 'calc_cover_paper', 'label' => 'Cover Paper (Unit)', 'value' => 2348.00, 'category' => 'costing'],
            ['key' => 'calc_interior_plating', 'label' => 'Interior Plating (Unit)', 'value' => 4500.00, 'category' => 'costing'],
            ['key' => 'calc_cover_plating', 'label' => 'Cover Plating (Unit)', 'value' => 14000.00, 'category' => 'costing'],
            ['key' => 'calc_impression', 'label' => 'Impression (Unit)', 'value' => 2000.00, 'category' => 'costing'],
            ['key' => 'calc_cover_impression', 'label' => 'Cover Impression (Unit)', 'value' => 8000.00, 'category' => 'costing'],
            ['key' => 'calc_folding', 'label' => 'Folding (Unit)', 'value' => 50.00, 'category' => 'costing'],
            ['key' => 'calc_lamination', 'label' => 'Lamination (Unit)', 'value' => 70.00, 'category' => 'costing'],
            ['key' => 'calc_binding', 'label' => 'Binding (Unit)', 'value' => 50.00, 'category' => 'costing'],
            ['key' => 'calc_cutting', 'label' => 'Cutting (Unit)', 'value' => 20.00, 'category' => 'costing'],
            ['key' => 'calc_packaging', 'label' => 'Packaging (Unit)', 'value' => 50.00, 'category' => 'costing'],
        ];

        // 3. Technical Variables
        $variables = [
            ['key' => 'var_rim_papers_surplus', 'label' => 'Rim of Papers Plus Surplus', 'value' => 180.00, 'category' => 'variables'],
            ['key' => 'var_plate_quantity', 'label' => 'Plate Quantity', 'value' => 150.00, 'category' => 'variables'],
            ['key' => 'var_number_of_sets', 'label' => 'Number of Sets', 'value' => 75.00, 'category' => 'variables'],
            ['key' => 'var_cover_paper_quantity', 'label' => 'Cover Paper Quantity', 'value' => 2.50, 'category' => 'variables'],
        ];

        $allRates = array_merge($interiorPapers, $platingPress, $specialCards, $calculatorUnits, $variables);

        foreach ($allRates as $rate) {
            DB::table('pricing_rates')->updateOrInsert(['key' => $rate['key']], $rate);
        }
    }
}

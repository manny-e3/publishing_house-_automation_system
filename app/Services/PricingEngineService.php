<?php

namespace App\Services;

use App\Models\Setting;
use App\Enums\BookTrimSize;
use App\Enums\BindingType;

/**
 * Interactive Pricing & Bulk Print Calculation Engine
 *
 * Implements the Osisi Hybrid pricing matrix, word-count tier adjustments,
 * trim size page calculations, add-ons, and dynamic multi-currency conversions (USD Stripe vs NGN Paystack).
 *
 * EXTENSION NOTE FOR FUTURE DEVELOPERS:
 * You can expand $TRIM_SIZE_SPECS to support alternative layout specs
 * such as 'eight_by_ten' (8x10 = 450 words/page) or 'landscape_photo' (180 words/page).
 */
class PricingEngineService
{
    public const BASE_OPERATIONS_FEE_USD = 150.00;
    public const MINIMUM_PRINT_QUANTITY = 500;
    public const DEFAULT_USD_TO_NGN_RATE = 1500.00;

    public const DISCLAIMER = '⚠️ DISCLAIMER: Total project cost is an architectural estimate based on self-reported metrics. Actual production costs may vary by up to 15% pending final design configurations.';

    /**
     * Per-word rates in USD by genre category
     */
    public const GENRE_RATES_USD = [
        'fiction' => 0.010,
        'non-fiction' => 0.012,
        'biography/memoir' => 0.014,
        'biography' => 0.014,
        'memoir' => 0.014,
        'sci-fi/fantasy' => 0.015,
        'academic/business' => 0.018,
        'academic' => 0.018,
        'business' => 0.018,
        'default' => 0.010,
    ];

    /**
     * Add-on service pricing
     */
    public const ADDONS_USD = [
        'proofreading_per_word' => 0.005,
        'cover_design_flat' => 300.00,
        'marketing_package_flat' => 500.00,
    ];

    /**
     * Trim size specifications: words per page.
     * Easily expandable for future alternative layouts.
     */
    public const TRIM_SIZE_SPECS = [
        'five_by_eight' => ['label' => '5x8 Pocket', 'words_per_page' => 300],
        'six_by_nine' => ['label' => '6x9 Trade Paperback', 'words_per_page' => 350],
        'seven_by_ten' => ['label' => '7x10 Executive', 'words_per_page' => 400],
        // Future extensions can be appended here:
        // 'eight_by_ten' => ['label' => '8x10 Illustrated', 'words_per_page' => 450],
        // 'landscape_photo' => ['label' => 'Landscape Art/Photo', 'words_per_page' => 180],
    ];

    /**
     * Get the active USD to NGN exchange rate from settings or fallback.
     */
    public function getExchangeRate(): float
    {
        $rate = Setting::get('exchange_rate_usd_ngn', (string) self::DEFAULT_USD_TO_NGN_RATE);
        return (float) ($rate ?: self::DEFAULT_USD_TO_NGN_RATE);
    }

    /**
     * Calculate comprehensive quote breakdown.
     *
     * @param array $params [
     *   'number_of_words' => int,
     *   'genre' => string,
     *   'currency' => 'USD'|'NGN',
     *   'include_proofreading' => bool,
     *   'include_cover_design' => bool,
     *   'include_marketing_package' => bool,
     *   'trim_size' => string ('five_by_eight'|'six_by_nine'|'seven_by_ten'),
     *   'binding_type' => string ('paperback'|'hardcover'),
     *   'print_quantity' => int (min 500),
     * ]
     * @return array Itemized financial metrics
     */
    public function calculate(array $params): array
    {
        $wordCount = max(1, (int) ($params['number_of_words'] ?? 0));
        $genre = strtolower(trim($params['genre'] ?? 'fiction'));
        $currency = strtoupper(trim($params['currency'] ?? 'USD')) === 'NGN' ? 'NGN' : 'USD';
        
        $includeProofreading = !empty($params['include_proofreading']);
        $includeCover = !empty($params['include_cover_design']);
        $includeMarketing = !empty($params['include_marketing_package']);

        $trimKey = $params['trim_size'] ?? 'six_by_nine';
        $wordsPerPage = self::TRIM_SIZE_SPECS[$trimKey]['words_per_page'] ?? 350;
        
        $binding = strtolower(trim($params['binding_type'] ?? 'paperback'));
        $isHardcover = ($binding === 'hardcover');
        
        $rawQty = (int) ($params['print_quantity'] ?? self::MINIMUM_PRINT_QUANTITY);
        $printQty = max(self::MINIMUM_PRINT_QUANTITY, $rawQty);

        // 1. Editorial & Production Fees (USD)
        $baseFeeUsd = self::BASE_OPERATIONS_FEE_USD;
        
        $genrePerWord = self::GENRE_RATES_USD[$genre] ?? null;
        if ($genrePerWord === null) {
            if (str_contains($genre, 'academic') || str_contains($genre, 'business')) {
                $genrePerWord = self::GENRE_RATES_USD['academic/business'];
            } elseif (str_contains($genre, 'sci-fi') || str_contains($genre, 'fantasy')) {
                $genrePerWord = self::GENRE_RATES_USD['sci-fi/fantasy'];
            } elseif (str_contains($genre, 'biography') || str_contains($genre, 'memoir')) {
                $genrePerWord = self::GENRE_RATES_USD['biography/memoir'];
            } elseif (str_contains($genre, 'non')) {
                $genrePerWord = self::GENRE_RATES_USD['non-fiction'];
            } else {
                $genrePerWord = self::GENRE_RATES_USD['fiction'];
            }
        }
        $genreWordFeeUsd = $wordCount * $genrePerWord;

        // Add-ons
        $proofreadingFeeUsd = $includeProofreading ? ($wordCount * self::ADDONS_USD['proofreading_per_word']) : 0.0;
        $coverDesignFeeUsd = $includeCover ? self::ADDONS_USD['cover_design_flat'] : 0.0;
        $marketingFeeUsd = $includeMarketing ? self::ADDONS_USD['marketing_package_flat'] : 0.0;

        $totalEditorialFeeUsd = $baseFeeUsd + $genreWordFeeUsd + $proofreadingFeeUsd + $coverDesignFeeUsd + $marketingFeeUsd;

        // 2. Physical Bulk Print Calculator
        $estimatedPages = max(40, (int) ceil($wordCount / $wordsPerPage));
        
        // Manufacturing base formula:
        // Paperback: $2.50 base + ($pages * $0.015)
        // Hardcover: $6.00 base + ($pages * $0.022)
        $perBookUnitPrintCostUsd = $isHardcover 
            ? (6.00 + ($estimatedPages * 0.022))
            : (2.50 + ($estimatedPages * 0.015));

        $totalBulkPrintUsd = $perBookUnitPrintCostUsd * $printQty;

        // 3. Grand Total in USD
        $totalProjectCostUsd = $totalEditorialFeeUsd + $totalBulkPrintUsd;

        // 4. Currency Conversion
        $exchangeRate = $this->getExchangeRate();
        $multiplier = ($currency === 'NGN') ? $exchangeRate : 1.0;
        $currencySymbol = ($currency === 'NGN') ? '₦' : '$';
        $paymentMerchant = ($currency === 'NGN') ? 'paystack' : 'stripe';

        return [
            'currency' => $currency,
            'currency_symbol' => $currencySymbol,
            'payment_merchant' => $paymentMerchant,
            'exchange_rate_used' => $exchangeRate,
            
            // USD Base Metrics
            'usd' => [
                'base_operations_fee' => round($baseFeeUsd, 2),
                'genre_word_fee' => round($genreWordFeeUsd, 2),
                'proofreading_fee' => round($proofreadingFeeUsd, 2),
                'cover_design_fee' => round($coverDesignFeeUsd, 2),
                'marketing_package_fee' => round($marketingFeeUsd, 2),
                'total_editorial_fee' => round($totalEditorialFeeUsd, 2),
                'unit_print_cost' => round($perBookUnitPrintCostUsd, 2),
                'total_bulk_print_estimate' => round($totalBulkPrintUsd, 2),
                'total_project_cost' => round($totalProjectCostUsd, 2),
            ],

            // Active Display Metrics (USD or NGN)
            'active' => [
                'base_operations_fee' => round($baseFeeUsd * $multiplier, 2),
                'genre_word_fee' => round($genreWordFeeUsd * $multiplier, 2),
                'proofreading_fee' => round($proofreadingFeeUsd * $multiplier, 2),
                'cover_design_fee' => round($coverDesignFeeUsd * $multiplier, 2),
                'marketing_package_fee' => round($marketingFeeUsd * $multiplier, 2),
                'total_editorial_fee' => round($totalEditorialFeeUsd * $multiplier, 2),
                'unit_print_cost' => round($perBookUnitPrintCostUsd * $multiplier, 2),
                'total_bulk_print_estimate' => round($totalBulkPrintUsd * $multiplier, 2),
                'total_project_cost' => round($totalProjectCostUsd * $multiplier, 2),
            ],

            // Specifications
            'specs' => [
                'word_count' => $wordCount,
                'words_per_page' => $wordsPerPage,
                'estimated_pages' => $estimatedPages,
                'trim_size' => $trimKey,
                'binding_type' => $binding,
                'print_quantity' => $printQty,
            ],

            'disclaimer' => self::DISCLAIMER,
        ];
    }
}


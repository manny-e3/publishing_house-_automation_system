<?php

namespace App\Services;

use App\Models\PricingRate;
use App\Models\ReviewCriterion;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;

class SettingsService
{
    /**
     * Get pricing rates grouped by category.
     */
    public function getPricingRates(): Collection
    {
        return PricingRate::all()->groupBy('category');
    }

    /**
     * Update bulk pricing rates.
     */
    public function updatePricingRates(array $rates): void
    {
        foreach ($rates as $id => $value) {
            PricingRate::where('id', $id)->update(['value' => $value]);
        }
    }

    /**
     * Get review criteria ordered by sort order.
     */
    public function getReviewCriteria(): Collection
    {
        return ReviewCriterion::orderBy('sort_order')->get();
    }

    /**
     * Create a new review criterion.
     */
    public function createReviewCriterion(array $data): ReviewCriterion
    {
        return ReviewCriterion::create($data);
    }

    /**
     * Update an existing review criterion.
     */
    public function updateReviewCriterion(ReviewCriterion $criterion, array $data): void
    {
        $criterion->update($data);
    }

    /**
     * Delete a review criterion.
     */
    public function deleteReviewCriterion(ReviewCriterion $criterion): void
    {
        $criterion->delete();
    }

    /**
     * Get global settings grouped by group, excluding branding and mail.
     */
    public function getGlobalSettings(): Collection
    {
        return Setting::whereNotIn('group', ['Branding', 'Mail', 'Email', 'Branding Settings', 'Mail Settings'])
            ->get()
            ->groupBy('group');
    }

    /**
     * Update global settings in bulk.
     */
    public function updateGlobalSettings(array $settings): void
    {
        foreach ($settings as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }
    }
}

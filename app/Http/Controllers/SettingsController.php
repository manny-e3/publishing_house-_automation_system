<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReviewCriterion;
use App\Services\SettingsService;

class SettingsController extends Controller
{
    protected $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    // Pricing Matrix
    public function pricingIndex()
    {
        $rates = $this->settingsService->getPricingRates();
        return view('admin.settings.pricing', compact('rates'));
    }

    public function pricingUpdate(Request $request)
    {
        $data = $request->validate([
            'rates.*' => 'required|numeric|min:0'
        ]);

        $this->settingsService->updatePricingRates($data['rates']);

        return back()->with('success', 'Pricing matrix updated successfully.');
    }

    // Review Criteria
    public function criteriaIndex()
    {
        $criteria = $this->settingsService->getReviewCriteria();
        return view('admin.settings.criteria', compact('criteria'));
    }

    public function criteriaStore(Request $request)
    {
        $data = $request->validate([
            'label' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if (!$request->has('is_active')) {
            $data['is_active'] = 1;
        }

        $this->settingsService->createReviewCriterion($data);

        return back()->with('success', 'Review criterion added successfully.');
    }

    public function criteriaUpdate(Request $request, ReviewCriterion $criterion)
    {
        $data = $request->validate([
            'label' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if (!$request->has('is_active')) {
            $data['is_active'] = 0;
        }

        $this->settingsService->updateReviewCriterion($criterion, $data);

        return back()->with('success', 'Review criterion updated successfully.');
    }

    public function criteriaDelete(ReviewCriterion $criterion)
    {
        $this->settingsService->deleteReviewCriterion($criterion);
        return back()->with('success', 'Review criterion removed.');
    }

    // Global Settings
    public function globalIndex()
    {
        $settings = $this->settingsService->getGlobalSettings();
        return view('admin.settings.global', compact('settings'));
    }

    public function globalUpdate(Request $request)
    {
        $data = $request->except('_token');

        $this->settingsService->updateGlobalSettings($data);

        return back()->with('success', 'System settings updated successfully.');
    }

    // Email Templates
    public function templatesIndex()
    {
        $templates = \App\Models\EmailTemplate::all();
        return view('admin.settings.templates', compact('templates'));
    }

    public function templatesUpdate(Request $request, \App\Models\EmailTemplate $template)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string'
        ]);

        $template->update($data);

        return back()->with('success', 'Email template updated successfully.');
    }
}

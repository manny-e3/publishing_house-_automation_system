<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PricingRate;
use App\Models\ReviewCriterion;
use App\Models\Setting;

class SettingsController extends Controller
{
    // Pricing Matrix
    public function pricingIndex()
    {
        $rates = PricingRate::all()->groupBy('category');
        return view('admin.settings.pricing', compact('rates'));
    }

    public function pricingUpdate(Request $request)
    {
        $data = $request->validate([
            'rates.*' => 'required|numeric|min:0'
        ]);

        foreach ($data['rates'] as $id => $value) {
            PricingRate::where('id', $id)->update(['value' => $value]);
        }

        return back()->with('success', 'Pricing matrix updated successfully.');
    }

    // Review Criteria
    public function criteriaIndex()
    {
        $criteria = ReviewCriterion::orderBy('sort_order')->get();
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

        ReviewCriterion::create($data);

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

        $criterion->update($data);

        return back()->with('success', 'Review criterion updated successfully.');
    }

    public function criteriaDelete(ReviewCriterion $criterion)
    {
        $criterion->delete();
        return back()->with('success', 'Review criterion removed.');
    }

    // Global Settings
    public function globalIndex()
    {
        $settings = Setting::whereNotIn('group', ['Branding', 'Mail', 'Email', 'Branding Settings', 'Mail Settings'])
            ->get()
            ->groupBy('group');
            
        return view('admin.settings.global', compact('settings'));
    }

    public function globalUpdate(Request $request)
    {
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }

        return back()->with('success', 'System settings updated successfully.');
    }

    // Email Templates
    public function templatesIndex()
    {
        $templates = EmailTemplate::all();
        return view('admin.settings.templates', compact('templates'));
    }

    public function templatesUpdate(Request $request, EmailTemplate $template)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string'
        ]);

        $template->update($data);

        return back()->with('success', 'Email template updated successfully.');
    }
}

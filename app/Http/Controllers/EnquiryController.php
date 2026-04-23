<?php

namespace App\Http\Controllers;

use App\Models\Prospect;
use App\Events\ProspectSubmitted;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    protected $prospectService;

    public function __construct(\App\Services\ProspectService $prospectService)
    {
        $this->prospectService = $prospectService;
    }

    public function enquiry()
    {
        $rates = \App\Models\PricingRate::all()->pluck('value', 'key');
        $groupedRates = \App\Models\PricingRate::all()->groupBy('category');
        return view('enquiry', compact('rates', 'groupedRates'));
    }

    public function store(Request $request)
    {
        // Convert checkbox "on" values to boolean
        $request->merge([
            'is_hard_cover' => $request->has('is_hard_cover'),
            'is_embossed' => $request->has('is_embossed'),
            'is_packaged' => $request->has('is_packaged'),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            
            'book_title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'stage_of_manuscript' => 'required|string|max:255',
            'number_of_words' => 'required|integer|min:1',
            'services' => 'required|array|min:1',
            'services.*' => 'in:editing,formatting,cover,printing',
            
            'print_quantity' => 'nullable|integer|min:1',
            'interior_paper' => 'nullable|string|exists:pricing_rates,key',
            'cover_paper' => 'nullable|string|exists:pricing_rates,key',
            'is_hard_cover' => 'boolean',
            'is_embossed' => 'boolean',
            'is_packaged' => 'boolean',

            'manuscript_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'cover_design_file' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:10240',
            
            'agreement_name' => 'required|string',
            'agreement_terms' => 'accepted',
            'estimated_cost' => 'nullable|numeric'
        ]);

        $validated['manuscript_file'] = $request->file('manuscript_file');
        $validated['cover_design_file'] = $request->file('cover_design_file');
        $validated['client_side_cost'] = $request->input('estimated_cost');

        $this->prospectService->submitEnquiry($validated, $request->ip());

        return back()->with('success', 'Enquiry submitted successfully! Our team will contact you soon.');
    }


    public function index()
    {
        $rates = \App\Models\PricingRate::all()->pluck('value', 'key');
        return view('welcome', compact('rates'));
    }
}

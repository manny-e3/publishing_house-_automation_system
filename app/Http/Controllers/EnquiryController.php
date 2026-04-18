<?php

namespace App\Http\Controllers;

use App\Models\Prospect;
use App\Events\ProspectSubmitted;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function enquiry()
    {
        $rates = \App\Models\PricingRate::all()->pluck('value', 'key');
        return view('enquiry', compact('rates'));
    }

    public function store(Request $request)
    {
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
            
            'manuscript_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'cover_design_file' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:10240',
            
            'agreement_name' => 'required|string',
            'agreement_terms' => 'accepted'
        ]);

        $filePath = $request->file('manuscript_file')->store('manuscripts', 'local');
        $coverDesignPath = $request->hasFile('cover_design_file') ? $request->file('cover_design_file')->store('cover_designs', 'local') : null;
        
        $rates = \App\Models\PricingRate::all()->pluck('value', 'key');
        
        $estimatedCost = $rates['fixed_setup_fee'] ?? 150000;
        
        if (in_array('editing', $validated['services'])) {
            $estimatedCost += ($rates['fixed_editing_fee'] ?? 0) + ($validated['number_of_words'] * ($rates['editing_per_word'] ?? 5));
        }
        
        if (in_array('formatting', $validated['services'])) {
            $estimatedCost += ($rates['fixed_formatting_fee'] ?? 0) + ($validated['number_of_words'] * ($rates['formatting_per_word'] ?? 2));
        }
        
        if (in_array('cover', $validated['services'])) {
            $estimatedCost += ($rates['fixed_cover_design_fee'] ?? 50000);
        }
        
        if (in_array('printing', $validated['services'])) {
            $estimatedCost += ($rates['fixed_printing_fee'] ?? 100000);
        }

        $prospect = Prospect::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            
            'book_title' => $validated['book_title'],
            'genre' => $validated['genre'],
            'stage_of_manuscript' => $validated['stage_of_manuscript'],
            'number_of_words' => $validated['number_of_words'],
            'quote_for_services' => $validated['services'],
            
            'manuscript_file_path' => $filePath,
            'cover_design_path' => $coverDesignPath,
            
            'agreement_name' => $validated['agreement_name'],
            'agreement_terms' => true,
            'ip_address' => $request->ip(),
            'estimated_cost' => $estimatedCost,
        ]);

        event(new ProspectSubmitted($prospect));

        return back()->with('success', 'Enquiry submitted successfully! Our team will contact you soon.');
    }


    public function index()
    {
        $rates = \App\Models\PricingRate::all()->pluck('value', 'key');
        return view('welcome', compact('rates'));
    }
}

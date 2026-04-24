<?php

namespace App\Services;

use App\Models\Prospect;
use App\Models\PricingRate;
use App\Events\ProspectSubmitted;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProspectService
{
    protected $contractService;

    public function __construct(ContractService $contractService)
    {
        $this->contractService = $contractService;
    }

    /**
     * Handle the submission of a new manuscript enquiry.
     */
    public function submitEnquiry(array $data, string $ipAddress): Prospect
    {
        // 1. Handle File Uploads
        $filePath = $data['manuscript_file']->store('manuscripts', 'local');
        $coverDesignPath = isset($data['cover_design_file']) 
            ? $data['cover_design_file']->store('cover_designs', 'local') 
            : null;

        // 2. Calculate Estimated Cost
        $estimatedCost = $this->calculateEstimatedCost($data);

        // 3. Create Prospect Record
        $prospect = Prospect::create([
            'user_id' => Auth::id(),
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            
            'book_title' => $data['book_title'],
            'genre' => $data['genre'],
            'stage_of_manuscript' => $data['stage_of_manuscript'],
            'number_of_words' => $data['number_of_words'],
            'quote_for_services' => $data['services'],
            
            'print_quantity' => $data['print_quantity'] ?? 1,
            'interior_paper' => $data['interior_paper'] ?? null,
            'cover_paper' => $data['cover_paper'] ?? null,
            'is_hard_cover' => $data['is_hard_cover'] ?? false,
            'is_embossed' => $data['is_embossed'] ?? false,
            'is_packaged' => $data['is_packaged'] ?? false,

            'manuscript_file_path' => $filePath,
            'cover_design_path' => $coverDesignPath,
            
            'agreement_name' => $data['agreement_name'],
            'agreement_terms' => true,
            'ip_address' => $ipAddress,
            'estimated_cost' => $data['client_side_cost'] ?? $estimatedCost,
        ]);

        // 4. Generate & Auto-Sign Submission Agreement
        $this->contractService->generateSubmissionContract($prospect, [
            'name' => $data['agreement_name'],
            'ip' => $ipAddress
        ]);

        // 5. Dispatch Event
        event(new ProspectSubmitted($prospect));

        return $prospect;
    }

    /**
     * Backend fallback cost calculation.
     */
    public function calculateEstimatedCost(array $data): float
    {
        $rates = PricingRate::all()->pluck('value', 'key');
        
        $cost = $rates['fixed_setup_fee'] ?? 150000;
        $services = $data['services'] ?? [];
        $words = $data['number_of_words'] ?? 0;

        if (in_array('editing', $services)) {
            $cost += ($rates['fixed_editing_fee'] ?? 0) + ($words * ($rates['editing_per_word'] ?? 5));
        }
        
        if (in_array('formatting', $services)) {
            $cost += ($rates['fixed_formatting_fee'] ?? 0) + ($words * ($rates['formatting_per_word'] ?? 2));
        }
        
        if (in_array('cover', $services)) {
            $cost += ($rates['fixed_cover_design_fee'] ?? 50000);
        }
        
        if (in_array('printing', $services)) {
            $cost += ($rates['fixed_printing_fee'] ?? 100000);
        }

        return (float) $cost;
    }
}

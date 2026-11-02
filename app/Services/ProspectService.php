<?php

namespace App\Services;

use App\Models\Prospect;
use App\Models\PricingRate;
use App\Events\ProspectSubmitted;
use App\Enums\WorkflowStatus;
use App\Enums\PublishingModel;
use App\Enums\DesignStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProspectService
{
    protected ContractService $contractService;
    protected PublishingTriageService $triageService;
    protected PricingEngineService $pricingService;

    public function __construct(
        ContractService $contractService,
        PublishingTriageService $triageService,
        PricingEngineService $pricingService
    ) {
        $this->contractService = $contractService;
        $this->triageService = $triageService;
        $this->pricingService = $pricingService;
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

        // 2. Perform Automated Triage
        $authorAge = isset($data['author_age']) ? (int) $data['author_age'] : null;
        $hasSocialPresence = !empty($data['has_social_presence']);
        $isEstablished = !empty($data['is_established_author']);
        $genre = $data['genre'] ?? 'Fiction';

        $determinedModel = $this->triageService->determinePublishingModel(
            $authorAge,
            $genre,
            $hasSocialPresence,
            $isEstablished
        );

        $triageReason = $this->triageService->getDeterminationReason(
            $determinedModel,
            $authorAge,
            $genre,
            $hasSocialPresence,
            $isEstablished
        );

        // 3. Calculate Pricing Metrics
        $currency = strtoupper($data['currency'] ?? 'NGN');
        $quoteBreakdown = $this->pricingService->calculate([
            'number_of_words' => (int) ($data['number_of_words'] ?? 0),
            'genre' => $genre,
            'currency' => $currency,
            'include_proofreading' => !empty($data['include_proofreading']) || (isset($data['services']) && in_array('proofreading', $data['services'])),
            'include_cover_design' => !empty($data['include_cover_design']) || (isset($data['services']) && in_array('cover', $data['services'])),
            'include_marketing_package' => !empty($data['include_marketing_package']) || (isset($data['services']) && in_array('marketing', $data['services'])),
            'trim_size' => $data['trim_size'] ?? 'six_by_nine',
            'binding_type' => !empty($data['is_hard_cover']) ? 'hardcover' : ($data['binding_type'] ?? 'paperback'),
            'print_quantity' => (int) ($data['print_quantity'] ?? 500),
        ]);

        $isTraditional = ($determinedModel === PublishingModel::Traditional);

        // Financial allocation based on triage
        $totalCalculatedFee = $isTraditional ? 0.00 : $quoteBreakdown['active']['total_editorial_fee'];
        $totalBulkPrint = $isTraditional ? 0.00 : $quoteBreakdown['active']['total_bulk_print_estimate'];
        $grandTotal = $isTraditional ? 0.00 : $quoteBreakdown['active']['total_project_cost'];
        $status = $isTraditional ? WorkflowStatus::Submitted->value : WorkflowStatus::LeadCaptured->value;
        $paymentStatus = $isTraditional ? 'paid' : 'unpaid';
        $paymentMerchant = $quoteBreakdown['payment_merchant'];

        // 4. Create Prospect / Manuscript Record
        $prospect = Prospect::create([
            'user_id' => Auth::id(),
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            
            'book_title' => $data['book_title'],
            'genre' => $genre,
            'stage_of_manuscript' => $data['stage_of_manuscript'] ?? 'completed',
            'number_of_words' => (int) ($data['number_of_words'] ?? 0),
            'quote_for_services' => $data['services'] ?? [],
            
            // Triage & Marketing Fields
            'marketing_source' => $data['marketing_source'] ?? $data['utm_source'] ?? null,
            'author_age' => $authorAge,
            'has_social_presence' => $hasSocialPresence,
            'is_established_author' => $isEstablished,
            'determined_model' => $determinedModel->value,
            'status' => $status,
            'design_phase' => DesignStatus::Queued->value,

            // Print & Production Details
            'requested_print_quantity' => max(500, (int) ($data['print_quantity'] ?? 500)),
            'print_quantity' => max(500, (int) ($data['print_quantity'] ?? 500)),
            'interior_paper' => $data['interior_paper'] ?? null,
            'cover_paper' => $data['cover_paper'] ?? null,
            'is_hard_cover' => !empty($data['is_hard_cover']),
            'is_embossed' => !empty($data['is_embossed']),
            'is_packaged' => !empty($data['is_packaged']),

            // File Uploads
            'manuscript_file_path' => $filePath,
            'cover_design_path' => $coverDesignPath,
            
            // Financial Metrics
            'total_calculated_fee' => $totalCalculatedFee,
            'total_bulk_print_estimate' => $totalBulkPrint,
            'estimated_cost' => $grandTotal,
            'payment_merchant' => $paymentMerchant,
            'payment_status' => $paymentStatus,

            // Legal Agreement & Metadata
            'agreement_name' => $data['agreement_name'],
            'agreement_terms' => true,
            'ip_address' => $ipAddress,
            'checklist_progress' => [],
            'metadata_file' => [
                'triage_reason' => $triageReason,
                'pricing_quote' => $quoteBreakdown,
                'ip_address' => $ipAddress,
                'submission_timestamp' => now()->toIso8601String(),
            ]
        ]);

        // 5. Generate & Auto-Sign Submission Agreement
        $this->contractService->generateSubmissionContract($prospect, [
            'name' => $data['agreement_name'],
            'ip' => $ipAddress
        ]);

        // 6. Dispatch Event
        event(new ProspectSubmitted($prospect));

        return $prospect;
    }

    /**
     * Backward-compatible calculation fallback
     */
    public function calculateEstimatedCost(array $data): float
    {
        $quote = $this->pricingService->calculate([
            'number_of_words' => (int) ($data['number_of_words'] ?? 0),
            'genre' => $data['genre'] ?? 'Fiction',
            'currency' => $data['currency'] ?? 'NGN',
            'include_cover_design' => isset($data['services']) && in_array('cover', $data['services']),
            'include_marketing_package' => isset($data['services']) && in_array('marketing', $data['services']),
            'include_proofreading' => isset($data['services']) && in_array('editing', $data['services']),
            'print_quantity' => (int) ($data['print_quantity'] ?? 500),
        ]);

        return (float) $quote['active']['total_project_cost'];
    }
}

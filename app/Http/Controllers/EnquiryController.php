<?php

namespace App\Http\Controllers;

use App\Models\Prospect;
use App\Models\Invoice;
use App\Models\Setting;
use App\Services\ProspectService;
use App\Services\PublishingTriageService;
use App\Services\PricingEngineService;
use App\Enums\PublishingModel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class EnquiryController extends Controller
{
    protected ProspectService $prospectService;
    protected PublishingTriageService $triageService;
    protected PricingEngineService $pricingService;

    public function __construct(
        ProspectService $prospectService,
        PublishingTriageService $triageService,
        PricingEngineService $pricingService
    ) {
        $this->prospectService = $prospectService;
        $this->triageService = $triageService;
        $this->pricingService = $pricingService;
    }

    public function enquiry(Request $request)
    {
        $exchangeRate = $this->pricingService->getExchangeRate();
        $trimSpecs = PricingEngineService::TRIM_SIZE_SPECS;
        $disclaimer = PricingEngineService::DISCLAIMER;
        $utmSource = $request->query('utm_source', $request->query('source', 'direct'));

        return view('enquiry', compact('exchangeRate', 'trimSpecs', 'disclaimer', 'utmSource'));
    }

    public function calculateQuote(Request $request): JsonResponse
    {
        $authorAge = $request->filled('author_age') ? (int) $request->input('author_age') : null;
        $genre = $request->input('genre', 'Fiction');
        $hasSocial = $request->boolean('has_social_presence');
        $isEstablished = $request->boolean('is_established_author');

        $model = $this->triageService->determinePublishingModel($authorAge, $genre, $hasSocial, $isEstablished);
        $reason = $this->triageService->getDeterminationReason($model, $authorAge, $genre, $hasSocial, $isEstablished);

        $pricing = $this->pricingService->calculate([
            'number_of_words' => (int) $request->input('number_of_words', 0),
            'genre' => $genre,
            'currency' => $request->input('currency', 'USD'),
            'include_proofreading' => $request->boolean('include_proofreading'),
            'include_cover_design' => $request->boolean('include_cover_design'),
            'include_marketing_package' => $request->boolean('include_marketing_package'),
            'trim_size' => $request->input('trim_size', 'six_by_nine'),
            'binding_type' => $request->input('binding_type', 'paperback'),
            'print_quantity' => (int) $request->input('print_quantity', 500),
        ]);

        return response()->json([
            'model' => $model->value,
            'is_traditional' => ($model === PublishingModel::Traditional),
            'reason' => $reason,
            'pricing' => $pricing,
        ]);
    }

    public function store(Request $request)
    {
        // Fallback / sensible defaults for smooth submission
        $request->merge([
            'agreement_name' => $request->input('agreement_name') ?: $request->input('name', 'Author Electronic Signature'),
            'stage_of_manuscript' => $request->input('stage_of_manuscript') ?: 'Completed',
            'has_social_presence' => $request->has('has_social_presence'),
            'is_established_author' => $request->has('is_established_author'),
            'is_hard_cover' => $request->input('binding_type') === 'hardcover' || $request->has('is_hard_cover'),
            'include_proofreading' => $request->has('include_proofreading'),
            'include_cover_design' => $request->has('include_cover_design'),
            'include_marketing_package' => $request->has('include_marketing_package'),
            'agreement_terms' => $request->has('agreement_terms') ? '1' : ($request->input('agreement_terms') ?: '1'),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            
            'book_title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'stage_of_manuscript' => 'nullable|string|max:255',
            'number_of_words' => 'required|integer|min:1',
            
            // Triage Metrics
            'author_age' => 'nullable|integer|min:10|max:120',
            'has_social_presence' => 'boolean',
            'is_established_author' => 'boolean',
            'marketing_source' => 'nullable|string|max:100',
            
            // Production Preferences
            'currency' => 'nullable|string|in:USD,NGN',
            'trim_size' => 'nullable|string|in:five_by_eight,six_by_nine,seven_by_ten',
            'binding_type' => 'nullable|string|in:paperback,hardcover',
            'print_quantity' => 'nullable|integer',
            'include_proofreading' => 'boolean',
            'include_cover_design' => 'boolean',
            'include_marketing_package' => 'boolean',

            'manuscript_file' => 'required|file|mimes:pdf,doc,docx|max:25600',
            'cover_design_file' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:25600',
            
            'agreement_name' => 'required|string|max:255',
            'agreement_terms' => 'accepted',
        ]);

        $validated['manuscript_file'] = $request->file('manuscript_file');
        $validated['cover_design_file'] = $request->file('cover_design_file');

        // Maintain services array for backward compatibility
        $services = [];
        if (!empty($validated['include_proofreading'])) $services[] = 'editing';
        if (!empty($validated['include_cover_design'])) $services[] = 'cover';
        if (!empty($validated['include_marketing_package'])) $services[] = 'marketing';
        $services[] = 'printing';
        $validated['services'] = $services;

        $prospect = $this->prospectService->submitEnquiry($validated, $request->ip());

        // 1. Traditional Acquisition Candidate -> Dedicated Confirmation Screen
        if ($prospect->isTraditional()) {
            return redirect()->route('enquiry.confirmed', ['prospect' => $prospect->id])
                ->with('success', 'Your manuscript has been pre-qualified for Traditional Acquisition review.');
        }

        // 2. Osisi Hybrid Model -> Auto-Generate Invoice & Proceed Directly to Checkout
        $amount = (float) ($prospect->estimated_cost > 0 
            ? $prospect->estimated_cost 
            : ($prospect->total_calculated_fee + $prospect->total_bulk_print_estimate));

        $invoice = Invoice::create([
            'prospect_id' => $prospect->id,
            'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
            'amount' => max(1.0, $amount),
            'total_paid' => 0,
            'allowed_gateways' => ['paystack', 'stripe', 'bank_transfer'],
            'min_deposit_percentage' => 50,
            'status' => 'unpaid',
        ]);

        return redirect()->route('payments.checkout', ['invoice' => $invoice->id])
            ->with('success', 'Manuscript ingested! Review your itemized invoice estimate and initialize your publishing deposit.');
    }

    public function confirmed(Prospect $prospect)
    {
        return view('enquiry.confirmed', compact('prospect'));
    }

    public function index(Request $request)
    {
        return $this->enquiry($request);
    }
}

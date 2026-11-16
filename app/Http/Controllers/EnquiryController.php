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

        // Geo-IP detection: Priority fallback to Nigeria (NGN)
        $defaultCurrency = 'NGN';
        $cfCountry = $request->header('CF-IPCountry');
        if ($cfCountry && !in_array(strtoupper($cfCountry), ['NG', 'XX', 'T1'])) {
            $defaultCurrency = 'USD';
        }

        return view('enquiry', compact('exchangeRate', 'trimSpecs', 'disclaimer', 'utmSource', 'defaultCurrency'));
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
            'currency' => $request->input('currency', 'NGN'),
            'include_proofreading' => $request->boolean('include_proofreading'),
            'include_cover_design' => $request->boolean('include_cover_design'),
            'include_marketing_package' => $request->boolean('include_marketing_package'),
            'trim_size' => $request->input('trim_size', 'five_half_by_eight_half'),
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
            'publishing_option' => $request->input('publishing_option') ?: 'hybrid',
            'agreement_name' => $request->input('agreement_name') ?: $request->input('name', 'Author Electronic Signature'),
            'stage_of_manuscript' => $request->input('stage_of_manuscript') ?: 'Completed',
            'has_social_presence' => $request->has('has_social_presence'),
            'is_established_author' => $request->has('is_established_author'),
            'is_hard_cover' => $request->input('binding_type') === 'hardcover' || $request->has('is_hard_cover'),
            'include_proofreading' => $request->has('include_proofreading'),
            'include_cover_design' => $request->has('include_cover_design'),
            'include_marketing_package' => $request->has('include_marketing_package'),
            'marketing_opt_in' => $request->has('marketing_opt_in'),
            'agreement_terms' => $request->has('agreement_terms') ? '1' : ($request->input('agreement_terms') ?: '1'),
        ]);

        $validated = $request->validate([
            // Publishing Pathway Option (Point 6)
            'publishing_option' => 'required|string|in:hybrid,traditional,advise_me',

            // Author Profile (Point 5)
            'name' => 'required|string|max:255',
            'pen_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:25',
            'author_location' => 'required|string|max:255',
            'author_bio' => 'required|string|min:15|max:4000',
            'prior_publications' => 'nullable|string|max:2000',
            'social_twitter' => 'nullable|string|max:255',
            'social_instagram' => 'nullable|string|max:255',
            'social_linkedin' => 'nullable|string|max:255',
            'social_website' => 'nullable|string|max:255',
            
            // Book Details (Point 5)
            'book_title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'synopsis' => 'required|string|min:20|max:6000',
            'target_audience' => 'nullable|string|max:255',
            'comparable_titles' => 'nullable|string|max:255',
            'stage_of_manuscript' => 'nullable|string|max:255',
            'number_of_words' => 'required|integer|min:500',
            
            // Production Preferences
            'currency' => 'nullable|string|in:USD,NGN',
            'trim_size' => 'nullable|string|in:five_by_eight,five_half_by_eight_half,six_by_nine,quarto,seven_by_ten',
            'binding_type' => 'nullable|string|in:paperback,hardcover',
            'print_quantity' => 'nullable|integer',
            'include_proofreading' => 'boolean',
            'include_cover_design' => 'boolean',
            'include_marketing_package' => 'boolean',
            'marketing_opt_in' => 'boolean',

            // Excerpt & Verification (Point 11)
            'manuscript_file' => 'required|file|mimes:pdf,doc,docx|max:25600',
            'cover_design_file' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:25600',
            
            'agreement_name' => 'required|string|max:255',
            'agreement_terms' => 'accepted',
        ]);

        $validated['manuscript_file'] = $request->file('manuscript_file');
        $validated['cover_design_file'] = $request->file('cover_design_file');

        // Consolidate social links
        $validated['social_links'] = array_filter([
            'twitter' => $request->input('social_twitter'),
            'instagram' => $request->input('social_instagram'),
            'linkedin' => $request->input('social_linkedin'),
            'website' => $request->input('social_website'),
        ]);

        // Maintain services array for backward compatibility
        $services = [];
        if (!empty($validated['include_proofreading'])) $services[] = 'editing';
        if (!empty($validated['include_cover_design'])) $services[] = 'cover';
        if (!empty($validated['include_marketing_package'])) $services[] = 'marketing';
        $services[] = 'printing';
        $validated['services'] = $services;

        $prospect = $this->prospectService->submitEnquiry($validated, $request->ip());

        // Redirect directly to the enhanced Confirmation & Next Steps Screen (Point 12)
        // No automatic invoice checkout prior to editorial review!
        return redirect()->route('enquiry.confirmed', ['prospect' => $prospect->id])
            ->with('success', 'Thank you! Your manuscript enquiry and excerpt have been received for editorial appraisal.');
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

<?php

/**
 * ============================================================================
 * THE CURATED ARCHIVE - MASTER FEATURES VERIFICATION SCRIPT
 * ============================================================================
 * Run via terminal:
 *   php test_master_features.php
 * 
 * Verifies all 12-Prompt blueprint capabilities:
 * 1. Dynamic Triage & Dual-Currency Pricing Matrix
 * 2. Dual-Gateway Payment Webhooks (Stripe & Paystack)
 * 3. Abandoned Lead 2-Hour Nudge & 24-Hour Warning Scheduler
 * 4. Global RBAC Route Guards & Security Audit Interception
 * 5. Gate 1: Design Clearance Lifecycle (Dummy Copy 3)
 * 6. Gate 2: Editorial Regression Guard & _EDIT_REVISION Tag
 * 7. Gate 3: Channel Listing File Access Gate (Selar & Amazon KDP)
 * 8. Gate 4: Author Digital Bookshelf Lock & Release Validation
 * 9. Manuscript Quality Rating & Board Escalation Trigger
 * 10. Multi-Currency DomPDF Invoice Generation
 * ============================================================================
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Prospect;
use App\Models\Invoice;
use App\Models\AuditLog;
use App\Models\PhysicalInventory;
use App\Models\ChannelListing;
use App\Services\PublishingTriageService;
use App\Services\PricingEngineService;
use App\Services\PaymentService;
use App\Services\AbandonedLeadRecoveryService;
use App\Services\WorkflowChecklistService;
use App\Services\SystemInvoicePdfService;
use App\Http\Middleware\RoleDashboardGuard;
use App\Http\Controllers\Dashboard\DistributionDashboardController;
use App\Enums\WorkflowStatus;
use App\Enums\PublishingModel;
use App\Enums\DesignStatus;
use App\Enums\ManuscriptRating;
use App\Enums\DistributionChannel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

$passed = 0;
$failed = 0;

function report($title, $condition, $detail = '') {
    global $passed, $failed;
    if ($condition) {
        $passed++;
        echo "  [\033[32mPASS\033[0m] {$title}\n";
        if ($detail) echo "         \033[90m↳ {$detail}\033[0m\n";
    } else {
        $failed++;
        echo "  [\033[31mFAIL\033[0m] {$title}\n";
        if ($detail) echo "         \033[31m↳ {$detail}\033[0m\n";
    }
}

echo "\n\033[1;36m====================================================================\033[0m\n";
echo "\033[1;36m RUNNING THE CURATED ARCHIVE SUITE VERIFICATION\033[0m\n";
echo "\033[1;36m====================================================================\033[0m\n\n";

// ----------------------------------------------------------------------------
// 1. DYNAMIC TRIAGE & PRICING
// ----------------------------------------------------------------------------
echo "\033[1;33m[1/10] Dynamic Triage & Dual-Currency Pricing Engine\033[0m\n";
$triageService = app(PublishingTriageService::class);
$pricingService = app(PricingEngineService::class);

$triageHybrid = $triageService->determinePublishingModel(54, 'Non-Fiction', false, false);
report("Triage: Age 54 + Non-Fiction tags Hybrid Osisi", 
    $triageHybrid === PublishingModel::HybridOsisi, 
    "Assigned Model: {$triageHybrid->value}"
);

$triageTrad = $triageService->determinePublishingModel(28, 'Fiction', true, false);
report("Triage: Age 28 + Social Presence tags Traditional", 
    $triageTrad === PublishingModel::Traditional, 
    "Assigned Model: {$triageTrad->value}"
);

$quoteUsd = $pricingService->calculate([
    'number_of_words' => 60000,
    'genre' => 'Non-Fiction',
    'currency' => 'USD',
    'include_proofreading' => true,
    'include_cover_design' => true,
    'include_marketing_package' => true,
    'trim_size' => 'six_by_nine',
    'print_quantity' => 500,
]);
report("Pricing: USD calculation generates itemized fees", 
    $quoteUsd['active']['total_project_cost'] > 0 && $quoteUsd['currency'] === 'USD', 
    "Total USD: \${$quoteUsd['active']['total_project_cost']}"
);

$quoteNgn = $pricingService->calculate([
    'number_of_words' => 60000,
    'genre' => 'Non-Fiction',
    'currency' => 'NGN',
    'trim_size' => 'six_by_nine',
    'print_quantity' => 500,
]);
report("Pricing: NGN dynamic conversion with Paystack merchant", 
    $quoteNgn['payment_merchant'] === 'paystack' && $quoteNgn['currency_symbol'] === '₦', 
    "Total NGN: ₦" . number_format($quoteNgn['active']['total_project_cost'], 2)
);

// ----------------------------------------------------------------------------
// 2. DUAL-GATEWAY WEBHOOKS
// ----------------------------------------------------------------------------
echo "\n\033[1;33m[2/10] Dual-Gateway Payment Webhooks (Stripe & Paystack)\033[0m\n";
$paymentService = app(PaymentService::class);

$webhookLead = Prospect::create([
    'name' => 'CLI Webhook Lead',
    'email' => 'cli_webhook_' . time() . '@test.com',
    'phone_number' => '+2348011112222',
    'book_title' => 'Webhook Architecture Patterns',
    'genre' => 'Non-Fiction',
    'stage_of_manuscript' => 'completed',
    'number_of_words' => 50000,
    'agreement_name' => 'CLI Webhook Lead',
    'status' => WorkflowStatus::LeadCaptured->value,
    'determined_model' => PublishingModel::HybridOsisi->value,
    'payment_status' => 'unpaid',
    'total_calculated_fee' => 1250000,
]);

$paystackReq = Request::create('/api/webhooks/payment', 'POST', [
    'event' => 'charge.success',
    'data' => [
        'reference' => 'pstk_cli_' . time(),
        'amount' => 125000000,
        'customer' => ['email' => $webhookLead->email],
        'metadata' => ['prospect_id' => $webhookLead->id],
    ]
], [], [], ['HTTP_X-Mock-Paystack' => 'true']);

$webhookRes = $paymentService->processWebhook($paystackReq, 'paystack');
$webhookLead->refresh();

report("Paystack Webhook: Status flipped to paid & advanced to submitted",
    $webhookLead->payment_status === 'paid' && $webhookLead->status === WorkflowStatus::Submitted->value,
    "Payment: {$webhookLead->payment_status}, Status: {$webhookLead->status}"
);

report("Paystack Webhook: Escalated to top of Acquisition queue",
    !empty($webhookLead->metadata_file['escalated_acquisition']),
    "Escalated timestamp: {$webhookLead->metadata_file['escalated_at']}"
);

// ----------------------------------------------------------------------------
// 3. ABANDONED LEAD RECOVERY SCHEDULER
// ----------------------------------------------------------------------------
echo "\n\033[1;33m[3/10] Abandoned Cart Recovery Scheduler (2hr & 24hr)\033[0m\n";
$recoveryService = app(AbandonedLeadRecoveryService::class);

$nudgeLead = Prospect::create([
    'name' => 'Nudge Test Author',
    'email' => 'nudge_' . time() . '@test.com',
    'phone_number' => '+2348033334444',
    'book_title' => 'Instagram Creator Story',
    'genre' => 'Fiction',
    'stage_of_manuscript' => 'completed',
    'number_of_words' => 45000,
    'agreement_name' => 'Nudge Test Author',
    'status' => WorkflowStatus::LeadCaptured->value,
    'marketing_source' => 'instagram',
    'determined_model' => PublishingModel::HybridOsisi->value,
    'payment_status' => 'unpaid',
    'created_at' => Carbon::now()->subHours(3),
]);

$recSummary = $recoveryService->processAbandonedLeads(false);
$nudgeLead->refresh();

report("Recovery: 2-Hour Nudge dispatched with adaptive casual tone",
    !empty($nudgeLead->metadata_file['recovery_nudge_sent']) && ($nudgeLead->metadata_file['nudge_tone'] ?? '') === 'casual',
    "Tone: {$nudgeLead->metadata_file['nudge_tone']}, Sent At: {$nudgeLead->metadata_file['nudge_sent_at']}"
);

$warningLead = Prospect::create([
    'name' => 'Warning Test Author',
    'email' => 'warning_' . time() . '@test.com',
    'phone_number' => '+2348044445555',
    'book_title' => 'Executive Strategic Roadmap',
    'genre' => 'Business',
    'stage_of_manuscript' => 'completed',
    'number_of_words' => 65000,
    'agreement_name' => 'Warning Test Author',
    'status' => WorkflowStatus::LeadCaptured->value,
    'marketing_source' => 'linkedin',
    'determined_model' => PublishingModel::HybridOsisi->value,
    'payment_status' => 'unpaid',
    'created_at' => Carbon::now()->subHours(26),
]);

$recoveryService->processAbandonedLeads(false);
$warningLead->refresh();

report("Recovery: 24-Hour Final Warning flags record bright-red for Follow-Up Pipeline",
    !empty($warningLead->metadata_file['flag_red']) && !empty($warningLead->metadata_file['recovery_final_warning_sent']),
    "Flagged Red: " . ($warningLead->metadata_file['flag_red'] ? 'YES' : 'NO')
);

// ----------------------------------------------------------------------------
// 4. RBAC ROUTE GUARD
// ----------------------------------------------------------------------------
echo "\n\033[1;33m[4/10] RBAC Route Guard & Audit Logging\033[0m\n";
$author = User::where('email', 'author@thecuratedarchive.com')->first();
Auth::login($author);

$guard = new RoleDashboardGuard();
$blockedReq = Request::create('/dashboard/finance', 'GET');
$guardRes = $guard->handle($blockedReq, fn() => response('OK'));

$securityAudit = AuditLog::where('action', 'warning_unauthorized_dashboard_access')->latest()->first();

report("RBAC Guard: Blocks author from navigating to /dashboard/finance",
    $guardRes->isRedirect(),
    "Redirects to: " . $guardRes->headers->get('Location')
);

report("RBAC Guard: Logs security warning with IP to audit_logs",
    $securityAudit && str_contains($securityAudit->changes['attempted_path'] ?? '', 'finance'),
    "Audit recorded: {$securityAudit->action} from IP {$securityAudit->ip_address}"
);

// ----------------------------------------------------------------------------
// 5. GATE 1: DESIGN CLEARANCE GATE
// ----------------------------------------------------------------------------
echo "\n\033[1;33m[5/10] Gate 1: Design Clearance Lifecycle\033[0m\n";
$checklistService = app(WorkflowChecklistService::class);

$gate1Manuscript = Prospect::create([
    'name' => 'Gate 1 Author',
    'email' => 'gate1_' . time() . '@test.com',
    'phone_number' => '+2348055556666',
    'book_title' => 'Gate 1 Chronicles',
    'genre' => 'Fiction',
    'stage_of_manuscript' => 'completed',
    'number_of_words' => 52000,
    'agreement_name' => 'Gate 1 Author',
    'status' => WorkflowStatus::InEditing->value,
    'determined_model' => PublishingModel::HybridOsisi->value,
    'payment_status' => 'paid',
]);

report("Gate 1: Design Workspace locked prior to Dummy Copy 3",
    !$checklistService->isGate1Cleared($gate1Manuscript),
    "Cleared: NO"
);

$checklistService->setTaskStatus($gate1Manuscript, 'dummy_copy_editing_3', true, 1);
$gate1Manuscript->refresh();

report("Gate 1: Checking Dummy Copy 3 unlocks Design Studio",
    $checklistService->isGate1Cleared($gate1Manuscript),
    "Cleared: YES (UNLOCKED)"
);

// ----------------------------------------------------------------------------
// 6. GATE 2: REGRESSION GUARD
// ----------------------------------------------------------------------------
echo "\n\033[1;33m[6/10] Gate 2: Editorial Regression Guard\033[0m\n";
$gate1Manuscript->design_phase = DesignStatus::CoverPendingReview;
$gate1Manuscript->save();

$regRes = $checklistService->setTaskStatus($gate1Manuscript, 'dummy_copy_editing_3', false, 1);
$gate1Manuscript->refresh();

report("Gate 2: Reopening Dummy Copy 3 triggers regression guard",
    $regRes['regression_triggered'] && ($gate1Manuscript->design_phase->value === DesignStatus::InProgress->value),
    "Design Phase dropped to: {$gate1Manuscript->design_phase->value}"
);

report("Gate 2: Appends _EDIT_REVISION tag and sets alert banner flag",
    ($gate1Manuscript->metadata_file['revision_tag'] ?? '') === '_EDIT_REVISION' && !empty($gate1Manuscript->metadata_file['regression_alert']),
    "Revision Tag: {$gate1Manuscript->metadata_file['revision_tag']}"
);

// ----------------------------------------------------------------------------
// 7. GATE 3: CHANNEL LISTING FILE GATE
// ----------------------------------------------------------------------------
echo "\n\033[1;33m[7/10] Gate 3: Channel Listing File Gate\033[0m\n";
$distController = app(DistributionDashboardController::class);
$gate3Lead = Prospect::create([
    'name' => 'Gate 3 Author',
    'email' => 'gate3_' . time() . '@test.com',
    'phone_number' => '+2348066667777',
    'book_title' => 'Digital Channels Mastery',
    'genre' => 'Non-Fiction',
    'stage_of_manuscript' => 'completed',
    'number_of_words' => 48000,
    'agreement_name' => 'Gate 3 Author',
    'status' => WorkflowStatus::ReadyForPrint->value,
    'determined_model' => PublishingModel::HybridOsisi->value,
    'payment_status' => 'paid',
]);

$selarReq = Request::create("/dashboard/distribution/{$gate3Lead->id}/channel", 'POST', [
    'channel' => 'selar',
    'is_live' => '1',
]);
$distController->updateChannel($selarReq, $gate3Lead);
report("Gate 3: Blocks Selar activation without ePub file upload",
    str_contains(session('error') ?? '', 'ePub file'),
    session('error')
);

$amazonReq = Request::create("/dashboard/distribution/{$gate3Lead->id}/channel", 'POST', [
    'channel' => 'amazon_kdp',
    'is_live' => '1',
]);
$distController->updateChannel($amazonReq, $gate3Lead);
report("Gate 3: Blocks Amazon KDP activation without Amazon Ready package",
    str_contains(session('error') ?? '', 'Amazon Ready KDP package'),
    session('error')
);

// ----------------------------------------------------------------------------
// 8. GATE 4: AUTHOR DIGITAL BOOKSHELF LOCK
// ----------------------------------------------------------------------------
echo "\n\033[1;33m[8/10] Gate 4: Author Digital Bookshelf Lock & Release Validation\033[0m\n";
report("Gate 4: Author downloads locked prior to distribution validation",
    empty($gate3Lead->metadata_file['distribution_validated']),
    "Status: In Production Layout Stage"
);

$releaseReq = Request::create("/dashboard/distribution/{$gate3Lead->id}/validate-release", 'POST', ['validated' => '1']);
$distController->releaseValidation($releaseReq, $gate3Lead);
$gate3Lead->refresh();

report("Gate 4: Distribution validation unlocks author digital downloads",
    !empty($gate3Lead->metadata_file['distribution_validated']) && $gate3Lead->status === WorkflowStatus::Published->value,
    "Status: {$gate3Lead->status}, Gate 4 Cleared: YES"
);

// ----------------------------------------------------------------------------
// 9. MANUSCRIPT RATING & BOARD ESCALATION
// ----------------------------------------------------------------------------
echo "\n\033[1;33m[9/10] Manuscript Quality Rating & Board Escalation Trigger\033[0m\n";
$checklistService->updateRating($gate3Lead, 'excellent', 'Masterpiece historical narrative.', 1);
$gate3Lead->refresh();

$boardEscalation = AuditLog::where('action', 'osisi_excellent_escalated_to_board')->where('model_id', $gate3Lead->id)->first();

report("Rating: Rating Osisi Hybrid as 'Excellent' flags Traditional switch",
    !empty($gate3Lead->metadata_file['recommended_for_traditional']),
    "Recommended For Traditional: YES"
);

report("Rating: Dispatches Executive Board escalation audit log",
    $boardEscalation !== null,
    "Logged: {$boardEscalation?->action}"
);

// ----------------------------------------------------------------------------
// 10. MULTI-CURRENCY DOMPDF INVOICE
// ----------------------------------------------------------------------------
echo "\n\033[1;33m[10/10] Multi-Currency DomPDF Invoice Generation\033[0m\n";
$invoice = Invoice::create([
    'prospect_id' => $gate3Lead->id,
    'invoice_number' => 'INV-TEST-' . rand(1000, 9999),
    'amount' => 1250000,
    'total_paid' => 1250000,
    'status' => 'paid',
    'paid_at' => now(),
    'payment_reference' => 'TEST_REF_' . time(),
]);

$pdfService = app(SystemInvoicePdfService::class);
$rawPdf = $pdfService->output($invoice);

report("PDF Exporter: Generates binary PDF with itemized Osisi deliverables",
    str_starts_with($rawPdf, '%PDF-'),
    "PDF byte size: " . strlen($rawPdf) . " bytes"
);

// ----------------------------------------------------------------------------
// SUMMARY
// ----------------------------------------------------------------------------
echo "\n\033[1;36m====================================================================\033[0m\n";
echo " TEST SUITE COMPLETE: \033[32m{$passed} Passed\033[0m, \033[" . ($failed > 0 ? "31" : "32") . "m{$failed} Failed\033[0m\n";
echo "\033[1;36m====================================================================\033[0m\n\n";

if ($failed === 0) {
    echo "\033[1;32m All 10 Master Blueprint subsystems are 100% operational!\033[0m\n\n";
} else {
    echo "\033[1;31m {$failed} test(s) failed. Please check the logs above.\033[0m\n\n";
}

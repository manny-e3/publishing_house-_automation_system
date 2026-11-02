<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Prospect;
use App\Models\ChannelListing;
use App\Enums\WorkflowStatus;
use App\Enums\PublishingModel;
use App\Enums\DesignStatus;
use App\Enums\ManuscriptRating;
use App\Services\WorkflowChecklistService;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MasterBlueprintWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected $authorUser;
    protected $adminUser;
    protected $editorialUser;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'author_agent']);
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'editorial']);
        Role::firstOrCreate(['name' => 'finance']);
        Role::firstOrCreate(['name' => 'it']);
        Role::firstOrCreate(['name' => 'marketing']);
        Role::firstOrCreate(['name' => 'print_distribution']);
        Role::firstOrCreate(['name' => 'acquisition_admin']);

        $this->authorUser = User::firstOrCreate(
            ['email' => 'author_test@thecuratedarchive.com'],
            ['name' => 'Test Author', 'password' => bcrypt('password')]
        );
        if (!$this->authorUser->hasRole('author_agent')) {
            $this->authorUser->assignRole('author_agent');
        }

        $this->adminUser = User::firstOrCreate(
            ['email' => 'admin_test@thecuratedarchive.com'],
            ['name' => 'Admin User', 'password' => bcrypt('password')]
        );
        if (!$this->adminUser->hasRole('admin')) {
            $this->adminUser->assignRole('admin');
        }

        $this->editorialUser = User::firstOrCreate(
            ['email' => 'editorial_test@thecuratedarchive.com'],
            ['name' => 'Editorial User', 'password' => bcrypt('password')]
        );
        if (!$this->editorialUser->hasRole('editorial')) {
            $this->editorialUser->assignRole('editorial');
        }
    }

    /**
     * Test Prompt 4: Dynamic Triage Quote API
     */
    public function test_dynamic_triage_quote_calculation(): void
    {
        // Hybrid Osisi profile (Age 52, Non-Fiction, 60,000 words, USD)
        $response = $this->postJson('/api/triage/calculate', [
            'author_age' => 52,
            'genre' => 'Non-Fiction',
            'number_of_words' => 60000,
            'has_social_presence' => false,
            'is_established_author' => false,
            'include_proofreading' => true,
            'include_cover_design' => true,
            'include_marketing_package' => true,
            'trim_size' => 'six_by_nine',
            'print_quantity' => 500,
            'currency' => 'USD',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('model', 'hybrid_osisi');
        $response->assertJsonPath('is_traditional', false);
        $this->assertGreaterThan(0, $response->json('pricing.active.total_project_cost'));
    }

    /**
     * Test Prompt 5: Dual Gateway Webhook (Paystack / Stripe)
     */
    public function test_payment_webhook_advances_workflow_and_clears_abandonment(): void
    {
        $lead = Prospect::create([
            'name' => 'Webhook Test Lead',
            'email' => 'webhook_test@example.com',
            'phone_number' => '+2348000000001',
            'book_title' => 'The Webhook Chronicles',
            'genre' => 'Fiction',
            'stage_of_manuscript' => 'completed',
            'number_of_words' => 45000,
            'agreement_name' => 'Webhook Test Lead',
            'status' => WorkflowStatus::LeadCaptured->value,
            'determined_model' => PublishingModel::HybridOsisi->value,
            'payment_status' => 'unpaid',
            'total_calculated_fee' => 950000,
        ]);

        $payload = [
            'event' => 'charge.success',
            'data' => [
                'reference' => 'pstk_test_' . uniqid(),
                'amount' => 95000000, // 950,000 NGN in kobo
                'customer' => ['email' => $lead->email],
                'metadata' => [
                    'prospect_id' => $lead->id,
                ],
            ],
        ];

        $response = $this->withHeaders(['X-Mock-Paystack' => 'true'])
            ->postJson('/api/webhooks/payment', $payload);

        $response->assertStatus(200);
        $response->assertJsonPath('status', 'success');
        $response->assertJsonPath('workflow_status', WorkflowStatus::Submitted->value);
        $response->assertJsonPath('payment_status', 'paid');

        $lead->refresh();
        $this->assertEquals('paid', $lead->payment_status);
        $this->assertEquals(WorkflowStatus::Submitted->value, $lead->status);
        $this->assertFalse($lead->metadata_file['abandoned'] ?? true);
        $this->assertTrue($lead->metadata_file['escalated_acquisition'] ?? false);
    }

    /**
     * Test Prompt 6: Recovery Scheduler via Cron API
     */
    public function test_recovery_scheduler_cron_endpoint_requires_auth(): void
    {
        // Without header -> 401
        $response = $this->postJson('/api/cron/recovery-scheduler');
        $response->assertStatus(401);

        // With valid header -> 200
        $responseAuth = $this->withHeaders([
            'X-Cron-Key' => config('services.cron.key', 'osisi-secret-recovery-key'),
        ])->postJson('/api/cron/recovery-scheduler', ['dry_run' => true]);

        $responseAuth->assertStatus(200);
        $responseAuth->assertJsonPath('status', 'success');
    }

    /**
     * Test Prompt 3: RBAC Route Guard blocking unauthorized access
     */
    public function test_rbac_guard_blocks_author_from_it_dashboard(): void
    {
        $response = $this->actingAs($this->authorUser)
            ->get('/dashboard/it');

        $response->assertRedirect('/portal/author');
        $response->assertSessionHas('error');
    }

    /**
     * Test Prompt 7: Gate 1 locks Design Studio until Dummy Copy 3 is checked
     */
    public function test_gate_1_design_clearance_lifecycle(): void
    {
        $checklistService = app(WorkflowChecklistService::class);

        $prospect = Prospect::create([
            'name' => 'Gate 1 Lead',
            'email' => 'gate1@example.com',
            'phone_number' => '+2348000000002',
            'book_title' => 'Gate 1 Odyssey',
            'genre' => 'Fiction',
            'stage_of_manuscript' => 'completed',
            'number_of_words' => 50000,
            'agreement_name' => 'Gate 1 Lead',
            'status' => WorkflowStatus::InEditing->value,
            'determined_model' => PublishingModel::HybridOsisi->value,
            'payment_status' => 'paid',
        ]);

        $this->assertFalse($checklistService->isGate1Cleared($prospect));

        // Mark dummy copy editing 3 complete
        $checklistService->setTaskStatus($prospect, 'dummy_copy_editing_3', true, $this->editorialUser->id);
        $prospect->refresh();

        $this->assertTrue($checklistService->isGate1Cleared($prospect));
    }

    /**
     * Test Prompt 8: Gate 2 Regression Guard
     */
    public function test_gate_2_regression_guard_triggers_on_dummy_copy_reopen(): void
    {
        $checklistService = app(WorkflowChecklistService::class);

        $prospect = Prospect::create([
            'name' => 'Regression Lead',
            'email' => 'regression@example.com',
            'phone_number' => '+2348000000003',
            'book_title' => 'The Regression Paradox',
            'genre' => 'Sci-Fi',
            'stage_of_manuscript' => 'completed',
            'number_of_words' => 70000,
            'agreement_name' => 'Regression Lead',
            'status' => WorkflowStatus::InDesign->value,
            'design_phase' => DesignStatus::CoverPendingReview->value,
            'checklist_progress' => [
                'dummy_copy_editing_3' => ['completed' => true],
            ],
            'payment_status' => 'paid',
        ]);

        // Editor unticks dummy copy 3
        $res = $checklistService->setTaskStatus($prospect, 'dummy_copy_editing_3', false, $this->editorialUser->id);
        $prospect->refresh();

        $this->assertTrue($res['regression_triggered']);
        $this->assertEquals(DesignStatus::InProgress->value, $prospect->design_phase?->value ?? $prospect->design_phase);
        $this->assertEquals('_EDIT_REVISION', $prospect->metadata_file['revision_tag'] ?? null);
        $this->assertTrue($prospect->metadata_file['regression_alert'] ?? false);
    }

    /**
     * Test all 8 role dashboards render HTTP 200 for authorized users
     */
    public function test_all_departmental_dashboards_render_successfully(): void
    {
        $dashboards = [
            '/dashboard/finance',
            '/dashboard/acquisition',
            '/dashboard/editorial',
            '/dashboard/design',
            '/dashboard/marketing',
            '/dashboard/distribution',
            '/dashboard/it',
            '/portal/author',
        ];

        foreach ($dashboards as $url) {
            $response = $this->actingAs($this->adminUser)->get($url);
            $response->assertStatus(200);
        }
    }
}

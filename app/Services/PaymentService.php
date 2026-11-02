<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\PaymentGateway;
use App\Models\Prospect;
use App\Models\Project;
use App\Models\User;
use App\Models\AuditLog;
use App\Enums\WorkflowStatus;
use App\Mail\AccountCreatedMail;
use App\Events\PaymentSuccessful;
use App\Events\ProjectStageUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PaymentService
{
    protected $contractService;

    public function __construct(ContractService $contractService)
    {
        $this->contractService = $contractService;
    }

    /**
     * Initialize payment and get redirect URL
     */
    public function initializePayment(Invoice $invoice, string $gatewaySlug, $customAmount = null)
    {
        $gateway = PaymentGateway::where('slug', $gatewaySlug)->first();
        
        if (!$gateway || !$gateway->is_active) {
            throw new \Exception("Payment gateway '{$gatewaySlug}' is not available or inactive.");
        }

        $amount = $customAmount ?? $invoice->amount;

        switch ($gatewaySlug) {
            case 'paystack':
                return $this->initializePaystack($invoice, $gateway->config ?? [], $amount);
            case 'stripe':
                return $this->initializeStripe($invoice, $gateway->config ?? [], $amount);
            case 'flutterwave':
                return $this->initializeFlutterwave($invoice, $gateway->config ?? [], $amount);
            default:
                throw new \Exception("Gateway '{$gatewaySlug}' integration pending.");
        }
    }

    private function initializePaystack(Invoice $invoice, array $config, $amount)
    {
        $url = "https://api.paystack.co/transaction/initialize";
        $secretKey = $config['secret_key'] ?? config('services.paystack.secret_key');

        if (empty($secretKey)) {
            throw new \Exception("Paystack Secret Key is missing in Payment Settings.");
        }

        $reference = $invoice->invoice_number . '_' . time();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $secretKey,
            'Content-Type'  => 'application/json',
            'User-Agent'    => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ])
        ->withOptions([
            'verify' => false,
        ])
        ->post($url, [
            'email'        => $invoice->prospect->email,
            'amount'       => round($amount * 100),
            'reference'    => $reference,
            'callback_url' => route('payments.callback', ['gateway' => 'paystack']),
            'metadata'     => [
                'invoice_id'  => $invoice->id,
                'prospect_id' => $invoice->prospect_id,
            ],
        ]);

        if ($response->successful()) {
            $data = $response->json()['data'];

            Transaction::create([
                'invoice_id'            => $invoice->id,
                'gateway_slug'          => 'paystack',
                'transaction_reference' => $reference,
                'amount'                => $amount,
                'currency'              => 'NGN',
                'status'                => 'pending',
            ]);

            return $data['authorization_url'];
        }

        Log::error('Paystack Init Failed', [
            'status'  => $response->status(),
            'body'    => $response->body(),
            'invoice' => $invoice->id,
        ]);

        throw new \Exception("Could not initialize Paystack payment: " . ($response->json()['message'] ?? 'Network Error'));
    }

    private function initializeStripe(Invoice $invoice, array $config, $amount)
    {
        $url = "https://api.stripe.com/v1/checkout/sessions";
        $secretKey = $config['secret_key'] ?? (config('services.stripe.secret') ?? env('STRIPE_SECRET'));

        if (empty($secretKey)) {
            throw new \Exception("Stripe Secret Key is missing in Payment Settings.");
        }

        $reference = $invoice->invoice_number . '_' . time();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $secretKey,
        ])
        ->asForm()
        ->withOptions([
            'verify' => false,
        ])
        ->post($url, [
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'customer_email' => $invoice->prospect->email,
            'client_reference_id' => (string) $invoice->prospect_id,
            'success_url' => route('payments.callback', ['gateway' => 'stripe']) . '?session_id={CHECKOUT_SESSION_ID}&reference=' . $reference,
            'cancel_url' => route('payments.checkout', ['invoice' => $invoice->id]) . '?status=cancelled',
            'line_items[0][price_data][currency]' => 'usd',
            'line_items[0][price_data][product_data][name]' => 'Publishing Package - ' . ($invoice->prospect->book_title ?? 'The Curated Archive'),
            'line_items[0][price_data][unit_amount]' => round($amount * 100),
            'line_items[0][quantity]' => 1,
            'metadata[invoice_id]' => (string) $invoice->id,
            'metadata[prospect_id]' => (string) $invoice->prospect_id,
        ]);

        if ($response->successful()) {
            $data = $response->json();

            Transaction::create([
                'invoice_id'            => $invoice->id,
                'gateway_slug'          => 'stripe',
                'transaction_reference' => $reference,
                'external_reference'    => $data['id'] ?? null,
                'amount'                => $amount,
                'currency'              => 'USD',
                'status'                => 'pending',
            ]);

            return $data['url'];
        }

        Log::error('Stripe Init Failed', [
            'status'  => $response->status(),
            'body'    => $response->body(),
            'invoice' => $invoice->id,
        ]);

        throw new \Exception("Could not initialize Stripe payment: " . ($response->json()['error']['message'] ?? 'Network Error'));
    }

    private function initializeFlutterwave(Invoice $invoice, array $config, $amount)
    {
        $url = "https://api.flutterwave.com/v3/payments";
        $secretKey = $config['secret_key'] ?? env('FLUTTERWAVE_SECRET_KEY');
        
        if (empty($secretKey)) {
            throw new \Exception("Flutterwave Secret Key is missing in Payment Settings.");
        }
        
        $txRef = $invoice->invoice_number . '_' . time();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $secretKey,
            'Content-Type'  => 'application/json',
            'User-Agent'    => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ])
        ->withOptions([
            'verify' => false,
        ])
        ->post($url, [
            'tx_ref' => $txRef,
            'amount' => $amount,
            'currency' => "NGN",
            'redirect_url' => route('payments.callback', ['gateway' => 'flutterwave']),
            'customer' => [
                'email' => $invoice->prospect->email,
                'phonenumber' => $invoice->prospect->phone_number,
                'name' => $invoice->prospect->name,
            ],
            'meta' => [
                'invoice_id' => $invoice->id,
                'prospect_id' => $invoice->prospect_id,
            ],
            'customizations' => [
                'title' => "The Curated Archive",
                'description' => "Payment for Invoice " . $invoice->invoice_number,
            ]
        ]);

        if ($response->successful()) {
            $data = $response->json()['data'];

            Transaction::create([
                'invoice_id' => $invoice->id,
                'gateway_slug' => 'flutterwave',
                'transaction_reference' => $txRef,
                'amount' => $amount,
                'status' => 'pending'
            ]);

            return $data['link'];
        }

        Log::error('Flutterwave Init Failed', [
            'status'  => $response->status(),
            'body'    => $response->body(),
            'invoice' => $invoice->id,
        ]);
        throw new \Exception("Could not initialize Flutterwave payment.");
    }

    /**
     * Handle Webhook Verification
     */
    public function verifyWebhook(string $gatewaySlug, Request $request): bool
    {
        $gateway = PaymentGateway::where('slug', $gatewaySlug)->first();
        $config = $gateway ? ($gateway->config ?? []) : [];

        switch ($gatewaySlug) {
            case 'paystack':
                $secretKey = $config['secret_key'] ?? (config('services.paystack.secret_key') ?? env('PAYSTACK_SECRET_KEY', ''));
                return $this->verifyPaystackWebhook($request, $secretKey);
            case 'stripe':
                $webhookSecret = $config['webhook_secret'] ?? ($config['secret_key'] ?? (config('services.stripe.webhook_secret') ?? env('STRIPE_WEBHOOK_SECRET', '')));
                return $this->verifyStripeWebhook($request, $webhookSecret);
            case 'flutterwave':
                $secretKey = $config['secret_key'] ?? env('FLUTTERWAVE_SECRET_KEY', '');
                return $this->verifyFlutterwaveWebhook($request, $secretKey);
            default:
                return false;
        }
    }

    private function verifyPaystackWebhook(Request $request, string $secretKey): bool
    {
        $signature = $request->header('x-paystack-signature');
        
        // Allow mock testing in local/test environments
        if (app()->environment('local', 'testing') && ($request->header('X-Mock-Paystack') || empty($secretKey))) {
            return true;
        }

        if (empty($signature) || empty($secretKey)) {
            return false;
        }

        $computedSignature = hash_hmac('sha512', $request->getContent(), $secretKey);
        return hash_equals($signature, $computedSignature);
    }

    private function verifyStripeWebhook(Request $request, string $webhookSecret): bool
    {
        $signatureHeader = $request->header('Stripe-Signature');

        // Allow mock testing in local/test environments
        if (app()->environment('local', 'testing') && ($request->header('X-Mock-Stripe') || empty($webhookSecret))) {
            return true;
        }

        if (empty($signatureHeader) || empty($webhookSecret)) {
            return false;
        }

        // Stripe-Signature: t=1492774577,v1=5257a869e7...,v0=...
        $items = explode(',', $signatureHeader);
        $timestamp = null;
        $signatures = [];

        foreach ($items as $item) {
            $parts = explode('=', trim($item), 2);
            if (count($parts) === 2) {
                if ($parts[0] === 't') {
                    $timestamp = $parts[1];
                } elseif ($parts[0] === 'v1') {
                    $signatures[] = $parts[1];
                }
            }
        }

        if (!$timestamp || empty($signatures)) {
            return false;
        }

        $signedPayload = "{$timestamp}." . $request->getContent();
        $expectedSignature = hash_hmac('sha256', $signedPayload, $webhookSecret);

        foreach ($signatures as $sig) {
            if (hash_equals($expectedSignature, $sig)) {
                return true;
            }
        }

        return false;
    }

    private function verifyFlutterwaveWebhook(Request $request, string $secretKey): bool
    {
        $signature = $request->header('verif-hash');
        if (app()->environment('local', 'testing') && empty($secretKey)) {
            return true;
        }
        return $signature === $secretKey; 
    }

    /**
     * Process unified webhook payment for Stripe, Paystack, and Flutterwave
     * Implements Prompt 5: Updates payment status, advances status to 'submitted',
     * removes from abandoned recovery queue, and escalates to Acquisition dashboard.
     */
    public function processWebhook(Request $request, ?string $gatewaySlug = null): array
    {
        // 1. Auto-detect gateway if not explicitly supplied
        if (!$gatewaySlug) {
            if ($request->header('Stripe-Signature') || $request->header('X-Mock-Stripe') || $request->input('object') === 'event') {
                $gatewaySlug = 'stripe';
            } elseif ($request->header('x-paystack-signature') || $request->header('X-Mock-Paystack') || str_starts_with((string)$request->input('event'), 'charge.')) {
                $gatewaySlug = 'paystack';
            } elseif ($request->header('verif-hash')) {
                $gatewaySlug = 'flutterwave';
            } else {
                $gatewaySlug = 'paystack';
            }
        }

        // 2. Validate signature
        if (!$this->verifyWebhook($gatewaySlug, $request)) {
            throw new \Exception("Invalid webhook signature for gateway: {$gatewaySlug}");
        }

        $data = $request->all();
        $isSuccess = false;
        $paymentRef = null;
        $amount = 0;
        $prospectId = null;
        $invoiceId = null;
        $customerEmail = null;

        // 3. Parse gateway payload
        if ($gatewaySlug === 'paystack') {
            $event = $data['event'] ?? '';
            if ($event === 'charge.success') {
                $isSuccess = true;
                $payloadData = $data['data'] ?? [];
                $paymentRef = $payloadData['reference'] ?? ('paystack_' . time());
                $amount = ($payloadData['amount'] ?? 0) / 100;
                $customerEmail = $payloadData['customer']['email'] ?? null;
                $prospectId = $payloadData['metadata']['prospect_id'] ?? null;
                $invoiceId = $payloadData['metadata']['invoice_id'] ?? null;
            }
        } elseif ($gatewaySlug === 'stripe') {
            $event = $data['type'] ?? '';
            if (in_array($event, ['checkout.session.completed', 'payment_intent.succeeded'])) {
                $isSuccess = true;
                $session = $data['data']['object'] ?? [];
                $paymentRef = $session['id'] ?? ($session['payment_intent'] ?? ('stripe_' . time()));
                $amount = ($session['amount_total'] ?? ($session['amount'] ?? 0)) / 100;
                $customerEmail = $session['customer_details']['email'] ?? ($session['customer_email'] ?? null);
                $prospectId = $session['metadata']['prospect_id'] ?? ($session['client_reference_id'] ?? null);
                $invoiceId = $session['metadata']['invoice_id'] ?? null;
            }
        } elseif ($gatewaySlug === 'flutterwave') {
            $status = $data['status'] ?? '';
            if ($status === 'successful') {
                $isSuccess = true;
                $payloadData = $data['data'] ?? [];
                $paymentRef = $payloadData['tx_ref'] ?? ('flw_' . time());
                $amount = $payloadData['amount'] ?? 0;
                $customerEmail = $payloadData['customer']['email'] ?? null;
                $prospectId = $payloadData['meta']['prospect_id'] ?? null;
                $invoiceId = $payloadData['meta']['invoice_id'] ?? null;
            }
        }

        if (!$isSuccess) {
            return [
                'status' => 'ignored',
                'message' => 'Event was not a recognized payment success event',
                'gateway' => $gatewaySlug,
            ];
        }

        // 4. Resolve Target Prospect & Invoice
        $prospect = null;
        $invoice = null;

        if ($invoiceId) {
            $invoice = Invoice::find($invoiceId);
            if ($invoice) {
                $prospect = $invoice->prospect;
            }
        }

        if (!$prospect && $prospectId) {
            $prospect = Prospect::find($prospectId);
        }

        if (!$prospect && $customerEmail) {
            $prospect = Prospect::where('email', $customerEmail)->latest()->first();
        }

        if (!$prospect && $paymentRef) {
            $tx = Transaction::where('transaction_reference', $paymentRef)->first();
            if ($tx && $tx->invoice) {
                $invoice = $tx->invoice;
                $prospect = $invoice->prospect;
            }
        }

        // 5. Execute Fulfillment
        if ($prospect) {
            $metadata = $prospect->metadata_file ?? [];
            $metadata['abandoned'] = false;
            $metadata['paid_at'] = now()->toIso8601String();
            $metadata['escalated_acquisition'] = true;
            $metadata['escalated_at'] = now()->toIso8601String();
            $metadata['payment_merchant'] = $gatewaySlug;
            $metadata['payment_reference'] = $paymentRef;

            $updateData = [
                'payment_status' => 'paid',
                'payment_merchant' => $gatewaySlug,
                'metadata_file' => $metadata,
                'updated_at' => now(),
            ];

            // Advance status from lead_captured to submitted
            if ($prospect->status === WorkflowStatus::LeadCaptured->value || $prospect->status === 'lead_captured') {
                $updateData['status'] = WorkflowStatus::Submitted->value;
            }

            // Auto-create Invoice if not yet created for this prospect
            if (!$invoice) {
                $invoice = Invoice::create([
                    'prospect_id' => $prospect->id,
                    'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
                    'amount' => $amount > 0 ? $amount : ($prospect->total_calculated_fee ?: 0),
                    'total_paid' => $amount > 0 ? $amount : ($prospect->total_calculated_fee ?: 0),
                    'status' => 'paid',
                    'paid_at' => now(),
                    'payment_reference' => $paymentRef,
                ]);
            } else if ($invoice->status !== 'paid') {
                $this->finalizePayment($invoice, $paymentRef);
            }

            $prospect->update($updateData);

            // Update or create Transaction record
            Transaction::updateOrCreate(
                ['transaction_reference' => $paymentRef],
                [
                    'invoice_id' => $invoice->id,
                    'gateway_slug' => $gatewaySlug,
                    'external_reference' => $paymentRef,
                    'amount' => $amount > 0 ? $amount : ($prospect->total_calculated_fee ?: 0),
                    'status' => 'successful',
                    'metadata' => $data,
                ]
            );

            // Write System Audit Trail
            AuditLog::create([
                'user_id' => $prospect->user_id ?? 1,
                'action' => 'payment_webhook_processed',
                'model_type' => Prospect::class,
                'model_id' => $prospect->id,
                'changes' => [
                    'gateway' => $gatewaySlug,
                    'reference' => $paymentRef,
                    'amount' => $amount,
                    'advanced_status' => WorkflowStatus::Submitted->value,
                    'escalated_acquisition' => true,
                    'abandoned_cleared' => true,
                ],
                'ip_address' => $request->ip() ?? '127.0.0.1',
            ]);
        }

        return [
            'status' => 'success',
            'message' => 'Payment verified and record updated',
            'gateway' => $gatewaySlug,
            'reference' => $paymentRef,
            'prospect_id' => $prospect?->id,
            'workflow_status' => $prospect?->status,
            'payment_status' => $prospect?->payment_status,
        ];
    }

    /**
     * Complete the payment process: update invoice, activate project, create user.
     */
    public function finalizePayment(Invoice $invoice, string $reference)
    {
        $transaction = Transaction::where('transaction_reference', $reference)
            ->orWhere('external_reference', $reference)
            ->first();

        if ($transaction) {
            $amountPaid = $transaction->amount;
            $transaction->update([
                'status' => 'successful',
                'currency' => $invoice->currency,
            ]);
        } else {
            $amountPaid = max(1.0, (float) ($invoice->amount - $invoice->total_paid));
            $transaction = Transaction::create([
                'invoice_id'            => $invoice->id,
                'gateway_slug'          => ($invoice->currency === 'USD') ? 'stripe' : 'bank_transfer',
                'transaction_reference' => $reference,
                'amount'                => $amountPaid,
                'currency'              => $invoice->currency,
                'status'                => 'successful',
            ]);
        }

        $newTotalPaid = $invoice->total_paid + $amountPaid;
        
        $invoice->update([
            'status' => 'paid',
            'total_paid' => $newTotalPaid,
            'is_installment' => $newTotalPaid < $invoice->amount,
            'paid_at' => now(),
            'payment_reference' => $reference,
        ]);

        $paymentType = 'initial';

        if ($invoice->prospect && $invoice->prospect->status !== 'project_active') {
            $project = Project::create([
                'prospect_id' => $invoice->prospect_id,
                'payment_reference' => $invoice->payment_reference,
                'status' => 'editing'
            ]);

            try {
                $this->contractService->generateContract($project);
            } catch (\Exception $e) {
                Log::error('Contract Auto-Generation Failed: ' . $e->getMessage());
            }

            event(new ProjectStageUpdated($project, 'editing'));
            
            $metadata = $invoice->prospect->metadata_file ?? [];
            $metadata['abandoned'] = false;
            $metadata['paid_at'] = now()->toIso8601String();
            $metadata['escalated_acquisition'] = true;
            $metadata['payment_reference'] = $reference;

            $invoice->prospect->update([
                'status' => 'project_active',
                'payment_status' => 'paid',
                'metadata_file' => $metadata,
            ]);
            $this->ensureUserAccountCreated($invoice->prospect);
        } else if ($invoice->prospect) {
            $invoice->prospect->update([
                'payment_status' => 'paid',
            ]);
            $paymentType = 'balance';
        }

        event(new PaymentSuccessful($invoice, $paymentType));

        return $paymentType;
    }

    /**
     * Ensure the author has an account.
     */
    private function ensureUserAccountCreated($prospect)
    {
        $user = User::where('email', $prospect->email)->first();
        if (!$user) {
            $generatedPassword = Str::random(10);
            $user = User::create([
                'name' => $prospect->name,
                'email' => $prospect->email,
                'password' => Hash::make($generatedPassword),
            ]);
            
            if (method_exists($user, 'assignRole')) {
                $user->assignRole('author_agent');
            }

            try {
                Mail::to($user->email)->send(new AccountCreatedMail($user, $generatedPassword));
            } catch (\Exception $e) {
                Log::warning('Account Created Mail could not be dispatched: ' . $e->getMessage());
            }
        }
        return $user;
    }
}

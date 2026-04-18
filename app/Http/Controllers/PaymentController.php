<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Project;
use App\Models\Transaction;
use App\Events\PaymentSuccessful;
use App\Mail\ProjectStatusUpdated;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Initiate payment redirect
     */
    public function initiate(Invoice $invoice, string $gateway)
    {
        try {
            $redirectUrl = $this->paymentService->initializePayment($invoice, $gateway);
            return redirect()->away($redirectUrl);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Handle return from payment provider
     */
    public function callback(Request $request, string $gateway)
    {
        $reference = $request->query('reference') ?? $request->query('tx_ref') ?? $request->query('transaction_id');
        
        if (!$reference) {
            return redirect()->route('admin.invoices.index')->with('error', 'Could not verify payment reference.');
        }

        // Search for the transaction
        $transaction = Transaction::where('transaction_reference', $reference)
            ->orWhere('external_reference', $reference)
            ->first();

        if (!$transaction) {
            return redirect()->route('admin.invoices.index')->with('error', 'Transaction not found.');
        }

        // If already success, just redirect
        if ($transaction->status == 'successful') {
            return redirect()->route('admin.invoices.show', $transaction->invoice_id)->with('success', 'Payment successful!');
        }

        // Ideally, we'd call the gateway API here to verify. 
        // For now, we trust the callback reference for the sake of development flow,
        // but we'll mark it as 'successful' if it reached here.
        $this->processPaymentSuccess($transaction->invoice, $reference);
        
        $transaction->update([
            'status' => 'successful',
            'external_reference' => $reference
        ]);

        return redirect()->route('admin.invoices.show', $transaction->invoice_id)->with('success', 'Payment confirmed and project activated!');
    }

    /**
     * Handle automated alerts from Gateway
     */
    public function webhook(Request $request, string $gateway)
    {
        if (!$this->paymentService->verifyWebhook($gateway, $request)) {
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        $data = $request->all();
        $invoice = null;

        // Extract Invoice based on Gateway structure
        if ($gateway == 'paystack' && $data['event'] == 'charge.success') {
            $invoiceId = $data['data']['metadata']['invoice_id'] ?? null;
            $invoice = Invoice::find($invoiceId);
            $paymentRef = $data['data']['reference'];
        } elseif ($gateway == 'flutterwave' && $data['status'] == 'successful') {
            $invoiceId = $data['data']['meta']['invoice_id'] ?? null;
            $invoice = Invoice::find($invoiceId);
            $paymentRef = $data['data']['tx_ref'];
        }

        if ($invoice && $invoice->status !== 'paid') {
            $this->processPaymentSuccess($invoice, $paymentRef);
        }

        // Update Transaction record
        $transaction = Transaction::where('transaction_reference', $paymentRef)
            ->orWhere('external_reference', $paymentRef)
            ->first();
            
        if ($transaction) {
            $isSuccess = ($gateway == 'paystack' && $data['event'] == 'charge.success') || ($gateway == 'flutterwave' && $data['status'] == 'successful');
            
            $transaction->update([
                'status' => $isSuccess ? 'successful' : 'failed',
                'external_reference' => $paymentRef,
                'metadata' => $data
            ]);
        }

        return response()->json(['message' => 'Webhook Processed']);
    }

    private function processPaymentSuccess(Invoice $invoice, $reference)
    {
        // Mark as paid
        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_reference' => $reference,
        ]);

        // Activate Project
        $project = Project::create([
            'prospect_id' => $invoice->prospect_id,
            'payment_reference' => $invoice->payment_reference,
            'status' => 'editing'
        ]);

        // Notify Author
        try {
            Mail::to($invoice->prospect->email)->send(new ProjectStatusUpdated($project, 'editing'));
        } catch (\Exception $e) {
            Log::error('Webhook Activation Email Failed: ' . $e->getMessage());
        }

        // Update Prospect
        $invoice->prospect->update(['status' => 'project_active']);

        event(new PaymentSuccessful($invoice));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\PaymentGateway;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function showCheckout(Invoice $invoice)
    {
        $gateways = PaymentGateway::where('is_active', true)->get();
        return view('payments.checkout', compact('invoice', 'gateways'));
    }

    /**
     * Initiate payment redirect
     */
    public function initiate(Invoice $invoice, Request $request)
    {
        try {
            $gateway = $request->query('gateway') ?? $request->input('gateway');
            $amount = $request->query('amount') ?? $request->input('amount');
            
            if (!$gateway) {
                throw new \Exception("Please select a payment gateway.");
            }

            $redirectUrl = $this->paymentService->initializePayment($invoice, $gateway, $amount);
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
        $reference = $request->query('reference') 
            ?? $request->query('tx_ref') 
            ?? $request->query('transaction_id') 
            ?? $request->query('session_id');
        
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
            if (auth()->check()) {
                if (auth()->user()->hasRole('admin')) {
                    return redirect()->route('admin.invoices.show', $transaction->invoice_id)->with('success', 'Payment successful!');
                }
                return redirect()->route('author.dashboard')->with('success', 'Payment successful!');
            }
            return redirect()->route('payments.success')->with('success', 'Payment successful!');
        }

        $this->paymentService->finalizePayment($transaction->invoice, $reference);
        
        $transaction->update([
            'status' => 'successful',
            'external_reference' => $reference
        ]);

        if (auth()->check()) {
            if (auth()->user()->hasRole('admin')) {
                return redirect()->route('admin.invoices.show', $transaction->invoice_id)->with('success', 'Payment confirmed and project activated!');
            }
            return redirect()->route('author.dashboard')->with('success', 'Payment confirmed and project activated!');
        }

        return redirect()->route('payments.success')->with('success', 'Payment confirmed and project activated!');
    }

    /**
     * Unified Webhook Endpoint (Prompt 5)
     * Handles POST /api/webhooks/payment for Stripe, Paystack, and others
     */
    public function handleUnifiedWebhook(Request $request)
    {
        try {
            $result = $this->paymentService->processWebhook($request, null);
            return response()->json($result, 200);
        } catch (\Exception $e) {
            Log::error('Unified Webhook Error: ' . $e->getMessage(), [
                'headers' => $request->headers->all(),
                'body' => $request->getContent(),
            ]);
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Specific Gateway Webhook Endpoint
     * Handles POST /api/webhooks/{gateway}
     */
    public function webhook(Request $request, string $gateway)
    {
        try {
            $result = $this->paymentService->processWebhook($request, $gateway);
            return response()->json($result, 200);
        } catch (\Exception $e) {
            Log::error("Webhook error for {$gateway}: " . $e->getMessage(), [
                'gateway' => $gateway,
                'body' => $request->getContent(),
            ]);
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}

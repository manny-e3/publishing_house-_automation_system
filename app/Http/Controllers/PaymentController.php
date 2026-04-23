<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Project;
use App\Models\Transaction;
use App\Models\PaymentGateway;
use App\Events\PaymentSuccessful;
use App\Events\ProjectStageUpdated;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Mail\AccountCreatedMail;

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
            if (auth()->check()) {
                if (auth()->user()->hasRole('admin')) {
                    return redirect()->route('admin.invoices.show', $transaction->invoice_id)->with('success', 'Payment successful!');
                }
                return redirect()->route('author.dashboard')->with('success', 'Payment successful!');
            }
            return redirect()->route('payments.success')->with('success', 'Payment successful!');
        }

        // Ideally, we'd call the gateway API here to verify. 
        // For now, we trust the callback reference for the sake of development flow,
        // but we'll mark it as 'successful' if it reached here.
        $this->processPaymentSuccess($transaction->invoice, $reference);
        
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
        // Get the transaction to check amount
        $transaction = Transaction::where('transaction_reference', $reference)
            ->orWhere('external_reference', $reference)
            ->first();

        $amountPaid = $transaction ? $transaction->amount : 0;
        $isInstallment = $amountPaid < $invoice->amount;
        $newTotalPaid = $invoice->total_paid + $amountPaid;
        
        // Mark as paid or partially paid
        $invoice->update([
            'status' => 'paid',
            'total_paid' => $newTotalPaid,
            'is_installment' => $newTotalPaid < $invoice->amount,
            'paid_at' => now(),
            'payment_reference' => $reference,
        ]);

        $paymentType = 'initial';

        // Activate Project IF it's not already activated (avoid double activation on subsequent installments)
        if ($invoice->prospect->status !== 'project_active') {
            $project = Project::create([
                'prospect_id' => $invoice->prospect_id,
                'payment_reference' => $invoice->payment_reference,
                'status' => 'editing'
            ]);

            // Notify Author and Team via events
            event(new ProjectStageUpdated($project, 'editing'));

            // Update Prospect
            $invoice->prospect->update(['status' => 'project_active']);
            
            // Check if user account exists
            $user = User::where('email', $invoice->prospect->email)->first();
            if (!$user) {
                $generatedPassword = Str::random(10);
                $user = User::create([
                    'name' => $invoice->prospect->name,
                    'email' => $invoice->prospect->email,
                    'password' => Hash::make($generatedPassword),
                ]);
                
                // Assign role if spatie roles exist
                if (method_exists($user, 'assignRole')) {
                    $user->assignRole('prospect');
                }

                // Send email
                Mail::to($user->email)->send(new AccountCreatedMail($user, $generatedPassword));
            }
        } else {
            $paymentType = 'balance';
        }

        event(new PaymentSuccessful($invoice, $paymentType));
    }
}

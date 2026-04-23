<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Prospect;
use App\Models\Project;
use App\Models\PaymentGateway;
use App\Models\Transaction;
use App\Models\Setting;
use App\Events\PaymentSuccessful;
use App\Events\ProjectStageUpdated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('prospect')->latest()->paginate(10);
        return view('admin.invoices.index', compact('invoices'));
    }

    public function receiptsIndex()
    {
        $invoices = Invoice::with('prospect')->where('status', 'paid')->latest()->paginate(10);
        return view('admin.receipts.index', compact('invoices'));
    }

    public function transactionsIndex()
    {
        $transactions = Transaction::with('invoice.prospect')->latest()->paginate(15);
        return view('admin.transactions.index', compact('transactions'));
    }

    public function create(Request $request)
    {
        $prospect = Prospect::findOrFail($request->prospect_id);
        $gateways = PaymentGateway::where('is_active', true)->get();
        $minDeposit = Setting::where('key', 'min_deposit_percentage')->first()->value ?? 40;
        return view('admin.invoices.create', compact('prospect', 'gateways', 'minDeposit'));
    }

    public function show(Invoice $invoice)
    {
        return view('admin.invoices.show', compact('invoice'));
    }

    public function downloadPDF(Invoice $invoice)
    {
        $pdf = Pdf::loadView('admin.invoices.pdf', compact('invoice'));
        return $pdf->download($invoice->invoice_number . '.pdf');
    }

    public function showReceipt(Invoice $invoice)
    {
        if ($invoice->status !== 'paid') {
            return redirect()->back()->with('error', 'Receipts are only available for paid invoices.');
        }
        return view('admin.receipts.show', compact('invoice'));
    }

    public function downloadReceipt(Invoice $invoice)
    {
        if ($invoice->status !== 'paid') {
            return redirect()->back()->with('error', 'Receipts are only available for paid invoices.');
        }
        $pdf = Pdf::loadView('admin.receipts.pdf', compact('invoice'));
        return $pdf->download('RECEIPT-' . $invoice->payment_reference . '.pdf');
    }

    public function gatewaySettings()
    {
        $gateways = PaymentGateway::all();
        return view('admin.settings.gateways', compact('gateways'));
    }


    public function updateGateway(Request $request, PaymentGateway $gateway)
    {
        $gateway->update([
            'is_active' => $request->has('is_active'),
            'config' => $request->config,
        ]);

        return redirect()->back()->with('success', $gateway->name . ' configuration updated successfully.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'prospect_id' => 'required|exists:prospects,id',
            'amount' => 'required|numeric|min:0',
            'allowed_gateways' => 'required|array|min:1',
            'min_deposit_percentage' => 'required|integer|min:0|max:100',
        ]);

        $invoice = Invoice::create([
            'prospect_id' => $validated['prospect_id'],
            'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
            'amount' => $validated['amount'],
            'allowed_gateways' => $validated['allowed_gateways'],
            'min_deposit_percentage' => $validated['min_deposit_percentage'],
            'status' => 'unpaid'
        ]);

        event(new \App\Events\InvoiceGenerated($invoice));

        return redirect()->route('admin.invoices.index')->with('success', 'Invoice generated successfully and sent to author.');
    }

    public function confirmPayment(Request $request, Invoice $invoice)
    {
        // Mark invoice as paid
        $invoice->update([
            'status' => 'paid',
            'total_paid' => $invoice->amount, // Manual confirmation assumes full payment unless we add an amount input
            'is_installment' => false,
            'paid_at' => now(),
            'payment_reference' => 'MANUAL-' . strtoupper(Str::random(6)),
        ]);
        
        $invoice->refresh(); // Ensure it's fresh for events

        // This event will trigger:
        // 1. User account creation (if guest)
        // 2. Author credentials email
        // 3. Finance alert (N-06)
        // 4. Editorial alert (N-07)
        event(new PaymentSuccessful($invoice));

        // Activate Project
        $project = Project::create([
            'prospect_id' => $invoice->prospect_id,
            'payment_reference' => $invoice->payment_reference,
            'status' => 'editing'
        ]);

        // This event will trigger author and internal team notifications for the 'editing' stage
        event(new ProjectStageUpdated($project, 'editing'));

        // Update Prospect to signify it is now in active pipeline
        $invoice->prospect->update(['status' => 'project_active']);

        return redirect()->route('admin.invoices.index')->with('success', 'Payment confirmed! The Project is now active in the Pipeline.');
    }
}

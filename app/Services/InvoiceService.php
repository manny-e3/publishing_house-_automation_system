<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Prospect;
use App\Events\InvoiceGenerated;
use Illuminate\Support\Str;

class InvoiceService
{
    /**
     * Generate a new invoice for a prospect.
     */
    public function generateInvoice(array $data): Invoice
    {
        $invoice = Invoice::create([
            'prospect_id' => $data['prospect_id'],
            'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
            'amount' => $data['amount'],
            'allowed_gateways' => $data['allowed_gateways'],
            'min_deposit_percentage' => $data['min_deposit_percentage'],
            'status' => 'unpaid'
        ]);

        event(new InvoiceGenerated($invoice));

        return $invoice;
    }
}

<?php

namespace App\Services;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPdfWrapper;

class SystemInvoicePdfService
{
    /**
     * Generate DomPDF invoice instance with itemized Osisi Hybrid calculations
     */
    public function generate(Invoice $invoice): DomPdfWrapper
    {
        return Pdf::loadView('admin.invoices.pdf', [
            'invoice' => $invoice,
            'prospect' => $invoice->prospect,
        ])->setPaper('a4', 'portrait');
    }

    /**
     * Download invoice directly as PDF
     */
    public function download(Invoice $invoice, ?string $filename = null)
    {
        $pdf = $this->generate($invoice);
        $name = $filename ?: ($invoice->invoice_number . '.pdf');
        return $pdf->download($name);
    }

    /**
     * Output raw PDF string for email attachments or cloud archiving
     */
    public function output(Invoice $invoice): string
    {
        return $this->generate($invoice)->output();
    }
}


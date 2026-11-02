<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 13px; color: #1e293b; line-height: 1.5; }
        .invoice-box { max-width: 800px; margin: auto; padding: 25px; }
        .header { margin-bottom: 35px; border-bottom: 2px solid #033d32; padding-bottom: 20px; }
        .header table { width: 100%; border-collapse: collapse; }
        .brand-title { font-size: 24px; font-weight: bold; color: #033d32; font-family: serif; letter-spacing: 0.05em; }
        .brand-sub { font-size: 11px; text-transform: uppercase; color: #64748b; letter-spacing: 0.1em; }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 11px; text-transform: uppercase; font-weight: bold; }
        .status-unpaid { background: #fee2e2; color: #991b1b; }
        .status-paid { background: #dcfce7; color: #166534; }
        
        .details table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        .details td { vertical-align: top; }
        .item-table { width: 100%; border-collapse: collapse; margin-top: 20px; margin-bottom: 25px; }
        .item-table th { background: #f8fafc; text-align: left; padding: 10px; border-bottom: 2px solid #cbd5e1; font-size: 12px; text-transform: uppercase; color: #475569; }
        .item-table td { padding: 12px 10px; border-bottom: 1px solid #e2e8f0; font-size: 12px; }
        .total-section { margin-top: 15px; text-align: right; }
        .total-section table { width: 320px; float: right; border-collapse: collapse; }
        .total-section td { padding: 6px 0; font-size: 13px; }
        .total-row { font-size: 16px; font-weight: bold; color: #033d32; border-top: 2px solid #033d32; }
        
        .disclaimer-box { clear: both; margin-top: 40px; padding: 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; font-size: 10px; color: #64748b; line-height: 1.4; }
        .footer { margin-top: 30px; border-top: 1px solid #e2e8f0; padding-top: 15px; text-align: center; color: #94a3b8; font-size: 10px; }
    </style>
</head>
<body>
    @php
        $currencySymbol = $invoice->currency_symbol;
        $currency = $invoice->currency;
        $model = $invoice->prospect->determined_model?->value ?? $invoice->prospect->determined_model ?? 'hybrid_osisi';
    @endphp

    <div class="invoice-box">
        <div class="header">
            <table>
                <tr>
                    <td>
                        <div class="brand-title">THE CURATED ARCHIVE</div>
                        <div class="brand-sub">Independent Publishing & Academic Distribution</div>
                    </td>
                    <td style="text-align: right;">
                        <h2 style="margin: 0; color: #033d32;">INVOICE</h2>
                        <div style="margin-top: 4px; font-weight: bold; color: #475569;">#{{ $invoice->invoice_number }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="details">
            <table>
                <tr>
                    <td style="width: 50%;">
                        <div style="color: #64748b; font-size: 10px; text-transform: uppercase; margin-bottom: 4px; font-weight: bold;">Billed To</div>
                        <strong style="font-size: 14px;">{{ $invoice->prospect->name }}</strong><br>
                        {{ $invoice->prospect->email }}<br>
                        {{ $invoice->prospect->phone_number }}<br>
                        <span style="display: inline-block; margin-top: 6px; padding: 2px 6px; background: #e0f2fe; color: #0369a1; border-radius: 3px; font-size: 10px; font-weight: bold;">
                            {{ $model === 'traditional' ? 'Traditional Publishing' : 'Osisi Hybrid Model' }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div style="color: #64748b; font-size: 10px; text-transform: uppercase; margin-bottom: 4px; font-weight: bold;">Billing Summary</div>
                        Date Issued: <strong>{{ $invoice->created_at->format('d M, Y') }}</strong><br>
                        Payment Status: 
                        <span class="status-badge status-{{ $invoice->status }}">{{ $invoice->is_installment ? 'Installment Active' : $invoice->status }}</span>
                        @if($invoice->payment_reference)
                            <br><span style="font-size: 10px; color: #64748b;">Ref: <code>{{ $invoice->payment_reference }}</code></span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <table class="item-table">
            <thead>
                <tr>
                    <th style="width: 70%;">Itemized Deliverable & Specifications</th>
                    <th style="text-align: right; width: 30%;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>Osisi Hybrid Production Package & Editorial Retainer</strong><br>
                        <span style="color: #64748b; font-size: 11px;">
                            Title: <em>"{{ $invoice->prospect->book_title }}"</em> &bull; Genre: {{ $invoice->prospect->genre }} ({{ number_format($invoice->prospect->number_of_words) }} words)
                        </span>
                    </td>
                    <td style="text-align: right;">{{ $currencySymbol }}{{ number_format($invoice->prospect->total_calculated_fee ?: $invoice->amount, 2) }}</td>
                </tr>
                <tr>
                    <td>
                        <strong>Physical Press Run Allocation</strong><br>
                        <span style="color: #64748b; font-size: 11px;">
                            Guaranteed Print Quantity: {{ number_format($invoice->prospect->requested_print_quantity ?: 500) }} copies &bull; Format: Paperback Trim Alignment
                        </span>
                    </td>
                    <td style="text-align: right;">
                        @if($invoice->prospect->total_bulk_print_estimate > 0)
                            {{ $currencySymbol }}{{ number_format($invoice->prospect->total_bulk_print_estimate, 2) }}
                        @else
                            <span style="color: #10b981; font-weight: bold;">Included / Pre-allocated</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Syndicated Channel Distribution & ISBN Licensing</strong><br>
                        <span style="color: #64748b; font-size: 11px;">
                            Multi-Platform Listing (Amazon KDP, Selar, African Book Collective) & Official ISBN Metadata Registry
                        </span>
                    </td>
                    <td style="text-align: right;"><span style="color: #10b981; font-weight: bold;">Bundled</span></td>
                </tr>
            </tbody>
        </table>

        <div class="total-section">
            <table>
                <tr>
                    <td style="color: #64748b;">Gross Production Cost:</td>
                    <td style="text-align: right; font-weight: bold;">{{ $currencySymbol }}{{ number_format($invoice->amount, 2) }}</td>
                </tr>
                @if($invoice->is_installment)
                <tr>
                    <td style="color: #166534;">Amount Settled:</td>
                    <td style="text-align: right; color: #166534; font-weight: bold;">-{{ $currencySymbol }}{{ number_format($invoice->total_paid, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td>Remaining Balance Due:</td>
                    <td style="text-align: right;">{{ $currencySymbol }}{{ number_format(max(0, $invoice->amount - $invoice->total_paid), 2) }}</td>
                </tr>
                @else
                <tr class="total-row">
                    <td>Total Due / Settled:</td>
                    <td style="text-align: right;">{{ $currencySymbol }}{{ number_format($invoice->amount, 2) }}</td>
                </tr>
                @endif
            </table>
        </div>

        <div class="disclaimer-box">
            <strong>Production Guarantee & Legal Notice:</strong> Total project costs represent itemized manufacturing, typesetting, and copyright registry charges. In accordance with The Curated Archive Osisi Hybrid agreement, authors retain 100% intellectual property ownership and standard royalty distributions.
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} The Curated Archive Publishing House. All rights reserved.<br>
            Official Electronic Invoice Generated on {{ date('Y-m-d H:i:s') }} UTC.
        </div>
    </div>
</body>
</html>

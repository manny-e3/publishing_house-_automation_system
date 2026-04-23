<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 13px; color: #333; }
        .invoice-box { max-width: 800px; margin: auto; padding: 20px; }
        .header { margin-bottom: 40px; }
        .header table { width: 100%; border-collapse: collapse; }
        .logo { font-size: 24px; font-weight: bold; color: #798bff; }
        .status-badge { padding: 5px 10px; border-radius: 4px; font-size: 11px; text-transform: uppercase; }
        .status-unpaid { background: #fee; color: #d11; }
        .status-paid { background: #efe; color: #1a1; }
        
        .details table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .details td { vertical-align: top; }
        .item-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .item-table th { background: #f5f6fa; text-align: left; padding: 10px; border-bottom: 2px solid #dbdfea; }
        .item-table td { padding: 10px; border-bottom: 1px solid #dbdfea; }
        .total-section { margin-top: 30px; text-align: right; }
        .total-section table { width: 250px; float: right; border-collapse: collapse; }
        .total-section td { padding: 5px 0; }
        .total-row { font-size: 18px; font-weight: bold; color: #333; border-top: 2px solid #798bff; }
        
        .footer { margin-top: 50px; border-top: 1px solid #dbdfea; padding-top: 20px; text-align: center; color: #888; font-size: 11px; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <table>
                <tr>
                    <td>
                        <div class="logo">The Curated Archive</div>
                        <div>Publishing Management System</div>
                    </td>
                    <td style="text-align: right;">
                        <h2 style="margin: 0;">INVOICE</h2>
                        <div style="margin-top: 5px;">#{{ $invoice->invoice_number }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="details">
            <table>
                <tr>
                    <td style="width: 50%;">
                        <div style="color: #888; font-size: 11px; text-transform: uppercase; margin-bottom: 5px;">Billed To</div>
                        <strong>{{ $invoice->prospect->name }}</strong><br>
                        {{ $invoice->prospect->email }}<br>
                        {{ $invoice->prospect->phone_number }}
                    </td>
                    <td style="text-align: right;">
                        <div style="color: #888; font-size: 11px; text-transform: uppercase; margin-bottom: 5px;">Invoice Summary</div>
                        Date: {{ $invoice->created_at->format('d M, Y') }}<br>
                        Status: <span class="status-badge status-{{ $invoice->status }}">{{ $invoice->is_installment ? 'Installment Paid' : $invoice->status }}</span>
                        @if($invoice->is_installment)
                        <br>Balance: ₦{{ number_format($invoice->amount - $invoice->total_paid, 2) }}
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <table class="item-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>Publishing Service Fee</strong><br>
                        <span style="color: #888; font-size: 11px;">Title: {{ $invoice->prospect->book_title }}</span>
                    </td>
                    <td style="text-align: right;">₦{{ number_format($invoice->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="total-section">
            <table>
                <tr>
                    <td>Subtotal</td>
                    <td>₦{{ number_format($invoice->amount, 2) }}</td>
                </tr>
                <tr>
                    <td>VAT (0%)</td>
                    <td>₦0.00</td>
                </tr>
                @if($invoice->is_installment)
                <tr>
                    <td style="color: #888;">Total Paid (Installments)</td>
                    <td style="color: #1a1;">₦{{ number_format($invoice->total_paid, 2) }}</td>
                </tr>
                <tr>
                    <td style="color: #888;">Balance Due</td>
                    <td style="color: #d11; font-weight: bold;">₦{{ number_format($invoice->amount - $invoice->total_paid, 2) }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td>Total</td>
                    <td>₦{{ number_format($invoice->amount, 2) }}</td>
                </tr>
            </table>
            <div style="clear: both;"></div>
        </div>

        <div class="footer">
            <p>Thank you for choosing The Curated Archive. If you have any questions about this invoice, please contact support@curatedarchive.com</p>
            <p>&copy; {{ date('Y') }} The Curated Archive. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

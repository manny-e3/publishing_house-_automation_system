<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt - {{ $invoice->payment_reference }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 13px; color: #333; }
        .receipt-box { max-width: 800px; margin: auto; padding: 20px; border: 1px solid #eee; }
        .header { margin-bottom: 40px; text-align: center; }
        .logo { font-size: 24px; font-weight: bold; color: #1ee0ac; }
        
        .receipt-details { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .receipt-details td { padding: 10px; border-bottom: 1px solid #f5f6fa; }
        .label { color: #888; font-size: 11px; text-transform: uppercase; width: 30%; }
        
        .item-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .item-table th { background: #f5f6fa; text-align: left; padding: 10px; border-bottom: 2px solid #1ee0ac; }
        .item-table td { padding: 15px 10px; border-bottom: 1px solid #dbdfea; }
        
        .stamp { margin-top: 40px; text-align: center; }
        .stamp-box { display: inline-block; padding: 10px 20px; border: 3px solid #1ee0ac; color: #1ee0ac; font-weight: bold; font-size: 24px; border-radius: 8px; transform: rotate(-10deg); text-transform: uppercase; }
        
        .footer { margin-top: 60px; text-align: center; color: #888; font-size: 11px; }
    </style>
</head>
<body>
    <div class="receipt-box">
        <div class="header">
            <div class="logo">The Curated Archive</div>
            <h2 style="margin: 10px 0;">OFFICIAL PAYMENT RECEIPT</h2>
            <div style="color: #888;">Transaction Reference: {{ $invoice->payment_reference }}</div>
        </div>

        <table class="receipt-details">
            <tr>
                <td class="label">Received From</td>
                <td><strong>{{ $invoice->prospect->name }}</strong> ({{ $invoice->prospect->email }})</td>
            </tr>
            <tr>
                <td class="label">Date of Payment</td>
                <td>{{ $invoice->paid_at ? \Carbon\Carbon::parse($invoice->paid_at)->format('d M, Y H:i') : $invoice->updated_at->format('d M, Y H:i') }}</td>
            </tr>
            <tr>
                <td class="label">Original Invoice</td>
                <td>#{{ $invoice->invoice_number }}</td>
            </tr>
            <tr>
                <td class="label">Payment Method</td>
                <td>Manual Bank Transfer / Confirmed by Admin</td>
            </tr>
        </table>

        <table class="item-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="text-align: right;">Amount Paid</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>Publishing Service Activation</strong><br>
                        <span style="color: #888; font-size: 11px;">Project: {{ $invoice->prospect->book_title }}</span>
                    </td>
                    <td style="text-align: right; font-size: 18px; font-weight: bold;">
                        ₦{{ number_format($invoice->is_installment ? $invoice->total_paid : $invoice->amount, 2) }}
                    </td>
                </tr>
                @if($invoice->is_installment)
                <tr>
                    <td style="text-align: right; font-size: 11px; color: #888;">Invoice Total: ₦{{ number_format($invoice->amount, 2) }}</td>
                    <td style="text-align: right; font-size: 11px; color: #d11;">Balance: ₦{{ number_format($invoice->amount - $invoice->total_paid, 2) }}</td>
                </tr>
                @endif
            </tbody>
        </table>

        <div class="stamp">
            <div class="stamp-box" style="border-color: {{ $invoice->is_installment ? '#09c' : '#1ee0ac' }}; color: {{ $invoice->is_installment ? '#09c' : '#1ee0ac' }};">
                {{ $invoice->is_installment ? 'INSTALLMENT' : 'PAID IN FULL' }}
            </div>
        </div>

        <div class="footer">
            <p>This is a computer-generated document and requires no signature.</p>
            <p>&copy; {{ date('Y') }} The Curated Archive. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

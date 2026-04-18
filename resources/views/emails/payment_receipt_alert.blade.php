<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; }
        .header { background: #0B1120; color: #fff; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .details { margin-top: 20px; background: #f9f9f9; padding: 15px; border-radius: 5px; }
        .footer { margin-top: 20px; font-size: 12px; color: #777; text-align: center; }
        .button { display: inline-block; padding: 10px 20px; background: #A67C00; color: #fff; text-decoration: none; border-radius: 3px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Payment Receipt Alert</h1>
        </div>
        <div class="content">
            <p>Hello Finance Team,</p>
            <p>A new payment has been successfully processed. Please find the receipt details below:</p>
            
            <div class="details">
                <p><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</p>
                <p><strong>Reference:</strong> {{ $invoice->payment_reference }}</p>
                <p><strong>Amount Paid:</strong> ₦{{ number_format($invoice->amount, 2) }}</p>
                <p><strong>Author:</strong> {{ $invoice->prospect->name }}</p>
                <p><strong>Book Title:</strong> {{ $invoice->prospect->book_title }}</p>
                <p><strong>Paid On:</strong> {{ $invoice->paid_at->format('M d, Y H:i') }}</p>
            </div>

            <p style="margin-top: 30px; text-align: center;">
                <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="button">View Invoice Details</a>
            </p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} The Curated Archive Publishing House</p>
        </div>
    </div>
</body>
</html>

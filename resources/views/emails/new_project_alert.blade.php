<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; }
        .header { background: #0B1120; color: #fff; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .details { margin-top: 20px; background: #eef2ff; padding: 15px; border-radius: 5px; border-left: 4px solid #4f46e5; }
        .footer { margin-top: 20px; font-size: 12px; color: #777; text-align: center; }
        .button { display: inline-block; padding: 10px 20px; background: #4f46e5; color: #fff; text-decoration: none; border-radius: 3px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New Project Activation</h1>
        </div>
        <div class="content">
            <p>Hello Editorial Team,</p>
            <p>A new project has been activated following successful payment. It is now ready for the production pipeline:</p>
            
            <div class="details">
                <p><strong>Project:</strong> {{ $invoice->prospect->book_title }}</p>
                <p><strong>Author:</strong> {{ $invoice->prospect->name }}</p>
                <p><strong>Genre:</strong> {{ $invoice->prospect->genre }}</p>
                <p><strong>Requested Services:</strong> 
                    @if($invoice->prospect->quote_for_services)
                        {{ implode(', ', array_map('ucfirst', $invoice->prospect->quote_for_services)) }}
                    @else
                        Standard
                    @endif
                </p>
            </div>

            <p>Please log in to the admin dashboard to review the manuscript files and initiate the editing process.</p>

            <p style="margin-top: 30px; text-align: center;">
                <a href="{{ route('admin.dashboard') }}" class="button">Go to Dashboard</a>
            </p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} The Curated Archive Publishing House</p>
        </div>
    </div>
</body>
</html>

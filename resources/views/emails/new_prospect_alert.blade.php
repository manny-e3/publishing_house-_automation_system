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
            <h1>New Manuscript Enquiry</h1>
        </div>
        <div class="content">
            <p>Hello Acquisitions Team,</p>
            <p>A new publishing enquiry has been received through the portal. Below is a summary of the prospect details:</p>
            
            <div class="details">
                <p><strong>Book Title:</strong> {{ $prospect->book_title }}</p>
                <p><strong>Author:</strong> {{ $prospect->name }}</p>
                <p><strong>Genre:</strong> {{ $prospect->genre }}</p>
                <p><strong>Word Count:</strong> {{ number_format($prospect->number_of_words) }} words</p>
                <p><strong>Est. Investment:</strong> ₦{{ number_format($prospect->estimated_cost, 2) }}</p>
                <p><strong>Services Requested:</strong> 
                    @if($prospect->quote_for_services)
                        {{ implode(', ', array_map('ucfirst', $prospect->quote_for_services)) }}
                    @else
                        Default
                    @endif
                </p>
            </div>

            <p style="margin-top: 30px; text-align: center;">
                <a href="{{ $url }}" class="button">Review Manuscript Excerpt</a>
            </p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} The Curated Archive Publishing House</p>
        </div>
    </div>
</body>
</html>

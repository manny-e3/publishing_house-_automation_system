@extends('emails.layout')

@section('content')
    <h1 class="h1">Payment Confirmed - Let's Start!</h1>
    <p>Hello {{ $prospect->name }},</p>
    <p>Success! We have received your payment for Invoice <strong>#{{ $invoice->invoice_number }}</strong>.</p>
    <p>Your project, <strong>"{{ $prospect->book_title }}"</strong>, has been officially activated in our pipeline. Our editorial team is now starting the **Editing Stage**.</p>
    <p><strong>Next Steps:</strong></p>
    <ul>
        <li>Typesetting & Initial Proof: You will be notified once this is ready for your review.</li>
        <li>Cover Design: We will reach out to discuss your cover concepts shortly.</li>
    </ul>
    <p>You can track your project's live progress anytime via your Author Dashboard.</p>
    <p>Cheers to a great book!<br>The Curated Archive Team</p>
@endsection

@extends('emails.layout')

@section('content')
    <h1 class="h1">Manuscript Excerpt Received!</h1>
    <p>Hello {{ $prospect->name }},</p>
    <p>Thank you for submitting your book details, synopsis, and excerpt for <strong>"{{ $prospect->book_title }}"</strong> to Griots Lounge Publishing. We have securely logged your submission package and our editorial team has been notified.</p>
    <p><strong>What happens next?</strong></p>
    <ul>
        <li><strong>Editorial Appraisal:</strong> 5–10 business days. Our team evaluates your synopsis and excerpt for prose craft, themes, and catalog fit.</li>
        <li><strong>Assessment Report:</strong> You will receive formal editorial feedback and a publication recommendation.</li>
        @if(($prospect->publishing_option ?? '') === 'traditional')
            <li><strong>Pathway:</strong> Traditional Acquisition Review (100% house-funded, zero author fees).</li>
        @elseif(($prospect->publishing_option ?? '') === 'advise_me')
            <li><strong>Pathway:</strong> Editorial Advisory & Assessment (recommendation will be delivered after appraisal).</li>
        @else
            <li><strong>Pathway:</strong> Hybrid Publishing (Collaborative model &mdash; provisional estimate: {{ $prospect->currency_symbol }}{{ number_format($prospect->estimated_cost, 2) }}).</li>
        @endif
        <li><strong>No Upfront Payment:</strong> Invoicing and deposit scheduling are only initiated after formal editorial review and mutual contract execution.</li>
    </ul>
    <p>We are excited to review your work!</p>
    <p>Warm Regards,<br><strong>Griots Lounge Editorial Board</strong><br><span style="font-size: 12px; color: #888;">Griots Lounge Publishing (Nigeria & Canada)</span></p>
@endsection

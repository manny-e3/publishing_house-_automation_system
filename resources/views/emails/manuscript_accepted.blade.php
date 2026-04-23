@extends('emails.layout')

@section('content')
    <h1 class="h1">Great News! Your Manuscript has been Accepted.</h1>
    <p>Hello {{ $prospect->name }},</p>
    <p>We are thrilled to inform you that our editorial team has accepted your manuscript, <strong>"{{ $prospect->book_title }}"</strong>, for publication!</p>
    <p>To move forward with the production and editing phase, we have attached your official invoice to this email. Once payment is confirmed, your project's status will be updated to "Active" and we will begin the typesetting and review process.</p>
    
    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $checkoutLink }}" class="btn">Proceed to Secure Payment</a>
    </div>

    <p style="margin-top: 30px;">If you have any questions before proceeding, please reply to this email.</p>
    <p>Welcome to the family!<br>The Curated Archive Team</p>
@endsection

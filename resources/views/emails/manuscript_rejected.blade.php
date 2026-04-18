@extends('emails.layout')

@section('content')
    <h1 class="h1">Manuscript Review Update</h1>
    <p>Hello {{ $prospect->name }},</p>
    <p>Thank you for giving us the opportunity to review your manuscript, <strong>"{{ $prospect->book_title }}"</strong>.</p>
    <p>After careful evaluation by our editorial team, we regret to inform you that we will not be moving forward with your project at this time. This decision is based on our current publication schedule and stylistic focus, and does not reflect on the quality of your work.</p>
    <p>We wish you the very best of luck with your writing journey and in finding the right home for your manuscript.</p>
    <p>Sincerely,<br>The Curated Archive Team</p>
@endsection

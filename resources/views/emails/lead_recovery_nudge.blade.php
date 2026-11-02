@extends('emails.layout')

@section('content')
<div style="font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; color: #1e293b; line-height: 1.6;">
    @if($tone === 'casual')
        <div style="display: inline-block; padding: 4px 12px; background-color: #fef3c7; color: #92400e; border-radius: 9999px; font-size: 12px; font-weight: 600; text-transform: uppercase; margin-bottom: 16px;">
            Your Manuscript Journey
        </div>
        <h2 style="font-size: 22px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 16px;">
            Hey {{ explode(' ', $prospect->name)[0] }}, don't leave your book on the shelf! ✨
        </h2>
        <p style="font-size: 15px; margin-bottom: 16px;">
            We noticed you took the first step in creating your publishing package for <strong>"{{ $prospect->book_title }}"</strong>. Every great story deserves an audience, and you're just one step away from turning that manuscript into a beautifully printed reality.
        </p>
        <p style="font-size: 15px; margin-bottom: 20px;">
            With our <strong>Osisi Hybrid</strong> model, you keep creative ownership while getting enterprise-grade cover design, interior typesetting, Amazon KDP distribution, and 500+ premium print copies.
        </p>
    @else
        <div style="display: inline-block; padding: 4px 12px; background-color: #e0f2fe; color: #0369a1; border-radius: 9999px; font-size: 12px; font-weight: 600; text-transform: uppercase; margin-bottom: 16px;">
            Publishing Proposal Follow-Up
        </div>
        <h2 style="font-size: 22px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 16px;">
            Follow-up Regarding Your Publishing Inquiry
        </h2>
        <p style="font-size: 15px; margin-bottom: 16px;">
            Dear {{ $prospect->name }},
        </p>
        <p style="font-size: 15px; margin-bottom: 16px;">
            Thank you for initiating a publishing proposal for <strong>"{{ $prospect->book_title }}"</strong> with The Curated Archive. We have cataloged your initial submission under our <strong>Osisi Hybrid</strong> publishing division.
        </p>
        <p style="font-size: 15px; margin-bottom: 20px;">
            Our editorial committee has reserved your production slot, encompassing three rounds of developmental copy-editing, direct ISBN registration, and global channel syndication across Amazon, Selar, and the African Book Collective.
        </p>
    @endif

    <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin-bottom: 24px;">
        <h4 style="margin-top: 0; margin-bottom: 12px; font-size: 14px; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b;">
            Project Overview
        </h4>
        <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
            <tr>
                <td style="padding: 6px 0; color: #64748b;">Book Title:</td>
                <td style="padding: 6px 0; font-weight: 600; color: #0f172a; text-align: right;">{{ $prospect->book_title }}</td>
            </tr>
            <tr>
                <td style="padding: 6px 0; color: #64748b;">Genre / Word Count:</td>
                <td style="padding: 6px 0; font-weight: 600; color: #0f172a; text-align: right;">{{ $prospect->genre }} ({{ number_format($prospect->number_of_words) }} words)</td>
            </tr>
            <tr>
                <td style="padding: 6px 0; color: #64748b;">Print Batch Quantity:</td>
                <td style="padding: 6px 0; font-weight: 600; color: #0f172a; text-align: right;">{{ number_format($prospect->requested_print_quantity ?: 500) }} copies</td>
            </tr>
            <tr>
                <td style="padding: 6px 0; color: #64748b;">Assigned Model:</td>
                <td style="padding: 6px 0; font-weight: 600; color: #047857; text-align: right;">Osisi Hybrid Publishing</td>
            </tr>
        </table>
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $resumeUrl }}" style="display: inline-block; background-color: #033d32; color: #ffffff; font-weight: 600; font-size: 16px; padding: 14px 32px; text-decoration: none; border-radius: 6px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
            Complete Your Publishing Submission &rarr;
        </a>
    </div>

    <p style="font-size: 13px; color: #64748b; text-align: center; margin-top: 24px;">
        Have questions or need assistance? Reply directly to this email and our editorial coordinators will assist you.
    </p>
</div>
@endsection


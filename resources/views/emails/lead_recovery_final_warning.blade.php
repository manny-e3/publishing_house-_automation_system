@extends('emails.layout')

@section('content')
<div style="font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; color: #1e293b; line-height: 1.6;">
    <div style="display: inline-block; padding: 4px 12px; background-color: #fee2e2; color: #991b1b; border-radius: 9999px; font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 16px;">
        ⚠️ Final 24-Hour Notice
    </div>

    <h2 style="font-size: 22px; font-weight: 700; color: #991b1b; margin-top: 0; margin-bottom: 16px;">
        Your Editorial Production Slot is Expiring
    </h2>

    <p style="font-size: 15px; margin-bottom: 16px;">
        Dear {{ $prospect->name }},
    </p>

    <p style="font-size: 15px; margin-bottom: 16px;">
        This is an urgent notice regarding your Osisi Hybrid publishing application for <strong>"{{ $prospect->book_title }}"</strong>.
    </p>

    <div style="background-color: #fff1f2; border-left: 4px solid #e11d48; padding: 16px; margin-bottom: 24px; border-radius: 0 6px 6px 0;">
        <p style="margin: 0; font-size: 14px; color: #881337; font-weight: 500;">
            Our press run schedule for this print cycle is reaching capacity. To maintain editorial turnaround timelines, unconfirmed author reservations are released back to the general registry after <strong>24 hours</strong>.
        </p>
    </div>

    <p style="font-size: 15px; margin-bottom: 20px;">
        By completing your initial payment now, you guarantee:
    </p>

    <ul style="font-size: 14px; color: #334155; margin-bottom: 24px; padding-left: 20px;">
        <li style="margin-bottom: 8px;"><strong>Reserved Editorial Review:</strong> Dedicated senior copy-editor assigned to your 3-pass revision cycle.</li>
        <li style="margin-bottom: 8px;"><strong>Locked-In Print Rates:</strong> Current bulk print quote honored regardless of paper market fluctuations.</li>
        <li style="margin-bottom: 8px;"><strong>Priority Global Distribution:</strong> Guaranteed placement on Amazon KDP, Selar, and African Book Collective catalogs.</li>
    </ul>

    <div style="text-align: center; margin: 32px 0;">
        <a href="{{ $resumeUrl }}" style="display: inline-block; background-color: #b91c1c; color: #ffffff; font-weight: 700; font-size: 16px; padding: 14px 36px; text-decoration: none; border-radius: 6px; box-shadow: 0 4px 6px -1px rgba(185, 28, 28, 0.3);">
            Secure Your Production Slot Now &rarr;
        </a>
    </div>

    <p style="font-size: 13px; color: #64748b; text-align: center; margin-top: 24px;">
        If you have decided not to proceed with this publication cycle, no action is needed; your reserved slot will automatically expire.
    </p>
</div>
@endsection


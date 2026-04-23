@extends('emails.layouts.master')

@section('header')
Payment Confirmation
@endsection

@section('content')
<p>Dear {{ $prospect->name }},</p>

<p>Thank you for your payment regarding your manuscript <strong>"{{ $prospect->book_title ?? 'Untitled' }}"</strong>.</p>

<p>Your payment has been successfully processed. Please find your official receipt attached to this email for your records.</p>

<p>If you have any questions about this transaction or the status of your project, you can log in to your Author Dashboard at any time to review your full payment history and project stage.</p>

<table style="width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px;">
    <tr>
        <td style="padding: 10px; border: 1px solid #e2e8f0; font-weight: bold; width: 30%;">Payment Reference</td>
        <td style="padding: 10px; border: 1px solid #e2e8f0;">{{ $invoice->payment_reference }}</td>
    </tr>
    <tr>
        <td style="padding: 10px; border: 1px solid #e2e8f0; font-weight: bold;">Amount Applied</td>
        <td style="padding: 10px; border: 1px solid #e2e8f0;">₦{{ number_format($invoice->total_paid, 2) }}</td>
    </tr>
</table>

<p style="margin-top: 30px; text-align: center;">
    <a href="{{ route('author.dashboard') }}" style="display: inline-block; padding: 12px 24px; background-color: #0f172a; color: #ffffff; text-decoration: none; border-radius: 4px; font-weight: bold;">GO TO DASHBOARD</a>
</p>

<p style="margin-top: 30px;">Best regards,<br>The Archive Team</p>
@endsection

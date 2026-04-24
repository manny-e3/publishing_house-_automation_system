<!DOCTYPE html>
<html>
<head>
    <title>Submission Agreement</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; line-height: 1.6; color: #333; }
        .header { text-align: center; margin-bottom: 50px; border-bottom: 2px solid #000; padding-bottom: 20px; }
        .title { font-size: 24px; font-weight: bold; text-transform: uppercase; }
        .section { margin-bottom: 30px; }
        .section-title { font-size: 18px; font-weight: bold; margin-bottom: 10px; border-bottom: 1px solid #ccc; }
        .footer { margin-top: 50px; font-size: 12px; border-top: 1px solid #ccc; padding-top: 10px; }
        .signature-box { margin-top: 40px; padding: 20px; border: 1px solid #eee; background: #f9f9f9; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Submission Agreement</div>
        <div>The Curated Archive Publishing House</div>
    </div>

    <div class="section">
        <p><strong>Date:</strong> {{ $date }}</p>
        <p><strong>Author:</strong> {{ $author }}</p>
        <p><strong>Manuscript Title:</strong> {{ $book_title }}</p>
    </div>

    <div class="section">
        <div class="section-title">1. Confidentiality</div>
        <p>We acknowledge that the manuscript submitted is the intellectual property of the Author and is confidential. The Curated Archive will not reproduce, distribute, or disclose the contents of the manuscript to third parties without the Author's explicit consent, except for internal evaluation purposes.</p>
    </div>

    <div class="section">
        <div class="section-title">2. Evaluation Purpose</div>
        <p>The manuscript is submitted solely for the purpose of evaluation by our editorial team. This submission does not constitute a guarantee of publication. A separate Publishing Agreement will be issued upon acceptance and project activation.</p>
    </div>

    <div class="section">
        <div class="section-title">3. Warranty</div>
        <p>The Author warrants that they are the sole creator and owner of the manuscript and that the work does not infringe upon any existing copyright or legal rights of others.</p>
    </div>

    <div class="signature-box">
        <div class="section-title">Digital Signature</div>
        <p><strong>Signed By:</strong> {{ $signature_name }}</p>
        <p><strong>IP Address:</strong> {{ $ip }}</p>
        <p><strong>Timestamp:</strong> {{ now()->format('Y-m-d H:i:s') }}</p>
        <p style="color: #666; font-style: italic;">This document is electronically signed and legally binding as a submission agreement.</p>
    </div>

    <div class="footer">
        © {{ date('Y') }} The Curated Archive Publishing House. All rights reserved.
    </div>
</body>
</html>

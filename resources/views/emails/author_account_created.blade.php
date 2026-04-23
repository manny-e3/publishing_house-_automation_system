<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Playfair Display', serif; line-height: 1.6; color: #101828; background: #F9FAFB; }
        .container { max-width: 600px; margin: 40px auto; padding: 40px; background: #ffffff; border-radius: 4px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { text-align: center; border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 30px; }
        .brand { font-size: 24px; font-weight: bold; font-style: italic; }
        .content { padding: 0 20px; }
        .credentials { background: #f3f4f6; padding: 20px; border-radius: 4px; font-family: 'Inter', sans-serif; margin: 30px 0; }
        .credential-item { margin-bottom: 10px; font-size: 14px; }
        .label { font-weight: bold; color: #6b7280; text-transform: uppercase; letter-spacing: 0.1em; font-size: 10px; display: block; }
        .value { font-family: monospace; font-size: 16px; color: #111827; }
        .button { display: inline-block; padding: 12px 24px; background: #0f172a; color: #ffffff !important; text-decoration: none; border-radius: 2px; font-weight: bold; font-size: 12px; letter-spacing: 0.1em; text-transform: uppercase; }
        .footer { margin-top: 40px; text-align: center; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="brand">The Archive</div>
        </div>
        <div class="content">
            <h2 style="font-size: 24px; margin-bottom: 20px;">Welcome to Your Personal Dashboard</h2>
            <p>Hello {{ $user->name }},</p>
            <p>Your publishing project has been successfully activated! To track your manuscript's progress, review editorial notes, and manage your production pipeline, we have created a secure account for you in <strong>The Archive</strong>.</p>
            
            <p>You can now access your "Enquiry Ledger" using the credentials below:</p>

            <div class="credentials">
                <div class="credential-item">
                    <span class="label">Access URL</span>
                    <span class="value">{{ route('login') }}</span>
                </div>
                <div class="credential-item">
                    <span class="label">Email Address</span>
                    <span class="value">{{ $user->email }}</span>
                </div>
                <div class="credential-item">
                    <span class="label">Temporary Password</span>
                    <span class="value">{{ $password }}</span>
                </div>
            </div>

            <p style="text-align: center; margin-top: 40px;">
                <a href="{{ route('login') }}" class="button">Access My Dashboard</a>
            </p>

            <p style="margin-top: 40px; font-size: 14px; color: #6b7280;"><em>For security reasons, we recommend changing your password immediately upon your first login.</em></p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} The Curated Archive Publishing House</p>
        </div>
    </div>
</body>
</html>

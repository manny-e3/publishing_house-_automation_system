<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Curated Archive</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f5f6fa; margin: 0; padding: 0; color: #526484; }
        .email-wrapper { width: 100%; padding: 40px 0; }
        .email-container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .email-header { background: #001f3f; padding: 30px; text-align: center; }
        .email-header h1 { color: #ffffff; margin: 0; font-family: 'Playfair Display', serif; font-size: 24px; }
        .email-body { padding: 40px; line-height: 1.6; }
        .email-footer { padding: 20px; text-align: center; font-size: 12px; color: #8091a7; border-top: 1px solid #e5e9f2; }
        .btn { display: inline-block; padding: 12px 30px; background-color: #eab308; color: #ffffff; text-decoration: none; border-radius: 4px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <div class="email-header">
                <h1>The Curated Archive</h1>
            </div>
            <div class="email-body">
                @yield('content')
            </div>
            <div class="email-footer">
                &copy; {{ date('Y') }} The Curated Archive. All rights reserved.<br>
                This is an automated security notification.
            </div>
        </div>
    </div>
</body>
</html>

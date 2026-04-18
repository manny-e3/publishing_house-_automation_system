<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Notification' }}</title>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f5f6fa; margin: 0; padding: 0; color: #526484; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f5f6fa; padding-bottom: 40px; }
        .main { background-color: #ffffff; width: 100%; max-width: 600px; margin: 0 auto; border-radius: 8px; border: 1px solid #dbdfea; overflow: hidden; margin-top: 40px; }
        .header { padding: 30px; text-align: center; background-color: #033d32; color: #ffffff; }
        .content { padding: 40px 30px; line-height: 1.6; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #8094ae; }
        .btn { display: inline-block; padding: 12px 30px; background-color: #3f51b5; color: #ffffff; text-decoration: none; border-radius: 4px; font-weight: 600; margin-top: 20px; }
        .h1 { font-size: 24px; font-weight: 700; color: #364a63; margin-bottom: 20px; }
        .text-soft { color: #8094ae; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="main">
            <div class="header">
                <span style="font-size: 1.5rem; font-weight: bold; font-family: serif;">THE CURATED ARCHIVE</span>
            </div>
            <div class="content">
                @yield('content')
            </div>
            <div class="footer">
                &copy; {{ date('Y') }} The Curated Archive. All rights reserved.<br>
                This is an automated system notification.
            </div>
        </div>
    </div>
</body>
</html>

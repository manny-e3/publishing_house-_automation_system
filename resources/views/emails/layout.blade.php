<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Notification' }}</title>
    <style>
        body { font-family: 'Jost', 'Inter', sans-serif; background-color: #FCFCFC; margin: 0; padding: 0; color: #526484; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #FCFCFC; padding-bottom: 40px; }
        .main { background-color: #ffffff; width: 100%; max-width: 600px; margin: 0 auto; border-radius: 8px; border: 1px solid #DCE5E0; overflow: hidden; margin-top: 40px; }
        .header { padding: 30px; text-align: center; background-color: #00382B; color: #ffffff; border-bottom: 3px solid #96BBA4; }
        .content { padding: 40px 30px; line-height: 1.6; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #8094ae; }
        .btn { display: inline-block; padding: 12px 30px; background-color: #00382B; color: #ffffff; text-decoration: none; border-radius: 4px; font-weight: 600; margin-top: 20px; }
        .h1 { font-size: 24px; font-weight: 700; color: #00382B; margin-bottom: 20px; font-family: 'EB Garamond', Georgia, serif; }
        .text-soft { color: #8094ae; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="main">
            <div class="header">
                <span style="font-size: 1.5rem; font-weight: bold; font-family: serif; letter-spacing: 1px;">GRIOTS LOUNGE PUBLISHING</span>
            </div>
            <div class="content">
                @yield('content')
            </div>
            <div class="footer">
                &copy; {{ date('Y') }} Griots Lounge Publishing (Nigeria & Canada). All rights reserved.<br>
                This is an automated system notification from the Griots Lounge Editorial Desk.
            </div>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e1e1e1; border-radius: 10px; }
        .header { text-align: center; border-bottom: 2px solid #798bff; padding-bottom: 10px; margin-bottom: 20px; }
        .status-badge { display: inline-block; padding: 5px 15px; background: #798bff; color: white; border-radius: 20px; font-weight: bold; text-transform: uppercase; font-size: 12px; }
        .footer { margin-top: 30px; font-size: 12px; color: #777; text-align: center; }
        .button { display: inline-block; padding: 10px 20px; background-color: #798bff; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="color: #798bff; margin-bottom: 5px;">The Curated Archive</h2>
            <p style="margin-top: 0; font-style: italic;">Where every story matters.</p>
        </div>
        
        <h3>Hello {{ $project->prospect->name }},</h3>
        
        <p>We are writing to provide you with a real-time update on your publishing journey for <strong>"{{ $project->prospect->book_title }}"</strong>.</p>
        
        <div style="margin: 20px 0;">
            <span class="status-badge">{{ $statusName }}</span>
        </div>

        <p>{!! nl2br(e($messageBody)) !!}</p>

        <p>You can track further progress or get in touch with your assigned editor by replying to this email.</p>

        <p>Thank you for choosing The Curated Archive.</p>
        
        <div class="footer">
            &copy; {{ date('Y') }} The Curated Archive Publishing House. <br>
            <em>This is an automated notification from your Project Dashboard.</em>
        </div>
    </div>
</body>
</html>

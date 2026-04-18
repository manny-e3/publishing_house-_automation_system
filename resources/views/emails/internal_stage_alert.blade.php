<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; }
        .header { background: #0B1120; color: #fff; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .details { margin-top: 20px; background: #fffbeb; padding: 15px; border-radius: 5px; border-left: 4px solid #d97706; }
        .footer { margin-top: 20px; font-size: 12px; color: #777; text-align: center; }
        .button { display: inline-block; padding: 10px 20px; background: #d97706; color: #fff; text-decoration: none; border-radius: 3px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pipeline Stage Alert</h1>
        </div>
        <div class="content">
            <p>Hello Team,</p>
            <p>A project has moved to a new stage in the production pipeline:</p>
            
            <div class="details">
                <p><strong>Stage:</strong> {{ ucwords(str_replace('_', ' ', $stage)) }}</p>
                <p><strong>Project:</strong> {{ $project->prospect->book_title }}</p>
                <p><strong>Author:</strong> {{ $project->prospect->name }}</p>
                <p><strong>Action Required:</strong> {{ $actionRequired }}</p>
            </div>

            <p style="margin-top: 30px; text-align: center;">
                <a href="{{ route('admin.projects.show', $project->id) }}" class="button">View Project Details</a>
            </p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} The Curated Archive Publishing House</p>
        </div>
    </div>
</body>
</html>

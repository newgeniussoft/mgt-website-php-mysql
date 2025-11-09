<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview: {{ $template->name }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        .preview-header {
            background: #2c3e50;
            color: white;
            padding: 1rem;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .preview-content {
            margin-top: 60px;
        }
        .preview-badge {
            background: #9b59b6;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="preview-header">
        <div>
            <span class="preview-badge">TEMPLATE PREVIEW</span>
            <strong style="margin-left: 1rem;">{{ $template->name }}</strong>
        </div>
        <div>
            <a href="{{ admin_url('templates/edit?id=' . $template->id) }}" style="color: white; text-decoration: none; margin-right: 1rem;">
                ← Back to Edit
            </a>
        </div>
    </div>
    
    <div class="preview-content">
        {!! $html !!}
    </div>
</body>
</html>

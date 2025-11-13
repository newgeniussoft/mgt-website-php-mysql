<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview: {{ $page->title }}</title>
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
            background: #e74c3c;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="preview-header">
        <div>
            <span class="preview-badge">PREVIEW MODE</span>
            <strong style="margin-left: 1rem;">{{ $page->title }}</strong>
        </div>
        <div>
            <a href="{{ admin_url('pages/edit?id=' . $page->id) }}" style="color: white; text-decoration: none; margin-right: 1rem;">
                ← Back to Edit
            </a>
        </div>
    </div>
    
    <div class="preview-content">
        @if($template)
            @php
                // Render template with page data
                $menuPages = \App\Models\Page::getMenuPages();
                $menuHtml = '';
                foreach ($menuPages as $menuPage) {
                    $menuHtml .= '<li><a href="/' . $menuPage->slug . '">' . $menuPage->title . '</a></li>';
                }
                
                // Render sections
                $sectionsHtml = '';
                foreach ($sections as $section) {
                    $sectionsHtml .= $section->render();
                }
                
                $variables = [
                    'page_title' => $page->title,
                    'meta_description' => $page->meta_description,
                    'meta_keywords' => $page->meta_keywords,
                    'site_name' => $_ENV['APP_NAME'] ?? 'My Website',
                    'menu_items' => $menuHtml,
                    'page_sections' => $sectionsHtml,
                    'custom_css' => '',
                    'custom_js' => ''
                ];
                
                echo $template->render($variables);
            @endphp
        @else
            <div style="padding: 2rem; text-align: center;">
                <h1>{{ $page->title }}</h1>
                <p>No template assigned</p>
                
                @if(!empty($sections))
                    <div style="max-width: 1200px; margin: 2rem auto;">
                        @foreach($sections as $section)
                            {!! $section->render() !!}
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>
</body>
</html>

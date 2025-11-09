@extends('layouts.admin')

@section('content')
<style>
    .editor-container {
        border: 1px solid #ddd;
        border-radius: 4px;
        overflow: hidden;
    }
    #htmlEditor, #cssEditor, #jsEditor {
        height: 500px;
        width: 100%;
    }
    .nav-tabs .nav-link.active {
        background-color: #007bff;
        color: white;
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Create New Template</h1>
        <a href="{{ admin_url('templates') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Templates
        </a>
    </div>

    @if(isset($_SESSION['error']))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ $_SESSION['error'] }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php unset($_SESSION['error']); ?>
    @endif

    <form method="POST" action="{{ admin_url('templates/store') }}" enctype="multipart/form-data" id="templateForm">
        <input type="hidden" name="csrf_token" value="{{ csrf_token() }}">
        <input type="hidden" name="html_content" id="html_content">
        <input type="hidden" name="css_content" id="css_content">
        <input type="hidden" name="js_content" id="js_content">
        
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Template Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Template Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug" placeholder="Auto-generated from name">
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#htmlTab" role="tab">
                                    <i class="fab fa-html5"></i> HTML
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#cssTab" role="tab">
                                    <i class="fab fa-css3-alt"></i> CSS
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#jsTab" role="tab">
                                    <i class="fab fa-js"></i> JavaScript
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body p-0">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="htmlTab" role="tabpanel">
                                <div class="editor-container">
                                    <div id="htmlEditor"></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="cssTab" role="tabpanel">
                                <div class="editor-container">
                                    <div id="cssEditor"></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="jsTab" role="tabpanel">
                                <div class="editor-container">
                                    <div id="jsEditor"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="thumbnail">Thumbnail</label>
                            <input type="file" class="form-control-file" id="thumbnail" name="thumbnail" accept="image/*">
                        </div>

                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_default" name="is_default">
                            <label class="custom-control-label" for="is_default">Set as Default Template</label>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Available Variables</h5>
                    </div>
                    <div class="card-body">
                        <small class="text-muted">Use these variables in your template:</small>
                        <ul class="list-unstyled mt-2" style="font-size: 12px;">
                            <li><code>@verbatim{{ page_title }}@endverbatim</code></li>
                            <li><code>@verbatim{{ meta_description }}@endverbatim</code></li>
                            <li><code>@verbatim{{ meta_keywords }}@endverbatim</code></li>
                            <li><code>@verbatim{{ site_name }}@endverbatim</code></li>
                            <li><code>@verbatim{{ menu_items }}@endverbatim</code></li>
                            <li><code>@verbatim{{ page_sections }}@endverbatim</code></li>
                            <li><code>@verbatim{{ custom_css }}@endverbatim</code></li>
                            <li><code>@verbatim{{ custom_js }}@endverbatim</code></li>
                        </ul>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-save"></i> Create Template
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Monaco Editor -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs/loader.min.js"></script>
<script>
require.config({ paths: { vs: 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs' } });

let htmlEditor, cssEditor, jsEditor;

require(['vs/editor/editor.main'], function () {
    // HTML Editor
    htmlEditor = monaco.editor.create(document.getElementById('htmlEditor'), {
        value: `<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@verbatim{{ page_title }}@endverbatim</title>
    <meta name="description" content="@verbatim{{ meta_description }}@endverbatim">
    @verbatim{{ custom_css }}@endverbatim
</head>
<body>
    <header>
        <h1>@verbatim{{ site_name }}@endverbatim</h1>
        <nav>@verbatim{{ menu_items }}@endverbatim</nav>
    </header>
    
    <main>
        <h1>@verbatim{{ page_title }}@endverbatim</h1>
        <div class="content">
            @verbatim{{ page_sections }}@endverbatim
        </div>
    </main>
    
    <footer>
        <p>&copy; 2024 @verbatim{{ site_name }}@endverbatim</p>
    </footer>
    
    @verbatim{{ custom_js }}@endverbatim
</body>
</html>`,
        language: 'html',
        theme: 'vs-dark',
        automaticLayout: true,
        minimap: { enabled: true }
    });

    // CSS Editor
    cssEditor = monaco.editor.create(document.getElementById('cssEditor'), {
        value: `body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

header {
    background: #333;
    color: white;
    padding: 1rem;
}

main {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1rem;
}

footer {
    background: #f5f5f5;
    padding: 2rem;
    text-align: center;
    margin-top: 3rem;
}`,
        language: 'css',
        theme: 'vs-dark',
        automaticLayout: true,
        minimap: { enabled: true }
    });

    // JavaScript Editor
    jsEditor = monaco.editor.create(document.getElementById('jsEditor'), {
        value: `// Custom JavaScript
console.log('Template loaded');

document.addEventListener('DOMContentLoaded', function() {
    // Your code here
});`,
        language: 'javascript',
        theme: 'vs-dark',
        automaticLayout: true,
        minimap: { enabled: true }
    });
});

// Auto-generate slug
document.getElementById('name').addEventListener('input', function() {
    if (!document.getElementById('slug').value) {
        const slug = this.value
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
        document.getElementById('slug').value = slug;
    }
});

// Form submission
document.getElementById('templateForm').addEventListener('submit', function(e) {
    document.getElementById('html_content').value = htmlEditor.getValue();
    document.getElementById('css_content').value = cssEditor.getValue();
    document.getElementById('js_content').value = jsEditor.getValue();
});
</script>
@endsection

@extends('layouts.admin')

@section('content')
<style>
    .editor-container {
        border: 1px solid #ddd;
        border-radius: 4px;
        overflow: hidden;
    }
    #htmlEditor, #cssEditor, #jsEditor {
        height: 400px;
        width: 100%;
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Create Section - {{ $page->title }}</h1>
        <a href="{{ admin_url('sections?page_id=' . $page->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Sections
        </a>
    </div>

    @if(isset($_SESSION['error']))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ $_SESSION['error'] }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php unset($_SESSION['error']); ?>
    @endif

    <form method="POST" action="{{ admin_url('sections/store') }}" id="sectionForm">
        <input type="hidden" name="csrf_token" value="{{ csrf_token() }}">
        <input type="hidden" name="page_id" value="{{ $page->id }}">
        <input type="hidden" name="html_template" id="html_template">
        <input type="hidden" name="css_styles" id="css_styles">
        <input type="hidden" name="js_scripts" id="js_scripts">
        
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Section Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Section Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug" placeholder="Auto-generated from name">
                        </div>

                        <div class="form-group">
                            <label for="type">Section Type</label>
                            <select class="form-control" id="type" name="type">
                                <option value="content">Content</option>
                                <option value="hero">Hero</option>
                                <option value="features">Features</option>
                                <option value="gallery">Gallery</option>
                                <option value="testimonials">Testimonials</option>
                                <option value="contact">Contact</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#htmlTab" role="tab">
                                    <i class="fab fa-html5"></i> HTML Template
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
                            <label for="settings">Settings (JSON)</label>
                            <textarea class="form-control" id="settings" name="settings" rows="5" placeholder='{"key": "value"}'>{}</textarea>
                            <small class="form-text text-muted">Optional JSON settings for this section</small>
                        </div>

                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" checked>
                            <label class="custom-control-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Available Variables</h5>
                    </div>
                    <div class="card-body">
                        <small class="text-muted">Use in HTML template:</small>
                        <ul class="list-unstyled mt-2" style="font-size: 12px;">
                            <li><code>@verbatim{{ content }}@endverbatim</code> - Section content</li>
                        </ul>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-save"></i> Create Section
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
    htmlEditor = monaco.editor.create(document.getElementById('htmlEditor'), {
        value: `<div class="section-wrapper">
    <div class="container">
        @verbatim{{ content }}@endverbatim
    </div>
</div>`,
        language: 'html',
        theme: 'vs-dark',
        automaticLayout: true,
        minimap: { enabled: false }
    });

    cssEditor = monaco.editor.create(document.getElementById('cssEditor'), {
        value: `.section-wrapper {
    padding: 3rem 0;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}`,
        language: 'css',
        theme: 'vs-dark',
        automaticLayout: true,
        minimap: { enabled: false }
    });

    jsEditor = monaco.editor.create(document.getElementById('jsEditor'), {
        value: `// Section JavaScript
console.log('Section loaded');`,
        language: 'javascript',
        theme: 'vs-dark',
        automaticLayout: true,
        minimap: { enabled: false }
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
document.getElementById('sectionForm').addEventListener('submit', function(e) {
    document.getElementById('html_template').value = htmlEditor.getValue();
    document.getElementById('css_styles').value = cssEditor.getValue();
    document.getElementById('js_scripts').value = jsEditor.getValue();
});
</script>
@endsection

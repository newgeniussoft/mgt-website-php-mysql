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
        <h1 class="h3 mb-0">Edit Template: {{ $template->name }}</h1>
        <a href="{{ admin_url('templates') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Templates
        </a>
    </div>

    @if(isset($_SESSION['success']))
        <div class="alert alert-success alert-dismissible fade show">
            {{ $_SESSION['success'] }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php unset($_SESSION['success']); ?>
    @endif

    @if(isset($_SESSION['error']))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ $_SESSION['error'] }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php unset($_SESSION['error']); ?>
    @endif

    <form method="POST" action="{{ admin_url('templates/update') }}" enctype="multipart/form-data" id="templateForm">
        <input type="hidden" name="csrf_token" value="{{ csrf_token() }}">
        <input type="hidden" name="id" value="{{ $template->id }}">
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
                            <input type="text" class="form-control" id="name" name="name" value="{{ $template->name }}" required>
                        </div>

                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug" value="{{ $template->slug }}">
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ $template->description }}</textarea>
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
                                <option value="active" {{ $template->status === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $template->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="thumbnail">Thumbnail</label>
                            @if($template->thumbnail)
                                <div class="mb-2">
                                    <img src="{{ $template->thumbnail }}" alt="Thumbnail" class="img-fluid" style="max-height: 150px;">
                                </div>
                            @endif
                            <input type="file" class="form-control-file" id="thumbnail" name="thumbnail" accept="image/*">
                        </div>

                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_default" name="is_default" {{ $template->is_default ? 'checked' : '' }}>
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

                <button type="submit" class="btn btn-primary btn-block mb-2">
                    <i class="fas fa-save"></i> Update Template
                </button>
                
                <a href="{{ admin_url('templates/preview?id=' . $template->id) }}" class="btn btn-secondary btn-block" target="_blank">
                    <i class="fas fa-eye"></i> Preview Template
                </a>
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
        value: <?php echo json_encode($template->html_content ?? ''); ?>,
        language: 'html',
        theme: 'vs-dark',
        automaticLayout: true,
        minimap: { enabled: true }
    });

    // CSS Editor
    cssEditor = monaco.editor.create(document.getElementById('cssEditor'), {
        value: <?php echo json_encode($template->css_content ?? ''); ?>,
        language: 'css',
        theme: 'vs-dark',
        automaticLayout: true,
        minimap: { enabled: true }
    });

    // JavaScript Editor
    jsEditor = monaco.editor.create(document.getElementById('jsEditor'), {
        value: <?php echo json_encode($template->js_content ?? ''); ?>,
        language: 'javascript',
        theme: 'vs-dark',
        automaticLayout: true,
        minimap: { enabled: true }
    });
});

// Form submission
document.getElementById('templateForm').addEventListener('submit', function(e) {
    document.getElementById('html_content').value = htmlEditor.getValue();
    document.getElementById('css_content').value = cssEditor.getValue();
    document.getElementById('js_content').value = jsEditor.getValue();
});
</script>
@endsection

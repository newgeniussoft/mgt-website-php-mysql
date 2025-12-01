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

<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Edit Section: {{ $section->name }}</h1>
        <div>
            <a href="{{ admin_url('sections/add-content?section_id=' . $section->id) }}" class="btn btn-success mr-2">
                <i class="fas fa-plus"></i> Add Content
            </a>
            <a href="{{ admin_url('sections?page_id=' . $page->id) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Sections
            </a>
        </div>
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

    <form method="POST" action="{{ admin_url('sections/update') }}" id="sectionForm">
        <input type="hidden" name="csrf_token" value="{{ csrf_token() }}">
        <input type="hidden" name="id" value="{{ $section->id }}">
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
                            <input type="text" class="form-control" id="name" name="name" value="{{ $section->name }}" required>
                        </div>

                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug" value="{{ $section->slug }}">
                        </div>

                        <div class="form-group">
                            <label for="type">Section Type</label>
                            <select class="form-control" id="type" name="type">
                                <option value="content" {{ $section->type === 'content' ? 'selected' : '' }}>Content</option>
                                <option value="hero" {{ $section->type === 'hero' ? 'selected' : '' }}>Hero</option>
                                <option value="features" {{ $section->type === 'features' ? 'selected' : '' }}>Features</option>
                                <option value="gallery" {{ $section->type === 'gallery' ? 'selected' : '' }}>Gallery</option>
                                <option value="testimonials" {{ $section->type === 'testimonials' ? 'selected' : '' }}>Testimonials</option>
                                <option value="contact" {{ $section->type === 'contact' ? 'selected' : '' }}>Contact</option>
                                <option value="custom" {{ $section->type === 'custom' ? 'selected' : '' }}>Custom</option>
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

                @if(!empty($contents))
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Section Contents ({{ count($contents) }})</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                @foreach($contents as $content)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $content->title ?: 'Untitled' }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                Language: {{ strtoupper($content->language) }} | 
                                                Type: {{ $content->content_type }}
                                            </small>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ admin_url('sections/edit-content?id=' . $content->id) }}" class="btn btn-primary">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <button type="button" class="btn btn-danger" onclick="deleteContent({{ $content->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="settings">Settings (JSON)</label>
                            <textarea class="form-control" id="settings" name="settings" rows="5">{{ $section->settings ?: '{}' }}</textarea>
                            <small class="form-text text-muted">Optional JSON settings for this section</small>
                        </div>

                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" {{ $section->is_active ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-save"></i> Update Section
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Delete Content Form -->
<form id="deleteContentForm" method="POST" action="{{ admin_url('sections/delete-content') }}" style="display: none;">
    <input type="hidden" name="csrf_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" id="deleteContentId">
</form>

<!-- Monaco Editor -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs/loader.min.js"></script>
<script>
require.config({ paths: { vs: 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs' } });

let htmlEditor, cssEditor, jsEditor;

require(['vs/editor/editor.main'], function () {
    htmlEditor = monaco.editor.create(document.getElementById('htmlEditor'), {
        value: <?php echo json_encode($section->html_template ?? ''); ?>,
        language: 'html',
        theme: 'vs-dark',
        automaticLayout: true,
        minimap: { enabled: false }
    });

    cssEditor = monaco.editor.create(document.getElementById('cssEditor'), {
        value: <?php echo json_encode($section->css_styles ?? ''); ?>,
        language: 'css',
        theme: 'vs-dark',
        automaticLayout: true,
        minimap: { enabled: false }
    });

    jsEditor = monaco.editor.create(document.getElementById('jsEditor'), {
        value: <?php echo json_encode($section->js_scripts ?? ''); ?>,
        language: 'javascript',
        theme: 'vs-dark',
        automaticLayout: true,
        minimap: { enabled: false }
    });
});

// Form submission
document.getElementById('sectionForm').addEventListener('submit', function(e) {
    document.getElementById('html_template').value = htmlEditor.getValue();
    document.getElementById('css_styles').value = cssEditor.getValue();
    document.getElementById('js_scripts').value = jsEditor.getValue();
});

function deleteContent(id) {
    if (confirm('Are you sure you want to delete this content?')) {
        document.getElementById('deleteContentId').value = id;
        document.getElementById('deleteContentForm').submit();
    }
}
</script>
@endsection

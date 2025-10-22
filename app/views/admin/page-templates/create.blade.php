@extends('admin.layout')

@section('title', 'Create Page Template')

@section('head')
<!-- CodeMirror CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/monokai.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/material.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/dracula.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/fold/foldgutter.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/hint/show-hint.css">

<style>
.CodeMirror {
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    height: 400px;
}
.CodeMirror-fullscreen {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    height: auto;
    z-index: 9999;
}
.editor-toolbar {
    background: #f8f9fa;
    border: 1px solid #ddd;
    border-bottom: none;
    border-radius: 4px 4px 0 0;
    padding: 8px 12px;
}
.editor-toolbar .btn {
    padding: 4px 8px;
    margin-right: 5px;
    font-size: 12px;
}
.variables-panel {
    max-height: 300px;
    overflow-y: auto;
}
.variable-item {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 8px 12px;
    margin-bottom: 8px;
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-plus-circle me-2"></i>Create Page Template
                </h1>
                <a href="/admin/page-templates" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Templates
                </a>
            </div>

            @if(isset($_SESSION['error']))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ $_SESSION['error'] }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @php unset($_SESSION['error']); @endphp
            @endif

            <form method="POST" action="/admin/page-templates/store" enctype="multipart/form-data">
                <div class="row">
                    <!-- Template Settings -->
                    <div class="col-lg-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-cog me-2"></i>Template Settings
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Template Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>

                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug</label>
                                    <input type="text" class="form-control" id="slug" name="slug" 
                                           placeholder="auto-generated">
                                    <small class="text-muted">Leave empty to auto-generate from name</small>
                                </div>

                                <div class="mb-3">
                                    <label for="template_type" class="form-label">Template Type</label>
                                    <select class="form-select" id="template_type" name="template_type">
                                        <option value="page">Page Template</option>
                                        <option value="layout">Layout Template</option>
                                        <option value="section">Section Template</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="thumbnail" class="form-label">Thumbnail</label>
                                    <input type="file" class="form-control" id="thumbnail" name="thumbnail" 
                                           accept="image/*">
                                    <small class="text-muted">Upload a preview image (optional)</small>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                    <label class="form-check-label" for="is_active">
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Template Variables -->
                        <div class="card shadow">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-tags me-2"></i>Template Variables
                                </h6>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="extractVariables()">
                                    <i class="fas fa-sync me-1"></i>Extract
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="variablesPanel" class="variables-panel">
                                    <p class="text-muted">Variables will appear here when you extract them from your template.</p>
                                </div>
                                <textarea id="variables" name="variables" style="display: none;">{}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Template Editor -->
                    <div class="col-lg-8">
                        <!-- HTML Template -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fab fa-html5 me-2"></i>HTML Template
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="editor-toolbar">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleFullscreen('htmlEditor')">
                                        <i class="fas fa-expand"></i> Fullscreen
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatCode('htmlEditor')">
                                        <i class="fas fa-code"></i> Format
                                    </button>
                                    <select class="form-select form-select-sm d-inline-block w-auto" onchange="changeTheme(this.value)">
                                        <option value="default">Default Theme</option>
                                        <option value="monokai">Monokai</option>
                                        <option value="material">Material</option>
                                        <option value="dracula">Dracula</option>
                                    </select>
                                </div>
                                <textarea id="htmlTemplate" name="html_template" required></textarea>
                            </div>
                        </div>

                        <!-- CSS Styles -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fab fa-css3-alt me-2"></i>CSS Styles
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="editor-toolbar">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleFullscreen('cssEditor')">
                                        <i class="fas fa-expand"></i> Fullscreen
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatCode('cssEditor')">
                                        <i class="fas fa-code"></i> Format
                                    </button>
                                </div>
                                <textarea id="cssStyles" name="css_styles"></textarea>
                            </div>
                        </div>

                        <!-- JavaScript -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fab fa-js-square me-2"></i>JavaScript
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="editor-toolbar">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleFullscreen('jsEditor')">
                                        <i class="fas fa-expand"></i> Fullscreen
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatCode('jsEditor')">
                                        <i class="fas fa-code"></i> Format
                                    </button>
                                </div>
                                <textarea id="jsScripts" name="js_scripts"></textarea>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <a href="/admin/page-templates" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </a>
                                    <div>
                                        <button type="button" class="btn btn-info me-2" onclick="previewTemplate()">
                                            <i class="fas fa-eye me-2"></i>Preview
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Create Template
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Template Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="previewFrame" style="width: 100%; height: 600px; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- CodeMirror JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/xml/xml.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/css/css.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/javascript/javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/htmlmixed/htmlmixed.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/fold/foldcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/fold/foldgutter.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/fold/xml-fold.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/closetag.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/closebrackets.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/hint/show-hint.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/hint/html-hint.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/hint/css-hint.min.js"></script>

<script>
let htmlEditor, cssEditor, jsEditor;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize CodeMirror editors
    htmlEditor = CodeMirror.fromTextArea(document.getElementById('htmlTemplate'), {
        mode: 'htmlmixed',
        theme: 'default',
        lineNumbers: true,
        lineWrapping: true,
        autoCloseTags: true,
        autoCloseBrackets: true,
        foldGutter: true,
        gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
        extraKeys: {
            "Ctrl-Space": "autocomplete",
            "F11": function(cm) { toggleFullscreen('htmlEditor'); },
            "Esc": function(cm) { if (cm.getOption("fullScreen")) toggleFullscreen('htmlEditor'); }
        }
    });

    cssEditor = CodeMirror.fromTextArea(document.getElementById('cssStyles'), {
        mode: 'css',
        theme: 'default',
        lineNumbers: true,
        lineWrapping: true,
        autoCloseBrackets: true,
        foldGutter: true,
        gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
        extraKeys: {
            "Ctrl-Space": "autocomplete",
            "F11": function(cm) { toggleFullscreen('cssEditor'); },
            "Esc": function(cm) { if (cm.getOption("fullScreen")) toggleFullscreen('cssEditor'); }
        }
    });

    jsEditor = CodeMirror.fromTextArea(document.getElementById('jsScripts'), {
        mode: 'javascript',
        theme: 'default',
        lineNumbers: true,
        lineWrapping: true,
        autoCloseBrackets: true,
        foldGutter: true,
        gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
        extraKeys: {
            "Ctrl-Space": "autocomplete",
            "F11": function(cm) { toggleFullscreen('jsEditor'); },
            "Esc": function(cm) { if (cm.getOption("fullScreen")) toggleFullscreen('jsEditor'); }
        }
    });

    // Auto-generate slug from name
    document.getElementById('name').addEventListener('input', function() {
        const slug = this.value.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
        document.getElementById('slug').value = slug;
    });

    // Set default template
    htmlEditor.setValue(`<!DOCTYPE html>
<html lang="{{ language|en }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ title }} - {{ site_name }}</title>
    <meta name="description" content="{{ meta_description }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>{{ custom_css }}</style>
</head>
<body>
    <main class="container my-5">
        <h1>{{ title }}</h1>
        @if(excerpt)
            <p class="lead">{{ excerpt }}</p>
        @endif
        
        @if(use_sections)
            {{ sections_html }}
        @else
            <div class="content">
                {{ content }}
            </div>
        @endif
    </main>
    
    <script>{{ custom_js }}</script>
</body>
</html>`);

    cssEditor.setValue(`/* Template Styles */
body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    line-height: 1.6;
    color: {{ text_color }};
}

h1, h2, h3, h4, h5, h6 {
    color: {{ heading_color }};
    font-weight: 600;
}

.content {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}`);

    jsEditor.setValue(`// Template JavaScript
document.addEventListener('DOMContentLoaded', function() {
    console.log('Template loaded successfully');
    
    // Add your custom JavaScript here
});`);
});

function toggleFullscreen(editorName) {
    const editor = window[editorName];
    editor.setOption("fullScreen", !editor.getOption("fullScreen"));
}

function formatCode(editorName) {
    const editor = window[editorName];
    const totalLines = editor.lineCount();
    editor.autoFormatRange({line: 0, ch: 0}, {line: totalLines});
}

function changeTheme(theme) {
    htmlEditor.setOption("theme", theme);
    cssEditor.setOption("theme", theme);
    jsEditor.setOption("theme", theme);
}

function extractVariables() {
    const htmlContent = htmlEditor.getValue();
    const cssContent = cssEditor.getValue();
    const jsContent = jsEditor.getValue();
    
    const formData = new FormData();
    formData.append('html_template', htmlContent);
    formData.append('css_styles', cssContent);
    formData.append('js_scripts', jsContent);
    
    fetch('/admin/page-templates/extract-variables', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        displayVariables(data.variables);
    })
    .catch(error => {
        console.error('Error extracting variables:', error);
    });
}

function displayVariables(variables) {
    const panel = document.getElementById('variablesPanel');
    const variablesInput = document.getElementById('variables');
    
    if (Object.keys(variables).length === 0) {
        panel.innerHTML = '<p class="text-muted">No variables found in template.</p>';
        variablesInput.value = '{}';
        return;
    }
    
    let html = '';
    for (const [key, defaultValue] of Object.entries(variables)) {
        html += `
            <div class="variable-item">
                <div class="d-flex justify-content-between align-items-center">
                    <strong>{{ ${key} }}</strong>
                    <input type="text" class="form-control form-control-sm w-50" 
                           value="${defaultValue}" 
                           onchange="updateVariable('${key}', this.value)"
                           placeholder="Default value">
                </div>
            </div>
        `;
    }
    
    panel.innerHTML = html;
    variablesInput.value = JSON.stringify(variables, null, 2);
}

function updateVariable(key, value) {
    const variablesInput = document.getElementById('variables');
    const variables = JSON.parse(variablesInput.value || '{}');
    variables[key] = value;
    variablesInput.value = JSON.stringify(variables, null, 2);
}

function previewTemplate() {
    // Create a form with template data and submit to preview
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/admin/page-templates/preview-temp';
    form.target = 'previewFrame';
    
    const fields = ['name', 'html_template', 'css_styles', 'js_scripts', 'variables'];
    fields.forEach(field => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = field;
        
        if (field === 'html_template') {
            input.value = htmlEditor.getValue();
        } else if (field === 'css_styles') {
            input.value = cssEditor.getValue();
        } else if (field === 'js_scripts') {
            input.value = jsEditor.getValue();
        } else {
            const element = document.getElementById(field) || document.querySelector(`[name="${field}"]`);
            input.value = element ? element.value : '';
        }
        
        form.appendChild(input);
    });
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
    
    new bootstrap.Modal(document.getElementById('previewModal')).show();
}
</script>
@endsection

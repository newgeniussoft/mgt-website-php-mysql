<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? '', ENT_QUOTES, 'UTF-8'); ?> - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- CodeMirror CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/monokai.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/eclipse.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/hint/show-hint.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/fold/foldgutter.min.css">
    
    <style>
        .CodeMirror {
            border: 1px solid #ddd;
            border-radius: 0.375rem;
            font-size: 14px;
            height: 400px;
        }
        .editor-toolbar {
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-bottom: none;
            border-radius: 0.375rem 0.375rem 0 0;
            padding: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .editor-toolbar .btn-group .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        .template-variables {
            background: #e3f2fd;
            border: 1px solid #90caf9;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        .variable-tag {
            background: #1976d2;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            margin: 0.125rem;
            display: inline-block;
            cursor: pointer;
        }
        .variable-tag:hover {
            background: #1565c0;
        }
        .preview-container {
            border: 1px solid #ddd;
            border-radius: 0.375rem;
            min-height: 300px;
            background: white;
            padding: 1rem;
        }
        .tab-content {
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 0.375rem 0.375rem;
            padding: 0;
        }
        .nav-tabs .nav-link {
            border-radius: 0;
        }
        .nav-tabs .nav-link.active {
            background: #f8f9fa;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/admin/dashboard">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/admin/pages">
                                <i class="fas fa-file-alt me-2"></i>Pages
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white active" href="/admin/layouts">
                                <i class="fas fa-th-large me-2"></i>Layouts
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><?php echo htmlspecialchars($title ?? '', ENT_QUOTES, 'UTF-8'); ?></h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="/admin/layouts" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-arrow-left me-2"></i>Back to Layouts
                        </a>
                    </div>
                </div>

                <!-- Flash Messages -->
                <?php if(isset($_SESSION['error']): ?>)
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($_SESSION['error'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <form method="POST" action="/admin/layouts/store" enctype="multipart/form-data">
                    <div class="row">
                        <!-- Left Column - Form Fields -->
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Layout Settings</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Layout Name *</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="slug" class="form-label">Slug</label>
                                        <input type="text" class="form-control" id="slug" name="slug" 
                                               placeholder="Auto-generated from name">
                                        <div class="form-text">Leave empty to auto-generate from name</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="thumbnail" class="form-label">Thumbnail</label>
                                        <input type="file" class="form-control" id="thumbnail" name="thumbnail" 
                                               accept="image/*">
                                        <div class="form-text">Upload a preview image for this layout</div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_active" 
                                                   name="is_active" checked>
                                            <label class="form-check-label" for="is_active">
                                                Active
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Template Variables Reference -->
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Available Variables</h6>
                                </div>
                                <div class="card-body">
                                    <div class="template-variables">
                                        <p class="small mb-2">Click to insert into template:</p>
                                        <span class="variable-tag" onclick="insertVariable('title')"><?php echo htmlspecialchars(title ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                                        <span class="variable-tag" onclick="insertVariable('excerpt')"><?php echo htmlspecialchars(excerpt ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                                        <span class="variable-tag" onclick="insertVariable('content')"><?php echo htmlspecialchars(content ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                                        <span class="variable-tag" onclick="insertVariable('featured_image')"><?php echo htmlspecialchars(featured_image ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                                        <span class="variable-tag" onclick="insertVariable('sections')"><?php echo htmlspecialchars(sections ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                                        <span class="variable-tag" onclick="insertVariable('author')"><?php echo htmlspecialchars(author ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                                        <span class="variable-tag" onclick="insertVariable('date')"><?php echo htmlspecialchars(date ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <p class="small mb-2">Conditional blocks:</p>
                                        <code class="small"><?php if(variable): ?>...<?php endif; ?></code>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Code Editors -->
                        <div class="col-lg-8">
                            <!-- Editor Tabs -->
                            <ul class="nav nav-tabs" id="editorTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="html-tab" data-bs-toggle="tab" 
                                            data-bs-target="#html-pane" type="button" role="tab">
                                        <i class="fab fa-html5 me-2"></i>HTML Template
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="css-tab" data-bs-toggle="tab" 
                                            data-bs-target="#css-pane" type="button" role="tab">
                                        <i class="fab fa-css3-alt me-2"></i>CSS Styles
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="js-tab" data-bs-toggle="tab" 
                                            data-bs-target="#js-pane" type="button" role="tab">
                                        <i class="fab fa-js-square me-2"></i>JavaScript
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="preview-tab" data-bs-toggle="tab" 
                                            data-bs-target="#preview-pane" type="button" role="tab">
                                        <i class="fas fa-eye me-2"></i>Preview
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="editorTabContent">
                                <!-- HTML Editor -->
                                <div class="tab-pane fade show active" id="html-pane" role="tabpanel">
                                    <div class="editor-toolbar">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-secondary btn-sm" 
                                                    onclick="formatCode('html')">
                                                <i class="fas fa-code"></i> Format
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" 
                                                    onclick="insertTemplate('basic')">
                                                <i class="fas fa-file-code"></i> Basic Template
                                            </button>
                                        </div>
                                        <div>
                                            <select class="form-select form-select-sm" onchange="changeTheme(this.value)">
                                                <option value="eclipse">Light Theme</option>
                                                <option value="monokai">Dark Theme</option>
                                            </select>
                                        </div>
                                    </div>
                                    <textarea id="html_template" name="html_template"></textarea>
                                </div>

                                <!-- CSS Editor -->
                                <div class="tab-pane fade" id="css-pane" role="tabpanel">
                                    <div class="editor-toolbar">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-secondary btn-sm" 
                                                    onclick="formatCode('css')">
                                                <i class="fas fa-palette"></i> Format
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" 
                                                    onclick="insertCSSTemplate()">
                                                <i class="fas fa-file-code"></i> Basic Styles
                                            </button>
                                        </div>
                                    </div>
                                    <textarea id="css_styles" name="css_styles"></textarea>
                                </div>

                                <!-- JavaScript Editor -->
                                <div class="tab-pane fade" id="js-pane" role="tabpanel">
                                    <div class="editor-toolbar">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-secondary btn-sm" 
                                                    onclick="formatCode('js')">
                                                <i class="fas fa-code"></i> Format
                                            </button>
                                        </div>
                                    </div>
                                    <textarea id="js_scripts" name="js_scripts"></textarea>
                                </div>

                                <!-- Preview -->
                                <div class="tab-pane fade" id="preview-pane" role="tabpanel">
                                    <div class="editor-toolbar">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm" 
                                                    onclick="updatePreview()">
                                                <i class="fas fa-sync"></i> Refresh Preview
                                            </button>
                                        </div>
                                    </div>
                                    <div class="preview-container" id="preview-container">
                                        <div class="text-center text-muted py-5">
                                            <i class="fas fa-eye fa-3x mb-3"></i>
                                            <p>Click "Refresh Preview" to see your layout</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="/admin/layouts" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>Create Layout
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- CodeMirror JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/xml/xml.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/css/css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/htmlmixed/htmlmixed.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/closetag.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/closebrackets.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/hint/show-hint.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/hint/html-hint.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/hint/css-hint.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/fold/foldcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/fold/foldgutter.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/fold/xml-fold.min.js"></script>

    <script>
        let htmlEditor, cssEditor, jsEditor;
        let currentTheme = 'eclipse';

        // Initialize CodeMirror editors
        document.addEventListener('DOMContentLoaded', function() {
            // HTML Editor
            htmlEditor = CodeMirror.fromTextArea(document.getElementById('html_template'), {
                mode: 'htmlmixed',
                theme: currentTheme,
                lineNumbers: true,
                autoCloseTags: true,
                autoCloseBrackets: true,
                extraKeys: {"Ctrl-Space": "autocomplete"},
                foldGutter: true,
                gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
                indentUnit: 2,
                tabSize: 2,
                lineWrapping: true
            });

            // CSS Editor
            cssEditor = CodeMirror.fromTextArea(document.getElementById('css_styles'), {
                mode: 'css',
                theme: currentTheme,
                lineNumbers: true,
                autoCloseBrackets: true,
                extraKeys: {"Ctrl-Space": "autocomplete"},
                foldGutter: true,
                gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
                indentUnit: 2,
                tabSize: 2,
                lineWrapping: true
            });

            // JavaScript Editor
            jsEditor = CodeMirror.fromTextArea(document.getElementById('js_scripts'), {
                mode: 'javascript',
                theme: currentTheme,
                lineNumbers: true,
                autoCloseBrackets: true,
                extraKeys: {"Ctrl-Space": "autocomplete"},
                foldGutter: true,
                gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
                indentUnit: 2,
                tabSize: 2,
                lineWrapping: true
            });

            // Auto-generate slug from name
            document.getElementById('name').addEventListener('input', function() {
                const name = this.value;
                const slug = name.toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                document.getElementById('slug').value = slug;
            });

            // Insert basic template
            insertTemplate('basic');
        });

        function changeTheme(theme) {
            currentTheme = theme;
            htmlEditor.setOption('theme', theme);
            cssEditor.setOption('theme', theme);
            jsEditor.setOption('theme', theme);
        }

        function insertVariable(variable) {
            const activeTab = document.querySelector('.nav-link.active').id;
            let editor;
            
            if (activeTab === 'html-tab') {
                editor = htmlEditor;
            } else if (activeTab === 'css-tab') {
                editor = cssEditor;
            } else if (activeTab === 'js-tab') {
                editor = jsEditor;
            }
            
            if (editor) {
                const cursor = editor.getCursor();
                editor.replaceRange('<?php echo htmlspecialchars(' + variable + ' ?? '', ENT_QUOTES, 'UTF-8'); ?>', cursor);
                editor.focus();
            }
        }

        function insertTemplate(type) {
            if (type === 'basic') {
                const basicTemplate = `<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <article class="page-content">
                <header class="page-header mb-4">
                    <?php if(title): ?>
                        <h1 class="page-title"><?php echo htmlspecialchars(title ?? '', ENT_QUOTES, 'UTF-8'); ?></h1>
                    <?php endif; ?>
                    <?php if(excerpt): ?>
                        <p class="page-excerpt lead text-muted"><?php echo htmlspecialchars(excerpt ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endif; ?>
                </header>
                
                <?php if(content): ?>
                    <div class="page-body">
                        <?php echo htmlspecialchars(content ?? '', ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                <?php endif; ?>
                
                <?php if(sections): ?>
                    <div class="page-sections mt-4">
                        <?php echo htmlspecialchars(sections ?? '', ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                <?php endif; ?>
            </article>
        </div>
    </div>
</div>`;
                htmlEditor.setValue(basicTemplate);
            }
        }

        function insertCSSTemplate() {
            const basicCSS = `.page-content {
    background: white;
    padding: 2rem;
    border-radius: 0.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.page-title {
    color: #198754;
    margin-bottom: 1rem;
}

.page-excerpt {
    border-left: 4px solid #198754;
    padding-left: 1rem;
}

.page-sections {
    margin-top: 2rem;
}`;
            cssEditor.setValue(basicCSS);
        }

        function formatCode(type) {
            let editor;
            if (type === 'html') editor = htmlEditor;
            else if (type === 'css') editor = cssEditor;
            else if (type === 'js') editor = jsEditor;
            
            if (editor) {
                const totalLines = editor.lineCount();
                editor.autoFormatRange({line: 0, ch: 0}, {line: totalLines});
            }
        }

        function updatePreview() {
            const html = htmlEditor.getValue();
            const css = cssEditor.getValue();
            const js = jsEditor.getValue();
            
            // Sample data for preview
            let previewHtml = html;
            const sampleData = {
                'title': 'Sample Page Title',
                'excerpt': 'This is a sample excerpt to demonstrate how the layout will look.',
                'content': '<p>This is sample content to show how your layout will render.</p>',
                'featured_image': '/assets/img/sample.jpg',
                'sections': '<div class="sample-section"><h3>Sample Section</h3><p>This is where page sections would appear.</p></div>',
                'author': 'John Doe',
                'date': new Date().toLocaleDateString()
            };
            
            // Replace variables
            for (const [key, value] of Object.entries(sampleData)) {
                const regex = new RegExp('\\{\\{\\s*' + key + '\\s*\\}\\}', 'g');
                previewHtml = previewHtml.replace(regex, value);
            }
            
            // Handle conditionals (basic implementation)
            previewHtml = previewHtml.replace(/@if\(([^)]+)\)(.*?)<?php endif; ?>/gs, function(match, condition, content) {
                if (sampleData[condition.trim()]) {
                    return content;
                }
                return '';
            });
            
            // Create preview with styles
            const previewContainer = document.getElementById('preview-container');
            previewContainer.innerHTML = `
                <style>${css}</style>
                ${previewHtml}
                <script>${js}<\/script>
            `;
        }
    </script>
</body>
</html>

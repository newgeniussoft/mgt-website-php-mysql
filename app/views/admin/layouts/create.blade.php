
 @extends('admin.layout')

 @push('styles')
     
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
 @endpush

 @section('title', 'Dashboard')
 @section('content')
            

            <!-- Main content -->
            <main class="col-span-8 lg:col-span-9 p-4">
                <div class="flex items-center justify-between">
                    <h1 class="text-xl font-bold">{{ $title ?? 'Create New Layout' }}</h1>
                    <div class="flex items-center space-x-2">
                        <a href="/admin/layouts" class="text-blue-500 hover:underline">
                            <i class="fas fa-arrow-left me-2"></i>Back to Layouts
                        </a>
                    </div>
                </div>

                <!-- Flash Messages -->
                @if(isset($_SESSION['error']))
                    <div class="bg-red-200 border border-red-400 rounded p-4 mt-4">
                        {{ $_SESSION['error'] }}
                    </div>
                    @php unset($_SESSION['error']); @endphp
                @endif

                <form method="POST" action="/admin/layouts/store" enctype="multipart/form-data" class="mt-4">
                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                        <!-- Left Column - Form Fields -->
                        <div>
                            <div class="bg-gray-100 p-4">
                                <h5 class="text-lg font-bold mb-2">Layout Settings</h5>
                                <div class="space-y-2">
                                    <div class="mb-3">
                                        <label for="name" class="block text-sm font-medium">Layout Name *</label>
                                        <input type="text" class="border border-gray-300 rounded px-2 py-1" id="name" name="name" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="slug" class="block text-sm font-medium">Slug</label>
                                        <input type="text" class="border border-gray-300 rounded px-2 py-1" id="slug" name="slug" 
                                               placeholder="Auto-generated from name">
                                        <div class="text-sm text-gray-500">Leave empty to auto-generate from name</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="block text-sm font-medium">Description</label>
                                        <textarea class="border border-gray-300 rounded px-2 py-1" id="description" name="description" rows="3"></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="thumbnail" class="block text-sm font-medium">Thumbnail</label>
                                        <input type="file" class="border border-gray-300 rounded px-2 py-1" id="thumbnail" name="thumbnail" 
                                               accept="image/*">
                                        <div class="text-sm text-gray-500">Upload a preview image for this layout</div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="flex items-center">
                                            <input type="checkbox" id="is_active" name="is_active" checked class="mr-2">
                                            <label for="is_active" class="text-sm font-medium">Active</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Template Variables Reference -->
                            <div class="bg-gray-100 p-4 mt-4">
                                <h6 class="text-sm font-bold mb-2">Available Variables</h6>
                                <div class="space-y-2">
                                    <div class="flex flex-wrap">
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 mr-2 mb-2" onclick="insertVariable('title')">{{ $title ?? 'title' }}</span>
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 mr-2 mb-2" onclick="insertVariable('excerpt')">{{ $excerpt ?? 'excerpt' }}</span>
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 mr-2 mb-2" onclick="insertVariable('content')">{{ $content ?? 'content' }}</span>
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 mr-2 mb-2" onclick="insertVariable('featured_image')">{{ $featured_image ?? 'featured_image' }}</span>
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 mr-2 mb-2" onclick="insertVariable('sections')">{{ $sections ?? 'sections' }}</span>
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 mr-2 mb-2" onclick="insertVariable('author')">{{ $author ?? 'author' }}</span>
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 mr-2 mb-2" onclick="insertVariable('date')">{{ $date ?? 'date' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Code Editors -->
                        <div class="col-span-3 space-y-4">
                            <!-- Editor Tabs -->
                            <ul class="flex border-b border-gray-300">
                                <li class="px-4 py-2 -mb-px cursor-pointer font-semibold border-b-2 border-transparent" role="presentation">
                                    <button class="text-blue-500" id="html-tab" data-bs-toggle="tab" 
                                            data-bs-target="#html-pane" type="button" role="tab">
                                        <i class="fab fa-html5 me-2"></i>HTML Template
                                    </button>
                                </li>
                                <li class="px-4 py-2 -mb-px cursor-pointer" role="presentation">
                                    <button class="" id="css-tab" data-bs-toggle="tab" 
                                            data-bs-target="#css-pane" type="button" role="tab">
                                        <i class="fab fa-css3-alt me-2"></i>CSS Styles
                                    </button>
                                </li>
                                <li class="px-4 py-2 -mb-px cursor-pointer" role="presentation">
                                    <button class="" id="js-tab" data-bs-toggle="tab" 
                                            data-bs-target="#js-pane" type="button" role="tab">
                                        <i class="fab fa-js-square me-2"></i>JavaScript
                                    </button>
                                </li>
                                <li class="px-4 py-2 -mb-px cursor-pointer" role="presentation">
                                    <button class="" id="preview-tab" data-bs-toggle="tab" 
                                            data-bs-target="#preview-pane" type="button" role="tab">
                                        <i class="fas fa-eye me-2"></i>Preview
                                    </button>
                                </li>
                            </ul>

                            <div class="space-y-4">
                                <!-- HTML Editor -->
                                <div class="border border-gray-300 rounded p-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <div class="flex items-center space-x-2">
                                            <button type="button" class="text-blue-500" onclick="formatCode('html')">
                                                <i class="fas fa-code"></i> Format
            @endsection
            
             @push('scripts')
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
                editor.replaceRange('{{ ' + variable + ' }}', cursor);
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
                    @if(title)
                        <h1 class="page-title">{{ title }}</h1>
                    @endif
                    @if(excerpt)
                        <p class="page-excerpt lead text-muted">{{ excerpt }}</p>
                    @endif
                </header>
                
                @if(content)
                    <div class="page-body">
                        {{ content }}
                    </div>
                @endif
                
                @if(sections)
                    <div class="page-sections mt-4">
                        {{ sections }}
                    </div>
                @endif
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
            previewHtml = previewHtml.replace(/if\(([^)]+)\)(.*?)endif/gs, function(match, condition, content) {
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
             @endpush
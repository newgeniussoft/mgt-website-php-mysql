<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page_title }} - Section Builder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- CodeMirror CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/monokai.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/eclipse.min.css">
    
    <!-- SortableJS -->
    <link href="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.css" rel="stylesheet">
    
    <style>
        .section-builder {
            display: flex;
            height: calc(100vh - 120px);
        }
        
        .sections-panel {
            width: 300px;
            background: #f8f9fa;
            border-right: 1px solid #dee2e6;
            overflow-y: auto;
        }
        
        .editor-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .section-item {
            background: white;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .section-item:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .section-item.active {
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
        }
        
        .section-item.sortable-ghost {
            opacity: 0.4;
        }
        
        .section-header {
            padding: 0.75rem;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: between;
            align-items: center;
        }
        
        .section-content {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            color: #666;
        }
        
        .editor-tabs {
            display: flex;
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
        
        .editor-tab {
            padding: 0.75rem 1.5rem;
            background: none;
            border: none;
            border-bottom: 2px solid transparent;
            cursor: pointer;
            font-weight: 500;
        }
        
        .editor-tab.active {
            background: white;
            border-bottom-color: #007bff;
            color: #007bff;
        }
        
        .editor-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .editor-wrapper {
            flex: 1;
            position: relative;
        }
        
        .CodeMirror {
            height: 100% !important;
            font-size: 14px;
        }
        
        .template-selector {
            padding: 1rem;
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
        
        .template-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .template-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .template-card:hover {
            border-color: #007bff;
            transform: translateY(-2px);
        }
        
        .template-card.selected {
            border-color: #007bff;
            background: #e7f3ff;
        }
        
        .template-icon {
            font-size: 2rem;
            color: #007bff;
            margin-bottom: 0.5rem;
        }
        
        .preview-panel {
            width: 400px;
            background: white;
            border-left: 1px solid #dee2e6;
            overflow-y: auto;
        }
        
        .preview-header {
            padding: 1rem;
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            font-weight: 600;
        }
        
        .preview-content {
            padding: 1rem;
        }
        
        .add-section-btn {
            width: 100%;
            padding: 1rem;
            border: 2px dashed #007bff;
            background: none;
            color: #007bff;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }
        
        .add-section-btn:hover {
            background: #e7f3ff;
        }
        
        .section-actions {
            display: flex;
            gap: 0.25rem;
        }
        
        .drag-handle {
            cursor: grab;
            color: #6c757d;
            margin-right: 0.5rem;
        }
        
        .drag-handle:active {
            cursor: grabbing;
        }
        
        .variable-input {
            margin-bottom: 0.5rem;
        }
        
        .variable-input label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #495057;
        }
        
        .no-sections {
            text-align: center;
            padding: 3rem 1rem;
            color: #6c757d;
        }
        
        .toolbar {
            padding: 0.5rem 1rem;
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: between;
            align-items: center;
        }
        
        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
            <div>
                <h1 class="h3 mb-0">{{ $page_title }}</h1>
                <small class="text-muted">Page: {{ $page['title'] }}</small>
            </div>
            <div class="btn-toolbar">
                <a href="/admin/pages" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-arrow-left me-1"></i>Back to Pages
                </a>
                <button class="btn btn-success me-2" onclick="savePage()">
                    <i class="fas fa-save me-1"></i>Save Changes
                </button>
                <a href="/admin/pages/preview?id={{ $page['id'] }}" class="btn btn-outline-info" target="_blank">
                    <i class="fas fa-eye me-1"></i>Preview
                </a>
            </div>
        </div>

        <!-- Section Builder Interface -->
        <div class="section-builder">
            <!-- Sections Panel -->
            <div class="sections-panel">
                <div class="p-3">
                    <button class="add-section-btn" onclick="showTemplateSelector()">
                        <i class="fas fa-plus me-2"></i>Add New Section
                    </button>
                    
                    <div id="sections-list">
                        @if(empty($sections))
                            <div class="no-sections">
                                <i class="fas fa-puzzle-piece mb-3" style="font-size: 3rem;"></i>
                                <p>No sections yet.<br>Click "Add New Section" to start building your page.</p>
                            </div>
                        @else
                            @foreach($sections as $section)
                                <div class="section-item" data-section-id="{{ $section['id'] }}" onclick="selectSection({{ $section['id'] }})">
                                    <div class="section-header">
                                        <div class="d-flex align-items-center flex-grow-1">
                                            <i class="fas fa-grip-vertical drag-handle"></i>
                                            <div>
                                                <div class="fw-bold">{{ $section['title'] ?: 'Untitled Section' }}</div>
                                                <small class="text-muted">{{ $sectionTypes[$section['section_type']] ?? $section['section_type'] }}</small>
                                            </div>
                                        </div>
                                        <div class="section-actions">
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteSection({{ $section['id'] }}); event.stopPropagation();">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @if($section['content'])
                                        <div class="section-content">
                                            {{ substr(strip_tags($section['content']), 0, 100) }}...
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Editor Panel -->
            <div class="editor-panel">
                <div class="template-selector" id="template-selector" style="display: none;">
                    <h5>Choose Section Template</h5>
                    <div class="template-grid" id="template-grid">
                        <!-- Templates will be loaded here -->
                    </div>
                </div>

                <div id="section-editor" style="display: none;">
                    <div class="toolbar">
                        <div>
                            <strong id="current-section-title">Section Editor</strong>
                        </div>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-secondary" onclick="duplicateSection()">
                                <i class="fas fa-copy"></i> Duplicate
                            </button>
                            <button class="btn btn-outline-primary" onclick="previewSection()">
                                <i class="fas fa-eye"></i> Preview
                            </button>
                        </div>
                    </div>

                    <!-- Section Settings -->
                    <div class="p-3 border-bottom">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label class="form-label">Section Title</label>
                                    <input type="text" class="form-control form-control-sm" id="section-title" placeholder="Enter section title">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label class="form-label">Template</label>
                                    <select class="form-select form-select-sm" id="section-template">
                                        <option value="custom">Custom Layout</option>
                                        <!-- Template options will be loaded here -->
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Template Variables -->
                        <div id="template-variables" style="display: none;">
                            <h6 class="mt-3 mb-2">Template Variables</h6>
                            <div id="variables-container"></div>
                        </div>
                    </div>

                    <!-- Editor Tabs -->
                    <div class="editor-tabs">
                        <button class="editor-tab active" data-tab="html" onclick="switchTab('html')">
                            <i class="fab fa-html5 me-1"></i>HTML
                        </button>
                        <button class="editor-tab" data-tab="css" onclick="switchTab('css')">
                            <i class="fab fa-css3-alt me-1"></i>CSS
                        </button>
                        <button class="editor-tab" data-tab="js" onclick="switchTab('js')">
                            <i class="fab fa-js-square me-1"></i>JavaScript
                        </button>
                        <button class="editor-tab" data-tab="content" onclick="switchTab('content')">
                            <i class="fas fa-edit me-1"></i>Content
                        </button>
                    </div>

                    <!-- Editor Content -->
                    <div class="editor-content">
                        <div class="editor-wrapper">
                            <textarea id="html-editor" style="display: none;"></textarea>
                            <textarea id="css-editor" style="display: none;"></textarea>
                            <textarea id="js-editor" style="display: none;"></textarea>
                            <textarea id="content-editor" style="display: none;"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Default message when no section selected -->
                <div id="no-section-selected" class="d-flex align-items-center justify-content-center h-100">
                    <div class="text-center text-muted">
                        <i class="fas fa-mouse-pointer mb-3" style="font-size: 3rem;"></i>
                        <h5>Select a section to edit</h5>
                        <p>Choose a section from the left panel or add a new one to get started.</p>
                    </div>
                </div>
            </div>

            <!-- Preview Panel -->
            <div class="preview-panel">
                <div class="preview-header">
                    <i class="fas fa-eye me-2"></i>Live Preview
                </div>
                <div class="preview-content">
                    <div id="section-preview">
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-desktop mb-3" style="font-size: 2rem;"></i>
                            <p>Preview will appear here when you select a section.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    
    <!-- CodeMirror Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/xml/xml.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/css/css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/htmlmixed/htmlmixed.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/closetag.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/closebrackets.min.js"></script>

    <script>
        let currentSection = null;
        let sections = @json($sections);
        let sectionTemplates = [];
        let editors = {};
        let sortable;

        document.addEventListener('DOMContentLoaded', function() {
            initializeEditors();
            initializeSortable();
            loadSectionTemplates();
        });

        function initializeEditors() {
            // HTML Editor
            editors.html = CodeMirror.fromTextArea(document.getElementById('html-editor'), {
                mode: 'htmlmixed',
                theme: 'eclipse',
                lineNumbers: true,
                autoCloseTags: true,
                autoCloseBrackets: true,
                indentUnit: 2,
                tabSize: 2
            });

            // CSS Editor
            editors.css = CodeMirror.fromTextArea(document.getElementById('css-editor'), {
                mode: 'css',
                theme: 'eclipse',
                lineNumbers: true,
                autoCloseBrackets: true,
                indentUnit: 2,
                tabSize: 2
            });

            // JavaScript Editor
            editors.js = CodeMirror.fromTextArea(document.getElementById('js-editor'), {
                mode: 'javascript',
                theme: 'eclipse',
                lineNumbers: true,
                autoCloseBrackets: true,
                indentUnit: 2,
                tabSize: 2
            });

            // Content Editor (simple text)
            editors.content = CodeMirror.fromTextArea(document.getElementById('content-editor'), {
                mode: 'htmlmixed',
                theme: 'eclipse',
                lineNumbers: true,
                lineWrapping: true,
                indentUnit: 2,
                tabSize: 2
            });

            // Hide all editors initially
            Object.values(editors).forEach(editor => {
                editor.getWrapperElement().style.display = 'none';
            });

            // Show HTML editor by default
            editors.html.getWrapperElement().style.display = 'block';
            editors.html.refresh();

            // Add change listeners
            Object.keys(editors).forEach(key => {
                editors[key].on('change', () => {
                    if (currentSection) {
                        updatePreview();
                    }
                });
            });
        }

        function initializeSortable() {
            const container = document.getElementById('sections-list');
            sortable = Sortable.create(container, {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'sortable-ghost',
                onEnd: function(evt) {
                    updateSectionOrder();
                }
            });
        }

        function loadSectionTemplates() {
            fetch('/admin/api/section-templates')
                .then(response => response.json())
                .then(data => {
                    sectionTemplates = data;
                    populateTemplateSelector();
                    populateTemplateDropdown();
                })
                .catch(error => {
                    console.error('Error loading templates:', error);
                });
        }

        function populateTemplateSelector() {
            const grid = document.getElementById('template-grid');
            grid.innerHTML = '';

            sectionTemplates.forEach(template => {
                const card = document.createElement('div');
                card.className = 'template-card';
                card.dataset.templateId = template.id;
                card.innerHTML = `
                    <div class="template-icon">
                        <i class="fas fa-${getTemplateIcon(template.category)}"></i>
                    </div>
                    <h6>${template.name}</h6>
                    <small class="text-muted">${template.description}</small>
                `;
                card.onclick = () => selectTemplate(template);
                grid.appendChild(card);
            });
        }

        function populateTemplateDropdown() {
            const select = document.getElementById('section-template');
            select.innerHTML = '<option value="custom">Custom Layout</option>';
            
            sectionTemplates.forEach(template => {
                const option = document.createElement('option');
                option.value = template.slug;
                option.textContent = template.name;
                select.appendChild(option);
            });
        }

        function getTemplateIcon(category) {
            const icons = {
                'hero': 'star',
                'services': 'cogs',
                'gallery': 'images',
                'contact': 'envelope',
                'general': 'puzzle-piece'
            };
            return icons[category] || 'puzzle-piece';
        }

        function showTemplateSelector() {
            document.getElementById('template-selector').style.display = 'block';
            document.getElementById('section-editor').style.display = 'none';
            document.getElementById('no-section-selected').style.display = 'none';
        }

        function selectTemplate(template) {
            // Create new section with template
            const sectionData = {
                page_id: {{ $page['id'] }},
                section_type: 'custom',
                title: template.name,
                content: '',
                section_html: template.html_template,
                section_css: template.css_template,
                section_js: template.js_template,
                layout_template: template.slug,
                settings: template.variables ? JSON.parse(template.variables) : {}
            };

            createSection(sectionData);
        }

        function createSection(data) {
            fetch('/admin/pages/add-section', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    location.reload(); // Reload to show new section
                } else {
                    alert('Failed to create section: ' + result.message);
                }
            })
            .catch(error => {
                console.error('Error creating section:', error);
                alert('Failed to create section');
            });
        }

        function selectSection(sectionId) {
            // Remove active class from all sections
            document.querySelectorAll('.section-item').forEach(item => {
                item.classList.remove('active');
            });

            // Add active class to selected section
            const sectionElement = document.querySelector(`[data-section-id="${sectionId}"]`);
            if (sectionElement) {
                sectionElement.classList.add('active');
            }

            // Find section data
            currentSection = sections.find(s => s.id == sectionId);
            if (!currentSection) return;

            // Show editor
            document.getElementById('template-selector').style.display = 'none';
            document.getElementById('section-editor').style.display = 'block';
            document.getElementById('no-section-selected').style.display = 'none';

            // Load section data into editor
            loadSectionData();
        }

        function loadSectionData() {
            if (!currentSection) return;

            // Update title
            document.getElementById('current-section-title').textContent = currentSection.title || 'Untitled Section';
            document.getElementById('section-title').value = currentSection.title || '';
            document.getElementById('section-template').value = currentSection.layout_template || 'custom';

            // Load code into editors
            editors.html.setValue(currentSection.section_html || '');
            editors.css.setValue(currentSection.section_css || '');
            editors.js.setValue(currentSection.section_js || '');
            editors.content.setValue(currentSection.content || '');

            // Load template variables if available
            loadTemplateVariables();

            // Update preview
            updatePreview();
        }

        function loadTemplateVariables() {
            const template = sectionTemplates.find(t => t.slug === currentSection.layout_template);
            const container = document.getElementById('variables-container');
            const variablesPanel = document.getElementById('template-variables');

            if (template && template.variables) {
                const variables = JSON.parse(template.variables);
                const settings = currentSection.settings ? JSON.parse(currentSection.settings) : {};
                
                container.innerHTML = '';
                
                Object.keys(variables).forEach(key => {
                    const div = document.createElement('div');
                    div.className = 'variable-input';
                    div.innerHTML = `
                        <label class="form-label">${variables[key]}</label>
                        <input type="text" class="form-control form-control-sm" 
                               data-variable="${key}" value="${settings[key] || ''}"
                               placeholder="${variables[key]}">
                    `;
                    container.appendChild(div);
                });

                variablesPanel.style.display = 'block';
            } else {
                variablesPanel.style.display = 'none';
            }
        }

        function switchTab(tab) {
            // Update tab buttons
            document.querySelectorAll('.editor-tab').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector(`[data-tab="${tab}"]`).classList.add('active');

            // Hide all editors
            Object.values(editors).forEach(editor => {
                editor.getWrapperElement().style.display = 'none';
            });

            // Show selected editor
            editors[tab].getWrapperElement().style.display = 'block';
            editors[tab].refresh();
        }

        function updatePreview() {
            if (!currentSection) return;

            const html = editors.html.getValue();
            const css = editors.css.getValue();
            const js = editors.js.getValue();

            const previewContent = document.getElementById('section-preview');
            previewContent.innerHTML = `
                <style>${css}</style>
                ${html}
                <script>${js}</script>
            `;
        }

        function savePage() {
            if (!currentSection) {
                alert('Please select a section to save');
                return;
            }

            const data = {
                id: currentSection.id,
                title: document.getElementById('section-title').value,
                section_html: editors.html.getValue(),
                section_css: editors.css.getValue(),
                section_js: editors.js.getValue(),
                content: editors.content.getValue(),
                layout_template: document.getElementById('section-template').value
            };

            // Collect template variables
            const variables = {};
            document.querySelectorAll('[data-variable]').forEach(input => {
                variables[input.dataset.variable] = input.value;
            });
            data.settings = variables;

            fetch('/admin/pages/update-section', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Section saved successfully!');
                } else {
                    alert('Failed to save section: ' + result.message);
                }
            })
            .catch(error => {
                console.error('Error saving section:', error);
                alert('Failed to save section');
            });
        }

        function deleteSection(sectionId) {
            if (!confirm('Are you sure you want to delete this section?')) return;

            fetch('/admin/pages/delete-section', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ section_id: sectionId })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    location.reload();
                } else {
                    alert('Failed to delete section: ' + result.message);
                }
            })
            .catch(error => {
                console.error('Error deleting section:', error);
                alert('Failed to delete section');
            });
        }

        function updateSectionOrder() {
            const sections = [];
            document.querySelectorAll('.section-item').forEach((item, index) => {
                sections.push({
                    id: item.dataset.sectionId,
                    sort_order: index + 1
                });
            });

            fetch('/admin/pages/update-section-order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(sections)
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert('Failed to update section order');
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update section order');
                location.reload();
            });
        }
    </script>
</body>
</html>

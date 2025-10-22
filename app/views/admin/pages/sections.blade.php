<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page_title }} - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.css" rel="stylesheet">
    
    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.css" rel="stylesheet">
    
    <style>
        .section-item {
            background: white;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        .section-item:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .section-item.sortable-ghost {
            opacity: 0.4;
        }
        .section-item.sortable-chosen {
            transform: rotate(5deg);
        }
        .section-header {
            background: #f8f9fa;
            padding: 1rem;
            border-bottom: 1px solid #ddd;
            border-radius: 0.5rem 0.5rem 0 0;
            cursor: move;
        }
        .section-content {
            padding: 1rem;
        }
        .section-type-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        .drag-handle {
            cursor: grab;
            color: #6c757d;
        }
        .drag-handle:active {
            cursor: grabbing;
        }
        .section-preview {
            max-height: 200px;
            overflow: hidden;
            position: relative;
        }
        .section-preview::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 30px;
            background: linear-gradient(transparent, white);
        }
        .add-section-card {
            border: 2px dashed #ddd;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }
        .add-section-card:hover {
            border-color: #198754;
            background: #e8f5e8;
        }
        .section-settings {
            background: #f8f9fa;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-top: 1rem;
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
                            <a class="nav-link text-white active" href="/admin/pages">
                                <i class="fas fa-file-alt me-2"></i>Pages
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/admin/layouts">
                                <i class="fas fa-th-large me-2"></i>Layouts
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">{{ $page_title }}</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="/admin/pages" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-arrow-left me-2"></i>Back to Pages
                        </a>
                        <a href="/admin/pages/edit?id={{ $page->id }}" class="btn btn-outline-primary me-2">
                            <i class="fas fa-edit me-2"></i>Edit Page
                        </a>
                        <a href="/admin/pages/preview?id={{ $page->id }}" class="btn btn-outline-info" target="_blank">
                            <i class="fas fa-eye me-2"></i>Preview
                        </a>
                    </div>
                </div>

                <!-- Flash Messages -->
                @if(isset($_SESSION['success_message']))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ $_SESSION['success_message'] }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @php unset($_SESSION['success_message']); @endphp
                @endif

                @if(isset($_SESSION['error_message']))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $_SESSION['error_message'] }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @php unset($_SESSION['error_message']); @endphp
                @endif

                <!-- Page Info -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">{{ $page->title }}</h5>
                        <p class="card-text text-muted">{{ $page->excerpt ?: 'No excerpt available' }}</p>
                        <div class="d-flex gap-2">
                            <span class="badge bg-{{ $page->status === 'published' ? 'success' : 'secondary' }}">
                                {{ ucfirst($page->status) }}
                            </span>
                            <span class="badge bg-info">{{ $page->language === 'es' ? 'Spanish' : 'English' }}</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Sections List -->
                    <div class="col-lg-8">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4>Page Sections</h4>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSectionModal">
                                <i class="fas fa-plus me-2"></i>Add Section
                            </button>
                        </div>

                        <div id="sections-container">
                            @if(empty($sections))
                                <div class="text-center py-5">
                                    <i class="fas fa-puzzle-piece text-muted mb-3" style="font-size: 3rem;"></i>
                                    <h5 class="text-muted">No sections yet</h5>
                                    <p class="text-muted">Add your first section to start building your page content.</p>
                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSectionModal">
                                        <i class="fas fa-plus me-2"></i>Add First Section
                                    </button>
                                </div>
                            @else
                                @foreach($sections as $section)
                                    <div class="section-item" data-section-id="{{ $section['id'] }}">
                                        <div class="section-header d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-grip-vertical drag-handle me-3"></i>
                                                <div>
                                                    <h6 class="mb-1">{{ $section['title'] ?: 'Untitled Section' }}</h6>
                                                    <span class="badge section-type-badge bg-primary">{{ $sectionTypes[$section['section_type']] ?? $section['section_type'] }}</span>
                                                </div>
                                            </div>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-outline-primary" onclick="editSection({{ $section['id'] }})">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" onclick="deleteSection({{ $section['id'] }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <div class="form-check form-switch ms-2">
                                                    <input class="form-check-input" type="checkbox" 
                                                           {{ $section['is_active'] ? 'checked' : '' }}
                                                           onchange="toggleSection({{ $section['id'] }}, this.checked)">
                                                </div>
                                            </div>
                                        </div>
                                        @if($section['content'])
                                            <div class="section-content">
                                                <div class="section-preview">
                                                    {!! substr(strip_tags($section['content']), 0, 200) !!}
                                                    @if(strlen(strip_tags($section['content'])) > 200)...@endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- Section Types Sidebar -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Available Section Types</h6>
                            </div>
                            <div class="card-body">
                                @foreach($sectionTypes as $type => $name)
                                    <div class="add-section-card card mb-2 cursor-pointer" 
                                         onclick="quickAddSection('{{ $type }}')">
                                        <div class="card-body py-2">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-{{ $type === 'text' ? 'align-left' : ($type === 'image' ? 'image' : ($type === 'gallery' ? 'images' : ($type === 'video' ? 'play' : 'puzzle-piece'))) }} me-2"></i>
                                                <span>{{ $name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="card mt-4">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Tips</h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled small">
                                    <li class="mb-2"><i class="fas fa-lightbulb text-warning me-2"></i>Drag sections to reorder them</li>
                                    <li class="mb-2"><i class="fas fa-eye text-info me-2"></i>Toggle sections on/off with the switch</li>
                                    <li class="mb-2"><i class="fas fa-code text-success me-2"></i>Use custom HTML sections for advanced layouts</li>
                                    <li><i class="fas fa-mobile-alt text-primary me-2"></i>All sections are mobile responsive</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add Section Modal -->
    <div class="modal fade" id="addSectionModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="/admin/pages/add-section">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Section</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="page_id" value="{{ $page->id }}">
                        
                        <div class="mb-3">
                            <label for="section_type" class="form-label">Section Type *</label>
                            <select class="form-select" id="section_type" name="section_type" required>
                                <option value="">Choose section type...</option>
                                @foreach($sectionTypes as $type => $name)
                                    <option value="{{ $type }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="section_title" class="form-label">Section Title</label>
                            <input type="text" class="form-control" id="section_title" name="title" 
                                   placeholder="Enter section title (optional)">
                        </div>

                        <div class="mb-3">
                            <label for="section_content" class="form-label">Content</label>
                            <textarea class="form-control" id="section_content" name="content" rows="8"></textarea>
                        </div>

                        <div class="section-settings" id="section-settings" style="display: none;">
                            <h6>Section Settings</h6>
                            <div id="settings-fields"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Add Section</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this section?</p>
                    <p class="text-danger"><strong>This action cannot be undone.</strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" action="/admin/pages/delete-section" style="display: inline;">
                        <input type="hidden" name="section_id" id="deleteSectionId">
                        <input type="hidden" name="page_id" value="{{ $page->id }}">
                        <button type="submit" class="btn btn-danger">Delete Section</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Section Modal -->
    <div class="modal fade" id="editSectionModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editSectionForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Section</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_section_id" name="section_id">
                        <input type="hidden" id="edit_page_id" name="page_id" value="{{ $page->id }}">
                        
                        <div class="mb-3">
                            <label for="edit_section_type" class="form-label">Section Type *</label>
                            <select class="form-select" id="edit_section_type" name="section_type" required>
                                @foreach($sectionTypes as $type => $name)
                                    <option value="{{ $type }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="edit_section_title" class="form-label">Section Title</label>
                            <input type="text" class="form-control" id="edit_section_title" name="title" 
                                   placeholder="Enter section title (optional)">
                        </div>

                        <div class="mb-3">
                            <label for="edit_section_content" class="form-label">Content</label>
                            <textarea class="form-control" id="edit_section_content" name="content" rows="8"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="edit_section_html" class="form-label">Custom HTML (Optional)</label>
                            <textarea class="form-control" id="edit_section_html" name="section_html" rows="4" 
                                      placeholder="Custom HTML code for this section"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="edit_section_css" class="form-label">Custom CSS (Optional)</label>
                            <textarea class="form-control" id="edit_section_css" name="section_css" rows="4" 
                                      placeholder="Custom CSS styles for this section"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="edit_section_js" class="form-label">Custom JavaScript (Optional)</label>
                            <textarea class="form-control" id="edit_section_js" name="section_js" rows="4" 
                                      placeholder="Custom JavaScript code for this section"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="edit_layout_template" class="form-label">Layout Template</label>
                            <select class="form-select" id="edit_layout_template" name="layout_template">
                                <option value="custom">Custom Layout</option>
                                <!-- Template options will be loaded here -->
                            </select>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active" value="1" checked>
                                <label class="form-check-label" for="edit_is_active">
                                    Section Active
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Section</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <script>
        let sortable;

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Summernote for both add and edit modals
            $('#section_content, #edit_section_content').summernote({
                height: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });

            // Initialize Sortable
            const container = document.getElementById('sections-container');
            if (container && container.children.length > 0) {
                sortable = Sortable.create(container, {
                    handle: '.drag-handle',
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    onEnd: function(evt) {
                        updateSectionOrder();
                    }
                });
            }

            // Section type change handler
            document.getElementById('section_type').addEventListener('change', function() {
                const sectionType = this.value;
                showSectionSettings(sectionType);
            });
            
            // Edit section form submit handler
            document.getElementById('editSectionForm').addEventListener('submit', function(e) {
                e.preventDefault();
                updateSection();
            });
        });

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

        function deleteSection(sectionId) {
            document.getElementById('deleteSectionId').value = sectionId;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

        function editSection(sectionId) {
            // Fetch section data and populate edit modal
            fetch(`/admin/pages/get-section?id=${sectionId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const section = data.section;
                        
                        // Populate form fields
                        document.getElementById('edit_section_id').value = section.id;
                        document.getElementById('edit_section_type').value = section.section_type;
                        document.getElementById('edit_section_title').value = section.title || '';
                        document.getElementById('edit_section_html').value = section.section_html || '';
                        document.getElementById('edit_section_css').value = section.section_css || '';
                        document.getElementById('edit_section_js').value = section.section_js || '';
                        document.getElementById('edit_layout_template').value = section.layout_template || 'custom';
                        document.getElementById('edit_is_active').checked = section.is_active == 1;
                        
                        // Set Summernote content
                        $('#edit_section_content').summernote('code', section.content || '');
                        
                        // Show modal
                        const modal = new bootstrap.Modal(document.getElementById('editSectionModal'));
                        modal.show();
                    } else {
                        alert('Failed to load section data: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load section data');
                });
        }
        
        function updateSection() {
            const formData = new FormData(document.getElementById('editSectionForm'));
            
            // Get Summernote content
            formData.set('content', $('#edit_section_content').summernote('code'));
            
            // Convert FormData to JSON
            const data = {};
            formData.forEach((value, key) => {
                // Map section_id to id for backend compatibility
                if (key === 'section_id') {
                    data['id'] = value;
                } else {
                    data[key] = value;
                }
            });
            data.is_active = document.getElementById('edit_is_active').checked ? 1 : 0;
            
            // Debug: Log the data being sent
            console.log('Sending data:', data);
            
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
                    // Close modal and reload page
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editSectionModal'));
                    modal.hide();
                    location.reload();
                } else {
                    alert('Failed to update section: ' + result.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update section');
            });
        }

        function toggleSection(sectionId, isActive) {
            // You can implement AJAX call to toggle section status
            console.log('Toggle section', sectionId, isActive);
        }

        function quickAddSection(sectionType) {
            document.getElementById('section_type').value = sectionType;
            showSectionSettings(sectionType);
            const modal = new bootstrap.Modal(document.getElementById('addSectionModal'));
            modal.show();
        }

        function showSectionSettings(sectionType) {
            const settingsContainer = document.getElementById('section-settings');
            const fieldsContainer = document.getElementById('settings-fields');
            
            // Clear previous settings
            fieldsContainer.innerHTML = '';
            
            if (!sectionType) {
                settingsContainer.style.display = 'none';
                return;
            }

            // Show different settings based on section type
            let settingsHTML = '';
            
            switch (sectionType) {
                case 'text':
                    settingsHTML = `
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Background Color</label>
                                <input type="color" class="form-control" name="settings[background_color]" value="#ffffff">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Text Color</label>
                                <input type="color" class="form-control" name="settings[text_color]" value="#333333">
                            </div>
                        </div>
                    `;
                    break;
                case 'gallery':
                    settingsHTML = `
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Columns</label>
                                <select class="form-select" name="settings[columns]">
                                    <option value="2">2 Columns</option>
                                    <option value="3" selected>3 Columns</option>
                                    <option value="4">4 Columns</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Spacing</label>
                                <select class="form-select" name="settings[spacing]">
                                    <option value="small">Small</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="large">Large</option>
                                </select>
                            </div>
                        </div>
                    `;
                    break;
                case 'cta':
                    settingsHTML = `
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Button Text</label>
                                <input type="text" class="form-control" name="settings[button_text]" value="Learn More">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Button Link</label>
                                <input type="url" class="form-control" name="settings[button_link]" placeholder="https://...">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="form-label">Primary Color</label>
                                <input type="color" class="form-control" name="settings[primary_color]" value="#198754">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Secondary Color</label>
                                <input type="color" class="form-control" name="settings[secondary_color]" value="#20c997">
                            </div>
                        </div>
                    `;
                    break;
            }
            
            if (settingsHTML) {
                fieldsContainer.innerHTML = settingsHTML;
                settingsContainer.style.display = 'block';
            } else {
                settingsContainer.style.display = 'none';
            }
        }
    </script>
</body>
</html>

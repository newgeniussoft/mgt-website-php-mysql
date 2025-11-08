<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .layout-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .layout-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .layout-thumbnail {
            height: 150px;
            background: #f8f9fa;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            overflow: hidden;
        }
        .layout-thumbnail img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }
        .layout-thumbnail .placeholder {
            color: #6c757d;
            font-size: 2rem;
        }
        .system-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
        }
        .usage-stats {
            font-size: 0.875rem;
            color: #6c757d;
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
                    <h1 class="h2">{{ $title }}</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="/admin/layouts/create" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Create New Layout
                        </a>
                    </div>
                </div>

                <!-- Flash Messages -->
                @php
                    $is_success = isset($_SESSION['success']);
                    $is_error = isset($_SESSION['error']);
                @endphp
                @if($is_success)
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ $_SESSION['success'] }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @php unset($_SESSION['success']); @endphp
                @endif

                @if($is_error)
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $_SESSION['error'] }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @php unset($_SESSION['error']); @endphp
                @endif

                <!-- Layouts Grid -->
                <div class="row">
                    @foreach($layouts as $layout)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card layout-card h-100 position-relative">
                                @if($layout['is_system'])
                                    <span class="badge bg-primary system-badge">System</span>
                                @endif
                                
                                <div class="card-body">
                                    <div class="layout-thumbnail">
                                        @if($layout['thumbnail'])
                                            <img src="/uploads/layouts/{{ $layout['thumbnail'] }}" alt="{{ $layout['name'] }}">
                                        @else
                                            <div class="placeholder">
                                                <i class="fas fa-th-large"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <h5 class="card-title">{{ $layout['name'] }}</h5>
                                    <p class="card-text text-muted">{{ $layout['description'] ?: 'No description available' }}</p>
                                    
                                    <div class="usage-stats mb-3">
                                        <i class="fas fa-file-alt me-1"></i>
                                        Used by {{ $layout['page_count'] }} page(s)
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="btn-group" role="group">
                                            <a href="/admin/layouts/preview?id={{ $layout['id'] }}" 
                                               class="btn btn-outline-primary btn-sm" target="_blank">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if(!$layout['is_system'])
                                                <a href="/admin/layouts/edit?id={{ $layout['id'] }}" 
                                                   class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                            
                                            <button type="button" class="btn btn-outline-info btn-sm" 
                                                    onclick="duplicateLayout({{ $layout['id'] }})">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                            
                                            @if(!$layout['is_system'] && $layout['page_count'] == 0)
                                                <button type="button" class="btn btn-outline-danger btn-sm" 
                                                        onclick="deleteLayout({{ $layout['id'] }}, '{{ $layout['name'] }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                        
                                        <small class="text-muted">
                                            @if($layout['is_active'])
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @php
                    $is_empty = empty($layouts);
                @endphp
                @if($is_empty)
                    <div class="text-center py-5">
                        <i class="fas fa-th-large text-muted mb-3" style="font-size: 4rem;"></i>
                        <h4 class="text-muted">No layouts found</h4>
                        <p class="text-muted">Create your first custom layout to get started.</p>
                        <a href="/admin/layouts/create" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Create New Layout
                        </a>
                    </div>
                @endif
            </main>
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
                    <p>Are you sure you want to delete the layout "<span id="layoutName"></span>"?</p>
                    <p class="text-danger"><strong>This action cannot be undone.</strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        <input type="hidden" name="id" id="deleteId">
                        <button type="submit" class="btn btn-danger">Delete Layout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Duplicate Form (hidden) -->
    <form id="duplicateForm" method="POST" action="/admin/layouts/duplicate" style="display: none;">
        <input type="hidden" name="id" id="duplicateId">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteLayout(id, name) {
            document.getElementById('layoutName').textContent = name;
            document.getElementById('deleteId').value = id;
            document.getElementById('deleteForm').action = '/admin/layouts/delete';
            
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

        function duplicateLayout(id) {
            document.getElementById('duplicateId').value = id;
            document.getElementById('duplicateForm').submit();
        }
    </script>
</body>
</html>

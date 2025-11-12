@extends('layouts.admin')

@section('title', 'Template Items')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Template Items</h2>
        <a href="/admin/template-items/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Template
        </a>
    </div>

    @if(isset($_SESSION['success']))
        <div class="alert alert-success alert-dismissible fade show">
            {{ $_SESSION['success'] }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    @endif

    @if(isset($_SESSION['error']))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ $_SESSION['error'] }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    @endif

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="/admin/template-items" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search templates..." value="{{ $search }}">
                </div>
                <div class="col-md-3">
                    <select name="model" class="form-select">
                        <option value="">All Models</option>
                        @foreach($models as $model)
                            <option value="{{ $model }}" {{ $modelFilter == $model ? 'selected' : '' }}>
                                {{ ucfirst($model) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ $statusFilter == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="draft" {{ $statusFilter == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="archived" {{ $statusFilter == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary w-100">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Templates Grid -->
    <div class="row">
        @if(count($templates) > 0)
            @foreach($templates as $template)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 template-card">
                        @if($template->thumbnail)
                            <img src="{{ $template->thumbnail }}" class="card-img-top" alt="{{ $template->name }}" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-code fa-3x text-muted"></i>
                            </div>
                        @endif
                        
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">{{ $template->name }}</h5>
                                @if($template->is_default)
                                    <span class="badge bg-success">Default</span>
                                @endif
                            </div>
                            
                            <p class="text-muted small mb-2">
                                <i class="fas fa-cube"></i> {{ ucfirst($template->model_name) }}
                            </p>
                            
                            @if($template->description)
                                <p class="card-text small text-muted">{{ substr($template->description, 0, 100) }}...</p>
                            @endif
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="badge bg-{{ $template->status == 'active' ? 'success' : ($template->status == 'draft' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($template->status) }}
                                </span>
                                
                                <div class="btn-group btn-group-sm">
                                    <a href="/admin/template-items/preview?id={{ $template->id }}" class="btn btn-outline-info" title="Preview" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="/admin/template-items/edit?id={{ $template->id }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="/admin/template-items/duplicate?id={{ $template->id }}" class="btn btn-outline-secondary" title="Duplicate">
                                        <i class="fas fa-copy"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" title="Delete" onclick="deleteTemplate({{ $template->id }}, '{{ $template->name }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No template items found. <a href="/admin/template-items/create">Create your first template</a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Template Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="templateName"></strong>?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST" action="/admin/template-items/delete">
                    <input type="hidden" name="csrf_token" value="{{ $_SESSION['csrf_token'] }}">
                    <input type="hidden" name="id" id="deleteId">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.template-card {
    transition: transform 0.2s;
}
.template-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
</style>

<script>
function deleteTemplate(id, name) {
    document.getElementById('deleteId').value = id;
    document.getElementById('templateName').textContent = name;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection

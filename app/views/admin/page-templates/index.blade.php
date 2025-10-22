@extends('admin.layout')

@section('title', 'Page Template Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-code me-2"></i>Page Templates
                </h1>
                <a href="/admin/page-templates/create" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create New Template
                </a>
            </div>

            @if(isset($_SESSION['success']))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ $_SESSION['success'] }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @php unset($_SESSION['success']); @endphp
            @endif

            @if(isset($_SESSION['error']))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ $_SESSION['error'] }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @php unset($_SESSION['error']); @endphp
            @endif

            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list me-2"></i>All Templates
                    </h6>
                </div>
                <div class="card-body">
                    @if(empty($templates))
                        <div class="text-center py-5">
                            <i class="fas fa-code fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No templates found</h5>
                            <p class="text-muted">Create your first page template to get started.</p>
                            <a href="/admin/page-templates/create" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Create Template
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">
                                            <i class="fas fa-image"></i>
                                        </th>
                                        <th>Template Name</th>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <th width="100">Pages Using</th>
                                        <th width="80">Status</th>
                                        <th width="200">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($templates as $template)
                                        <tr>
                                            <td class="text-center">
                                                @if($template['thumbnail'])
                                                    <img src="/uploads/page-templates/{{ $template['thumbnail'] }}" 
                                                         alt="{{ $template['name'] }}" 
                                                         class="img-thumbnail" 
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px; border-radius: 4px;">
                                                        <i class="fas fa-code text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <strong>{{ $template['name'] }}</strong>
                                                        @if($template['is_system'])
                                                            <span class="badge bg-info ms-2">System</span>
                                                        @endif
                                                        <br>
                                                        <small class="text-muted">{{ $template['slug'] }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ ucfirst($template['template_type']) }}</span>
                                            </td>
                                            <td>
                                                <small>{{ $template['description'] ?: 'No description' }}</small>
                                            </td>
                                            <td class="text-center">
                                                @if($template['page_count'] > 0)
                                                    <span class="badge bg-success">{{ $template['page_count'] }}</span>
                                                @else
                                                    <span class="text-muted">0</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($template['is_active'])
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="/admin/page-templates/preview?id={{ $template['id'] }}" 
                                                       class="btn btn-sm btn-outline-info" 
                                                       title="Preview">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    @if(!$template['is_system'])
                                                        <a href="/admin/page-templates/edit?id={{ $template['id'] }}" 
                                                           class="btn btn-sm btn-outline-primary" 
                                                           title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endif
                                                    
                                                    <form method="POST" action="/admin/page-templates/duplicate" class="d-inline">
                                                        <input type="hidden" name="id" value="{{ $template['id'] }}">
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-outline-secondary" 
                                                                title="Duplicate">
                                                            <i class="fas fa-copy"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    @if(!$template['is_system'] && $template['page_count'] == 0)
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger" 
                                                                title="Delete"
                                                                onclick="confirmDelete({{ $template['id'] }}, '{{ addslashes($template['name']) }}')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
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
                <p>Are you sure you want to delete the template "<strong id="templateName"></strong>"?</p>
                <p class="text-danger"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="/admin/page-templates/delete" id="deleteForm" class="d-inline">
                    <input type="hidden" name="id" id="deleteId">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Delete Template
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    document.getElementById('deleteId').value = id;
    document.getElementById('templateName').textContent = name;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection

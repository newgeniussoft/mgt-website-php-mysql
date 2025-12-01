@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Templates</h1>
        <a href="{{ admin_url('templates/create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Template
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

    <div class="row">
        @if(empty($templates))
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-file-code fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No templates found</p>
                        <a href="{{ admin_url('templates/create') }}" class="btn btn-primary">
                            Create Your First Template
                        </a>
                    </div>
                </div>
            </div>
        @else
            @foreach($templates as $template)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        @if($template->thumbnail)
                            <img src="{{ $template->thumbnail }}" class="card-img-top" alt="{{ $template->name }}" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-file-code fa-4x text-muted"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">
                                {{ $template->name }}
                                @if($template->is_default)
                                    <span class="badge badge-primary">Default</span>
                                @endif
                            </h5>
                            <p class="card-text text-muted">{{ $template->description ?: 'No description' }}</p>
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-file"></i> Pages: 
                                    @php
                                        $pagesCount = $template->countPages();
                                    @endphp
                                    <strong>{{ $pagesCount }}</strong>
                                </small>
                            </div>
                            <div class="mb-2">
                                <span class="badge badge-{{ $template->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($template->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            <div class="btn-group btn-group-sm d-flex">
                                <a href="{{ admin_url('templates/edit?id=' . $template->id) }}" class="btn btn-primary flex-fill">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="{{ admin_url('templates/preview?id=' . $template->id) }}" class="btn btn-secondary" target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" class="btn btn-info" onclick="duplicateTemplate({{ $template->id }})">
                                    <i class="fas fa-copy"></i>
                                </button>
                                <button type="button" class="btn btn-danger" onclick="deleteTemplate({{ $template->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST" action="{{ admin_url('templates/delete') }}" style="display: none;">
    <input type="hidden" name="csrf_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" id="deleteTemplateId">
</form>

<!-- Duplicate Form -->
<form id="duplicateForm" method="POST" action="{{ admin_url('templates/duplicate') }}" style="display: none;">
    <input type="hidden" name="csrf_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" id="duplicateTemplateId">
</form>

<script>
function deleteTemplate(id) {
    if (confirm('Are you sure you want to delete this template? This action cannot be undone.')) {
        document.getElementById('deleteTemplateId').value = id;
        document.getElementById('deleteForm').submit();
    }
}

function duplicateTemplate(id) {
    if (confirm('Do you want to create a copy of this template?')) {
        document.getElementById('duplicateTemplateId').value = id;
        document.getElementById('duplicateForm').submit();
    }
}
</script>
@endsection

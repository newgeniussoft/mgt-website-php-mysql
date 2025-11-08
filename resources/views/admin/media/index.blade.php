@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-images"></i> Media Library
                </h1>
                <div>
                    <button class="btn btn-success" data-toggle="modal" data-target="#createFolderModal">
                        <i class="fas fa-folder-plus"></i> New Folder
                    </button>
                    <a href="{{ admin_url('media/upload' . ($currentFolder ? '?folder=' . $currentFolder->id : '')) }}" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Upload Files
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    @if(isset($success) && $success)
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ $success }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif
    
    @if(isset($error) && $error)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ $error }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif
    
    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Total Files</h5>
                    <h2>{{ $stats['total'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Images</h5>
                    <h2>{{ $stats['images'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>Documents</h5>
                    <h2>{{ $stats['documents'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>Total Size</h5>
                    <h2>{{ formatFileSize($stats['total_size'] ?? 0) }}</h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Folder Sidebar -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-folder"></i> Folders</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ admin_url('media') }}" class="list-group-item list-group-item-action {{ !$currentFolder ? 'active' : '' }}">
                            <i class="fas fa-home"></i> All Media
                        </a>
                        @foreach($folders as $folder)
                            <a href="{{ admin_url('media?folder=' . $folder->id) }}" 
                               class="list-group-item list-group-item-action {{ $currentFolder && $currentFolder->id == $folder->id ? 'active' : '' }}">
                                <i class="fas fa-folder"></i> {{ $folder->name }}
                                <span class="badge badge-secondary float-right">{{ $folder->getMediaCount() }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9">
            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ admin_url('media') }}" class="form-inline">
                        <div class="form-group mr-3">
                            <input type="text" name="search" class="form-control" placeholder="Search files..." value="{{ $search }}">
                        </div>
                        
                        <div class="form-group mr-3">
                            <select name="type" class="form-control">
                                <option value="">All Types</option>
                                <option value="image" {{ $type === 'image' ? 'selected' : '' }}>Images</option>
                                <option value="video" {{ $type === 'video' ? 'selected' : '' }}>Videos</option>
                                <option value="audio" {{ $type === 'audio' ? 'selected' : '' }}>Audio</option>
                                <option value="document" {{ $type === 'document' ? 'selected' : '' }}>Documents</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        
                        <a href="{{ admin_url('media') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </form>
                </div>
            </div>
    
            <!-- Breadcrumb -->
            @if($currentFolder)
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ admin_url('media') }}">All Media</a></li>
                        @foreach($currentFolder->getBreadcrumb() as $folder)
                            <li class="breadcrumb-item active">{{ $folder->name }}</li>
                        @endforeach
                    </ol>
                </nav>
            @endif
            
            <!-- Bulk Actions -->
            <div class="card mb-3" id="bulkActionsBar" style="display: none;">
                <div class="card-body py-2">
                    <form method="POST" action="{{ admin_url('media/bulk-action') }}" id="bulkForm">
                        @csrf
                        <div class="form-inline">
                            <span class="mr-3"><strong><span id="selectedCount">0</span> selected</strong></span>
                            <select name="action" class="form-control form-control-sm mr-2" required>
                                <option value="">-- Select Action --</option>
                                <option value="move">Move to Folder</option>
                                <option value="delete">Delete</option>
                            </select>
                            <select name="folder_id" class="form-control form-control-sm mr-2" id="bulkFolderSelect" style="display: none;">
                                <option value="">-- Root --</option>
                                @foreach($folders as $folder)
                                    <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary mr-2">Apply</button>
                            <button type="button" class="btn btn-sm btn-secondary" onclick="clearSelection()">Clear</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Media Grid -->
            <div class="row">
                @if(count($mediaItems) > 0)
                    @foreach($mediaItems as $media)
                        <div class="col-md-3 col-sm-4 col-6 mb-4">
                            <div class="card media-card">
                                <div class="custom-control custom-checkbox media-checkbox">
                                    <input type="checkbox" class="custom-control-input media-select" id="media-{{ $media->id }}" value="{{ $media->id }}" onchange="updateBulkActions()">
                                    <label class="custom-control-label" for="media-{{ $media->id }}"></label>
                                </div>
                                <div class="media-thumbnail">
                                    @if($media->isImage())
                                        <img src="{{ asset($media->url) }}" alt="{{ $media->alt_text }}" class="card-img-top">
                                    @else
                                        <div class="file-icon">
                                            <i class="fas fa-{{ getFileIcon($media->type) }} fa-4x text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body p-2">
                                    <h6 class="card-title mb-1 text-truncate" title="{{ $media->original_filename }}">
                                        {{ $media->title ?: $media->original_filename }}
                                    </h6>
                                    <small class="text-muted">{{ $media->getFormattedSize() }}</small>
                                    <div class="mt-2 btn-group-sm" role="group">
                                        <button onclick="renameMedia({{ $media->id }}, '{{ $media->original_filename }}')" class="btn btn-sm btn-info" title="Rename">
                                            <i class="fas fa-i-cursor"></i>
                                        </button>
                                        <button onclick="moveMedia({{ $media->id }})" class="btn btn-sm btn-warning" title="Move">
                                            <i class="fas fa-folder-open"></i>
                                        </button>
                                        <a href="{{ admin_url('media/edit?id=' . $media->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="deleteMedia({{ $media->id }})" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No media files found. <a href="{{ admin_url('media/upload') }}">Upload some files</a> to get started.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST" action="{{ admin_url('media/delete') }}" class="d-none">
    @csrf
    <input type="hidden" name="id" id="deleteId">
</form>

<!-- Rename Form -->
<form id="renameForm" method="POST" action="{{ admin_url('media/rename') }}" class="d-none">
    @csrf
    <input type="hidden" name="id" id="renameId">
    <input type="hidden" name="new_name" id="renameNewName">
</form>

<!-- Move Form -->
<form id="moveForm" method="POST" action="{{ admin_url('media/move') }}" class="d-none">
    @csrf
    <input type="hidden" name="id" id="moveId">
    <input type="hidden" name="folder_id" id="moveFolderId">
</form>

<!-- Create Folder Modal -->
<div class="modal fade" id="createFolderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ admin_url('media/folder/create') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-folder-plus"></i> Create New Folder</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="folderName">Folder Name *</label>
                        <input type="text" class="form-control" id="folderName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="folderParent">Parent Folder</label>
                        <select class="form-control" id="folderParent" name="parent_id">
                            <option value="">-- Root --</option>
                            @foreach($folders as $folder)
                                <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="folderDescription">Description</label>
                        <textarea class="form-control" id="folderDescription" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Folder</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Move Modal -->
<div class="modal fade" id="moveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-folder-open"></i> Move File</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Select Destination Folder</label>
                    <select class="form-control" id="moveModalFolderId">
                        <option value="">-- Root --</option>
                        @foreach($folders as $folder)
                            <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitMove()">Move File</button>
            </div>
        </div>
    </div>
</div>

<style>
    .media-card {
        position: relative;
        transition: transform 0.2s;
    }
    .media-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .media-thumbnail {
        height: 150px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
    }
    .media-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .file-icon {
        padding: 20px;
    }
    .media-checkbox {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 10;
    }
    .media-checkbox .custom-control-label::before,
    .media-checkbox .custom-control-label::after {
        width: 1.5rem;
        height: 1.5rem;
    }
</style>

<script>
function deleteMedia(id) {
    if (confirm('Are you sure you want to delete this media file? This action cannot be undone.')) {
        document.getElementById('deleteId').value = id;
        document.getElementById('deleteForm').submit();
    }
}

function renameMedia(id, currentName) {
    const newName = prompt('Enter new filename:', currentName);
    if (newName && newName !== currentName) {
        document.getElementById('renameId').value = id;
        document.getElementById('renameNewName').value = newName;
        document.getElementById('renameForm').submit();
    }
}

function moveMedia(id) {
    document.getElementById('moveId').value = id;
    $('#moveModal').modal('show');
}

function submitMove() {
    const folderId = document.getElementById('moveModalFolderId').value;
    document.getElementById('moveFolderId').value = folderId;
    document.getElementById('moveForm').submit();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.media-select:checked');
    const count = checkboxes.length;
    const bulkBar = document.getElementById('bulkActionsBar');
    const selectedCount = document.getElementById('selectedCount');
    
    if (count > 0) {
        bulkBar.style.display = 'block';
        selectedCount.textContent = count;
        
        // Update hidden inputs in bulk form
        const bulkForm = document.getElementById('bulkForm');
        // Remove old hidden inputs
        bulkForm.querySelectorAll('input[name="ids[]"]').forEach(input => input.remove());
        // Add new hidden inputs
        checkboxes.forEach(checkbox => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = checkbox.value;
            bulkForm.appendChild(input);
        });
    } else {
        bulkBar.style.display = 'none';
    }
}

function clearSelection() {
    document.querySelectorAll('.media-select').forEach(checkbox => {
        checkbox.checked = false;
    });
    updateBulkActions();
}

// Show/hide folder select based on action
document.addEventListener('DOMContentLoaded', function() {
    const actionSelect = document.querySelector('#bulkForm select[name="action"]');
    const folderSelect = document.getElementById('bulkFolderSelect');
    
    if (actionSelect) {
        actionSelect.addEventListener('change', function() {
            if (this.value === 'move') {
                folderSelect.style.display = 'inline-block';
                folderSelect.required = true;
            } else {
                folderSelect.style.display = 'none';
                folderSelect.required = false;
            }
        });
    }
});
</script>

@php
function getFileIcon($type) {
    $icons = [
        'image' => 'image',
        'video' => 'video',
        'audio' => 'music',
        'document' => 'file-alt',
        'other' => 'file'
    ];
    return $icons[$type] ?? 'file';
}

function formatFileSize($bytes) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }
    return round($bytes, 2) . ' ' . $units[$i];
}
@endphp

@endsection

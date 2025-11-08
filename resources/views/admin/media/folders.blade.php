@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-folder"></i> Manage Folders
                </h1>
                <div>
                    <button class="btn btn-success" data-toggle="modal" data-target="#createFolderModal">
                        <i class="fas fa-folder-plus"></i> New Folder
                    </button>
                    <a href="{{ admin_url('media') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Media
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
    
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">All Folders</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Parent</th>
                            <th>Files</th>
                            <th>Description</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($folders) > 0)
                            @foreach($folders as $folder)
                                <tr>
                                    <td>
                                        <i class="fas fa-folder text-warning"></i>
                                        <strong>{{ $folder->name }}</strong>
                                    </td>
                                    <td><code>{{ $folder->slug }}</code></td>
                                    <td>
                                        @if($folder->parent_id)
                                            @php
                                                $parent = $folder->getParent();
                                            @endphp
                                            {{ $parent ? $parent->name : '-' }}
                                        @else
                                            <span class="text-muted">Root</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">{{ $folder->getMediaCount() }}</span>
                                    </td>
                                    <td>{{ $folder->description ?: '-' }}</td>
                                    <td>{{ $folder->created_at }}</td>
                                    <td>
                                        <a href="{{ admin_url('media?folder=' . $folder->id) }}" class="btn btn-sm btn-info" title="View Files">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button onclick="editFolder({{ $folder->id }}, '{{ $folder->name }}', '{{ $folder->parent_id }}', '{{ addslashes($folder->description) }}')" 
                                                class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="deleteFolder({{ $folder->id }})" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    No folders found. Create one to get started.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

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
                            @foreach($rootFolders as $folder)
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

<!-- Edit Folder Modal -->
<div class="modal fade" id="editFolderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ admin_url('media/folder/update') }}">
                @csrf
                <input type="hidden" id="editFolderId" name="id">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Folder</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editFolderName">Folder Name *</label>
                        <input type="text" class="form-control" id="editFolderName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="editFolderParent">Parent Folder</label>
                        <select class="form-control" id="editFolderParent" name="parent_id">
                            <option value="">-- Root --</option>
                            @foreach($rootFolders as $folder)
                                <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editFolderDescription">Description</label>
                        <textarea class="form-control" id="editFolderDescription" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Folder</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteFolderForm" method="POST" action="{{ admin_url('media/folder/delete') }}" class="d-none">
    @csrf
    <input type="hidden" name="id" id="deleteFolderId">
</form>

<script>
function editFolder(id, name, parentId, description) {
    document.getElementById('editFolderId').value = id;
    document.getElementById('editFolderName').value = name;
    document.getElementById('editFolderParent').value = parentId || '';
    document.getElementById('editFolderDescription').value = description || '';
    $('#editFolderModal').modal('show');
}

function deleteFolder(id) {
    if (confirm('Are you sure you want to delete this folder? This action cannot be undone.')) {
        document.getElementById('deleteFolderId').value = id;
        document.getElementById('deleteFolderForm').submit();
    }
}
</script>

@endsection

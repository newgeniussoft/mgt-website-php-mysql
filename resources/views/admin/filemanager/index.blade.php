@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-folder-open"></i> File Manager
                </h1>
                <div>
                    <button class="btn btn-success" data-toggle="modal" data-target="#createFolderModal">
                        <i class="fas fa-folder-plus"></i> New Folder
                    </button>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#uploadModal">
                        <i class="fas fa-upload"></i> Upload Files
                    </button>
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
        @php unset($_SESSION['media_success']); @endphp
    @endif
    
    @if(isset($error) && $error)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ $error }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
        @php unset($_SESSION['media_error']); @endphp
    @endif
    
    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Total Files</h5>
                    <h2>{{ $stats['total_files'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Total Folders</h5>
                    <h2>{{ $stats['total_folders'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>Images</h5>
                    <h2>{{ $stats['images'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>Total Size</h5>
                    <h2>{{ formatBytes($stats['total_size']) }}</h2>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ admin_url('filemanager') }}">
                    <i class="fas fa-home"></i> Root
                </a>
            </li>
            @foreach($breadcrumb as $crumb)
                <li class="breadcrumb-item active">
                    <a href="{{ admin_url('filemanager?path=' . urlencode($crumb['path'])) }}">
                        {{ $crumb['name'] }}
                    </a>
                </li>
            @endforeach
        </ol>
    </nav>
    
    <!-- File/Folder Grid -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-folder"></i> 
                Current Location: /uploads/{{ $currentPath ?: 'root' }}
            </h5>
        </div>
        <div class="card-body">
            @if(count($items) > 0)
                <div class="row">
                    @foreach($items as $item)
                        <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-4">
                            <div class="card file-item {{ $item['is_dir'] ? 'folder-item' : 'file-item' }}">
                                <div class="card-body text-center p-3">
                                    @if($item['is_dir'])
                                        <a href="{{ admin_url('filemanager?path=' . urlencode($item['path'])) }}" class="folder-link">
                                            <i class="fas fa-folder fa-4x text-warning"></i>
                                            <h6 class="mt-2 mb-0 text-truncate" title="{{ $item['name'] }}">
                                                {{ $item['name'] }}
                                            </h6>
                                            <small class="text-muted">{{ formatBytes($item['size']) }}</small>
                                        </a>
                                    @else
                                        <div class="file-preview">
                                            @if($item['type'] === 'image')
                                                <img src="{{ asset($item['url']) }}" class="img-fluid" alt="{{ $item['name'] }}" style="max-height: 100px;">
                                            @else
                                                <i class="fas fa-{{ getFileIcon($item['type']) }} fa-4x text-muted"></i>
                                            @endif
                                        </div>
                                        <h6 class="mt-2 mb-0 text-truncate" title="{{ $item['name'] }}">
                                            {{ $item['name'] }}
                                        </h6>
                                        <small class="text-muted">{{ formatBytes($item['size']) }}</small>
                                    @endif
                                    
                                    <!-- Action Buttons -->
                                    <div class="mt-2 btn-group-vertical btn-group-sm w-100">
                                        @if(!$item['is_dir'])
                                            <a href="{{ asset($item['url']) }}" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="{{ admin_url('filemanager/download?path=' . urlencode($item['path'])) }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        @endif
                                        <button onclick="renameItem('{{ addslashes($item['name']) }}')" class="btn btn-sm btn-primary">
                                            <i class="fas fa-i-cursor"></i> Rename
                                        </button>
                                        <button onclick="moveItem('{{ addslashes($item['name']) }}')" class="btn btn-sm btn-warning">
                                            <i class="fas fa-arrows-alt"></i> Move
                                        </button>
                                        <button onclick="deleteItem('{{ addslashes($item['name']) }}', {{ $item['is_dir'] ? 'true' : 'false' }})" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-folder-open fa-5x text-muted mb-3"></i>
                    <h5>This folder is empty</h5>
                    <p class="text-muted">Upload files or create a new folder to get started</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Create Folder Modal -->
<div class="modal fade" id="createFolderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ admin_url('filemanager/create-folder') }}">
                @csrf
                <input type="hidden" name="current_path" value="{{ $currentPath }}">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-folder-plus"></i> Create New Folder</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="folderName">Folder Name *</label>
                        <input type="text" class="form-control" id="folderName" name="folder_name" required 
                               placeholder="Enter folder name">
                        <small class="form-text text-muted">Only letters, numbers, underscores, and hyphens allowed</small>
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

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ admin_url('filemanager/upload') }}" enctype="multipart/form-data" id="uploadForm">
                @csrf
                <input type="hidden" name="current_path" value="{{ $currentPath }}">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-upload"></i> Upload Files</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Select Files</label>
                        <div class="custom-file">
                            <input type="file" name="files[]" id="fileInput" class="custom-file-input" multiple required onchange="updateFileList()">
                            <label class="custom-file-label" for="fileInput">Choose files...</label>
                        </div>
                        <small class="form-text text-muted">Maximum file size: 50MB per file</small>
                    </div>
                    <div id="filePreview" class="mt-3"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload Files</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Rename Form -->
<form id="renameForm" method="POST" action="{{ admin_url('filemanager/rename') }}" class="d-none">
    @csrf
    <input type="hidden" name="current_path" value="{{ $currentPath }}">
    <input type="hidden" name="old_name" id="renameOldName">
    <input type="hidden" name="new_name" id="renameNewName">
</form>

<!-- Delete Form -->
<form id="deleteForm" method="POST" action="{{ admin_url('filemanager/delete') }}" class="d-none">
    @csrf
    <input type="hidden" name="current_path" value="{{ $currentPath }}">
    <input type="hidden" name="item_name" id="deleteItemName">
</form>

<!-- Move Form -->
<form id="moveForm" method="POST" action="{{ admin_url('filemanager/move') }}" class="d-none">
    @csrf
    <input type="hidden" name="source_path" value="{{ $currentPath }}">
    <input type="hidden" name="item_name" id="moveItemName">
    <input type="hidden" name="dest_path" id="moveDestPath">
</form>

<!-- Move Modal -->
<div class="modal fade" id="moveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-arrows-alt"></i> Move Item</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Moving: <strong id="moveItemDisplay"></strong></p>
                <div class="form-group">
                    <label>Select Destination Folder</label>
                    <input type="text" class="form-control mb-2" id="destPathInput" placeholder="Enter path or select below">
                    <small class="form-text text-muted">Leave empty for root, or enter path like: folder1/subfolder</small>
                </div>
                <div id="folderTree" class="border rounded p-2" style="max-height: 300px; overflow-y: auto;">
                    <div class="folder-tree-item" onclick="selectDestination('')">
                        <i class="fas fa-home"></i> <strong>Root</strong>
                    </div>
                    <!-- Folder tree will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitMove()">Move Here</button>
            </div>
        </div>
    </div>
</div>

<style>
    .file-item, .folder-item {
        transition: transform 0.2s, box-shadow 0.2s;
        cursor: pointer;
    }
    .file-item:hover, .folder-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .folder-link {
        text-decoration: none;
        color: inherit;
    }
    .folder-link:hover {
        text-decoration: none;
    }
    .file-preview {
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .folder-tree-item {
        padding: 8px;
        cursor: pointer;
        border-radius: 4px;
    }
    .folder-tree-item:hover {
        background: #f0f0f0;
    }
    .folder-tree-item.selected {
        background: #007bff;
        color: white;
    }
</style>

<script>
function renameItem(oldName) {
    const newName = prompt('Enter new name:', oldName);
    if (newName && newName !== oldName) {
        document.getElementById('renameOldName').value = oldName;
        document.getElementById('renameNewName').value = newName;
        document.getElementById('renameForm').submit();
    }
}

function deleteItem(itemName, isFolder) {
    const type = isFolder ? 'folder' : 'file';
    const message = isFolder 
        ? `Are you sure you want to delete the folder "${itemName}" and all its contents? This action cannot be undone.`
        : `Are you sure you want to delete "${itemName}"? This action cannot be undone.`;
    
    if (confirm(message)) {
        document.getElementById('deleteItemName').value = itemName;
        document.getElementById('deleteForm').submit();
    }
}

function moveItem(itemName) {
    document.getElementById('moveItemName').value = itemName;
    document.getElementById('moveItemDisplay').textContent = itemName;
    document.getElementById('destPathInput').value = '';
    
    // Load folder tree
    loadFolderTree();
    
    $('#moveModal').modal('show');
}

function loadFolderTree() {
    const currentPath = '{{ $currentPath }}';
    fetch('{{ admin_url("filemanager/folder-tree") }}?current_path=' + encodeURIComponent(currentPath))
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderFolderTree(data.tree);
            }
        });
}

function renderFolderTree(folders, parentElement = null, level = 0) {
    const container = parentElement || document.getElementById('folderTree');
    
    folders.forEach(folder => {
        const div = document.createElement('div');
        div.className = 'folder-tree-item';
        div.style.paddingLeft = (level * 20 + 10) + 'px';
        div.innerHTML = `<i class="fas fa-folder"></i> ${folder.name}`;
        div.onclick = () => selectDestination(folder.path);
        container.appendChild(div);
        
        if (folder.children && folder.children.length > 0) {
            renderFolderTree(folder.children, container, level + 1);
        }
    });
}

function selectDestination(path) {
    document.getElementById('destPathInput').value = path;
    document.querySelectorAll('.folder-tree-item').forEach(item => {
        item.classList.remove('selected');
    });
    event.target.classList.add('selected');
}

function submitMove() {
    const destPath = document.getElementById('destPathInput').value;
    document.getElementById('moveDestPath').value = destPath;
    document.getElementById('moveForm').submit();
}

function updateFileList() {
    const input = document.getElementById('fileInput');
    const preview = document.getElementById('filePreview');
    const label = document.querySelector('.custom-file-label');
    
    if (input.files.length > 0) {
        label.textContent = input.files.length + ' file(s) selected';
        
        preview.innerHTML = '<h6>Selected Files:</h6>';
        for (let i = 0; i < input.files.length; i++) {
            const file = input.files[i];
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            
            preview.innerHTML += `
                <div class="alert alert-info py-2">
                    <i class="fas fa-file"></i> <strong>${file.name}</strong> (${fileSize} MB)
                </div>
            `;
        }
    } else {
        label.textContent = 'Choose files...';
        preview.innerHTML = '';
    }
}
</script>

@php
function getFileIcon($type) {
    $icons = [
        'image' => 'image',
        'video' => 'video',
        'audio' => 'music',
        'document' => 'file-alt',
        'archive' => 'file-archive',
        'code' => 'file-code',
        'other' => 'file'
    ];
    return $icons[$type] ?? 'file';
}

function formatBytes($bytes) {
    if ($bytes == 0) return '0 B';
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $i = floor(log($bytes) / log(1024));
    return round($bytes / pow(1024, $i), 2) . ' ' . $units[$i];
}
@endphp

@endsection

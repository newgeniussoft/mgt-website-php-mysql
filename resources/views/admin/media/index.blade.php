@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-images"></i> Media Library
                </h1>
                <a href="{{ admin_url('media/upload' . ($currentFolder ? '?folder=' . $currentFolder->id : '')) }}" class="btn btn-primary">
                    <i class="fas fa-upload"></i> Upload Files
                </a>
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
    
    <!-- Media Grid -->
    <div class="row">
        @if(count($mediaItems) > 0)
            @foreach($mediaItems as $media)
                <div class="col-md-2 col-sm-4 col-6 mb-4">
                    <div class="card media-card">
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
                            <div class="mt-2">
                                <a href="{{ admin_url('media/edit?id=' . $media->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ admin_url('media/download?id=' . $media->id) }}" class="btn btn-sm btn-success" title="Download">
                                    <i class="fas fa-download"></i>
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

<!-- Delete Form -->
<form id="deleteForm" method="POST" action="{{ admin_url('media/delete') }}" class="d-none">
    @csrf
    <input type="hidden" name="id" id="deleteId">
</form>

<style>
    .media-card {
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
</style>

<script>
function deleteMedia(id) {
    if (confirm('Are you sure you want to delete this media file? This action cannot be undone.')) {
        document.getElementById('deleteId').value = id;
        document.getElementById('deleteForm').submit();
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

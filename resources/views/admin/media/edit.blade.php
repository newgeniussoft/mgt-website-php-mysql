@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-edit"></i> Edit Media
                </h1>
                <a href="{{ admin_url('media') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Library
                </a>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Media Details</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ admin_url('media/update') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{ $media->id }}">
                        
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="title" 
                                   name="title" 
                                   value="{{ $media->title }}"
                                   placeholder="Enter a descriptive title">
                        </div>
                        
                        @if($media->isImage())
                            <div class="form-group">
                                <label for="alt_text">Alt Text</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="alt_text" 
                                       name="alt_text" 
                                       value="{{ $media->alt_text }}"
                                       placeholder="Describe the image for accessibility">
                                <small class="form-text text-muted">Important for SEO and accessibility</small>
                            </div>
                        @endif
                        
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" 
                                      id="description" 
                                      name="description" 
                                      rows="3"
                                      placeholder="Add a description">{{ $media->description }}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="folder_id">Folder</label>
                            <select name="folder_id" id="folder_id" class="form-control">
                                <option value="">-- Root --</option>
                                @foreach($folders as $folder)
                                    <option value="{{ $folder->id }}" {{ $media->folder_id == $folder->id ? 'selected' : '' }}>
                                        {{ $folder->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="is_public" 
                                       name="is_public"
                                       {{ $media->is_public ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_public">
                                    Public (visible to everyone)
                                </label>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                        <a href="{{ admin_url('media') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Preview -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-eye"></i> Preview</h5>
                </div>
                <div class="card-body text-center">
                    @if($media->isImage())
                        <img src="{{ asset($media->url) }}" alt="{{ $media->alt_text }}" class="img-fluid">
                    @elseif($media->isVideo())
                        <video controls class="w-100">
                            <source src="{{ asset($media->url) }}" type="{{ $media->mime_type }}">
                        </video>
                    @elseif($media->isAudio())
                        <audio controls class="w-100">
                            <source src="{{ asset($media->url) }}" type="{{ $media->mime_type }}">
                        </audio>
                    @else
                        <i class="fas fa-file fa-5x text-muted"></i>
                        <p class="mt-3">{{ $media->original_filename }}</p>
                    @endif
                </div>
            </div>
            
            <!-- File Info -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> File Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th>Filename:</th>
                            <td>{{ $media->original_filename }}</td>
                        </tr>
                        <tr>
                            <th>Type:</th>
                            <td><span class="badge badge-primary">{{ strtoupper($media->type) }}</span></td>
                        </tr>
                        <tr>
                            <th>Size:</th>
                            <td>{{ $media->getFormattedSize() }}</td>
                        </tr>
                        <tr>
                            <th>MIME Type:</th>
                            <td>{{ $media->mime_type }}</td>
                        </tr>
                        @if($media->width && $media->height)
                            <tr>
                                <th>Dimensions:</th>
                                <td>{{ $media->width }} × {{ $media->height }} px</td>
                            </tr>
                        @endif
                        <tr>
                            <th>Downloads:</th>
                            <td>{{ $media->downloads ?? 0 }}</td>
                        </tr>
                        <tr>
                            <th>Uploaded:</th>
                            <td>{{ $media->created_at }}</td>
                        </tr>
                    </table>
                    
                    <div class="mt-3">
                        <a href="{{ admin_url('media/download?id=' . $media->id) }}" class="btn btn-success btn-block">
                            <i class="fas fa-download"></i> Download
                        </a>
                        <button onclick="copyUrl()" class="btn btn-info btn-block">
                            <i class="fas fa-copy"></i> Copy URL
                        </button>
                        <button onclick="deleteMedia()" class="btn btn-danger btn-block">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST" action="{{ admin_url('media/delete') }}" class="d-none">
    @csrf
    <input type="hidden" name="id" value="{{ $media->id }}">
</form>

<script>
function copyUrl() {
    const url = '{{ asset($media->url) }}';
    navigator.clipboard.writeText(url).then(function() {
        alert('URL copied to clipboard!');
    });
}

function deleteMedia() {
    if (confirm('Are you sure you want to delete this media file? This action cannot be undone.')) {
        document.getElementById('deleteForm').submit();
    }
}
</script>

@endsection

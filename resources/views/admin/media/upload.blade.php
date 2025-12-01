@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-upload"></i> Upload Media
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
                    <h5 class="mb-0">Upload Files</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ admin_url('media/store') }}" enctype="multipart/form-data" id="uploadForm">
                        @csrf
                        
                        <div class="form-group">
                            <label>Select Folder (Optional)</label>
                            <select name="folder_id" class="form-control">
                                <option value="">-- Root --</option>
                                @foreach($folders as $folder)
                                    <option value="{{ $folder->id }}" {{ $selectedFolder == $folder->id ? 'selected' : '' }}>
                                        {{ $folder->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Choose Files</label>
                            <div class="custom-file">
                                <input type="file" 
                                       name="files[]" 
                                       id="fileInput" 
                                       class="custom-file-input" 
                                       multiple 
                                       required
                                       onchange="updateFileList()">
                                <label class="custom-file-label" for="fileInput">Choose files...</label>
                            </div>
                            <small class="form-text text-muted">
                                Maximum file size: 10MB. Supported formats: Images (JPG, PNG, GIF, WebP), Videos (MP4, AVI, MOV), Audio (MP3, WAV, OGG), Documents (PDF, DOC, XLS, etc.)
                            </small>
                        </div>
                        
                        <!-- File Preview -->
                        <div id="filePreview" class="mt-3"></div>
                        
                        <!-- Upload Progress -->
                        <div id="uploadProgress" class="mt-3" style="display: none;">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                     role="progressbar" 
                                     style="width: 0%"
                                     id="progressBar">0%</div>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Upload Files
                            </button>
                            <a href="{{ admin_url('media') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Upload Information</h5>
                </div>
                <div class="card-body">
                    <h6>Supported File Types:</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-image text-primary"></i> <strong>Images:</strong> JPG, PNG, GIF, WebP, SVG, BMP</li>
                        <li><i class="fas fa-video text-danger"></i> <strong>Videos:</strong> MP4, AVI, MOV, WMV, FLV, WebM</li>
                        <li><i class="fas fa-music text-success"></i> <strong>Audio:</strong> MP3, WAV, OGG, AAC, FLAC</li>
                        <li><i class="fas fa-file-alt text-info"></i> <strong>Documents:</strong> PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, CSV</li>
                    </ul>
                    
                    <hr>
                    
                    <h6>Tips:</h6>
                    <ul>
                        <li>You can upload multiple files at once</li>
                        <li>Maximum file size is 10MB per file</li>
                        <li>Use descriptive filenames for better organization</li>
                        <li>Organize files into folders for easy management</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .file-preview-item {
        display: inline-block;
        margin: 5px;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background: #f8f9fa;
    }
    .file-preview-item i {
        font-size: 24px;
        margin-right: 10px;
    }
</style>

<script>
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
            const icon = getFileIcon(file.type);
            
            preview.innerHTML += `
                <div class="file-preview-item">
                    <i class="fas fa-${icon}"></i>
                    <span><strong>${file.name}</strong> (${fileSize} MB)</span>
                </div>
            `;
        }
    } else {
        label.textContent = 'Choose files...';
        preview.innerHTML = '';
    }
}

function getFileIcon(mimeType) {
    if (mimeType.startsWith('image/')) return 'image';
    if (mimeType.startsWith('video/')) return 'video';
    if (mimeType.startsWith('audio/')) return 'music';
    if (mimeType.includes('pdf')) return 'file-pdf';
    if (mimeType.includes('word')) return 'file-word';
    if (mimeType.includes('excel') || mimeType.includes('spreadsheet')) return 'file-excel';
    if (mimeType.includes('powerpoint') || mimeType.includes('presentation')) return 'file-powerpoint';
    return 'file';
}

// Show progress on submit
document.getElementById('uploadForm').addEventListener('submit', function() {
    document.getElementById('uploadProgress').style.display = 'block';
    let progress = 0;
    const interval = setInterval(function() {
        progress += 10;
        if (progress <= 90) {
            document.getElementById('progressBar').style.width = progress + '%';
            document.getElementById('progressBar').textContent = progress + '%';
        } else {
            clearInterval(interval);
        }
    }, 200);
});
</script>

@endsection

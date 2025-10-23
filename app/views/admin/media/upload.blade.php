@extends('admin.layout')    

@section('content')
<div class="flex h-screen bg-gray-100">
    
    <div class="flex-1 flex flex-col overflow-hidden">
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-6 py-8">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-3xl font-semibold text-gray-800">Upload Media</h1>
                        <p class="text-gray-600 mt-1">Upload files to your media library</p>
                    </div>
                    <a href="/admin/media" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Media
                    </a>
                </div>

                <!-- Upload Form -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <form id="uploadForm" method="POST" enctype="multipart/form-data" action="/admin/media/upload">
                            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                            
                            <!-- Folder Selection -->
                            <div class="mb-6">
                                <label for="folder_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Upload to Folder (Optional)
                                </label>
                                <select name="folder_id" id="folder_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">No Folder (Root)</option>
                                    <?php foreach ($folders as $folder): ?>
                                        <option value="<?= $folder['id'] ?>">
                                            <?= htmlspecialchars($folder['name']) ?>
                                            <?php if ($folder['file_count'] > 0): ?>
                                                (<?= $folder['file_count'] ?> files)
                                            <?php endif; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- File Upload Area -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Select Files
                                </label>
                                
                                <!-- Drag and Drop Area -->
                                <div id="dropZone" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors duration-200">
                                    <div id="dropZoneContent">
                                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                        <p class="text-lg text-gray-600 mb-2">Drag and drop files here</p>
                                        <p class="text-sm text-gray-500 mb-4">or</p>
                                        <button type="button" onclick="document.getElementById('fileInput').click()" 
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                                            Choose Files
                                        </button>
                                        <input type="file" id="fileInput" name="files[]" multiple 
                                               class="hidden" accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv">
                                    </div>
                                    
                                    <!-- Upload Progress -->
                                    <div id="uploadProgress" class="hidden">
                                        <div class="mb-4">
                                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                                <span>Uploading files...</span>
                                                <span id="progressText">0%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div id="progressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                                            </div>
                                        </div>
                                        <div id="uploadStatus" class="text-sm text-gray-600"></div>
                                    </div>
                                </div>
                                
                                <!-- File Type Info -->
                                <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                                    <h4 class="text-sm font-medium text-blue-800 mb-2">Supported File Types:</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm text-blue-700">
                                        <div>
                                            <strong>Images:</strong> JPG, PNG, GIF, WebP, SVG, BMP
                                        </div>
                                        <div>
                                            <strong>Videos:</strong> MP4, AVI, MOV, WMV, FLV, WebM
                                        </div>
                                        <div>
                                            <strong>Audio:</strong> MP3, WAV, OGG, AAC, FLAC
                                        </div>
                                        <div>
                                            <strong>Documents:</strong> PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, CSV
                                        </div>
                                    </div>
                                    <p class="text-xs text-blue-600 mt-2">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Maximum file size: 10MB per file
                                    </p>
                                </div>
                            </div>

                            <!-- Selected Files Preview -->
                            <div id="filePreview" class="hidden mb-6">
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Selected Files:</h4>
                                <div id="fileList" class="space-y-2"></div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end">
                                <button type="submit" id="uploadButton" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i class="fas fa-upload mr-2"></i>
                                    Upload Files
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Upload Tips -->
                <div class="mt-6 bg-white rounded-lg shadow">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Upload Tips</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-medium text-gray-800 mb-2">
                                    <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                                    Best Practices
                                </h4>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>• Use descriptive filenames</li>
                                    <li>• Optimize images before uploading</li>
                                    <li>• Keep file sizes reasonable</li>
                                    <li>• Organize files using folders</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800 mb-2">
                                    <i class="fas fa-shield-alt text-green-500 mr-2"></i>
                                    Security
                                </h4>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>• Files are automatically scanned</li>
                                    <li>• Only safe file types are allowed</li>
                                    <li>• Thumbnails are generated for images</li>
                                    <li>• All uploads are logged</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
// File upload handling
const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('fileInput');
const filePreview = document.getElementById('filePreview');
const fileList = document.getElementById('fileList');
const uploadButton = document.getElementById('uploadButton');
const uploadForm = document.getElementById('uploadForm');

// Drag and drop handlers
dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('border-blue-400', 'bg-blue-50');
});

dropZone.addEventListener('dragleave', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-blue-400', 'bg-blue-50');
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-blue-400', 'bg-blue-50');
    
    const files = e.dataTransfer.files;
    handleFiles(files);
});

// File input change handler
fileInput.addEventListener('change', (e) => {
    handleFiles(e.target.files);
});

// Handle selected files
function handleFiles(files) {
    if (files.length === 0) return;
    
    // Update file input
    const dt = new DataTransfer();
    for (let file of files) {
        dt.items.add(file);
    }
    fileInput.files = dt.files;
    
    // Show preview
    displayFilePreview(files);
    uploadButton.disabled = false;
}

// Display file preview
function displayFilePreview(files) {
    fileList.innerHTML = '';
    
    for (let file of files) {
        const fileItem = document.createElement('div');
        fileItem.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg';
        
        const fileInfo = document.createElement('div');
        fileInfo.className = 'flex items-center';
        
        const fileIcon = getFileIcon(file.type);
        const fileName = file.name;
        const fileSize = formatFileSize(file.size);
        
        fileInfo.innerHTML = `
            <i class="${fileIcon} text-xl mr-3"></i>
            <div>
                <p class="text-sm font-medium text-gray-900">${fileName}</p>
                <p class="text-xs text-gray-500">${fileSize}</p>
            </div>
        `;
        
        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.className = 'text-red-600 hover:text-red-800';
        removeButton.innerHTML = '<i class="fas fa-times"></i>';
        removeButton.onclick = () => removeFile(file.name);
        
        fileItem.appendChild(fileInfo);
        fileItem.appendChild(removeButton);
        fileList.appendChild(fileItem);
    }
    
    filePreview.classList.remove('hidden');
}

// Get file icon based on type
function getFileIcon(mimeType) {
    if (mimeType.startsWith('image/')) return 'fas fa-image text-green-600';
    if (mimeType.startsWith('video/')) return 'fas fa-video text-purple-600';
    if (mimeType.startsWith('audio/')) return 'fas fa-music text-blue-600';
    if (mimeType.includes('pdf')) return 'fas fa-file-pdf text-red-600';
    if (mimeType.includes('word') || mimeType.includes('document')) return 'fas fa-file-word text-blue-600';
    if (mimeType.includes('excel') || mimeType.includes('spreadsheet')) return 'fas fa-file-excel text-green-600';
    if (mimeType.includes('powerpoint') || mimeType.includes('presentation')) return 'fas fa-file-powerpoint text-orange-600';
    return 'fas fa-file text-gray-600';
}

// Remove file from selection
function removeFile(fileName) {
    const dt = new DataTransfer();
    for (let file of fileInput.files) {
        if (file.name !== fileName) {
            dt.items.add(file);
        }
    }
    fileInput.files = dt.files;
    
    if (fileInput.files.length === 0) {
        filePreview.classList.add('hidden');
        uploadButton.disabled = true;
    } else {
        displayFilePreview(fileInput.files);
    }
}

// Format file size
function formatFileSize(bytes) {
    const units = ['B', 'KB', 'MB', 'GB'];
    let size = bytes;
    let unitIndex = 0;
    
    while (size >= 1024 && unitIndex < units.length - 1) {
        size /= 1024;
        unitIndex++;
    }
    
    return Math.round(size * 100) / 100 + ' ' + units[unitIndex];
}

// Form submission with progress
uploadForm.addEventListener('submit', (e) => {
    e.preventDefault();
    
    if (fileInput.files.length === 0) {
        alert('Please select files to upload');
        return;
    }
    
    const formData = new FormData(uploadForm);
    
    // Show progress
    document.getElementById('dropZoneContent').classList.add('hidden');
    document.getElementById('uploadProgress').classList.remove('hidden');
    uploadButton.disabled = true;
    
    // Upload with progress tracking
    const xhr = new XMLHttpRequest();
    
    xhr.upload.addEventListener('progress', (e) => {
        if (e.lengthComputable) {
            const percentComplete = (e.loaded / e.total) * 100;
            document.getElementById('progressBar').style.width = percentComplete + '%';
            document.getElementById('progressText').textContent = Math.round(percentComplete) + '%';
        }
    });
    
    xhr.addEventListener('load', () => {
        if (xhr.status === 200) {
            window.location.href = '/admin/media';
        } else {
            alert('Upload failed. Please try again.');
            resetUploadForm();
        }
    });
    
    xhr.addEventListener('error', () => {
        alert('Upload failed. Please try again.');
        resetUploadForm();
    });
    
    xhr.open('POST', '/admin/media/upload');
    xhr.send(formData);
});

// Reset upload form
function resetUploadForm() {
    document.getElementById('dropZoneContent').classList.remove('hidden');
    document.getElementById('uploadProgress').classList.add('hidden');
    document.getElementById('progressBar').style.width = '0%';
    document.getElementById('progressText').textContent = '0%';
    uploadButton.disabled = false;
}

// Initial state
uploadButton.disabled = true;
</script>

@endsection

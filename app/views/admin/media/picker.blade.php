<!-- Media Picker Modal -->
<div id="mediaPicker" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-5/6 max-w-6xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium text-gray-900">Select Media</h3>
                <button onclick="closeMediaPicker()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Filters -->
            <div class="mb-6 flex flex-wrap gap-4">
                <div class="flex-1 min-w-64">
                    <input type="text" id="pickerSearch" placeholder="Search files..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <select id="pickerType" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Types</option>
                    <option value="image">Images</option>
                    <option value="video">Videos</option>
                    <option value="audio">Audio</option>
                    <option value="document">Documents</option>
                    <option value="other">Other</option>
                </select>
                
                <select id="pickerFolder" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Folders</option>
                </select>
                
                <button onclick="filterPickerMedia()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                
                <button onclick="clearPickerFilters()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-times mr-2"></i>Clear
                </button>
            </div>

            <!-- Media Grid -->
            <div id="pickerContent" class="mb-6">
                <div id="pickerLoading" class="text-center py-12">
                    <i class="fas fa-spinner fa-spin text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600">Loading media...</p>
                </div>
                
                <div id="pickerGrid" class="hidden grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 max-h-96 overflow-y-auto">
                    <!-- Media items will be loaded here -->
                </div>
                
                <div id="pickerEmpty" class="hidden text-center py-12">
                    <i class="fas fa-folder-open text-6xl text-gray-300 mb-4"></i>
                    <h4 class="text-xl font-medium text-gray-900 mb-2">No media found</h4>
                    <p class="text-gray-500">Try adjusting your search criteria or upload new files.</p>
                </div>
            </div>

            <!-- Selected Media Preview -->
            <div id="selectedMediaPreview" class="hidden mb-6 p-4 bg-blue-50 rounded-lg">
                <h4 class="text-sm font-medium text-blue-800 mb-2">Selected Media:</h4>
                <div id="selectedMediaInfo" class="flex items-center space-x-4">
                    <!-- Selected media info will be shown here -->
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    <span id="pickerResultCount">0 files</span>
                </div>
                
                <div class="flex space-x-3">
                    <button onclick="closeMediaPicker()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button id="selectMediaButton" onclick="selectMedia()" disabled
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed">
                        Select Media
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Media Picker Variables
let pickerCallback = null;
let selectedMedia = null;
let pickerData = { media: [], folders: [] };

// Open Media Picker
function openMediaPicker(callback, options = {}) {
    pickerCallback = callback;
    selectedMedia = null;
    
    // Set filter options if provided
    if (options.type) {
        document.getElementById('pickerType').value = options.type;
    }
    
    // Show modal
    document.getElementById('mediaPicker').classList.remove('hidden');
    
    // Load media
    loadPickerMedia();
}

// Close Media Picker
function closeMediaPicker() {
    document.getElementById('mediaPicker').classList.add('hidden');
    pickerCallback = null;
    selectedMedia = null;
    clearPickerFilters();
}

// Load Media for Picker
function loadPickerMedia() {
    const search = document.getElementById('pickerSearch').value;
    const type = document.getElementById('pickerType').value;
    const folder = document.getElementById('pickerFolder').value;
    
    // Show loading
    document.getElementById('pickerLoading').classList.remove('hidden');
    document.getElementById('pickerGrid').classList.add('hidden');
    document.getElementById('pickerEmpty').classList.add('hidden');
    
    // Build query parameters
    const params = new URLSearchParams();
    if (search) params.append('search', search);
    if (type) params.append('type', type);
    if (folder) params.append('folder', folder);
    
    // Fetch media
    fetch(`/admin/media/picker?${params.toString()}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                pickerData = data;
                displayPickerMedia(data.media);
                updatePickerFolders(data.folders);
            } else {
                console.error('Failed to load media');
                showPickerEmpty();
            }
        })
        .catch(error => {
            console.error('Error loading media:', error);
            showPickerEmpty();
        });
}

// Display Media in Picker
function displayPickerMedia(media) {
    const grid = document.getElementById('pickerGrid');
    const loading = document.getElementById('pickerLoading');
    const empty = document.getElementById('pickerEmpty');
    const resultCount = document.getElementById('pickerResultCount');
    
    loading.classList.add('hidden');
    
    if (media.length === 0) {
        empty.classList.remove('hidden');
        grid.classList.add('hidden');
        resultCount.textContent = '0 files';
        return;
    }
    
    // Build media grid
    grid.innerHTML = '';
    media.forEach(item => {
        const mediaItem = createPickerMediaItem(item);
        grid.appendChild(mediaItem);
    });
    
    grid.classList.remove('hidden');
    empty.classList.add('hidden');
    resultCount.textContent = `${media.length} file${media.length !== 1 ? 's' : ''}`;
}

// Create Media Item for Picker
function createPickerMediaItem(item) {
    const div = document.createElement('div');
    div.className = 'group relative bg-gray-50 rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-200 cursor-pointer';
    div.onclick = () => selectPickerMedia(item);
    
    // Preview
    let preview = '';
    if (item.file_type === 'image') {
        const imageSrc = item.thumbnail_path || item.file_path;
        preview = `<img src="${imageSrc}" alt="${item.alt_text || item.original_name}" class="w-full h-32 object-cover">`;
    } else {
        const iconClass = getFileIcon(item.file_type);
        const extension = item.filename.split('.').pop().toUpperCase();
        preview = `
            <div class="w-full h-32 flex items-center justify-center">
                <div class="text-center">
                    <i class="${iconClass} text-4xl mb-2"></i>
                    <p class="text-xs text-gray-600">${extension}</p>
                </div>
            </div>
        `;
    }
    
    div.innerHTML = `
        ${preview}
        <div class="p-3 bg-white">
            <h4 class="text-sm font-medium text-gray-900 truncate" title="${item.original_name}">
                ${item.title || item.original_name}
            </h4>
            <p class="text-xs text-gray-500 mt-1">
                ${formatFileSize(item.file_size)}
            </p>
        </div>
        <div class="absolute inset-0 bg-blue-600 bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 flex items-center justify-center">
            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                <i class="fas fa-check-circle text-white text-2xl"></i>
            </div>
        </div>
    `;
    
    return div;
}

// Select Media Item
function selectPickerMedia(item) {
    selectedMedia = item;
    
    // Update visual selection
    const grid = document.getElementById('pickerGrid');
    const items = grid.querySelectorAll('.group');
    items.forEach(el => {
        el.classList.remove('ring-4', 'ring-blue-500');
    });
    
    // Find and highlight selected item
    const selectedElement = Array.from(items).find(el => {
        const title = el.querySelector('h4').textContent;
        return title === (item.title || item.original_name);
    });
    
    if (selectedElement) {
        selectedElement.classList.add('ring-4', 'ring-blue-500');
    }
    
    // Show selected media preview
    showSelectedMediaPreview(item);
    
    // Enable select button
    document.getElementById('selectMediaButton').disabled = false;
}

// Show Selected Media Preview
function showSelectedMediaPreview(item) {
    const preview = document.getElementById('selectedMediaPreview');
    const info = document.getElementById('selectedMediaInfo');
    
    let thumbnail = '';
    if (item.file_type === 'image') {
        const imageSrc = item.thumbnail_path || item.file_path;
        thumbnail = `<img src="${imageSrc}" alt="${item.alt_text || item.original_name}" class="w-16 h-16 object-cover rounded">`;
    } else {
        const iconClass = getFileIcon(item.file_type);
        thumbnail = `
            <div class="w-16 h-16 bg-gray-100 rounded flex items-center justify-center">
                <i class="${iconClass} text-2xl"></i>
            </div>
        `;
    }
    
    info.innerHTML = `
        ${thumbnail}
        <div>
            <h5 class="font-medium text-gray-900">${item.title || item.original_name}</h5>
            <p class="text-sm text-gray-600">${formatFileSize(item.file_size)} • ${item.file_type}</p>
            <p class="text-xs text-gray-500">${item.mime_type}</p>
        </div>
    `;
    
    preview.classList.remove('hidden');
}

// Select Media (Final Action)
function selectMedia() {
    if (selectedMedia && pickerCallback) {
        pickerCallback(selectedMedia);
        closeMediaPicker();
    }
}

// Filter Media
function filterPickerMedia() {
    loadPickerMedia();
}

// Clear Filters
function clearPickerFilters() {
    document.getElementById('pickerSearch').value = '';
    document.getElementById('pickerType').value = '';
    document.getElementById('pickerFolder').value = '';
    document.getElementById('selectedMediaPreview').classList.add('hidden');
    document.getElementById('selectMediaButton').disabled = true;
}

// Update Folder Options
function updatePickerFolders(folders) {
    const folderSelect = document.getElementById('pickerFolder');
    const currentValue = folderSelect.value;
    
    // Clear existing options except "All Folders"
    folderSelect.innerHTML = '<option value="">All Folders</option>';
    
    // Add folder options
    folders.forEach(folder => {
        const option = document.createElement('option');
        option.value = folder.id;
        option.textContent = folder.name;
        if (folder.file_count > 0) {
            option.textContent += ` (${folder.file_count} files)`;
        }
        folderSelect.appendChild(option);
    });
    
    // Restore selected value if it still exists
    if (currentValue) {
        folderSelect.value = currentValue;
    }
}

// Show Empty State
function showPickerEmpty() {
    document.getElementById('pickerLoading').classList.add('hidden');
    document.getElementById('pickerGrid').classList.add('hidden');
    document.getElementById('pickerEmpty').classList.remove('hidden');
    document.getElementById('pickerResultCount').textContent = '0 files';
}

// Get File Icon
function getFileIcon(fileType) {
    switch (fileType) {
        case 'image': return 'fas fa-image text-green-600';
        case 'video': return 'fas fa-video text-purple-600';
        case 'audio': return 'fas fa-music text-blue-600';
        case 'document': return 'fas fa-file-alt text-red-600';
        default: return 'fas fa-file text-gray-600';
    }
}

// Format File Size
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

// Search on Enter key
document.getElementById('pickerSearch').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        filterPickerMedia();
    }
});

// Close modal when clicking outside
document.getElementById('mediaPicker').addEventListener('click', function(e) {
    if (e.target === this) {
        closeMediaPicker();
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('mediaPicker').classList.contains('hidden')) {
        closeMediaPicker();
    }
});
</script>

@extends('admin.layout')

@section('content')
<div class="flex h-screen bg-gray-100">
    
    <div class="flex-1 flex flex-col overflow-hidden">
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-6 py-8">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-3xl font-semibold text-gray-800">Media Library</h1>
                        <p class="text-gray-600 mt-1">Manage your uploaded files and media</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="/admin/media/upload" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                            <i class="fas fa-upload mr-2"></i>
                            Upload Files
                        </a>
                        <a href="/admin/media/folders" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                            <i class="fas fa-folder mr-2"></i>
                            Manage Folders
                        </a>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-file text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Files</p>
                                <p class="text-2xl font-semibold text-gray-900"><?= $stats['total_files'] ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-images text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Images</p>
                                <p class="text-2xl font-semibold text-gray-900"><?= $stats['images'] ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                <i class="fas fa-video text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Videos</p>
                                <p class="text-2xl font-semibold text-gray-900"><?= $stats['videos'] ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                <i class="fas fa-file-alt text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Documents</p>
                                <p class="text-2xl font-semibold text-gray-900"><?= $stats['documents'] ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                                <i class="fas fa-hdd text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Storage Used</p>
                                <p class="text-2xl font-semibold text-gray-900"><?= formatFileSize($stats['total_size']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters and Search -->
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="p-6">
                        <form method="GET" class="flex flex-wrap gap-4">
                            <!-- Search -->
                            <div class="flex-1 min-w-64">
                                <input type="text" name="search" placeholder="Search files..." 
                                       value="<?= htmlspecialchars($filters['search']) ?>"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            
                            <!-- File Type Filter -->
                            <select name="type" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">All Types</option>
                                <option value="image" <?= $filters['type'] === 'image' ? 'selected' : '' ?>>Images</option>
                                <option value="video" <?= $filters['type'] === 'video' ? 'selected' : '' ?>>Videos</option>
                                <option value="audio" <?= $filters['type'] === 'audio' ? 'selected' : '' ?>>Audio</option>
                                <option value="document" <?= $filters['type'] === 'document' ? 'selected' : '' ?>>Documents</option>
                                <option value="other" <?= $filters['type'] === 'other' ? 'selected' : '' ?>>Other</option>
                            </select>
                            
                            <!-- Folder Filter -->
                            <select name="folder" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">All Folders</option>
                                <?php foreach ($folders as $folder): ?>
                                    <option value="<?= $folder['id'] ?>" <?= $filters['folder_id'] == $folder['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($folder['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                                <i class="fas fa-search mr-2"></i>Filter
                            </button>
                            
                            <a href="/admin/media" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                                <i class="fas fa-times mr-2"></i>Clear
                            </a>
                        </form>
                    </div>
                </div>

                <!-- Breadcrumb -->
                <?php if (!empty($breadcrumb)): ?>
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="p-4">
                        <nav class="flex" aria-label="Breadcrumb">
                            <ol class="flex items-center space-x-4">
                                <li>
                                    <a href="/admin/media" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-home"></i>
                                        <span class="sr-only">Home</span>
                                    </a>
                                </li>
                                <?php foreach ($breadcrumb as $folder): ?>
                                <li>
                                    <div class="flex items-center">
                                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                        <a href="/admin/media?folder=<?= $folder['id'] ?>" 
                                           class="text-blue-600 hover:text-blue-800">
                                            <?= htmlspecialchars($folder['name']) ?>
                                        </a>
                                    </div>
                                </li>
                                <?php endforeach; ?>
                            </ol>
                        </nav>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Media Grid -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <?php if (empty($media)): ?>
                            <div class="text-center py-12">
                                <i class="fas fa-folder-open text-6xl text-gray-300 mb-4"></i>
                                <h3 class="text-xl font-medium text-gray-900 mb-2">No files found</h3>
                                <p class="text-gray-500 mb-6">Get started by uploading your first file.</p>
                                <a href="/admin/media/upload" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg inline-flex items-center">
                                    <i class="fas fa-upload mr-2"></i>
                                    Upload Files
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                                <?php foreach ($media as $item): ?>
                                    <div class="group relative bg-gray-50 rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-200">
                                        <!-- File Preview -->
                                        <div class="aspect-square flex items-center justify-center p-4">
                                            <?php if ($item['file_type'] === 'image'): ?>
                                                <?php if ($item['thumbnail_path']): ?>
                                                    <img src="<?= $item['thumbnail_path'] ?>" 
                                                         alt="<?= htmlspecialchars($item['alt_text'] ?: $item['original_name']) ?>"
                                                         class="w-full h-full object-cover rounded">
                                                <?php else: ?>
                                                    <img src="<?= $item['file_path'] ?>" 
                                                         alt="<?= htmlspecialchars($item['alt_text'] ?: $item['original_name']) ?>"
                                                         class="w-full h-full object-cover rounded">
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <div class="text-center">
                                                    <?php
                                                    $iconClass = $item['file_type'] === 'video' ? 'fas fa-video text-purple-500' : 
                                                                 ($item['file_type'] === 'audio' ? 'fas fa-music text-green-500' : 
                                                                  ($item['file_type'] === 'document' ? 'fas fa-file-alt text-red-500' : 
                                                                   'fas fa-file text-gray-500'));
                                                    ?>
                                                    <i class="<?= $iconClass ?> text-4xl mb-2"></i>
                                                    <p class="text-xs text-gray-600 truncate">
                                                        <?= strtoupper(pathinfo($item['filename'], PATHINFO_EXTENSION)) ?>
                                                    </p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- File Info -->
                                        <div class="p-3 bg-white">
                                            <h4 class="text-sm font-medium text-gray-900 truncate" title="<?= htmlspecialchars($item['original_name']) ?>">
                                                <?= htmlspecialchars($item['title'] ?: $item['original_name']) ?>
                                            </h4>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <?= formatFileSize($item['file_size']) ?>
                                            </p>
                                            <?php if ($item['folder_name']): ?>
                                                <p class="text-xs text-blue-600 mt-1">
                                                    <i class="fas fa-folder mr-1"></i>
                                                    <?= htmlspecialchars($item['folder_name']) ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- Actions Overlay -->
                                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                                            <div class="flex space-x-2">
                                                <a href="<?= $item['file_path'] ?>" target="_blank" 
                                                   class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-full" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="/admin/media/edit?id=<?= $item['id'] ?>" 
                                                   class="bg-green-600 hover:bg-green-700 text-white p-2 rounded-full" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="/admin/media/download?id=<?= $item['id'] ?>" 
                                                   class="bg-purple-600 hover:bg-purple-700 text-white p-2 rounded-full" title="Download">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <button onclick="deleteMedia(<?= $item['id'] ?>, '<?= htmlspecialchars($item['original_name']) ?>')" 
                                                        class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-full" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Delete Media File</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Are you sure you want to delete "<span id="deleteFileName"></span>"? This action cannot be undone.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <form id="deleteForm" method="POST" action="/admin/media/delete">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    <input type="hidden" name="id" id="deleteMediaId">
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-700">
                        Delete
                    </button>
                    <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-24 hover:bg-gray-400">
                        Cancel
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteMedia(id, filename) {
    document.getElementById('deleteMediaId').value = id;
    document.getElementById('deleteFileName').textContent = filename;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Helper function for file size formatting
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
</script>

@endsection

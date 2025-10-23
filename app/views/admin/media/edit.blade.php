@include('admin.partials.header')

<div class="flex h-screen bg-gray-100">
    @include('admin.partials.sidebar')
    
    <div class="flex-1 flex flex-col overflow-hidden">
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-6 py-8">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-3xl font-semibold text-gray-800">Edit Media</h1>
                        <p class="text-gray-600 mt-1">Update media information and settings</p>
                    </div>
                    <a href="/admin/media" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Media
                    </a>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Media Preview -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Media Preview</h3>
                                
                                <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden mb-4">
                                    <?php if ($media['file_type'] === 'image'): ?>
                                        <?php if ($media['thumbnail_path']): ?>
                                            <img src="<?= $media['thumbnail_path'] ?>" 
                                                 alt="<?= htmlspecialchars($media['alt_text'] ?: $media['original_name']) ?>"
                                                 class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <img src="<?= $media['file_path'] ?>" 
                                                 alt="<?= htmlspecialchars($media['alt_text'] ?: $media['original_name']) ?>"
                                                 class="w-full h-full object-cover">
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center">
                                            <?php
                                            $iconClass = match($media['file_type']) {
                                                'video' => 'fas fa-video text-purple-500',
                                                'audio' => 'fas fa-music text-green-500',
                                                'document' => 'fas fa-file-alt text-red-500',
                                                default => 'fas fa-file text-gray-500'
                                            };
                                            ?>
                                            <div class="text-center">
                                                <i class="<?= $iconClass ?> text-6xl mb-4"></i>
                                                <p class="text-sm text-gray-600">
                                                    <?= strtoupper(pathinfo($media['filename'], PATHINFO_EXTENSION)) ?>
                                                </p>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- File Information -->
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-sm font-medium text-gray-700">Original Name:</label>
                                        <p class="text-sm text-gray-900 break-all"><?= htmlspecialchars($media['original_name']) ?></p>
                                    </div>
                                    
                                    <div>
                                        <label class="text-sm font-medium text-gray-700">File Size:</label>
                                        <p class="text-sm text-gray-900"><?= formatFileSize($media['file_size']) ?></p>
                                    </div>
                                    
                                    <div>
                                        <label class="text-sm font-medium text-gray-700">File Type:</label>
                                        <p class="text-sm text-gray-900 capitalize"><?= $media['file_type'] ?></p>
                                    </div>
                                    
                                    <div>
                                        <label class="text-sm font-medium text-gray-700">MIME Type:</label>
                                        <p class="text-sm text-gray-900"><?= $media['mime_type'] ?></p>
                                    </div>
                                    
                                    <?php if ($media['width'] && $media['height']): ?>
                                    <div>
                                        <label class="text-sm font-medium text-gray-700">Dimensions:</label>
                                        <p class="text-sm text-gray-900"><?= $media['width'] ?> × <?= $media['height'] ?> pixels</p>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <div>
                                        <label class="text-sm font-medium text-gray-700">Uploaded:</label>
                                        <p class="text-sm text-gray-900"><?= date('M j, Y g:i A', strtotime($media['created_at'])) ?></p>
                                    </div>
                                    
                                    <div>
                                        <label class="text-sm font-medium text-gray-700">Uploaded by:</label>
                                        <p class="text-sm text-gray-900"><?= htmlspecialchars($media['uploaded_by_name']) ?></p>
                                    </div>
                                    
                                    <div>
                                        <label class="text-sm font-medium text-gray-700">Downloads:</label>
                                        <p class="text-sm text-gray-900"><?= $media['download_count'] ?></p>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-6 space-y-2">
                                    <a href="<?= $media['file_path'] ?>" target="_blank" 
                                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-eye mr-2"></i>
                                        View Original
                                    </a>
                                    
                                    <a href="/admin/media/download?id=<?= $media['id'] ?>" 
                                       class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-download mr-2"></i>
                                        Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Form -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-6">Media Information</h3>
                                
                                <form method="POST" action="/admin/media/edit?id=<?= $media['id'] ?>">
                                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                                    
                                    <!-- Title -->
                                    <div class="mb-6">
                                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                            Title
                                        </label>
                                        <input type="text" id="title" name="title" 
                                               value="<?= htmlspecialchars($media['title'] ?? '') ?>"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                               placeholder="Enter a descriptive title">
                                        <p class="text-xs text-gray-500 mt-1">A descriptive title for this media file</p>
                                    </div>

                                    <!-- Alt Text (for images) -->
                                    <?php if ($media['file_type'] === 'image'): ?>
                                    <div class="mb-6">
                                        <label for="alt_text" class="block text-sm font-medium text-gray-700 mb-2">
                                            Alt Text <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="alt_text" name="alt_text" 
                                               value="<?= htmlspecialchars($media['alt_text'] ?? '') ?>"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                               placeholder="Describe the image for accessibility"
                                               required>
                                        <p class="text-xs text-gray-500 mt-1">Alternative text for screen readers and accessibility</p>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Description -->
                                    <div class="mb-6">
                                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                            Description
                                        </label>
                                        <textarea id="description" name="description" rows="4"
                                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                  placeholder="Enter a detailed description"><?= htmlspecialchars($media['description'] ?? '') ?></textarea>
                                        <p class="text-xs text-gray-500 mt-1">Optional detailed description of the media file</p>
                                    </div>

                                    <!-- Folder -->
                                    <div class="mb-6">
                                        <label for="folder_id" class="block text-sm font-medium text-gray-700 mb-2">
                                            Folder
                                        </label>
                                        <select name="folder_id" id="folder_id" 
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="">No Folder (Root)</option>
                                            <?php foreach ($folders as $folder): ?>
                                                <option value="<?= $folder['id'] ?>" 
                                                        <?= $media['folder_id'] == $folder['id'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($folder['name']) ?>
                                                    <?php if ($folder['file_count'] > 0): ?>
                                                        (<?= $folder['file_count'] ?> files)
                                                    <?php endif; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <p class="text-xs text-gray-500 mt-1">Organize your media by moving it to a folder</p>
                                    </div>

                                    <!-- Visibility -->
                                    <div class="mb-6">
                                        <div class="flex items-center">
                                            <input type="checkbox" id="is_public" name="is_public" 
                                                   <?= $media['is_public'] ? 'checked' : '' ?>
                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                            <label for="is_public" class="ml-2 block text-sm text-gray-900">
                                                Public Access
                                            </label>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Allow public access to this file</p>
                                    </div>

                                    <!-- File URL -->
                                    <div class="mb-6">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            File URL
                                        </label>
                                        <div class="flex">
                                            <input type="text" readonly 
                                                   value="<?= main_url() . $media['file_path'] ?>"
                                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg bg-gray-50 text-gray-600">
                                            <button type="button" onclick="copyToClipboard('<?= main_url() . $media['file_path'] ?>')"
                                                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-r-lg">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Direct URL to access this file</p>
                                    </div>

                                    <!-- Submit Buttons -->
                                    <div class="flex justify-end space-x-3">
                                        <a href="/admin/media" 
                                           class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg">
                                            Cancel
                                        </a>
                                        <button type="submit" 
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                                            <i class="fas fa-save mr-2"></i>
                                            Update Media
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Usage Information -->
                        <div class="bg-white rounded-lg shadow mt-6">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Usage Information</h3>
                                
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h4 class="text-sm font-medium text-yellow-800">
                                                Important Notes
                                            </h4>
                                            <div class="mt-2 text-sm text-yellow-700">
                                                <ul class="list-disc list-inside space-y-1">
                                                    <li>Changing the folder will not affect existing links to this file</li>
                                                    <li>The file URL will remain the same regardless of folder changes</li>
                                                    <li>Alt text is important for SEO and accessibility</li>
                                                    <li>Making a file private will restrict public access</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
// Copy to clipboard function
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const button = event.target.closest('button');
        const originalContent = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check"></i>';
        button.classList.remove('bg-gray-600', 'hover:bg-gray-700');
        button.classList.add('bg-green-600');
        
        setTimeout(() => {
            button.innerHTML = originalContent;
            button.classList.remove('bg-green-600');
            button.classList.add('bg-gray-600', 'hover:bg-gray-700');
        }, 2000);
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
        alert('Failed to copy URL to clipboard');
    });
}

// Format file size helper function
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

@include('admin.partials.footer')

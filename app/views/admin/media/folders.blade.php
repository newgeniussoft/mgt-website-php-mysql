@extends('admin.layout')

@section('content')
<div class="flex h-screen bg-gray-100">
    
    <div class="flex-1 flex flex-col overflow-hidden">
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-6 py-8">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-3xl font-semibold text-gray-800">Manage Folders</h1>
                        <p class="text-gray-600 mt-1">Organize your media files with folders</p>
                    </div>
                    <div class="flex space-x-3">
                        <button onclick="openCreateModal()" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            New Folder
                        </button>
                        <a href="/admin/media" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Media
                        </a>
                    </div>
                </div>

                <!-- Folders List -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <?php if (empty($folders)): ?>
                            <div class="text-center py-12">
                                <i class="fas fa-folder text-6xl text-gray-300 mb-4"></i>
                                <h3 class="text-xl font-medium text-gray-900 mb-2">No folders created</h3>
                                <p class="text-gray-500 mb-6">Create your first folder to organize your media files.</p>
                                <button onclick="openCreateModal()" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg inline-flex items-center">
                                    <i class="fas fa-plus mr-2"></i>
                                    Create Folder
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="space-y-4">
                                <?php foreach ($folders as $folder): ?>
                                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-4">
                                                <div class="p-3 bg-blue-100 rounded-lg">
                                                    <i class="fas fa-folder text-blue-600 text-xl"></i>
                                                </div>
                                                <div>
                                                    <h4 class="text-lg font-medium text-gray-900">
                                                        <?= htmlspecialchars($folder['name']) ?>
                                                    </h4>
                                                    <?php if ($folder['description']): ?>
                                                        <p class="text-sm text-gray-600 mt-1">
                                                            <?= htmlspecialchars($folder['description']) ?>
                                                        </p>
                                                    <?php endif; ?>
                                                    <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                                        <span>
                                                            <i class="fas fa-file mr-1"></i>
                                                            <?= $folder['file_count'] ?> files
                                                        </span>
                                                        <?php if (isset($folder['subfolder_count']) && $folder['subfolder_count'] > 0): ?>
                                                            <span>
                                                                <i class="fas fa-folder mr-1"></i>
                                                                <?= $folder['subfolder_count'] ?> subfolders
                                                            </span>
                                                        <?php endif; ?>
                                                        <span>
                                                            Created: <?= date('M j, Y', strtotime($folder['created_at'])) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="flex items-center space-x-2">
                                                <a href="/admin/media?folder=<?= $folder['id'] ?>" 
                                                   class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm"
                                                   title="View Files">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button onclick="openEditModal(<?= $folder['id'] ?>, '<?= htmlspecialchars($folder['name']) ?>', '<?= htmlspecialchars($folder['description'] ?? '') ?>', <?= $folder['parent_id'] ?? 'null' ?>)" 
                                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm"
                                                        title="Edit Folder">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button onclick="openDeleteModal(<?= $folder['id'] ?>, '<?= htmlspecialchars($folder['name']) ?>', <?= $folder['file_count'] ?>, <?= $folder['subfolder_count'] ?? 0 ?>)" 
                                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm"
                                                        title="Delete Folder">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <!-- Subfolders -->
                                        <?php if (!empty($folder['subfolders'])): ?>
                                            <div class="ml-12 mt-4 space-y-2">
                                                <?php foreach ($folder['subfolders'] as $subfolder): ?>
                                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                                        <div class="flex items-center space-x-3">
                                                            <i class="fas fa-folder text-gray-400"></i>
                                                            <div>
                                                                <span class="text-sm font-medium text-gray-700">
                                                                    <?= htmlspecialchars($subfolder['name']) ?>
                                                                </span>
                                                                <span class="text-xs text-gray-500 ml-2">
                                                                    (<?= $subfolder['file_count'] ?> files)
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center space-x-1">
                                                            <a href="/admin/media?folder=<?= $subfolder['id'] ?>" 
                                                               class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs"
                                                               title="View Files">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <button onclick="openEditModal(<?= $subfolder['id'] ?>, '<?= htmlspecialchars($subfolder['name']) ?>', '<?= htmlspecialchars($subfolder['description'] ?? '') ?>', <?= $subfolder['parent_id'] ?? 'null' ?>)" 
                                                                    class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs"
                                                                    title="Edit Folder">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button onclick="openDeleteModal(<?= $subfolder['id'] ?>, '<?= htmlspecialchars($subfolder['name']) ?>', <?= $subfolder['file_count'] ?>, <?= $subfolder['subfolder_count'] ?? 0 ?>)" 
                                                                    class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs"
                                                                    title="Delete Folder">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
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

<!-- Create Folder Modal -->
<div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Create New Folder</h3>
            <form id="createForm" method="POST" action="/admin/media/folders">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="action" value="create">
                
                <div class="mb-4">
                    <label for="create_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Folder Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="create_name" name="name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Enter folder name">
                </div>
                
                <div class="mb-4">
                    <label for="create_description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea id="create_description" name="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Optional description"></textarea>
                </div>
                
                <div class="mb-6">
                    <label for="create_parent_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Parent Folder
                    </label>
                    <select name="parent_id" id="create_parent_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Root (No Parent)</option>
                        <?php foreach ($folders as $folder): ?>
                            <option value="<?= $folder['id'] ?>">
                                <?= htmlspecialchars($folder['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeCreateModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Create Folder
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Folder Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Folder</h3>
            <form id="editForm" method="POST" action="/admin/media/folders">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="folder_id" id="edit_folder_id">
                
                <div class="mb-4">
                    <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Folder Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="edit_name" name="name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div class="mb-4">
                    <label for="edit_description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea id="edit_description" name="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                </div>
                
                <div class="mb-6">
                    <label for="edit_parent_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Parent Folder
                    </label>
                    <select name="parent_id" id="edit_parent_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Root (No Parent)</option>
                        <?php foreach ($folders as $folder): ?>
                            <option value="<?= $folder['id'] ?>">
                                <?= htmlspecialchars($folder['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeEditModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Update Folder
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Delete Folder</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500" id="deleteMessage">
                    Are you sure you want to delete this folder?
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <form id="deleteForm" method="POST" action="/admin/media/folders">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="folder_id" id="delete_folder_id">
                    <button type="submit" id="deleteButton" 
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-700">
                        Delete
                    </button>
                    <button type="button" onclick="closeDeleteModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-24 hover:bg-gray-400">
                        Cancel
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Create Modal Functions
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
    document.getElementById('create_name').focus();
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
    document.getElementById('createForm').reset();
}

// Edit Modal Functions
function openEditModal(id, name, description, parentId) {
    document.getElementById('edit_folder_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_description').value = description;
    document.getElementById('edit_parent_id').value = parentId || '';
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('edit_name').focus();
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editForm').reset();
}

// Delete Modal Functions
function openDeleteModal(id, name, fileCount, subfolderCount) {
    document.getElementById('delete_folder_id').value = id;
    
    let message = `Are you sure you want to delete "${name}"?`;
    
    if (fileCount > 0 || subfolderCount > 0) {
        message = `Cannot delete "${name}" because it contains `;
        const parts = [];
        if (fileCount > 0) parts.push(`${fileCount} file${fileCount > 1 ? 's' : ''}`);
        if (subfolderCount > 0) parts.push(`${subfolderCount} subfolder${subfolderCount > 1 ? 's' : ''}`);
        message += parts.join(' and ') + '. Please move or delete the contents first.';
        
        document.getElementById('deleteButton').disabled = true;
        document.getElementById('deleteButton').classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        message += ' This action cannot be undone.';
        document.getElementById('deleteButton').disabled = false;
        document.getElementById('deleteButton').classList.remove('opacity-50', 'cursor-not-allowed');
    }
    
    document.getElementById('deleteMessage').textContent = message;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modals when clicking outside
document.getElementById('createModal').addEventListener('click', function(e) {
    if (e.target === this) closeCreateModal();
});

document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});

// Escape key to close modals
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCreateModal();
        closeEditModal();
        closeDeleteModal();
    }
});
</script>

@endsection

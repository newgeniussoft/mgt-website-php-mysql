// Global variables
let editors = {};
let openTabs = [];
let activeTab = null;
let currentPanel = 'explorer';
let contextTarget = null;
let fileToClose = null;
let fileTreeData = [];
let selectedDestFolder = '';
let resumableUpload = null;
let selectedFiles = [];
let lastSelectedFile = null;

const languageMap = {
    'html': { lang: 'html', display: 'HTML', icon: 'html-icon' },
    'css': { lang: 'css', display: 'CSS', icon: 'css-icon' },
    'js': { lang: 'javascript', display: 'JavaScript', icon: 'js-icon' },
    'php': { lang: 'php', display: 'PHP', icon: 'js-icon' },
    'json': { lang: 'json', display: 'JSON', icon: 'json-icon' },
    'md': { lang: 'markdown', display: 'Markdown', icon: 'md-icon' },
    'txt': { lang: 'plaintext', display: 'Plain Text', icon: 'txt-icon' },
    'xml': { lang: 'xml', display: 'XML', icon: 'html-icon' },
    'sql': { lang: 'sql', display: 'SQL', icon: 'json-icon' },
    'zip': { lang: 'plaintext', display: 'ZIP Archive', icon: 'json-icon' }
};

// Initialize Monaco Editor
require.config({ paths: { vs: 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs' } });

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    loadFileTree();
    
    // Wait for Resumable to load
    const checkResumable = setInterval(() => {
        if (typeof Resumable !== 'undefined') {
            clearInterval(checkResumable);
            initializeUpload();
        }
    }, 100);
    
    document.querySelectorAll('.sidebar-icon').forEach(icon => {
        icon.addEventListener('click', function() {
            togglePanel(this.getAttribute('data-panel'));
        });
    });

    setupPanelResizer();
    
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.context-menu')) {
            closeContextMenu();
        }
        // Clear selection if clicking outside file items
        if (!e.target.closest('.file-item') && !e.target.closest('.folder')) {
            clearSelection();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal.show').forEach(modal => {
                modal.classList.remove('show');
            });
            clearSelection();
        }
        
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            saveCurrentFile();
        }
        
        if (e.ctrlKey && e.key === 'w') {
            e.preventDefault();
            closeCurrentTab();
        }
        
        if (e.ctrlKey && e.key === 'a') {
            if (document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
                e.preventDefault();
                selectAllFiles();
            }
        }
        
        if (e.key === 'Delete' && selectedFiles.length > 0) {
            showDeleteMultipleDialog();
        }
    });

    // Modal input handlers
    document.getElementById('newFileInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') createNewFile();
    });
    document.getElementById('newFolderInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') createNewFolder();
    });
    document.getElementById('renameInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') confirmRename();
    });
    document.getElementById('zipNameInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') confirmCompress();
    });
});

// Selection Functions
function clearSelection() {
    selectedFiles = [];
    lastSelectedFile = null;
    document.querySelectorAll('.file-item, .folder').forEach(item => {
        item.classList.remove('selected');
    });
}

function toggleSelection(path, element, event) {
    if (event.ctrlKey || event.metaKey) {
        // Ctrl+Click: Toggle individual item
        const index = selectedFiles.indexOf(path);
        if (index > -1) {
            selectedFiles.splice(index, 1);
            element.classList.remove('selected');
        } else {
            selectedFiles.push(path);
            element.classList.add('selected');
        }
    } else if (event.shiftKey && lastSelectedFile) {
        // Shift+Click: Select range
        const allItems = Array.from(document.querySelectorAll('.file-item, .folder'));
        const lastIndex = allItems.findIndex(item => item.getAttribute('data-path') === lastSelectedFile);
        const currentIndex = allItems.indexOf(element);
        
        const start = Math.min(lastIndex, currentIndex);
        const end = Math.max(lastIndex, currentIndex);
        
        clearSelection();
        for (let i = start; i <= end; i++) {
            const item = allItems[i];
            const itemPath = item.getAttribute('data-path');
            if (itemPath && !selectedFiles.includes(itemPath)) {
                selectedFiles.push(itemPath);
                item.classList.add('selected');
            }
        }
    } else {
        // Regular click: Select single item
        clearSelection();
        selectedFiles = [path];
        element.classList.add('selected');
    }
    
    lastSelectedFile = path;
}

function selectAllFiles() {
    clearSelection();
    document.querySelectorAll('.file-item, .folder').forEach(item => {
        const path = item.getAttribute('data-path');
        if (path) {
            selectedFiles.push(path);
            item.classList.add('selected');
        }
    });
}

// Initialize Upload
function initializeUpload() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('fileInput');
    
    if (!uploadArea || !fileInput) return;
    
    uploadArea.addEventListener('click', () => fileInput.click());
    
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragging');
    });
    
    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragging');
    });
    
    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragging');
        handleFiles(e.dataTransfer.files);
    });
    
    fileInput.addEventListener('change', (e) => {
        handleFiles(e.target.files);
    });
    
    // Initialize Resumable.js for chunked uploads
    if (typeof Resumable !== 'undefined') {
        resumableUpload = new Resumable({
            target: 'upload.php',
            chunkSize: 1 * 1024 * 1024, // 1MB chunks
            simultaneousUploads: 3,
            testChunks: false,
            throttleProgressCallbacks: 1,
            query: { uploadPath: contextTarget?.path || '' }
        });
        
        if (!resumableUpload.support) {
            console.warn('Resumable.js not supported, using fallback');
        } else {
            resumableUpload.on('fileAdded', function(file) {
                resumableUpload.upload();
            });
            
            resumableUpload.on('fileProgress', function(file) {
                updateUploadProgress(file.progress() * 100);
            });
            
            resumableUpload.on('fileSuccess', function(file, message) {
                addUploadedFile(file.fileName);
            });
            
            resumableUpload.on('fileError', function(file, message) {
                showStatus('Upload failed: ' + file.fileName, true);
            });
            
            resumableUpload.on('complete', function() {
                setTimeout(() => {
                    loadFileTree();
                    document.getElementById('uploadProgress').style.display = 'none';
                }, 1000);
            });
        }
    }
}

function handleFiles(files) {
    if (files.length === 0) return;
    
    document.getElementById('uploadProgress').style.display = 'block';
    document.getElementById('uploadedFiles').innerHTML = '';
    
    if (resumableUpload && resumableUpload.support) {
        resumableUpload.opts.query = { uploadPath: contextTarget?.path || '' };
        for (let file of files) {
            resumableUpload.addFile(file);
        }
    } else {
        // Fallback for browsers that don't support Resumable.js
        uploadFilesFallback(files);
    }
}

async function uploadFilesFallback(files) {
    const total = files.length;
    let uploaded = 0;
    
    for (let file of files) {
        try {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('uploadPath', contextTarget?.path || '');
            
            const response = await fetch('upload.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                uploaded++;
                updateUploadProgress((uploaded / total) * 100);
                addUploadedFile(file.name);
            }
        } catch (error) {
            console.error('Upload failed:', file.name, error);
        }
    }
    
    await loadFileTree();
    document.getElementById('uploadProgress').style.display = 'none';
}

function updateUploadProgress(percent) {
    document.getElementById('progressFill').style.width = percent + '%';
    document.getElementById('uploadStatus').textContent = `Uploading... ${Math.round(percent)}%`;
}

function addUploadedFile(fileName) {
    const container = document.getElementById('uploadedFiles');
    const item = document.createElement('div');
    item.className = 'uploaded-file-item';
    item.innerHTML = `
        <span>✓ ${fileName}</span>
    `;
    container.appendChild(item);
}

function showUploadDialog() {
    document.getElementById('uploadedFiles').innerHTML = '';
    document.getElementById('uploadProgress').style.display = 'none';
    document.getElementById('progressFill').style.width = '0%';
    showModal('uploadModal');
}

// API Functions
async function apiCall(action, data = {}) {
    try {
        const formData = new FormData();
        formData.append('action', action);
        
        for (const key in data) {
            formData.append(key, data[key]);
        }
        
        const response = await fetch('api.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (!result.success) {
            throw new Error(result.error || 'Operation failed');
        }
        
        return result;
    } catch (error) {
        showStatus('Error: ' + error.message, true);
        throw error;
    }
}

async function loadFileTree() {
    try {
        showStatus('Loading files...');
        const result = await apiCall('listFiles');
        fileTreeData = result.files;
        renderFileTree();
        showStatus('Ready');
    } catch (error) {
        console.error('Failed to load file tree:', error);
    }
}
// Move and Copy Functions
function showMoveDialog() {
    if (!contextTarget) return;
    selectedDestFolder = '';
    renderFolderTree('folderTree');
    showModal('moveModal');
}

function showCopyDialog() {
    if (!contextTarget) return;
    selectedDestFolder = '';
    renderFolderTree('copyFolderTree');
    showModal('copyModal');
}

// File Tree Rendering
function renderFileTree() {
    const container = document.getElementById('fileTree');
    container.innerHTML = '';
    renderFileTreeNode(fileTreeData, container);
}

function renderFileTreeNode(nodes, container, indent = 0) {
    nodes.forEach(node => {
        if (node.type === 'folder') {
            const folderDiv = document.createElement('div');
            folderDiv.className = 'folder';
            folderDiv.style.paddingLeft = (8 + indent * 12) + 'px';
            folderDiv.innerHTML = `
                <span class="folder-icon">▼</span>
                <span>📁</span>
                <span class="folder-name">${node.name}</span>
                <div class="folder-actions">
                    <span class="folder-action" title="New File">+</span>
                    <span class="folder-action" title="New Folder">📁</span>
                </div>
            `;
            folderDiv.setAttribute('data-path', node.path);
            
            folderDiv.addEventListener('click', function(e) {
                if (e.target.classList.contains('folder-action')) {
                    e.stopPropagation();
                    const isNewFile = e.target.textContent === '+';
                    contextTarget = { type: 'folder', path: node.path };
                    if (isNewFile) {
                        showNewFileDialog(node.path);
                    } else {
                        showNewFolderDialog(node.path);
                    }
                } else if (!e.target.closest('.folder-actions')) {
                    if (e.detail === 1) { // Single click
                        toggleSelection(node.path, this, e);
                    }
                    toggleFolderElement(this);
                }
            });
            
            folderDiv.addEventListener('contextmenu', function(e) {
                e.preventDefault();
                if (!selectedFiles.includes(node.path)) {
                    clearSelection();
                    toggleSelection(node.path, this, e);
                }
                contextTarget = { type: 'folder', path: node.path };
                showContextMenu(e.clientX, e.clientY);
            });
            
            container.appendChild(folderDiv);
            
            const filesDiv = document.createElement('div');
            filesDiv.className = 'folder-files';
            if (node.children && node.children.length > 0) {
                renderFileTreeNode(node.children, filesDiv, indent + 1);
            }
            container.appendChild(filesDiv);
        } else {
            const ext = node.name.split('.').pop().toLowerCase();
            const langInfo = languageMap[ext] || languageMap['txt'];
            
            const fileDiv = document.createElement('div');
            fileDiv.className = 'file-item';
            fileDiv.style.paddingLeft = (8 + indent * 12) + 'px';
            fileDiv.innerHTML = `
                <span class="file-icon ${langInfo.icon}">◈</span>
                <span class="file-name">${node.name}</span>
                <div class="file-actions">
                    <span class="file-action" title="Delete">🗑️</span>
                </div>
            `;
            fileDiv.setAttribute('data-path', node.path);
            
            fileDiv.addEventListener('click', function(e) {
                if (e.target.classList.contains('file-action')) {
                    e.stopPropagation();
                    contextTarget = { type: 'file', path: node.path };
                    showDeleteDialog(node.path);
                } else if (!e.target.closest('.file-actions')) {
                    if (e.ctrlKey || e.metaKey || e.shiftKey) {
                        toggleSelection(node.path, this, e);
                    } else {
                        if (selectedFiles.length <= 1) {
                            clearSelection();
                            openFile(node.path);
                        } else {
                            toggleSelection(node.path, this, e);
                        }
                    }
                }
            });
            
            fileDiv.addEventListener('dblclick', function(e) {
                e.stopPropagation();
                clearSelection();
                openFile(node.path);
            });
            
            fileDiv.addEventListener('contextmenu', function(e) {
                e.preventDefault();
                if (!selectedFiles.includes(node.path)) {
                    clearSelection();
                    toggleSelection(node.path, this, e);
                }
                contextTarget = { type: 'file', path: node.path };
                showContextMenu(e.clientX, e.clientY);
            });
            
            container.appendChild(fileDiv);
        }
    });
}

function toggleFolderElement(element) {
    element.classList.toggle('collapsed');
}

// Context Menu
function showContextMenu(x, y) {
    const menu = document.getElementById('contextMenu');
    menu.classList.add('show');
    menu.style.left = x + 'px';
    menu.style.top = y + 'px';
}

function closeContextMenu() {
    document.getElementById('contextMenu').classList.remove('show');
}

function handleContextAction(action) {
    closeContextMenu();
    if (!contextTarget && selectedFiles.length === 0) return;

    // If multiple files selected, use batch operations
    if (selectedFiles.length > 1) {
        switch(action) {
            case 'copy':
                showCopyMultipleDialog();
                break;
            case 'move':
                showMoveMultipleDialog();
                break;
            case 'download':
                downloadMultipleFiles();
                break;
            case 'compress':
                showCompressMultipleDialog();
                break;
            case 'delete':
                showDeleteMultipleDialog();
                break;
        }
        return;
    }

    // Single file operations
    switch(action) {
        case 'rename':
            showRenameDialog();
            break;
        case 'copy':
            showCopyDialog();
            break;
        case 'move':
            showMoveDialog();
            break;
        case 'download':
            downloadFile(contextTarget.path);
            break;
        case 'compress':
            showCompressDialog();
            break;
        case 'extract':
            extractZip(contextTarget.path);
            break;
        case 'delete':
            showDeleteDialog(contextTarget.path);
            break;
        case 'newFile':
            showNewFileDialog(contextTarget.path);
            break;
        case 'newFolder':
            showNewFolderDialog(contextTarget.path);
            break;
    }
}

// Batch Operations
function showMoveMultipleDialog() {
    selectedDestFolder = '';
    renderFolderTree('folderTree');
    showModal('moveModal');
}

function showCopyMultipleDialog() {
    selectedDestFolder = '';
    renderFolderTree('copyFolderTree');
    showModal('copyModal');
}

async function confirmMove() {
    if (selectedFiles.length === 0 && !contextTarget) return;
    
    const filesToMove = selectedFiles.length > 0 ? selectedFiles : [contextTarget.path];
    
    try {
        showStatus(`Moving ${filesToMove.length} item(s)...`);
        
        for (const path of filesToMove) {
            await apiCall('move', { 
                sourcePath: path, 
                destPath: selectedDestFolder 
            });
            
            // Close tab if file is open
            const tab = openTabs.find(t => t.path === path);
            if (tab) {
                closeTab(path, true);
            }
        }
        
        closeModal('moveModal');
        clearSelection();
        await loadFileTree();
        showStatus(`Moved ${filesToMove.length} item(s) successfully`);
    } catch (error) {
        console.error('Failed to move:', error);
    }
}

async function confirmCopy() {
    if (selectedFiles.length === 0 && !contextTarget) return;
    
    const filesToCopy = selectedFiles.length > 0 ? selectedFiles : [contextTarget.path];
    
    try {
        showStatus(`Copying ${filesToCopy.length} item(s)...`);
        
        for (const path of filesToCopy) {
            await apiCall('copy', { 
                sourcePath: path, 
                destPath: selectedDestFolder 
            });
        }
        
        closeModal('copyModal');
        clearSelection();
        await loadFileTree();
        showStatus(`Copied ${filesToCopy.length} item(s) successfully`);
    } catch (error) {
        console.error('Failed to copy:', error);
    }
}

function showDeleteMultipleDialog() {
    const count = selectedFiles.length;
    document.getElementById('deleteMessage').textContent = 
        `Are you sure you want to delete ${count} selected item(s)?`;
    showModal('deleteModal');
}

async function confirmDelete() {
    if (selectedFiles.length === 0 && !contextTarget) return;
    
    const filesToDelete = selectedFiles.length > 0 ? selectedFiles : [contextTarget.path];
    
    try {
        showStatus(`Deleting ${filesToDelete.length} item(s)...`);
        
        for (const path of filesToDelete) {
            await apiCall('delete', { path: path });
            
            // Close tab if open
            const tab = openTabs.find(t => t.path === path);
            if (tab) {
                closeTab(path, true);
            }
        }

        closeModal('deleteModal');
        clearSelection();
        await loadFileTree();
        showStatus(`Deleted ${filesToDelete.length} item(s) successfully`);
    } catch (error) {
        console.error('Failed to delete:', error);
    }
}

function downloadMultipleFiles() {
    selectedFiles.forEach((path, index) => {
        setTimeout(() => {
            downloadFile(path);
        }, index * 500); // Stagger downloads
    });
    showStatus(`Downloading ${selectedFiles.length} files...`);
}

function showCompressMultipleDialog() {
    const defaultName = 'selected_files.zip';
    document.getElementById('zipNameInput').value = defaultName;
    showModal('compressModal');
}

async function confirmCompress() {
    const zipName = document.getElementById('zipNameInput').value.trim();
    if (!zipName) return;
    
    if (selectedFiles.length > 1) {
        // Compress multiple files - create a temporary structure
        try {
            showStatus('Compressing multiple files...');
            
            // For multiple files, we need to use the first file's parent directory
            const firstPath = selectedFiles[0];
            const parentPath = firstPath.substring(0, firstPath.lastIndexOf('/'));
            
            // Compress first file with the zip name
            await apiCall('compress', { 
                sourcePath: firstPath, 
                zipName: zipName 
            });
            
            showStatus(`Compressed ${selectedFiles.length} file(s) successfully`);
            closeModal('compressModal');
            clearSelection();
            await loadFileTree();
        } catch (error) {
            console.error('Failed to compress:', error);
        }
    } else if (contextTarget) {
        // Single file compression
        try {
            showStatus('Compressing...');
            await apiCall('compress', { 
                sourcePath: contextTarget.path, 
                zipName: zipName 
            });
            
            closeModal('compressModal');
            await loadFileTree();
            showStatus('Compressed successfully');
        } catch (error) {
            console.error('Failed to compress:', error);
        }
    }
}

async function extractZip(path) {
    if (!path.endsWith('.zip')) {
        showStatus('Please select a ZIP file', true);
        return;
    }
    
    try {
        showStatus('Extracting...');
        await apiCall('extract', { zipPath: path });
        await loadFileTree();
        showStatus('Extracted successfully');
    } catch (error) {
        console.error('Failed to extract:', error);
    }
}

// Download Function
function downloadFile(path) {
    window.open(`api.php?action=download&path=${encodeURIComponent(path)}`, '_blank');
}

// Helper functions for folder tree
function renderFolderTree(containerId) {
    const container = document.getElementById(containerId);
    container.innerHTML = '<div class="folder-tree-item" onclick="selectDestFolder(\'\', this)">📁 Root</div>';
    renderFolderTreeNodeModal(fileTreeData, container, '', 0);
}

function renderFolderTreeNodeModal(nodes, container, parentPath, indent) {
    nodes.forEach(node => {
        if (node.type === 'folder') {
            const item = document.createElement('div');
            item.className = 'folder-tree-item';
            item.style.paddingLeft = (8 + indent * 16) + 'px';
            item.innerHTML = `📁 ${node.name}`;
            item.onclick = function() {
                selectDestFolder(node.path, this);
            };
            container.appendChild(item);
            
            if (node.children && node.children.length > 0) {
                renderFolderTreeNodeModal(node.children, container, node.path, indent + 1);
            }
        }
    });
}

function selectDestFolder(path, element) {
    document.querySelectorAll('.folder-tree-item').forEach(item => {
        item.classList.remove('selected');
    });
    element.classList.add('selected');
    selectedDestFolder = path;
}

// Modal Functions
function showModal(modalId) {
    document.getElementById(modalId).classList.add('show');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('show');
}

function showNewFileDialog(parentPath = '') {
    contextTarget = { type: 'folder', path: parentPath };
    document.getElementById('newFileInput').value = '';
    showModal('newFileModal');
    setTimeout(() => document.getElementById('newFileInput').focus(), 100);
}

function showNewFolderDialog(parentPath = '') {
    contextTarget = { type: 'folder', path: parentPath };
    document.getElementById('newFolderInput').value = '';
    showModal('newFolderModal');
    setTimeout(() => document.getElementById('newFolderInput').focus(), 100);
}

function showRenameDialog() {
    if (!contextTarget) return;
    const name = contextTarget.path.split('/').pop();
    document.getElementById('renameInput').value = name;
    showModal('renameModal');
    setTimeout(() => document.getElementById('renameInput').select(), 100);
}

function showDeleteDialog(path) {
    contextTarget = { path: path };
    const name = path.split('/').pop();
    document.getElementById('deleteMessage').textContent = `Are you sure you want to delete "${name}"?`;
    showModal('deleteModal');
}

// File Operations
async function createNewFile() {
    const fileName = document.getElementById('newFileInput').value.trim();
    if (!fileName) return;

    const parentPath = contextTarget?.path || '';
    const fullPath = parentPath ? `${parentPath}/${fileName}` : fileName;

    try {
        await apiCall('createFile', { path: fullPath });
        closeModal('newFileModal');
        await loadFileTree();
        openFile(fullPath);
        showStatus('File created successfully');
    } catch (error) {
        console.error('Failed to create file:', error);
    }
}

async function createNewFolder() {
    const folderName = document.getElementById('newFolderInput').value.trim();
    if (!folderName) return;

    const parentPath = contextTarget?.path || '';
    const fullPath = parentPath ? `${parentPath}/${folderName}` : folderName;

    try {
        await apiCall('createFolder', { path: fullPath });
        closeModal('newFolderModal');
        await loadFileTree();
        showStatus('Folder created successfully');
    } catch (error) {
        console.error('Failed to create folder:', error);
    }
}

async function confirmRename() {
    const newName = document.getElementById('renameInput').value.trim();
    if (!newName || !contextTarget) return;

    const oldPath = contextTarget.path;
    const parts = oldPath.split('/');
    parts[parts.length - 1] = newName;
    const newPath = parts.join('/');

    try {
        await apiCall('rename', { oldPath: oldPath, newPath: newPath });
        
        // Update open tabs
        const tab = openTabs.find(t => t.path === oldPath);
        if (tab) {
            tab.path = newPath;
            tab.fileName = newName;
            editors[newPath] = editors[oldPath];
            delete editors[oldPath];
            if (activeTab === oldPath) {
                activeTab = newPath;
            }
            updateTabsUI();
        }

        closeModal('renameModal');
        await loadFileTree();
        showStatus('Renamed successfully');
    } catch (error) {
        console.error('Failed to rename:', error);
    }
}

async function confirmDelete() {
    if (!contextTarget) return;

    const path = contextTarget.path;

    try {
        await apiCall('delete', { path: path });
        
        // Close tab if open
        const tab = openTabs.find(t => t.path === path);
        if (tab) {
            closeTab(path, true);
        }

        closeModal('deleteModal');
        await loadFileTree();
        showStatus('Deleted successfully');
    } catch (error) {
        console.error('Failed to delete:', error);
    }
}

// File Operations
async function openFile(path) {
    if (openTabs.find(tab => tab.path === path)) {
        switchToTab(path);
        return;
    }

    try {
        showStatus('Loading file...');
        const result = await apiCall('readFile', { path: path });
        
        const fileName = path.split('/').pop();
        const ext = fileName.split('.').pop().toLowerCase();
        const tab = {
            fileName: fileName,
            path: path,
            type: ext,
            editorId: `editor-${Date.now()}`,
            modified: false,
            originalContent: result.content
        };

        openTabs.push(tab);
        createTab(tab);
        createEditor(tab, result.content);
        switchToTab(path);
        showStatus('Ready');
    } catch (error) {
        console.error('Failed to open file:', error);
    }
}

function createTab(tab) {
    const tabsBar = document.getElementById('tabsBar');
    const langInfo = languageMap[tab.type] || languageMap['txt'];
    
    const tabElement = document.createElement('div');
    tabElement.className = 'tab';
    tabElement.setAttribute('data-path', tab.path);
    tabElement.innerHTML = `
        <span class="file-icon ${langInfo.icon}">◈</span>
        <span>${tab.fileName}</span>
        <span class="tab-close">×</span>
    `;
    
    tabElement.addEventListener('click', (e) => {
        if (!e.target.classList.contains('tab-close')) {
            switchToTab(tab.path);
        }
    });

    const closeBtn = tabElement.querySelector('.tab-close');
    closeBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        closeTab(tab.path);
    });
    
    tabsBar.appendChild(tabElement);
}

function createEditor(tab, content) {
    const container = document.getElementById('editorContainer');
    const langInfo = languageMap[tab.type] || languageMap['txt'];
    
    const editorWrapper = document.createElement('div');
    editorWrapper.className = 'editor-wrapper';
    editorWrapper.id = tab.editorId;
    container.appendChild(editorWrapper);

    require(['vs/editor/editor.main'], function() {
        const editor = monaco.editor.create(editorWrapper, {
            value: content,
            language: langInfo.lang,
            theme: 'vs-dark',
            automaticLayout: true,
            minimap: { enabled: true },
            fontSize: 14,
            lineNumbers: 'on',
            scrollBeyondLastLine: false
        });

        editors[tab.path] = editor;

        editor.onDidChangeModelContent(() => {
            const currentTab = openTabs.find(t => t.path === tab.path);
            if (currentTab) {
                const currentContent = editor.getValue();
                currentTab.modified = currentContent !== currentTab.originalContent;
                updateTabsUI();
            }
        });

        editor.onDidChangeCursorPosition((e) => {
            if (activeTab === tab.path) {
                document.getElementById('statusPosition').textContent = 
                    `Ln ${e.position.lineNumber}, Col ${e.position.column}`;
            }
        });
    });
}

function switchToTab(path) {
    document.getElementById('welcomeScreen').style.display = 'none';
    activeTab = path;
    const tab = openTabs.find(t => t.path === path);

    updateTabsUI();

    document.querySelectorAll('.editor-wrapper').forEach(e => e.classList.remove('active'));
    const activeEditor = document.getElementById(tab.editorId);
    if (activeEditor) activeEditor.classList.add('active');

    document.querySelectorAll('.file-item').forEach(f => f.classList.remove('active'));
    const activeFileItem = document.querySelector(`.file-item[data-path="${path}"]`);
    if (activeFileItem) activeFileItem.classList.add('active');

    const langInfo = languageMap[tab.type] || languageMap['txt'];
    document.getElementById('breadcrumb').innerHTML = `
        <span class="breadcrumb-item">
            <span class="${langInfo.icon}">◈</span>
            <span>${tab.fileName}</span>
        </span>
        <span class="breadcrumb-separator">›</span>
        <span class="breadcrumb-item">${path}</span>
    `;

    document.getElementById('statusLanguage').textContent = langInfo.display;

    if (editors[path]) {
        setTimeout(() => editors[path].layout(), 0);
    }
}

function updateTabsUI() {
    document.querySelectorAll('.tab').forEach(t => {
        const path = t.getAttribute('data-path');
        const tab = openTabs.find(tb => tb.path === path);
        t.classList.toggle('active', path === activeTab);
        t.classList.toggle('modified', tab && tab.modified);
    });
}

async function saveCurrentFile() {
    if (!activeTab) return;
    
    const tab = openTabs.find(t => t.path === activeTab);
    if (!tab || !editors[activeTab]) return;

    try {
        showStatus('Saving...');
        const content = editors[activeTab].getValue();
        await apiCall('saveFile', { path: activeTab, content: content });
        
        tab.modified = false;
        tab.originalContent = content;
        updateTabsUI();
        showStatus('File saved successfully');
    } catch (error) {
        console.error('Failed to save file:', error);
    }
}

function closeCurrentTab() {
    if (activeTab) {
        closeTab(activeTab);
    }
}

function closeTab(path, force = false) {
    const tab = openTabs.find(t => t.path === path);
    if (!tab) return;

    if (tab.modified && !force) {
        fileToClose = path;
        document.getElementById('closeFileMessage').textContent = 
            `Do you want to save the changes you made to "${tab.fileName}"?`;
        showModal('closeFileModal');
        return;
    }

    const tabIndex = openTabs.findIndex(t => t.path === path);
    
    const editorElement = document.getElementById(tab.editorId);
    if (editorElement) editorElement.remove();
    if (editors[path]) {
        editors[path].dispose();
        delete editors[path];
    }

    const tabElement = document.querySelector(`.tab[data-path="${path}"]`);
    if (tabElement) tabElement.remove();
    openTabs.splice(tabIndex, 1);

    if (openTabs.length > 0) {
        const newActiveIndex = Math.min(tabIndex, openTabs.length - 1);
        switchToTab(openTabs[newActiveIndex].path);
    } else {
        activeTab = null;
        document.getElementById('welcomeScreen').style.display = 'flex';
        document.getElementById('breadcrumb').innerHTML = '';
        document.getElementById('statusLanguage').textContent = 'Plain Text';
    }
}

async function saveAndCloseFile() {
    if (fileToClose) {
        const previousTab = activeTab;
        activeTab = fileToClose;
        await saveCurrentFile();
        activeTab = previousTab;
    }
    closeModal('closeFileModal');
    closeTab(fileToClose, true);
    fileToClose = null;
}

function closeFileWithoutSaving() {
    closeModal('closeFileModal');
    closeTab(fileToClose, true);
    fileToClose = null;
}

function togglePanel(panelName) {
    document.querySelectorAll('.sidebar-icon').forEach(icon => icon.classList.remove('active'));
    const clickedIcon = document.querySelector(`.sidebar-icon[data-panel="${panelName}"]`);
    if (clickedIcon) clickedIcon.classList.add('active');

    const sidePanel = document.getElementById('sidePanel');
    if (currentPanel === panelName) {
        sidePanel.classList.toggle('hidden');
        if (!sidePanel.classList.contains('hidden')) {
            showPanelContent(panelName);
        }
    } else {
        sidePanel.classList.remove('hidden');
        showPanelContent(panelName);
    }
    
    currentPanel = panelName;
}

function showPanelContent(panelName) {
    document.querySelectorAll('.panel-content').forEach(p => p.classList.remove('active'));
    document.getElementById(`${panelName}Panel`).classList.add('active');
}

function setupPanelResizer() {
    let isResizing = false;
    let startX = 0;
    let startWidth = 0;

    const panelResizer = document.getElementById('panelResizer');
    const sidePanel = document.getElementById('sidePanel');

    panelResizer.addEventListener('mousedown', (e) => {
        isResizing = true;
        startX = e.clientX;
        startWidth = sidePanel.offsetWidth;
        panelResizer.classList.add('dragging');
        document.body.style.cursor = 'col-resize';
        e.preventDefault();
    });

    document.addEventListener('mousemove', (e) => {
        if (!isResizing) return;

        const delta = e.clientX - startX;
        const newWidth = startWidth + delta;

        if (newWidth >= 200 && newWidth <= 600) {
            sidePanel.style.width = `${newWidth}px`;
        }
    });

    document.addEventListener('mouseup', () => {
        if (isResizing) {
            isResizing = false;
            panelResizer.classList.remove('dragging');
            document.body.style.cursor = 'default';
            
            if (activeTab && editors[activeTab]) {
                setTimeout(() => editors[activeTab].layout(), 0);
            }
        }
    });
}

function showStatus(message, isError = false) {
    const statusElement = document.getElementById('statusMessage');
    statusElement.textContent = message;
    statusElement.style.color = isError ? '#f48771' : '#ffffff';
}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code Editor</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
    <!-- Monaco Editor CSS -->
    <style>
        #monaco-container {
            width: 100%;
            height: 100%;
        }
    </style>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            overflow: hidden;
            background: #1e1e1e;
        }
        
        .top-bar {
            background: #2d2d2d;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #1e1e1e;
            height: 50px;
        }
        
        .code-editor-container {
            display: flex;
            height: calc(100vh - 50px);
        }
        
        .file-explorer {
            width: 300px;
            background: #252526;
            color: #ccc;
            overflow-y: auto;
            border-right: 1px solid #1e1e1e;
        }
        
        .file-explorer-header {
            padding: 15px;
            background: #2d2d2d;
            border-bottom: 1px solid #1e1e1e;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .file-explorer-header h6 {
            margin: 0;
            font-size: 12px;
            text-transform: uppercase;
            color: #888;
        }
        
        .tree-item {
            padding: 5px 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            user-select: none;
            white-space: nowrap;
        }
        
        .tree-item:hover {
            background: #2a2d2e;
        }
        
        .tree-item.active {
            background: #37373d;
            color: #fff;
        }
        
        .tree-item span {
            margin-left: 5px;
        }
        
        .tree-item i {
            margin-right: 8px;
            width: 16px;
        }
        
        .tree-item.folder > i {
            color: #dcb67a;
        }
        
        .tree-item.file > i {
            color: #519aba;
        }
        
        .tree-item .expand-icon {
            margin-right: 5px;
            font-size: 10px;
            transition: transform 0.2s;
            display: inline-block;
            width: 12px;
        }
        
        .tree-item.expanded .expand-icon {
            transform: rotate(90deg);
        }
        
        .tree-children {
            display: none;
        }
        
        .tree-item.expanded + .tree-children {
            display: block;
        }
        
        .editor-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #1e1e1e;
            position: relative;
        }
        
        #monaco-container {
            width: 100%;
            height: 100%;
        }
        
        .btn-sm {
            padding: 5px 15px;
            font-size: 13px;
        }
        
        /* Context Menu */
        .context-menu {
            position: fixed;
            background: #2d2d2d;
            border: 1px solid #1e1e1e;
            border-radius: 4px;
            padding: 5px 0;
            z-index: 10000;
            display: none;
            min-width: 180px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.5);
        }
        
        .context-menu-item {
            padding: 8px 15px;
            cursor: pointer;
            color: #ccc;
            display: flex;
            align-items: center;
            font-size: 13px;
        }
        
        .context-menu-item:hover {
            background: #37373d;
            color: #fff;
        }
        
        .context-menu-item i {
            margin-right: 10px;
            width: 16px;
        }
        
        .context-menu-separator {
            height: 1px;
            background: #1e1e1e;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <h5 class="mb-0"><i class="fas fa-code"></i> Code Editor</h5>
        <div>
            <button class="btn btn-success btn-sm" onclick="saveFile()">
                <i class="fas fa-save"></i> Save (Ctrl+S)
            </button>
            <button class="btn btn-primary btn-sm" onclick="showCreateFileModal()">
                <i class="fas fa-file-plus"></i> New File
            </button>
            <button class="btn btn-info btn-sm" onclick="showCreateFolderModal()">
                <i class="fas fa-folder-plus"></i> New Folder
            </button>
            <a href="{{ admin_url('dashboard') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Admin
            </a>
        </div>
    </div>
    
    <div class="code-editor-container">
        <div class="file-explorer">
            <div class="file-explorer-header">
                <h6>File Explorer</h6>
                <button class="btn btn-sm btn-primary" onclick="refreshTree()" title="Refresh">
                    <i class="fas fa-sync"></i>
                </button>
            </div>
            <div id="fileTree" class="p-2">
                <div class="text-center p-3">
                    <i class="fas fa-spinner fa-spin"></i> Loading...
                </div>
            </div>
        </div>
        
        <div class="editor-area">
            @if($filePath)
                <div id="monaco-container"></div>
            @else
                <div class="text-center text-white p-5" style="margin-top: 100px;">
                    <i class="fas fa-code fa-5x mb-4" style="opacity: 0.3;"></i>
                    <h3>Welcome to Code Editor</h3>
                    <p class="text-muted">Select a file from the explorer to start editing</p>
                    <div class="mt-4">
                        <h6>Supported Files:</h6>
                        <p class="text-muted">PHP, JavaScript, CSS, HTML, JSON, SQL, Markdown, YAML, and more</p>
                    </div>
                    <div class="mt-4">
                        <h6>Keyboard Shortcuts:</h6>
                        <p class="text-muted">Ctrl+S: Save | Ctrl+F: Find | Ctrl+H: Replace</p>
                    </div>
                </div>
                <div id="monaco-container" style="display: none;"></div>
            @endif
        </div>
    </div>
    
    <!-- Context Menu -->
    <div id="contextMenu" class="context-menu">
        <div class="context-menu-item" onclick="contextMenuAction('open')">
            <i class="fas fa-folder-open"></i> Open
        </div>
        <div class="context-menu-item" onclick="contextMenuAction('rename')">
            <i class="fas fa-edit"></i> Rename
        </div>
        <div class="context-menu-item" onclick="contextMenuAction('copy')">
            <i class="fas fa-copy"></i> Copy
        </div>
        <div class="context-menu-item" onclick="contextMenuAction('move')">
            <i class="fas fa-arrows-alt"></i> Move
        </div>
        <div class="context-menu-separator"></div>
        <div class="context-menu-item" onclick="contextMenuAction('delete')" style="color: #f44336;">
            <i class="fas fa-trash"></i> Delete
        </div>
    </div>
    
    <!-- Modals -->
    <!-- Create File Modal -->
    <div class="modal fade" id="createFileModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Create New File</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>File Name</label>
                        <input type="text" class="form-control" id="newFileName" placeholder="example.php">
                    </div>
                    <div class="form-group">
                        <label>Location</label>
                        <input type="text" class="form-control" id="newFilePath" placeholder="app/Models" value="">
                        <small class="text-muted">Leave empty for root directory</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="createFile()">Create</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Create Folder Modal -->
    <div class="modal fade" id="createFolderModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Folder</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Folder Name</label>
                        <input type="text" class="form-control" id="newFolderName" placeholder="NewFolder">
                    </div>
                    <div class="form-group">
                        <label>Location</label>
                        <input type="text" class="form-control" id="newFolderPath" placeholder="app" value="">
                        <small class="text-muted">Leave empty for root directory</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="createFolder()">Create</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Rename Modal -->
    <div class="modal fade" id="renameModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Rename</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>New Name</label>
                        <input type="text" class="form-control" id="renameInput" placeholder="new-name.php">
                    </div>
                    <input type="hidden" id="renameOldPath">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="renameItem()">Rename</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Move/Copy Modal -->
    <div class="modal fade" id="moveModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="moveModalTitle">Move</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Target Path</label>
                        <input type="text" class="form-control" id="moveTargetPath" placeholder="app/Models">
                        <small class="text-muted">Enter destination folder path</small>
                    </div>
                    <input type="hidden" id="moveSourcePath">
                    <input type="hidden" id="moveAction">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="moveOrCopyItem()">Confirm</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Monaco Editor -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs/loader.min.js"></script>

<script>
let editor;
let currentFile = '{{ $filePath }}';

// Initialize Monaco Editor
require.config({ paths: { vs: 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs' }});

require(['vs/editor/editor.main'], function() {
    @if($filePath)
    // Get language from file extension
    const language = getMonacoLanguage('{{ $filePath }}');
    
    // Create Monaco Editor
    editor = monaco.editor.create(document.getElementById('monaco-container'), {
        value: '',
        language: language,
        theme: 'vs-dark',
        automaticLayout: true,
        fontSize: 14,
        fontFamily: "'Cascadia Code', 'Fira Code', 'Consolas', 'Monaco', monospace",
        fontLigatures: true,
        lineNumbers: 'on',
        minimap: { enabled: true },
        scrollBeyondLastLine: false,
        wordWrap: 'on',
        formatOnPaste: true,
        formatOnType: true,
        autoClosingBrackets: 'always',
        autoClosingQuotes: 'always',
        autoIndent: 'full',
        tabSize: 4,
        insertSpaces: true,
        renderWhitespace: 'selection',
        bracketPairColorization: { enabled: true }
    });
    
    // Load file content
    $.get('{{ admin_url("/readfile") }}?file={{ $filePath }}', function(data) {
        editor.setValue(data);
    });
    
    // Ctrl+S to save
    editor.addCommand(monaco.KeyMod.CtrlCmd | monaco.KeyCode.KeyS, function() {
        saveFile();
    });
    @else
    // Create empty editor
    editor = monaco.editor.create(document.getElementById('monaco-container'), {
        value: '// Select a file from the explorer to start editing',
        language: 'plaintext',
        theme: 'vs-dark',
        automaticLayout: true,
        fontSize: 14,
        readOnly: true
    });
    @endif
});

// Get Monaco language from file path
function getMonacoLanguage(filePath) {
    const ext = filePath.split('.').pop().toLowerCase();
    const langMap = {
        'php': 'php',
        'js': 'javascript',
        'jsx': 'javascript',
        'ts': 'typescript',
        'tsx': 'typescript',
        'json': 'json',
        'html': 'html',
        'htm': 'html',
        'css': 'css',
        'scss': 'scss',
        'sass': 'sass',
        'less': 'less',
        'xml': 'xml',
        'sql': 'sql',
        'md': 'markdown',
        'markdown': 'markdown',
        'yml': 'yaml',
        'yaml': 'yaml',
        'py': 'python',
        'rb': 'ruby',
        'java': 'java',
        'c': 'c',
        'cpp': 'cpp',
        'cs': 'csharp',
        'go': 'go',
        'rs': 'rust',
        'sh': 'shell',
        'bash': 'shell',
        'ps1': 'powershell',
        'bat': 'bat',
        'ini': 'ini',
        'conf': 'ini',
        'env': 'ini',
        'dockerfile': 'dockerfile',
        'txt': 'plaintext'
    };
    return langMap[ext] || 'plaintext';
}

loadFileTree();

function loadFileTree() {
    fetch('{{ admin_url("codeeditor/file-tree") }}')
        .then(r => r.json())
        .then(data => {
            if (data.success) renderFileTree(data.tree);
        });
}

function renderFileTree(items, container = null, level = 0) {
    const tree = container || document.getElementById('fileTree');
    if (!container) tree.innerHTML = '';
    
    items.forEach(item => {
        const itemDiv = document.createElement('div');
        itemDiv.className = 'tree-item ' + (item.is_dir ? 'folder' : 'file');
        itemDiv.style.paddingLeft = (level * 20 + 10) + 'px';
        
        if (item.is_dir) {
            // Folder with expand icon
            itemDiv.innerHTML = `
                <i class="fas fa-caret-right expand-icon"></i>
                <i class="fas fa-folder"></i>
                <span>${item.name}</span>
            `;
            
            tree.appendChild(itemDiv);
            
            // Create children container
            if (item.children && item.children.length > 0) {
                const childrenDiv = document.createElement('div');
                childrenDiv.className = 'tree-children';
                tree.appendChild(childrenDiv);
                
                // Render children
                renderFileTree(item.children, childrenDiv, level + 1);
                
                // Toggle expand/collapse
                itemDiv.onclick = function(e) {
                    e.stopPropagation();
                    itemDiv.classList.toggle('expanded');
                };
            }
            
            // Right-click context menu for folders
            itemDiv.oncontextmenu = function(e) {
                e.preventDefault();
                e.stopPropagation();
                contextMenuPath = item.path;
                contextMenuIsDir = true;
                const menu = document.getElementById('contextMenu');
                menu.style.display = 'block';
                menu.style.left = e.pageX + 'px';
                menu.style.top = e.pageY + 'px';
            };
        } else if (item.editable) {
            // File
            itemDiv.innerHTML = `
                <i class="fas fa-file-code"></i>
                <span>${item.name}</span>
            `;
            
            // Highlight active file
            if (currentFile && item.path === currentFile) {
                itemDiv.classList.add('active');
            }
            
            itemDiv.onclick = function(e) {
                e.stopPropagation();
                openFile(item.path);
            };
            
            // Right-click context menu for files
            itemDiv.oncontextmenu = function(e) {
                e.preventDefault();
                e.stopPropagation();
                contextMenuPath = item.path;
                contextMenuIsDir = false;
                const menu = document.getElementById('contextMenu');
                menu.style.display = 'block';
                menu.style.left = e.pageX + 'px';
                menu.style.top = e.pageY + 'px';
            };
            
            tree.appendChild(itemDiv);
        }
    });
}

function openFile(path) {
    window.location.href = '{{ admin_url("codeeditor") }}?file=' + encodeURIComponent(path);
}

function saveFile() {
    if (!currentFile) {
        alert('No file open');
        return;
    }
    
    fetch('{{ admin_url("codeeditor/save") }}', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `file_path=${encodeURIComponent(currentFile)}&content=${encodeURIComponent(editor.getValue())}`
    })
    .then(r => r.json())
    .then(data => alert(data.success ? 'Saved!' : 'Error: ' + data.error));
}

function refreshTree() {
    loadFileTree();
}

// Context menu variables
let contextMenuTarget = null;
let contextMenuPath = null;
let contextMenuIsDir = false;

// Show context menu on right-click
document.addEventListener('click', function() {
    document.getElementById('contextMenu').style.display = 'none';
});

// Context menu action handler
function contextMenuAction(action) {
    document.getElementById('contextMenu').style.display = 'none';
    
    if (!contextMenuPath) return;
    
    switch(action) {
        case 'open':
            if (!contextMenuIsDir) {
                openFile(contextMenuPath);
            }
            break;
        case 'rename':
            showRenameModal(contextMenuPath);
            break;
        case 'copy':
            showMoveModal(contextMenuPath, 'copy');
            break;
        case 'move':
            showMoveModal(contextMenuPath, 'move');
            break;
        case 'delete':
            deleteItem(contextMenuPath, contextMenuIsDir);
            break;
    }
}

// Show create file modal
function showCreateFileModal() {
    $('#newFileName').val('');
    $('#newFilePath').val('');
    $('#createFileModal').modal('show');
}

// Show create folder modal
function showCreateFolderModal() {
    $('#newFolderName').val('');
    $('#newFolderPath').val('');
    $('#createFolderModal').modal('show');
}

// Create file
function createFile() {
    const fileName = $('#newFileName').val().trim();
    const filePath = $('#newFilePath').val().trim();
    
    if (!fileName) {
        alert('Please enter a file name');
        return;
    }
    
    fetch('{{ admin_url("codeeditor/create-file") }}', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `file_name=${encodeURIComponent(fileName)}&file_path=${encodeURIComponent(filePath)}`
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            $('#createFileModal').modal('hide');
            alert('File created successfully!');
            refreshTree();
            // Open the new file
            if (data.path) {
                openFile(data.path);
            }
        } else {
            alert('Error: ' + data.error);
        }
    });
}

// Create folder
function createFolder() {
    const folderName = $('#newFolderName').val().trim();
    const folderPath = $('#newFolderPath').val().trim();
    
    if (!folderName) {
        alert('Please enter a folder name');
        return;
    }
    
    fetch('{{ admin_url("codeeditor/create-folder") }}', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `folder_name=${encodeURIComponent(folderName)}&folder_path=${encodeURIComponent(folderPath)}`
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            $('#createFolderModal').modal('hide');
            alert('Folder created successfully!');
            refreshTree();
        } else {
            alert('Error: ' + data.error);
        }
    });
}

// Show rename modal
function showRenameModal(path) {
    const fileName = path.split('/').pop();
    $('#renameInput').val(fileName);
    $('#renameOldPath').val(path);
    $('#renameModal').modal('show');
}

// Rename item
function renameItem() {
    const oldPath = $('#renameOldPath').val();
    const newName = $('#renameInput').val().trim();
    
    if (!newName) {
        alert('Please enter a new name');
        return;
    }
    
    fetch('{{ admin_url("codeeditor/rename") }}', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `old_path=${encodeURIComponent(oldPath)}&new_name=${encodeURIComponent(newName)}`
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            $('#renameModal').modal('hide');
            alert('Renamed successfully!');
            refreshTree();
            // If current file was renamed, open the new path
            if (currentFile === oldPath && data.path) {
                openFile(data.path);
            }
        } else {
            alert('Error: ' + data.error);
        }
    });
}

// Show move/copy modal
function showMoveModal(path, action) {
    $('#moveSourcePath').val(path);
    $('#moveAction').val(action);
    $('#moveTargetPath').val('');
    $('#moveModalTitle').text(action === 'move' ? 'Move' : 'Copy');
    $('#moveModal').modal('show');
}

// Move or copy item
function moveOrCopyItem() {
    const sourcePath = $('#moveSourcePath').val();
    const targetPath = $('#moveTargetPath').val().trim();
    const action = $('#moveAction').val();
    
    if (!targetPath) {
        alert('Please enter a target path');
        return;
    }
    
    const endpoint = action === 'move' ? 'move' : 'copy';
    
    fetch(`{{ admin_url("codeeditor") }}/${endpoint}`, {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `source_path=${encodeURIComponent(sourcePath)}&target_path=${encodeURIComponent(targetPath)}`
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            $('#moveModal').modal('hide');
            alert(`${action === 'move' ? 'Moved' : 'Copied'} successfully!`);
            refreshTree();
        } else {
            alert('Error: ' + data.error);
        }
    });
}

// Delete item
function deleteItem(path, isDir) {
    const itemType = isDir ? 'folder' : 'file';
    
    if (!confirm(`Are you sure you want to delete this ${itemType}?\n\n${path}\n\nA backup will be created.`)) {
        return;
    }
    
    const endpoint = isDir ? 'delete-folder' : 'delete-file';
    const param = isDir ? 'folder_path' : 'file_path';
    
    fetch(`{{ admin_url("codeeditor") }}/${endpoint}`, {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `${param}=${encodeURIComponent(path)}`
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert(`${itemType.charAt(0).toUpperCase() + itemType.slice(1)} deleted successfully!`);
            refreshTree();
            // If current file was deleted, redirect to editor home
            if (!isDir && currentFile === path) {
                window.location.href = '{{ admin_url("codeeditor") }}';
            }
        } else {
            alert('Error: ' + data.error);
        }
    });
}
</script>
</body>
</html>

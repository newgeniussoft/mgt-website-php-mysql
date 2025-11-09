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
    
    <!-- CodeMirror CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/monokai.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/dialog/dialog.min.css">

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
        }
        
        .CodeMirror {
            height: 100% !important;
            font-size: 14px;
            font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
        }
        
        .btn-sm {
            padding: 5px 15px;
            font-size: 13px;
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
                <textarea id="codeEditor">{{ $fileContent }}</textarea>
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
                <textarea id="codeEditor" style="display: none;"></textarea>
            @endif
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- CodeMirror JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/php/php.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/javascript/javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/css/css.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/htmlmixed/htmlmixed.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/closebrackets.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/matchbrackets.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/search/search.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/dialog/dialog.min.js"></script>

<script>
let editor;
let currentFile = '{{ $filePath }}';

// Initialize CodeMirror
@if($filePath)
editor = CodeMirror.fromTextArea(document.getElementById('codeEditor'), {
    mode: '{{ $mode }}',
    theme: 'monokai',
    lineNumbers: true,
    autoCloseBrackets: true,
    matchBrackets: true,
    indentUnit: 4,
    lineWrapping: true,
    extraKeys: {"Ctrl-S": saveFile}
});
@else
// Initialize empty editor
editor = CodeMirror.fromTextArea(document.getElementById('codeEditor'), {
    mode: 'text/plain',
    theme: 'monokai',
    lineNumbers: true,
    autoCloseBrackets: true,
    matchBrackets: true,
    indentUnit: 4,
    lineWrapping: true,
    extraKeys: {"Ctrl-S": saveFile}
});
@endif

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
</script>
</body>
</html>

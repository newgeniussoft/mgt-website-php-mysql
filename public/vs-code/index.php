<?php
session_start();

// Set the root directory for the file manager (change this to your project path)
define('ROOT_DIR', __DIR__ . '/project');

// Create project directory if it doesn't exist
if (!file_exists(ROOT_DIR)) {
    mkdir(ROOT_DIR, 0755, true);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code Editor</title>
    <link rel="stylesheet" href="style.css">
    
    <link rel="icon" type="image/png" sizes="16x16" href="/images/logos/vs-logo.png">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-icon active" data-panel="explorer" title="Explorer (Ctrl+Shift+E)">
                <svg width="24" height="24" viewBox="0 0 16 16" fill="currentColor">
                    <path d="M14.5 2H7.71l-.85-.85L6.51 1h-5l-.5.5v11l.5.5h13l.5-.5v-10zm-.51 8.49V13h-12V7h4.49l.35-.35.86-.86H14v4.7z"/>
                </svg>
            </div>
            <div class="sidebar-icon" data-panel="search" title="Search (Ctrl+Shift+F)">
                <svg width="24" height="24" viewBox="0 0 16 16" fill="currentColor">
                    <path d="M11.5 7a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0zm-.82 4.74a6 6 0 1 1 1.06-1.06l3.04 3.04a.75.75 0 1 1-1.06 1.06l-3.04-3.04z"/>
                </svg>
            </div>
        </div>

        <!-- Side Panel -->
        <div class="side-panel" id="sidePanel">
            <div class="panel-resizer" id="panelResizer"></div>
            
            <!-- Explorer Panel -->
            <div class="panel-content active" id="explorerPanel">
                <div class="panel-header">
                    <span>EXPLORER</span>
                    <div class="panel-actions">
                        <div class="panel-action-btn" title="New File" onclick="showNewFileDialog()">+</div>
                        <div class="panel-action-btn" title="New Folder" onclick="showNewFolderDialog()">📁</div>
                        <div class="panel-action-btn" title="Upload Files" onclick="showUploadDialog()">⬆️</div>
                        <div class="panel-action-btn" title="Refresh" onclick="loadFileTree()">🔄</div>
                    </div>
                </div>
                <div id="fileTree"></div>
            </div>

            <!-- Search Panel -->
            <div class="panel-content" id="searchPanel">
                <div class="panel-header">SEARCH</div>
                <input type="text" class="search-input" placeholder="Search in files">
                <div class="search-results">No results</div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Top Menu Bar -->
            <div class="top-bar">
                <div class="menu-item">
                    File
                    <div class="dropdown">
                        <div class="dropdown-item" onclick="showNewFileDialog()">
                            <span>New File</span>
                            <span class="shortcut">Ctrl+N</span>
                        </div>
                        <div class="dropdown-item" onclick="showNewFolderDialog()">
                            <span>New Folder</span>
                        </div>
                        <div class="dropdown-item" onclick="showUploadDialog()">
                            <span>Upload Files</span>
                        </div>
                        <div class="dropdown-separator"></div>
                        <div class="dropdown-item" onclick="saveCurrentFile()">
                            <span>Save</span>
                            <span class="shortcut">Ctrl+S</span>
                        </div>
                        <div class="dropdown-separator"></div>
                        <div class="dropdown-item" onclick="closeCurrentTab()">
                            <span>Close Editor</span>
                            <span class="shortcut">Ctrl+W</span>
                        </div>
                    </div>
                </div>
                <div class="menu-item">
                    Edit
                    <div class="dropdown">
                        <div class="dropdown-item">
                            <span>Undo</span>
                            <span class="shortcut">Ctrl+Z</span>
                        </div>
                        <div class="dropdown-item">
                            <span>Redo</span>
                            <span class="shortcut">Ctrl+Y</span>
                        </div>
                        <div class="dropdown-separator"></div>
                        <div class="dropdown-item">
                            <span>Find</span>
                            <span class="shortcut">Ctrl+F</span>
                        </div>
                        <div class="dropdown-item">
                            <span>Replace</span>
                            <span class="shortcut">Ctrl+H</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs Bar -->
            <div class="tabs-bar" id="tabsBar"></div>

            <!-- Breadcrumb -->
            <div class="breadcrumb" id="breadcrumb"></div>

            <!-- Editor -->
            <div class="editor-container" id="editorContainer">
                <div class="welcome-screen" id="welcomeScreen">
                    <svg viewBox="0 0 16 16" fill="currentColor">
                        <path d="M14.5 2H7.71l-.85-.85L6.51 1h-5l-.5.5v11l.5.5h13l.5-.5v-10zm-.51 8.49V13h-12V7h4.49l.35-.35.86-.86H14v4.7z"/>
                    </svg>
                    <div>Select a file to start editing</div>
                </div>
            </div>

            <!-- Status Bar -->
            <div class="status-bar">
                <span class="status-item" id="statusMessage">Ready</span>
                <span class="status-item">UTF-8</span>
                <span class="status-item">LF</span>
                <span class="status-item" id="statusLanguage">Plain Text</span>
                <span class="status-item" style="margin-left: auto;" id="statusPosition">Ln 1, Col 1</span>
            </div>
        </div>
    </div>

    <!-- Context Menu -->
    <div class="context-menu" id="contextMenu">
        <div class="context-menu-item" onclick="handleContextAction('rename')">✏️ Rename</div>
        <div class="context-menu-item" onclick="handleContextAction('copy')">📋 Copy</div>
        <div class="context-menu-item" onclick="handleContextAction('move')">📦 Move</div>
        <div class="context-menu-item" onclick="handleContextAction('download')">⬇️ Download</div>
        <div class="context-menu-separator"></div>
        <div class="context-menu-item" onclick="handleContextAction('compress')">🗜️ Compress to ZIP</div>
        <div class="context-menu-item" onclick="handleContextAction('extract')">📂 Extract ZIP</div>
        <div class="context-menu-separator"></div>
        <div class="context-menu-item" onclick="handleContextAction('delete')">🗑️ Delete</div>
        <div class="context-menu-separator"></div>
        <div class="context-menu-item" onclick="handleContextAction('newFile')">+ New File</div>
        <div class="context-menu-item" onclick="handleContextAction('newFolder')">📁 New Folder</div>
    </div>

    <!-- Modals -->
    <div class="modal" id="newFileModal">
        <div class="modal-content">
            <div class="modal-header">New File</div>
            <div class="modal-body">
                <label>Enter file name:</label>
                <input type="text" class="modal-input" id="newFileInput" placeholder="filename.js">
            </div>
            <div class="modal-footer">
                <button class="modal-btn modal-btn-secondary" onclick="closeModal('newFileModal')">Cancel</button>
                <button class="modal-btn modal-btn-primary" onclick="createNewFile()">Create</button>
            </div>
        </div>
    </div>

    <div class="modal" id="newFolderModal">
        <div class="modal-content">
            <div class="modal-header">New Folder</div>
            <div class="modal-body">
                <label>Enter folder name:</label>
                <input type="text" class="modal-input" id="newFolderInput" placeholder="folder-name">
            </div>
            <div class="modal-footer">
                <button class="modal-btn modal-btn-secondary" onclick="closeModal('newFolderModal')">Cancel</button>
                <button class="modal-btn modal-btn-primary" onclick="createNewFolder()">Create</button>
            </div>
        </div>
    </div>

    <div class="modal" id="uploadModal">
        <div class="modal-content">
            <div class="modal-header">Upload Files</div>
            <div class="modal-body">
                <div class="upload-area" id="uploadArea">
                    <p>📁 Drag & drop files here or click to browse</p>
                    <input type="file" id="fileInput" multiple hidden>
                </div>
                <div id="uploadProgress" style="display: none;">
                    <div class="progress-bar">
                        <div class="progress-fill" id="progressFill"></div>
                    </div>
                    <p id="uploadStatus">Uploading...</p>
                </div>
                <div id="uploadedFiles"></div>
            </div>
            <div class="modal-footer">
                <button class="modal-btn modal-btn-secondary" onclick="closeModal('uploadModal')">Close</button>
            </div>
        </div>
    </div>

    <div class="modal" id="renameModal">
        <div class="modal-content">
            <div class="modal-header">Rename</div>
            <div class="modal-body">
                <label>Enter new name:</label>
                <input type="text" class="modal-input" id="renameInput">
            </div>
            <div class="modal-footer">
                <button class="modal-btn modal-btn-secondary" onclick="closeModal('renameModal')">Cancel</button>
                <button class="modal-btn modal-btn-primary" onclick="confirmRename()">Rename</button>
            </div>
        </div>
    </div>

    <div class="modal" id="moveModal">
        <div class="modal-content">
            <div class="modal-header">Move To</div>
            <div class="modal-body">
                <label>Select destination folder:</label>
                <div id="folderTree" class="folder-tree-modal"></div>
            </div>
            <div class="modal-footer">
                <button class="modal-btn modal-btn-secondary" onclick="closeModal('moveModal')">Cancel</button>
                <button class="modal-btn modal-btn-primary" onclick="confirmMove()">Move</button>
            </div>
        </div>
    </div>

    <div class="modal" id="copyModal">
        <div class="modal-content">
            <div class="modal-header">Copy To</div>
            <div class="modal-body">
                <label>Select destination folder:</label>
                <div id="copyFolderTree" class="folder-tree-modal"></div>
            </div>
            <div class="modal-footer">
                <button class="modal-btn modal-btn-secondary" onclick="closeModal('copyModal')">Cancel</button>
                <button class="modal-btn modal-btn-primary" onclick="confirmCopy()">Copy</button>
            </div>
        </div>
    </div>

    <div class="modal" id="compressModal">
        <div class="modal-content">
            <div class="modal-header">Compress to ZIP</div>
            <div class="modal-body">
                <label>Enter ZIP file name:</label>
                <input type="text" class="modal-input" id="zipNameInput" placeholder="archive.zip">
            </div>
            <div class="modal-footer">
                <button class="modal-btn modal-btn-secondary" onclick="closeModal('compressModal')">Cancel</button>
                <button class="modal-btn modal-btn-primary" onclick="confirmCompress()">Compress</button>
            </div>
        </div>
    </div>

    <div class="modal" id="deleteModal">
        <div class="modal-content">
            <div class="modal-header">Delete</div>
            <div class="modal-body">
                <p id="deleteMessage">Are you sure you want to delete this item?</p>
            </div>
            <div class="modal-footer">
                <button class="modal-btn modal-btn-secondary" onclick="closeModal('deleteModal')">Cancel</button>
                <button class="modal-btn modal-btn-danger" onclick="confirmDelete()">Delete</button>
            </div>
        </div>
    </div>

    <div class="modal" id="closeFileModal">
        <div class="modal-content">
            <div class="modal-header">Unsaved Changes</div>
            <div class="modal-body">
                <p id="closeFileMessage">Do you want to save the changes?</p>
            </div>
            <div class="modal-footer">
                <button class="modal-btn modal-btn-secondary" onclick="closeFileWithoutSaving()">Don't Save</button>
                <button class="modal-btn modal-btn-secondary" onclick="closeModal('closeFileModal')">Cancel</button>
                <button class="modal-btn modal-btn-primary" onclick="saveAndCloseFile()">Save</button>
            </div>
        </div>
    </div>

    <!-- Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs/loader.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/resumablejs@1.1.0/resumable.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
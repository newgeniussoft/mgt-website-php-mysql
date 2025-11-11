<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code Editor</title>
    <link rel="stylesheet" href="{{ asset('css/editor.css') }}">
</head>
<body>
     <form method="POST" action="{{ isset($template) ? admin_url('templates/update') : admin_url('templates/store') }}" enctype="multipart/form-data" id="templateForm">
        <input type="hidden" name="csrf_token" value="{{ csrf_token() }}">
        @if(isset($template))
        <input type="hidden" name="id" value="{{ $template->id }}">
        @endif
        <input type="hidden" name="html_content" id="html_content" value="{{ $template->html_content }}">
        <input type="hidden" name="css_content" id="css_content" value="{{ $template->css_content }}">
        <input type="hidden" name="js_content" id="js_content" value="{{ $template->js_content }}">
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-icon active" onclick="togglePanel('explorer')" title="Explorer (Ctrl+Shift+E)">
                <svg width="24" height="24" viewBox="0 0 16 16" fill="currentColor">
                    <path d="M14.5 2H7.71l-.85-.85L6.51 1h-5l-.5.5v11l.5.5h13l.5-.5v-10zm-.51 8.49V13h-12V7h4.49l.35-.35.86-.86H14v4.7z"/>
                </svg>
            </div>
            <div class="sidebar-icon" onclick="togglePanel('infos')" title="Information">
                <svg width="24" height="24" viewBox="0 0 16 16" fill="currentColor">
                    <path d="M192,0 L-1.42108547e-14,0 L-1.42108547e-14,384 L298.666667,384 L298.666667,106.666667 L192,0 Z M256,341.333333 L42.6666667,341.333333 L42.6666667,42.6666667 L128,42.6666667 L128,170.666667 L256,170.666667 L256,341.333333 Z M256,128 L170.666667,128 L170.666667,42.6666667 L174.293333,42.6666667 L256,124.373333 L256,128 Z"/>
                </svg>
            </div>
            <div class="sidebar-icon" onclick="togglePanel('search')" title="Search (Ctrl+Shift+F)">
                <svg width="24" height="24" viewBox="0 0 16 16" fill="currentColor">
                    <path d="M11.5 7a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0zm-.82 4.74a6 6 0 1 1 1.06-1.06l3.04 3.04a.75.75 0 1 1-1.06 1.06l-3.04-3.04z"/>
                </svg>
            </div>
            <div class="sidebar-icon" onclick="togglePanel('extensions')" title="Extensions (Ctrl+Shift+X)">
                <svg width="24" height="24" viewBox="0 0 16 16" fill="currentColor">
                    <path d="M11.5 1h-7l-.5.5v6l.5.5h7l.5-.5v-6l-.5-.5zM11 7H5V2h6v5zm-8.5-.5l-.5.5v7l.5.5h7l.5-.5v-7l-.5-.5H8v6l-3-2-3 2V6.5z"/>
                </svg>
            </div>
        </div>

        <!-- Side Panel -->
        <div class="side-panel" id="sidePanel">
            <div class="panel-resizer" id="panelResizer"></div>
            
            <!-- Explorer Panel -->
            <div class="panel-content active" id="explorerPanel">
                <div class="panel-header">EXPLORER</div>
                <div>
                    <div class="folder" onclick="toggleFolder(this)">
                        <span class="folder-icon">▼</span>
                        <span>📁</span>
                        <span>{{ $template->name }}</span>
                    </div>
                    <div class="folder-files">
                        <div class="file-item" onclick="openFile('index.html', 'html')">
                            <span class="file-icon html-icon">◈</span>
                            <span>index.html</span>
                        </div>
                        <div class="file-item" onclick="openFile('style.css', 'css')">
                            <span class="file-icon css-icon">◈</span>
                            <span>style.css</span>
                        </div>
                        <div class="file-item" onclick="openFile('script.js', 'js')">
                            <span class="file-icon js-icon">◈</span>
                            <span>script.js</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Panel -->
            <div class="panel-content" id="searchPanel">
                <div class="panel-header">SEARCH</div>
                <input type="text" class="search-input" placeholder="Search">
                <input type="text" class="search-input" placeholder="files to include" style="margin-top: 0;">
                <input type="text" class="search-input" placeholder="files to exclude" style="margin-top: 0;">
                <div class="search-results">No results found</div>
            </div>
            <!-- Search Panel -->
            <div class="panel-content" id="infosPanel">
                <div class="panel-header">Informations</div>
                <input type="text" id="name" class="search-input" name="name"  placeholder="Name" value="{{ $template->name }}">
                <input type="text" id="slug" class="search-input" name="slug"  placeholder="Slug" value="{{ $template->slug }}">
                <textarea id="description" name="description" class="search-input" placeholder="Description" rows="4" style="width:187px">{{ $template->description }}</textarea>
                <select class="search-input" id="status"  style="width:187px" name="status">
                    @if(isset($template))
                                        <option value="active" {{ $template->status === 'active' ? 'selected' : ''
                                            }}>Active</option>
                                        <option value="inactive" {{ $template->status === 'inactive' ? 'selected' : ''
                                            }}>Inactive</option>
                                            @else
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                    @endif
                                    </select>
                                    <div class="toggle demo-row">
        <input id="c3" type="checkbox" role="switch" name="is_default" aria-checked="false"  {{ isset($template) && $template->is_default ? 'checked' : '' }} />
        <label for="c3">Default</label>
      </div>
                
                <label  class="search-results" for="thumbnail">Thumbnail</label>
                <input type="hidden" name="thumbnail_removed" id="thumbnail_removed">
                 @if(isset($template) && $template->thumbnail)
                                    <div id="thumbnail-preview" class="thumbnail-preview">
                                        <button type="button" onclick="removeThumbnail()" class="btn-remove-thumbnail">Remove</button>
                                        <img src="{{ $template->thumbnail }}" id="img-thumbnail" alt="Thumbnail" class="img-fluid"
                                            style="max-height: 150px;">
                                    </div>
                                    @endif
                <label class="file-upload">
  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2">
    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v4h16v-4M12 12V4m0 0L8 8m4-4 4 4" />
  </svg>
  <div class="file-label">
    <strong>Upload a file</strong>
    <span>PNG, JPG, PDF up to 10MB</span>
  </div>
  <input type="file" id="fileInput"  name="thumbnail"
                                        accept="image/*"/>
</label>

<div class="file-name" id="thumbnail">No file selected</div>
<div class="section-variable">
                                <h5 class="mb-0">Available Variables</h5>
                                <small class="text-muted">Use these variables in your template:</small>
                                <ul class="list-unstyled mt-2" style="font-size: 12px;">
                                    <li><code>@verbatim{{ page_title }}@endverbatim</code></li>
                                    <li><code>@verbatim{{ meta_description }}@endverbatim</code></li>
                                    <li><code>@verbatim{{ meta_keywords }}@endverbatim</code></li>
                                    <li><code>@verbatim{{ site_name }}@endverbatim</code></li>
                                    <li><code>@verbatim{{ menu_items }}@endverbatim</code></li>
                                    <li><code>@verbatim{{ page_sections }}@endverbatim</code></li>
                                    <li><code>@verbatim{{ custom_css }}@endverbatim</code></li>
                                    <li><code>@verbatim{{ custom_js }}@endverbatim</code></li>
                                </ul>
                            </div>

            </div>

            <!-- Extensions Panel -->
            <div class="panel-content" id="extensionsPanel">
                <div class="panel-header">Templates: All templates</div>
                @foreach ($templates as $template)
                    <a href="{{ admin_url('templates/edit?id=' . $template->id) }}" class="extension-item">
                        <div class="extension-name">{{ $template->name }}</div>
                        <div class="extension-desc">{{ $template->description }}</div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Top Menu Bar -->
            <div class="top-bar">
                <div class="menu-item">
                    File
                    <div class="dropdown">
                        <div class="dropdown-item">
                            <span>New File</span>
                            <span class="shortcut">Ctrl+N</span>
                        </div>
                        <div class="dropdown-item">
                            <span>Open File...</span>
                            <span class="shortcut">Ctrl+O</span>
                        </div>
                        <div class="dropdown-item">
                            <span>Open Folder...</span>
                            <span class="shortcut">Ctrl+K Ctrl+O</span>
                        </div>
                        <div class="dropdown-separator"></div>
                        <button type="submit" class="dropdown-item">
                            <span>Save</span>
                            <span class="shortcut">Ctrl+S</span>
                        </button>
                        <div class="dropdown-item">
                            <span>Save As...</span>
                            <span class="shortcut">Ctrl+Shift+S</span>
                        </div>
                        <div class="dropdown-separator"></div>
                        <div class="dropdown-item" onclick="window.location = '{{ admin_url('templates') }}'">
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
                            <span>Cut</span>
                            <span class="shortcut">Ctrl+X</span>
                        </div>
                        <div class="dropdown-item">
                            <span>Copy</span>
                            <span class="shortcut">Ctrl+C</span>
                        </div>
                        <div class="dropdown-item">
                            <span>Paste</span>
                            <span class="shortcut">Ctrl+V</span>
                        </div>
                    </div>
                </div>
                <div class="menu-item">
                    Selection
                    <div class="dropdown">
                        <div class="dropdown-item">
                            <span>Select All</span>
                            <span class="shortcut">Ctrl+A</span>
                        </div>
                        <div class="dropdown-item">
                            <span>Expand Selection</span>
                            <span class="shortcut">Shift+Alt+→</span>
                        </div>
                        <div class="dropdown-item">
                            <span>Shrink Selection</span>
                            <span class="shortcut">Shift+Alt+←</span>
                        </div>
                    </div>
                </div>
                <div class="menu-item">
                    View
                    <div class="dropdown">
                        <div class="dropdown-item">
                            <span>Command Palette...</span>
                            <span class="shortcut">Ctrl+Shift+P</span>
                        </div>
                        <div class="dropdown-item">
                            <span>Open View...</span>
                            <span class="shortcut">Ctrl+Q</span>
                        </div>
                        <div class="dropdown-separator"></div>
                        <div class="dropdown-item">
                            <span>Explorer</span>
                            <span class="shortcut">Ctrl+Shift+E</span>
                        </div>
                        <div class="dropdown-item">
                            <span>Search</span>
                            <span class="shortcut">Ctrl+Shift+F</span>
                        </div>
                        <div class="dropdown-item">
                            <span>Extensions</span>
                            <span class="shortcut">Ctrl+Shift+X</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs Bar -->
            <div class="tabs-bar" id="tabsBar">
                <!-- Tabs will be dynamically added here -->
            </div>

            <!-- Breadcrumb -->
            <div class="breadcrumb" id="breadcrumb">
                <!-- Breadcrumb will be dynamically updated -->
            </div>

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
                <span class="status-item">UTF-8</span>
                <span class="status-item">LF</span>
                <span class="status-item" id="statusLanguage">Plain Text</span>
                <span class="status-item" style="margin-left: auto;" id="statusPosition">Ln 1, Col 1</span>
            </div>
        </div>
    </div>
    </form>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs/loader.min.js"></script>
    <script>
        let editors = {};
        let openTabs = [];
        let activeTab = null;

        
  const fileInput = document.getElementById('fileInput');
  const fileName = document.getElementById('thumbnail');

  fileInput.addEventListener('change', () => {
    if (fileInput.files.length > 0) {
      fileName.textContent = "📄 " + fileInput.files[0].name;
    } else {
      fileName.textContent = "No file selected";
    }
  });

        const languageMap = {
            'html': { lang: 'html', display: 'HTML', icon: 'html-icon' },
            'css': { lang: 'css', display: 'CSS', icon: 'css-icon' },
            'js': { lang: 'javascript', display: 'JavaScript', icon: 'js-icon' },
            'json': { lang: 'json', display: 'JSON', icon: 'json-icon' },
            'md': { lang: 'markdown', display: 'Markdown', icon: 'md-icon' }
        };

        require.config({ paths: { vs: 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs' } });

        require(['vs/editor/editor.main'], function () {
            console.log('Monaco Editor loaded');
        });

        function openFile(fileName, type) {
            // Check if file is already open
            if (openTabs.find(tab => tab.fileName === fileName)) {
                switchToTab(fileName);
                return;
            }

            // Create new tab
            const tab = {
                fileName: fileName,
                type: type,
                editorId: `editor-${Date.now()}`
            };

            openTabs.push(tab);
            createTab(tab);
            createEditor(tab);
            switchToTab(fileName);
        }

        function createTab(tab) {
            const tabsBar = document.getElementById('tabsBar');
            const langInfo = languageMap[tab.type];
            
            const tabElement = document.createElement('div');
            tabElement.className = 'tab';
            tabElement.setAttribute('data-file', tab.fileName);
            tabElement.innerHTML = `
                <span class="file-icon ${langInfo.icon}">◈</span>
                <span>${tab.fileName}</span>
                <span class="tab-close" onclick="closeTab(event, '${tab.fileName}')">×</span>
            `;
            tabElement.onclick = (e) => {
                if (!e.target.classList.contains('tab-close')) {
                    switchToTab(tab.fileName);
                }
            };
            
            tabsBar.appendChild(tabElement);
        }

        function createEditor(tab) {
            const container = document.getElementById('editorContainer');
            const langInfo = languageMap[tab.type];
            
            const editorWrapper = document.createElement('div');
            editorWrapper.className = 'editor-wrapper';
            editorWrapper.id = tab.editorId;
            container.appendChild(editorWrapper);

            require(['vs/editor/editor.main'], function () {
                const html_content = document.getElementById('html_content').value.replace(/\\r\\n/g, "\r\n");
                const css_content = document.getElementById('css_content').value.replace(/\\r\\n/g, "\r\n");
                const js_content = document.getElementById('js_content').value.replace(/\\r\\n/g, "\r\n");
                var content = "";
                if (langInfo.lang === 'html') {
                    content = html_content;
                } else if (langInfo.lang === 'css') {
                    content = css_content;
                } else if (langInfo.lang === 'javascript') {
                    content = js_content;
                }
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
                // Detect content change
                editor.onDidChangeModelContent((event) => {
                    
                     const editorValue =  editor.getValue();
            if (langInfo.lang === 'html') {
                document.getElementById('html_content').value = editorValue;
            } else if (langInfo.lang === 'css') {
                document.getElementById('css_content').value = editorValue;
            } else if (langInfo.lang === 'javascript') {
                document.getElementById('js_content').value = editorValue;
            }
                });

                editors[tab.fileName] = editor;

                editor.onDidChangeCursorPosition((e) => {
                    if (activeTab === tab.fileName) {
                        document.getElementById('statusPosition').textContent = 
                            `Ln ${e.position.lineNumber}, Col ${e.position.column}`;
                    }
                });
            });
        }

        function switchToTab(fileName) {
            // Hide welcome screen
            document.getElementById('welcomeScreen').style.display = 'none';

            // Update active tab
            activeTab = fileName;
            const tab = openTabs.find(t => t.fileName === fileName);

            // Update tab UI
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelector(`.tab[data-file="${fileName}"]`).classList.add('active');

            // Update editor UI
            document.querySelectorAll('.editor-wrapper').forEach(e => e.classList.remove('active'));
            document.getElementById(tab.editorId).classList.add('active');

            // Update file item in explorer
            document.querySelectorAll('.file-item').forEach(f => f.classList.remove('active'));
            Array.from(document.querySelectorAll('.file-item')).find(item => 
                item.textContent.includes(fileName)
            )?.classList.add('active');

            // Update breadcrumb
            const langInfo = languageMap[tab.type];
            document.getElementById('breadcrumb').innerHTML = `
                <span class="breadcrumb-item">
                    <span class="${langInfo.icon}">◈</span>
                    <span>${fileName}</span>
                </span>
                <span class="breadcrumb-separator">›</span>
                <span class="breadcrumb-item">${tab.type}</span>
            `;

            // Update status bar
            document.getElementById('statusLanguage').textContent = langInfo.display;

            // Trigger layout refresh
            if (editors[fileName]) {
                setTimeout(() => editors[fileName].layout(), 0);
            }
             const editorValue = editors[fileName].getValue();
            if (fileName === 'index.html') {
                document.getElementById('html_content').value = editorValue;
            } else if (fileName === 'style.css') {
                document.getElementById('css_content').value = editorValue;
            } else if (fileName === 'script.js') {
                document.getElementById('js_content').value = editorValue;
            }
        }

        function closeTab(event, fileName) {
            
            event.stopPropagation();
            
            const tabIndex = openTabs.findIndex(t => t.fileName === fileName);
            if (tabIndex === -1) return;

            const tab = openTabs[tabIndex];
            
            // Remove editor
            const editorElement = document.getElementById(tab.editorId);
            
            const editorValue = editors[fileName].getValue();
            if (fileName === 'index.html') {
                document.getElementById('html_content').value = editorValue;
            } else if (fileName === 'style.css') {
                document.getElementById('css_content').value = editorValue;
            } else if (fileName === 'script.js') {
                document.getElementById('js_content').value = editorValue;
            }
            
            if (editorElement) {
                editorElement.remove();
            }
            if (editors[fileName]) {
                editors[fileName].dispose();
                delete editors[fileName];
            }

            // Remove tab
            document.querySelector(`.tab[data-file="${fileName}"]`).remove();
            openTabs.splice(tabIndex, 1);

            // Switch to another tab or show welcome screen
            if (openTabs.length > 0) {
                const newActiveIndex = Math.min(tabIndex, openTabs.length - 1);
                switchToTab(openTabs[newActiveIndex].fileName);
            } else {
                activeTab = null;
                document.getElementById('welcomeScreen').style.display = 'flex';
                document.getElementById('breadcrumb').innerHTML = '';
                document.getElementById('statusLanguage').textContent = 'Plain Text';
            }
        }

        function toggleFolder(element) {
            element.classList.toggle('collapsed');
        }

        let currentPanel = 'explorer';
        function togglePanel(panelName) {
            // Update sidebar icons
            document.querySelectorAll('.sidebar-icon').forEach(icon => icon.classList.remove('active'));
            event.currentTarget.classList.add('active');

            // If clicking the same panel, toggle visibility
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

        // Panel Resizing
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
                
                // Trigger layout refresh for visible editor
                if (activeTab && editors[activeTab]) {
                    setTimeout(() => editors[activeTab].layout(), 0);
                }
            }
        });
        
        function removeThumbnail() {
            document.getElementById('thumbnail').innerHTML = 'No file selected';
            document.getElementById('thumbnail_removed').value = '1';
            document.getElementById('thumbnail-preview').style.display = 'none';
        }

        document.getElementById('templateForm').addEventListener('submit', function (e) {
            e.preventDefault();
            
            // Check required fields
            const requiredFields = ['name', 'slug'];
            const missingFields = requiredFields.filter(field => !document.getElementById(field).value);
            if (missingFields.length > 0) {
                alert(`Please fill in all required fields: ${missingFields.join(', ')}`);
                return;
            }
            
            // Submit the form
            document.getElementById('templateForm').submit();
            

        });
       
    </script>
</body>
</html>
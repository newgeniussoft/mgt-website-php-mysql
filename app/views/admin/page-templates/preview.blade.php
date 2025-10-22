@extends('admin.layout')

@section('title', 'Preview Template')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-eye me-2"></i>Template Preview: {{ $template['name'] }}
                </h1>
                <div>
                    @if(!$template['is_system'])
                        <a href="/admin/page-templates/edit?id={{ $template['id'] }}" class="btn btn-primary me-2">
                            <i class="fas fa-edit me-2"></i>Edit Template
                        </a>
                    @endif
                    <a href="/admin/page-templates" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Templates
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Template Info -->
                <div class="col-lg-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-info-circle me-2"></i>Template Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $template['name'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Slug:</strong></td>
                                    <td><code>{{ $template['slug'] }}</code></td>
                                </tr>
                                <tr>
                                    <td><strong>Type:</strong></td>
                                    <td><span class="badge bg-secondary">{{ ucfirst($template['template_type']) }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($template['is_active'])
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                        @if($template['is_system'])
                                            <span class="badge bg-info ms-1">System</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ date('M j, Y', strtotime($template['created_at'])) }}</td>
                                </tr>
                                @if($template['updated_at'] != $template['created_at'])
                                <tr>
                                    <td><strong>Updated:</strong></td>
                                    <td>{{ date('M j, Y', strtotime($template['updated_at'])) }}</td>
                                </tr>
                                @endif
                            </table>
                            
                            @if($template['description'])
                                <div class="mt-3">
                                    <strong>Description:</strong>
                                    <p class="text-muted mt-1">{{ $template['description'] }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Sample Data -->
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-database me-2"></i>Sample Data
                            </h6>
                        </div>
                        <div class="card-body">
                            <small class="text-muted">The preview uses the following sample data:</small>
                            <div class="mt-2" style="max-height: 300px; overflow-y: auto;">
                                <pre class="bg-light p-2 rounded" style="font-size: 11px;"><code>{{ json_encode($sample_data, JSON_PRETTY_PRINT) }}</code></pre>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview -->
                <div class="col-lg-8">
                    <div class="card shadow">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-desktop me-2"></i>Live Preview
                            </h6>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-secondary active" onclick="setPreviewMode('desktop')">
                                    <i class="fas fa-desktop"></i> Desktop
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setPreviewMode('tablet')">
                                    <i class="fas fa-tablet-alt"></i> Tablet
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setPreviewMode('mobile')">
                                    <i class="fas fa-mobile-alt"></i> Mobile
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-info" onclick="openFullPreview()">
                                    <i class="fas fa-external-link-alt"></i> Full Screen
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="preview-container text-center" style="background: #f8f9fa; padding: 20px;">
                                <div id="previewFrame" class="preview-frame" style="margin: 0 auto; background: white; box-shadow: 0 4px 8px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden; transition: all 0.3s ease;">
                                    <iframe src="data:text/html;charset=utf-8,{{ urlencode($rendered['html']) }}" 
                                            style="width: 100%; height: 600px; border: none; display: block;">
                                    </iframe>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Template Code Tabs -->
                    <div class="card shadow mt-4">
                        <div class="card-header py-3">
                            <ul class="nav nav-tabs card-header-tabs" id="codeTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="html-tab" data-bs-toggle="tab" data-bs-target="#html" type="button" role="tab">
                                        <i class="fab fa-html5 me-1"></i>HTML
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="css-tab" data-bs-toggle="tab" data-bs-target="#css" type="button" role="tab">
                                        <i class="fab fa-css3-alt me-1"></i>CSS
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="js-tab" data-bs-toggle="tab" data-bs-target="#js" type="button" role="tab">
                                        <i class="fab fa-js-square me-1"></i>JavaScript
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body p-0">
                            <div class="tab-content" id="codeTabContent">
                                <div class="tab-pane fade show active" id="html" role="tabpanel">
                                    <pre class="mb-0 p-3" style="max-height: 400px; overflow-y: auto; background: #f8f9fa;"><code class="language-html">{{ htmlspecialchars($template['html_template']) }}</code></pre>
                                </div>
                                <div class="tab-pane fade" id="css" role="tabpanel">
                                    <pre class="mb-0 p-3" style="max-height: 400px; overflow-y: auto; background: #f8f9fa;"><code class="language-css">{{ htmlspecialchars($template['css_styles'] ?: '/* No CSS styles defined */') }}</code></pre>
                                </div>
                                <div class="tab-pane fade" id="js" role="tabpanel">
                                    <pre class="mb-0 p-3" style="max-height: 400px; overflow-y: auto; background: #f8f9fa;"><code class="language-javascript">{{ htmlspecialchars($template['js_scripts'] ?: '// No JavaScript defined') }}</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Full Screen Preview Modal -->
<div class="modal fade" id="fullPreviewModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $template['name'] }} - Full Screen Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="fullPreviewFrame" style="width: 100%; height: 100%; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function setPreviewMode(mode) {
    const frame = document.getElementById('previewFrame');
    const buttons = document.querySelectorAll('.btn-group .btn');
    
    // Remove active class from all buttons
    buttons.forEach(btn => btn.classList.remove('active'));
    
    // Add active class to clicked button
    event.target.classList.add('active');
    
    // Set frame width based on mode
    switch(mode) {
        case 'desktop':
            frame.style.width = '100%';
            frame.style.maxWidth = 'none';
            break;
        case 'tablet':
            frame.style.width = '768px';
            frame.style.maxWidth = '100%';
            break;
        case 'mobile':
            frame.style.width = '375px';
            frame.style.maxWidth = '100%';
            break;
    }
}

function openFullPreview() {
    const originalFrame = document.querySelector('#previewFrame iframe');
    const fullFrame = document.getElementById('fullPreviewFrame');
    
    // Copy the source to the full screen frame
    fullFrame.src = originalFrame.src;
    
    // Show the modal
    new bootstrap.Modal(document.getElementById('fullPreviewModal')).show();
}

// Syntax highlighting (basic)
document.addEventListener('DOMContentLoaded', function() {
    // You can add Prism.js or similar for better syntax highlighting
    const codeBlocks = document.querySelectorAll('pre code');
    codeBlocks.forEach(block => {
        // Basic syntax highlighting could be added here
        block.style.fontSize = '12px';
        block.style.lineHeight = '1.4';
    });
});
</script>
@endsection

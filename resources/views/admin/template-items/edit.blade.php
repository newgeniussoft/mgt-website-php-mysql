@extends('layouts.admin')

@section('title', 'Edit Template Item')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Edit Template Item</h2>
        <a href="{{ admin_url('template-items') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Templates
        </a>
    </div>

    <form method="POST" action="{{ admin_url('template-items/update') }}" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="{{ $_SESSION['csrf_token'] }}">
        <input type="hidden" name="id" value="{{ $template->id }}">
        
        <div class="row">
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Template Name *</label>
                            <input type="text" name="name" class="form-control" value="{{ $template->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2">{{ $template->description }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Model Name *</label>
                                <select name="model_name" class="form-select" required>
                                    @foreach($availableModels as $key => $label)
                                        <option value="{{ $key }}" {{ $template->model_name == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Default Keys</label>
                                <input type="text" name="default_keys" class="form-control" value="{{ $template->default_keys }}" placeholder="name,image,price">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- HTML Template -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">HTML Template *</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="extractVariables()">
                            <i class="fas fa-magic"></i> Extract Variables
                        </button>
                    </div>
                    <div class="card-body">
                        <textarea name="html_template" id="htmlTemplate" class="form-control font-monospace" rows="12" required>{{ $template->html_template }}</textarea>
                    </div>
                </div>

                <!-- CSS Styles -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">CSS Styles</h5>
                    </div>
                    <div class="card-body">
                        <textarea name="css_styles" class="form-control font-monospace" rows="8">{{ $template->css_styles }}</textarea>
                    </div>
                </div>

                <!-- JavaScript Code -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">JavaScript Code</h5>
                    </div>
                    <div class="card-body">
                        <textarea name="js_code" class="form-control font-monospace" rows="6">{{ $template->js_code }}</textarea>
                    </div>
                </div>

                <!-- Variables -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Template Variables</h5>
                        <button type="button" class="btn btn-sm btn-success" onclick="addVariable()">
                            <i class="fas fa-plus"></i> Add Variable
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="variablesContainer">
                            @if(count($variables) > 0)
                                @foreach($variables as $var)
                                    <div class="border rounded p-3 mb-3 variable-item">
                                        <div class="row g-2">
                                            <div class="col-md-3">
                                                <input type="text" name="var_key[]" class="form-control form-control-sm" placeholder="Key" value="{{ $var['key'] }}" required>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" name="var_label[]" class="form-control form-control-sm" placeholder="Label" value="{{ $var['label'] }}">
                                            </div>
                                            <div class="col-md-2">
                                                <select name="var_type[]" class="form-select form-select-sm">
                                                    <option value="text" {{ $var['type'] == 'text' ? 'selected' : '' }}>Text</option>
                                                    <option value="number" {{ $var['type'] == 'number' ? 'selected' : '' }}>Number</option>
                                                    <option value="url" {{ $var['type'] == 'url' ? 'selected' : '' }}>URL</option>
                                                    <option value="date" {{ $var['type'] == 'date' ? 'selected' : '' }}>Date</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" name="var_default[]" class="form-control form-control-sm" placeholder="Default" value="{{ $var['default'] ?? '' }}">
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-sm btn-danger w-100" onclick="this.closest('.variable-item').remove()">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted">No variables defined. Click "Extract Variables" to auto-detect.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Settings -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="active" {{ $template->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="draft" {{ $template->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="archived" {{ $template->status == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="is_default" class="form-check-input" id="isDefault" {{ $template->is_default ? 'checked' : '' }}>
                                <label class="form-check-label" for="isDefault">
                                    Set as Default Template
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Thumbnail</label>
                            @if($template->thumbnail)
                                <div class="mb-2">
                                    <img src="{{ $template->thumbnail }}" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                            @endif
                            <input type="file" name="thumbnail" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-save"></i> Update Template
                        </button>
                        <a href="/admin/template-items/preview?id={{ $template->id }}" class="btn btn-info w-100 mb-2" target="_blank">
                            <i class="fas fa-eye"></i> Preview
                        </a>
                        <a href="/admin/template-items" class="btn btn-secondary w-100">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
let variableIndex = {{ count($variables) }};

function addVariable(key = '', label = '', type = 'text', defaultValue = '') {
    const container = document.getElementById('variablesContainer');
    
    const varDiv = document.createElement('div');
    varDiv.className = 'border rounded p-3 mb-3 variable-item';
    varDiv.innerHTML = `
        <div class="row g-2">
            <div class="col-md-3">
                <input type="text" name="var_key[]" class="form-control form-control-sm" placeholder="Key" value="${key}" required>
            </div>
            <div class="col-md-4">
                <input type="text" name="var_label[]" class="form-control form-control-sm" placeholder="Label" value="${label}">
            </div>
            <div class="col-md-2">
                <select name="var_type[]" class="form-select form-select-sm">
                    <option value="text" ${type === 'text' ? 'selected' : ''}>Text</option>
                    <option value="number" ${type === 'number' ? 'selected' : ''}>Number</option>
                    <option value="url" ${type === 'url' ? 'selected' : ''}>URL</option>
                    <option value="date" ${type === 'date' ? 'selected' : ''}>Date</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="text" name="var_default[]" class="form-control form-control-sm" placeholder="Default" value="${defaultValue}">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-sm btn-danger w-100" onclick="this.closest('.variable-item').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;
    
    container.appendChild(varDiv);
    variableIndex++;
}

function extractVariables() {
    const html = document.getElementById('htmlTemplate').value;
    
    fetch('/admin/template-items/extract-variables', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'html=' + encodeURIComponent(html)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.variables.length > 0) {
            if (confirm(`Found ${data.variables.length} variable(s). Replace existing variables?`)) {
                document.getElementById('variablesContainer').innerHTML = '';
                variableIndex = 0;
                
                data.variables.forEach(variable => {
                    addVariable(variable.key, variable.label, variable.type, variable.default);
                });
            }
        } else {
            alert('No variables found in template');
        }
    });
}
</script>
@endsection

@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-cog"></i> General Settings
                </h1>
            </div>
        </div>
    </div>
    
    @if(isset($success) && $success)
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ $success }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    
    @if(isset($error) && $error)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ $error }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Settings Groups</h5>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($groups as $group)
                        <a href="?group={{ $group }}" 
                           class="list-group-item list-group-item-action {{ $activeGroup === $group ? 'active' : '' }}">
                            <i class="fas fa-{{ getGroupIcon($group) }}"></i>
                            {{ ucfirst($group) }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Settings Form -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-{{ getGroupIcon($activeGroup) }}"></i>
                        {{ ucfirst($activeGroup) }} Settings
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ admin_url('settings/update') }}" enctype="multipart/form-data">
                        @csrf
                        
                        @if(isset($groupedSettings[$activeGroup]))
                            @foreach($groupedSettings[$activeGroup] as $setting)
                                <div class="form-group">
                                    <label for="{{ $setting->key }}">
                                        {{ $setting->label ?? ucfirst(str_replace('_', ' ', $setting->key)) }}
                                        @if($setting->description)
                                            <small class="text-muted d-block">{{ $setting->description }}</small>
                                        @endif
                                    </label>
                                    
                                    @if($setting->type === 'textarea')
                                        <textarea 
                                            class="form-control" 
                                            id="{{ $setting->key }}" 
                                            name="{{ $setting->key }}" 
                                            rows="4">{{ $setting->value }}</textarea>
                                    
                                    @elseif($setting->type === 'boolean')
                                        <div class="custom-control custom-switch">
                                            <input 
                                                type="checkbox" 
                                                class="custom-control-input" 
                                                id="{{ $setting->key }}" 
                                                name="{{ $setting->key }}" 
                                                value="1"
                                                {{ $setting->value ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="{{ $setting->key }}">
                                                {{ $setting->value ? 'Enabled' : 'Disabled' }}
                                            </label>
                                        </div>
                                    
                                    @elseif($setting->type === 'image')
                                        <div class="input-group">
                                            <input 
                                                type="text" 
                                                class="form-control" 
                                                id="{{ $setting->key }}_display" 
                                                value="{{ $setting->value }}" 
                                                readonly>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button" onclick="document.getElementById('{{ $setting->key }}').click()">
                                                    <i class="fas fa-upload"></i> Upload
                                                </button>
                                            </div>
                                        </div>
                                        <input 
                                            type="file" 
                                            id="{{ $setting->key }}" 
                                            name="{{ $setting->key }}" 
                                            class="d-none" 
                                            accept="image/*"
                                            onchange="document.getElementById('{{ $setting->key }}_display').value = this.files[0]?.name || '{{ $setting->value }}'">
                                        @if($setting->value)
                                            <div class="mt-2">
                                                <img src="{{ asset($setting->value) }}" alt="{{ $setting->label }}" class="img-thumbnail" style="max-width: 200px;">
                                            </div>
                                        @endif
                                    
                                    @elseif($setting->type === 'number')
                                        <input 
                                            type="number" 
                                            class="form-control" 
                                            id="{{ $setting->key }}" 
                                            name="{{ $setting->key }}" 
                                            value="{{ $setting->value }}">
                                    
                                    @elseif($setting->type === 'email')
                                        <input 
                                            type="email" 
                                            class="form-control" 
                                            id="{{ $setting->key }}" 
                                            name="{{ $setting->key }}" 
                                            value="{{ $setting->value }}">
                                    
                                    @elseif($setting->type === 'url')
                                        <input 
                                            type="url" 
                                            class="form-control" 
                                            id="{{ $setting->key }}" 
                                            name="{{ $setting->key }}" 
                                            value="{{ $setting->value }}"
                                            placeholder="https://">
                                    
                                    @else
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            id="{{ $setting->key }}" 
                                            name="{{ $setting->key }}" 
                                            value="{{ $setting->value }}">
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> No settings found for this group.
                            </div>
                        @endif
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Settings
                            </button>
                            
                            <button type="button" class="btn btn-outline-secondary" onclick="if(confirm('Are you sure you want to reset these settings?')) { document.getElementById('resetForm').submit(); }">
                                <i class="fas fa-undo"></i> Reset to Defaults
                            </button>
                        </div>
                    </form>
                    
                    <!-- Hidden reset form -->
                    <form id="resetForm" method="POST" action="{{ admin_url('settings/reset') }}" class="d-none">
                        @csrf
                        <input type="hidden" name="group" value="{{ $activeGroup }}">
                    </form>
                </div>
            </div>
            
            <!-- Settings Info Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Information</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Total Settings:</strong> {{ count($groupedSettings[$activeGroup] ?? []) }}</p>
                    <p class="mb-0"><strong>Last Updated:</strong> {{ date('Y-m-d H:i:s') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .list-group-item.active {
        background-color: #667eea;
        border-color: #667eea;
    }
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
    .list-group-item.active:hover {
        background-color: #5568d3;
    }
</style>

@php
function getGroupIcon($group) {
    $icons = [
        'general' => 'cog',
        'contact' => 'address-book',
        'social' => 'share-alt',
        'email' => 'envelope',
        'seo' => 'search',
        'appearance' => 'palette',
        'system' => 'server'
    ];
    return $icons[$group] ?? 'cog';
}
@endphp

@endsection

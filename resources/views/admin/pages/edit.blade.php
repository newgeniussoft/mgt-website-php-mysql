@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Edit Page: {{ $page->title }}</h1>
        <div>
            <a href="{{ admin_url('sections?page_id=' . $page->id) }}" class="btn btn-info mr-2">
                <i class="fas fa-th-large"></i> Manage Sections
            </a>
            <a href="{{ admin_url('pages') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Pages
            </a>
        </div>
    </div>

    @if(isset($_SESSION['success']))
        <div class="alert alert-success alert-dismissible fade show">
            {{ $_SESSION['success'] }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php unset($_SESSION['success']); ?>
    @endif

    @if(isset($_SESSION['error']))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ $_SESSION['error'] }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php unset($_SESSION['error']); ?>
    @endif

    <form method="POST" action="{{ admin_url('pages/update') }}" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="{{ csrf_token() }}">
        <input type="hidden" name="id" value="{{ $page->id }}">
        
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Page Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="title">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ $page->title }}" required>
                        </div>

                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug" value="{{ $page->slug }}">
                        </div>

                        <div class="form-group">
                            <label for="meta_title">Meta Title</label>
                            <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ $page->meta_title }}">
                        </div>

                        <div class="form-group">
                            <label for="meta_description">Meta Description</label>
                            <textarea class="form-control" id="meta_description" name="meta_description" rows="3">{{ $page->meta_description }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="meta_keywords">Meta Keywords</label>
                            <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" value="{{ $page->meta_keywords }}">
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Translations</h5>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" role="tablist">
                            @foreach($locales as $index => $lc)
                                <li class="nav-item">
                                    <a class="nav-link {{ $index === 0 ? 'active' : '' }}" data-toggle="tab" href="#tab-{{ $lc }}" role="tab">{{ strtoupper($lc) }}</a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content border-left border-right border-bottom p-3">
                            @foreach($locales as $index => $lc)
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="tab-{{ $lc }}" role="tabpanel">
                                    <div class="form-group">
                                        <label>Title ({{ strtoupper($lc) }})</label>
                                        <input type="text" class="form-control" name="translations[{{ $lc }}][title]" value="{{ $translations[$lc]['title'] ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Meta Title ({{ strtoupper($lc) }})</label>
                                        <input type="text" class="form-control" name="translations[{{ $lc }}][meta_title]" value="{{ $translations[$lc]['meta_title'] ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Meta Description ({{ strtoupper($lc) }})</label>
                                        <textarea class="form-control" name="translations[{{ $lc }}][meta_description]" rows="3">{{ $translations[$lc]['meta_description'] ?? '' }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @if(!empty($sections))
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Page Sections ({{ count($sections) }})</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                @foreach($sections as $section)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $section->name }}</strong>
                                            <span class="badge badge-{{ $section->is_active ? 'success' : 'secondary' }} ml-2">
                                                {{ $section->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                            <br>
                                            <small class="text-muted">Type: {{ $section->type }}</small>
                                        </div>
                                        <a href="{{ admin_url('sections/edit?id=' . $section->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            <a href="{{ admin_url('sections/create?page_id=' . $page->id) }}" class="btn btn-success btn-sm mt-3">
                                <i class="fas fa-plus"></i> Add New Section
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="template_id">Template</label>
                            <select class="form-control" id="template_id" name="template_id">
                                <option value="">Default Template</option>
                                @foreach($templates as $template)
                                    <option value="{{ $template->id }}" {{ $page->template_id == $template->id ? 'selected' : '' }}>
                                        {{ $template->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="draft" {{ $page->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ $page->status === 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ $page->status === 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="parent_id">Parent Page</label>
                            <select class="form-control" id="parent_id" name="parent_id">
                                <option value="">None (Top Level)</option>
                                @foreach($pages as $p)
                                    @if($p->id != $page->id)
                                        <option value="{{ $p->id }}" {{ $page->parent_id == $p->id ? 'selected' : '' }}>
                                            {{ $p->title }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="menu_order">Menu Order</label>
                            <input type="number" class="form-control" id="menu_order" name="menu_order" value="{{ $page->menu_order }}">
                        </div>

                        <div class="form-group">
                            <label for="featured_image">Featured Image</label>
                            @if($page->featured_image)
                                <div class="mb-2">
                                    <img src="{{ $page->featured_image }}" alt="Featured Image" class="img-fluid" style="max-height: 150px;">
                                </div>
                            @endif
                            <input type="file" class="form-control-file" id="featured_image" name="featured_image" accept="image/*">
                        </div>

                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="is_homepage" name="is_homepage" {{ $page->is_homepage ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_homepage">Set as Homepage</label>
                        </div>

                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="show_in_menu" name="show_in_menu" {{ $page->show_in_menu ? 'checked' : '' }}>
                            <label class="custom-control-label" for="show_in_menu">Show in Menu</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block mb-2">
                    <i class="fas fa-save"></i> Update Page
                </button>
                
                <a href="{{ admin_url('pages/preview?id=' . $page->id) }}" class="btn btn-secondary btn-block" target="_blank">
                    <i class="fas fa-eye"></i> Preview Page
                </a>
            </div>
        </div>
    </form>
</div>
@endsection

@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Create New Page</h1>
        <a href="{{ admin_url('pages') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Pages
        </a>
    </div>

    @if(isset($_SESSION['error']))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ $_SESSION['error'] }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php unset($_SESSION['error']); ?>
    @endif

    <form method="POST" action="{{ admin_url('pages/store') }}" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="{{ csrf_token() }}">
        
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Page Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="title">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>

                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug" placeholder="Auto-generated from title">
                            <small class="form-text text-muted">Leave empty to auto-generate from title</small>
                        </div>

                        <div class="form-group">
                            <label for="meta_title">Meta Title</label>
                            <input type="text" class="form-control" id="meta_title" name="meta_title">
                        </div>

                        <div class="form-group">
                            <label for="meta_description">Meta Description</label>
                            <textarea class="form-control" id="meta_description" name="meta_description" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="meta_keywords">Meta Keywords</label>
                            <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" placeholder="keyword1, keyword2, keyword3">
                        </div>
                    </div>
                </div>
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
                                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                                <option value="archived">Archived</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="parent_id">Parent Page</label>
                            <select class="form-control" id="parent_id" name="parent_id">
                                <option value="">None (Top Level)</option>
                                @foreach($pages as $p)
                                    <option value="{{ $p->id }}">{{ $p->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="menu_order">Menu Order</label>
                            <input type="number" class="form-control" id="menu_order" name="menu_order" value="0">
                        </div>

                        <div class="form-group">
                            <label for="featured_image">Featured Image</label>
                            <input type="file" class="form-control-file" id="featured_image" name="featured_image" accept="image/*">
                        </div>

                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="is_homepage" name="is_homepage">
                            <label class="custom-control-label" for="is_homepage">Set as Homepage</label>
                        </div>

                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="show_in_menu" name="show_in_menu" checked>
                            <label class="custom-control-label" for="show_in_menu">Show in Menu</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-save"></i> Create Page
                </button>
            </div>
        </div>
    </form>
</div>

<script>
// Auto-generate slug from title
document.getElementById('title').addEventListener('input', function() {
    if (!document.getElementById('slug').value) {
        const slug = this.value
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
        document.getElementById('slug').value = slug;
    }
});
</script>
@endsection

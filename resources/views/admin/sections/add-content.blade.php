@extends('layouts.admin')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endpush

@section('content')
<!-- Summernote CSS -->

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ isset($section) ? 'Edit Content' : 'Add Content' }} - {{ isset($section) ? $section->name : '' }}</h1>
        <a href="{{ admin_url('sections/edit?id=' . $section->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Sectionss
        </a>
    </div>

    @if(isset($_SESSION['error']))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ $_SESSION['error'] }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php unset($_SESSION['error']); ?>
    @endif

    <form method="POST" action="{{ isset($section) ? admin_url('sections/update-content') : admin_url('sections/store-content') }}">
        <input type="hidden" name="csrf_token" value="{{ csrf_token() }}">
        <input type="hidden" name="section_id" value="{{ $section->id }}">
        <input type="hidden" name="id" value="{{ isset($content) ? $content->id : '' }}">
        
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Content</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ isset($content) ? $content->title : '' }}">
                        </div>

                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea class="form-control" id="content" name="content" rows="15">{{ isset($content) ? $content->content : '' }}</textarea>
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
                            <label for="content_type">Content Type</label>
                            <select class="form-control" id="content_type" name="content_type">
                                <option value="html" {{ isset($content) && $content->content_type === 'html' ? 'selected' : '' }}>HTML</option>
                                <option value="text" {{ isset($content) && $content->content_type === 'text' ? 'selected' : '' }}>Plain Text</option>
                                <option value="markdown" {{ isset($content) && $content->content_type === 'markdown' ? 'selected' : '' }}>Markdown</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="language">Language</label>
                            <select class="form-control" id="language" name="language">
                                <option value="en" {{ isset($content) && $content->language === 'en' ? 'selected' : '' }}>English</option>
                                <option value="fr" {{ isset($content) && $content->language === 'fr' ? 'selected' : '' }}>Français</option>
                                <option value="es" {{ isset($content) && $content->language === 'es' ? 'selected' : '' }}>Español</option>
                                <option value="de" {{ isset($content) && $content->language === 'de' ? 'selected' : '' }}>Deutsch</option>
                            </select>
                        </div>

                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" {{ isset($content) && $content->is_active ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-save"></i> {{ isset($content) ? 'Update Content' : 'Add Content' }}
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Summernote JS -->
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
$(function() {
    $('#content').summernote({
        height: 400,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
});
</script>
@endpush

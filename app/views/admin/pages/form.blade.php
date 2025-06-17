@extends('admin.dashboard')
@section('content')
<h1>{{ $action == 'edit' ? 'Edit' : 'Create' }} Page</h1>
@if($error)
<div class="alert alert-danger">{{ $error }}</div>
@endif
<form method="POST">
    <div class="form-group">
        <label>Path</label>
        <input type="text" name="path" class="form-control" value="{{ $page['path'] ?? '' }}" required>
    </div>
    <div class="form-group">
        <label>Menu Title (EN)</label>
        <input type="text" name="menu_title" class="form-control" value="{{ $page['menu_title'] ?? '' }}" required>
    </div>
    <div class="form-group">
        <label>Menu Title (ES)</label>
        <input type="text" name="menu_title_es" class="form-control" value="{{ $page['menu_title_es'] ?? '' }}" required>
    </div>
    <div class="form-group">
        <label>Meta Title (EN)</label>
        <textarea name="meta_title" class="form-control" required>{{ $page['meta_title'] ?? '' }}</textarea>
    </div>
    <div class="form-group">
        <label>Meta Title (ES)</label>
        <textarea name="meta_title_es" class="form-control" required>{{ $page['meta_title_es'] ?? '' }}</textarea>
    </div>
    <div class="form-group">
        <label>Meta Description (EN)</label>
        <textarea name="meta_description" class="form-control" required>{{ $page['meta_description'] ?? '' }}</textarea>
    </div>
    <div class="form-group">
        <label>Meta Description (ES)</label>
        <textarea name="meta_description_es" class="form-control" required>{{ $page['meta_description_es'] ?? '' }}</textarea>
    </div>
    <div class="form-group">
        <label>Meta Keywords (EN)</label>
        <textarea name="meta_keywords" class="form-control" required>{{ $page['meta_keywords'] ?? '' }}</textarea>
    </div>
    <div class="form-group">
        <label>Meta Keywords (ES)</label>
        <textarea name="meta_keywords_es" class="form-control" required>{{ $page['meta_keywords_es'] ?? '' }}</textarea>
    </div>
    <div class="form-group">
        <label>Meta Image</label>
        <input type="text" name="meta_image" class="form-control" value="{{ $page['meta_image'] ?? '' }}">
    </div>
    <div class="form-group">
        <label>Title H1 (EN)</label>
        <input type="text" name="title_h1" class="form-control" value="{{ $page['title_h1'] ?? '' }}">
    </div>
    <div class="form-group">
        <label>Title H1 (ES)</label>
        <input type="text" name="title_h1_es" class="form-control" value="{{ $page['title_h1_es'] ?? '' }}">
    </div>
    <div class="form-group">
        <label>Title H2 (EN)</label>
        <input type="text" name="title_h2" class="form-control" value="{{ $page['title_h2'] ?? '' }}">
    </div>
    <div class="form-group">
        <label>Title H2 (ES)</label>
        <input type="text" name="title_h2_es" class="form-control" value="{{ $page['title_h2_es'] ?? '' }}">
    </div>
    <button type="submit" class="btn btn-success">{{ $action == 'edit' ? 'Update' : 'Create' }}</button>
    <a href="/access/pages" class="btn btn-secondary">Cancel</a>
</form>
@endsection

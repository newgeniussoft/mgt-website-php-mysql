@extends('layouts.admin')

@section('title', 'Edit Blog - ' . $item->title)

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit me-2"></i>Edit Blog: {{ $item->title }}
        </h1>
        <a href="{{ admin_url('blogs') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Blogs
        </a>
    </div>

    <form method="POST" action="{{ admin_url('blogs/update') }}" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="{{ $_SESSION['csrf_token'] }}">
        <input type="hidden" name="id" value="{{ $item->id }}">

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-info-circle me-1"></i>Blog Content (EN)
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title (EN) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ $item->title }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="short_texte" class="form-label">Short Text (EN)</label>
                            <textarea class="form-control" id="short_texte" name="short_texte" rows="2">{{ $item->short_texte }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description (EN)</label>
                            <textarea class="form-control" id="description" name="description" rows="6">{{ $item->description }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-language me-1"></i>Blog Content (ES)
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title_es" class="form-label">Title (ES) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title_es" name="title_es" value="{{ $item->title_es }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="short_texte_es" class="form-label">Short Text (ES)</label>
                            <textarea class="form-control" id="short_texte_es" name="short_texte_es" rows="2">{{ $item->short_texte_es }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="description_es" class="form-label">Description (ES)</label>
                            <textarea class="form-control" id="description_es" name="description_es" rows="6">{{ $item->description_es }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-image me-1"></i>Featured Image
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($item->image)
                            <div class="mb-3">
                                <label class="form-label">Current Image</label>
                                <div>
                                    <img src="/uploads/{{ $item->image }}" class="img-thumbnail" style="max-width: 200px; max-height: 120px;">
                                </div>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label for="image" class="form-label">Update Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewBlogImage(this)">
                            <div class="form-text">Leave blank to keep current image.</div>
                        </div>
                        <div id="imagePreview" class="mt-2"></div>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-1"></i>Update Blog
                            </button>
                            <a href="{{ admin_url('blogs') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function previewBlogImage(input) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'img-thumbnail mt-2';
            img.style.maxWidth = '200px';
            img.style.maxHeight = '120px';
            preview.appendChild(img);
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection

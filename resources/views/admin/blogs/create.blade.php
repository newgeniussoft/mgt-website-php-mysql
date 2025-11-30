@extends('layouts.admin')

@section('title', 'Create Blog')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus me-2"></i>Create Blog
        </h1>
        <a href="{{ admin_url('blogs') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Blogs
        </a>
    </div>

    <form method="POST" action="{{ admin_url('blogs/store') }}" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="{{ $_SESSION['csrf_token'] }}">

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
                            <input type="text" class="form-control" id="title" name="title" placeholder="English title" required>
                        </div>
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug" placeholder="auto from title if left blank">
                            <div class="form-text">Lowercase letters, numbers, and hyphens only. Must be unique.</div>
                        </div>
                        <div class="mb-3">
                            <label for="short_texte" class="form-label">Short Text (EN)</label>
                            <textarea class="form-control" id="short_texte" name="short_texte" rows="2" placeholder="Short summary (max 500 chars)"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description (EN)</label>
                            <textarea class="form-control" id="description" name="description" rows="6" placeholder="Full description"></textarea>
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
                            <input type="text" class="form-control" id="title_es" name="title_es" placeholder="Título en español" required>
                        </div>
                        <div class="mb-3">
                            <label for="short_texte_es" class="form-label">Short Text (ES)</label>
                            <textarea class="form-control" id="short_texte_es" name="short_texte_es" rows="2" placeholder="Resumen corto (máx 500 caracteres)"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="description_es" class="form-label">Description (ES)</label>
                            <textarea class="form-control" id="description_es" name="description_es" rows="6" placeholder="Descripción completa"></textarea>
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
                        <div class="mb-3">
                            <label for="image" class="form-label">Image <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" required onchange="previewBlogImage(this)">
                            <div class="form-text">Uploaded to <code>/uploads/blogs</code>. Original name base preserved.</div>
                        </div>
                        <div id="imagePreview" class="mt-2"></div>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-1"></i>Create Blog
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

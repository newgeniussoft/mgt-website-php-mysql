@extends('layouts.admin')

@section('title', 'Edit Tour - ' . $tour['title'])

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit me-2"></i>Edit Tour: {{ $tour['title'] }}
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ admin_url('tours/details?tour_id=' . $tour['id']) }}" class="btn btn-info">
                <i class="fas fa-list me-1"></i>Manage Details ({{ count($tour['details']) }})
            </a>
            <a href="{{ admin_url('tours/photos?tour_id=' . $tour['id']) }}" class="btn btn-primary">
                <i class="fas fa-images me-1"></i>Manage Photos ({{ count($tour['photos']) }})
            </a>
            <a href="{{ admin_url('tours') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Tours
            </a>
        </div>
    </div>

    <!-- Translation Links -->
    @if(!empty($tour['translations']) && count($tour['translations']) > 1)
    <div class="alert alert-info mb-4">
        <h6><i class="fas fa-language me-2"></i>Available Translations:</h6>
        <div class="d-flex gap-2 flex-wrap">
            @foreach($tour['translations'] as $translation)
                <a href="{{ admin_url('tours/edit?id=' . $translation['id']) }}" 
                   class="btn btn-sm {{ $translation['id'] == $tour['id'] ? 'btn-primary' : 'btn-outline-primary' }}">
                    {{ strtoupper($translation['language']) }} - {{ $translation['title'] }}
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <form method="POST" action="{{ admin_url('tours/update') }}" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="{{ $_SESSION['csrf_token'] }}">
        <input type="hidden" name="id" value="{{ $tour['id'] }}">
        
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-info-circle me-1"></i>Basic Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Tour Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required
                                       value="{{ $tour['name'] }}">
                                <div class="form-text">Internal identifier for the tour</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="language" class="form-label">Language <span class="text-danger">*</span></label>
                                <select class="form-select" id="language" name="language" required>
                                    @foreach($languages as $code => $name)
                                        <option value="{{ $code }}" {{ $tour['language'] === $code ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Tour Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required
                                   value="{{ $tour['title'] }}">
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug"
                                   value="{{ $tour['slug'] ?? '' }}" placeholder="e.g., machu-picchu-adventure">
                            <div class="form-text">URL-friendly identifier. Leave blank to auto-generate.</div>
                        </div>

                        <div class="mb-3">
                            <label for="subtitle" class="form-label">Subtitle</label>
                            <input type="text" class="form-control" id="subtitle" name="subtitle"
                                   value="{{ $tour['subtitle'] ?? '' }}">
                        </div>

                        <div class="mb-3">
                            <label for="short_description" class="form-label">Short Description</label>
                            <textarea class="form-control" id="short_description" name="short_description" rows="3">{{ $tour['short_description'] ?? '' }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Full Description</label>
                            <textarea class="form-control summernote" id="description" name="description" rows="8">{{ $tour['description'] ?? '' }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="itinerary" class="form-label">Itinerary Overview</label>
                            <textarea class="form-control" id="itinerary" name="itinerary" rows="5">{{ $tour['itinerary'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Tour Details -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-cogs me-1"></i>Tour Details
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="price" class="form-label">Price (USD)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="price" name="price" 
                                           step="0.01" min="0" value="{{ $tour['price'] ?? '' }}">
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="duration_days" class="form-label">Duration (Days)</label>
                                <input type="number" class="form-control" id="duration_days" name="duration_days" 
                                       min="1" value="{{ $tour['duration_days'] ?? '' }}">
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="max_participants" class="form-label">Max Participants</label>
                                <input type="number" class="form-control" id="max_participants" name="max_participants" 
                                       min="1" value="{{ $tour['max_participants'] ?? '' }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="difficulty_level" class="form-label">Difficulty Level</label>
                                <select class="form-select" id="difficulty_level" name="difficulty_level">
                                    @foreach($difficultyLevels as $value => $label)
                                        <option value="{{ $value }}" {{ $tour['difficulty_level'] === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Category</label>
                                <input type="text" class="form-control" id="category" name="category" 
                                       value="{{ $tour['category'] ?? '' }}"
                                       list="categoryList">
                                <datalist id="categoryList">
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}">
                                    @endforeach
                                </datalist>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="location" name="location" 
                                   value="{{ $tour['location'] ?? '' }}">
                        </div>
                    </div>
                </div>

                <!-- Highlights and Pricing -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-star me-1"></i>Highlights & Pricing Details
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="highlights" class="form-label">Tour Highlights</label>
                            <textarea class="form-control" id="highlights" name="highlights" rows="5">{{ !empty($tour['highlights_array']) ? implode("\n", $tour['highlights_array']) : '' }}</textarea>
                            <div class="form-text">Enter each highlight on a separate line</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price_includes" class="form-label">Price Includes</label>
                                <textarea class="form-control" id="price_includes" name="price_includes" rows="6">{{ !empty($tour['price_includes_array']) ? implode("\n", $tour['price_includes_array']) : '' }}</textarea>
                                <div class="form-text">Enter each inclusion on a separate line</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="price_excludes" class="form-label">Price Excludes</label>
                                <textarea class="form-control" id="price_excludes" name="price_excludes" rows="6">{{ !empty($tour['price_excludes_array']) ? implode("\n", $tour['price_excludes_array']) : '' }}</textarea>
                                <div class="form-text">Enter each exclusion on a separate line</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO Settings -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-search me-1"></i>SEO Settings
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="meta_title" class="form-label">Meta Title</label>
                            <input type="text" class="form-control" id="meta_title" name="meta_title" 
                                   maxlength="60" value="{{ $tour['meta_title'] ?? '' }}">
                            <div class="form-text">Recommended: 50-60 characters</div>
                        </div>

                        <div class="mb-3">
                            <label for="meta_description" class="form-label">Meta Description</label>
                            <textarea class="form-control" id="meta_description" name="meta_description" 
                                      rows="3" maxlength="160">{{ $tour['meta_description'] ?? '' }}</textarea>
                            <div class="form-text">Recommended: 150-160 characters</div>
                        </div>

                        <div class="mb-3">
                            <label for="meta_keywords" class="form-label">Meta Keywords</label>
                            <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" 
                                   value="{{ $tour['meta_keywords'] ?? '' }}">
                            <div class="form-text">Comma-separated keywords</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Publish Settings -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-cog me-1"></i>Publish Settings
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="draft" {{ $tour['status'] === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="active" {{ $tour['status'] === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $tour['status'] === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="featured" name="featured" 
                                       {{ $tour['featured'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="featured">
                                    <i class="fas fa-star text-warning me-1"></i>Featured Tour
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="sort_order" class="form-label">Sort Order</label>
                            <input type="number" class="form-control" id="sort_order" name="sort_order" 
                                   value="{{ $tour['sort_order'] ?? 0 }}" min="0">
                            <div class="form-text">Lower numbers appear first</div>
                        </div>

                        <div class="mb-3">
                            <label for="translation_group" class="form-label">Translation Group</label>
                            <input type="text" class="form-control" id="translation_group" name="translation_group" 
                                   value="{{ $tour['translation_group'] ?? '' }}">
                            <div class="form-text">Link related translations</div>
                        </div>

                        <div class="text-muted small">
                            <div><strong>Created:</strong> {{ date('M j, Y g:i A', strtotime($tour['created_at'])) }}</div>
                            <div><strong>Updated:</strong> {{ date('M j, Y g:i A', strtotime($tour['updated_at'])) }}</div>
                        </div>
                    </div>
                </div>

                <!-- Current Images -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-images me-1"></i>Current Images
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($tour['image'])
                            <div class="mb-3">
                                <label class="form-label">Current Main Image</label>
                                <div>
                                    <img src="/uploads/{{ $tour['image'] }}" 
                                         class="img-thumbnail" style="max-width: 200px; max-height: 150px;">
                                </div>
                            </div>
                        @endif

                        @if($tour['cover_image'])
                            <div class="mb-3">
                                <label class="form-label">Current Cover Image</label>
                                <div>
                                    <img src="/uploads/{{ $tour['cover_image'] }}" 
                                         class="img-thumbnail" style="max-width: 200px; max-height: 100px;">
                                </div>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="image" class="form-label">Update Main Image</label>
                            <input type="file" class="form-control" id="image" name="image" 
                                   accept="image/*" onchange="previewImage(this, 'imagePreview')">
                            <div class="form-text">Leave blank to keep current image</div>
                            <div id="imagePreview" class="mt-2"></div>
                        </div>

                        <div class="mb-3">
                            <label for="cover_image" class="form-label">Update Cover Image</label>
                            <input type="file" class="form-control" id="cover_image" name="cover_image" 
                                   accept="image/*" onchange="previewImage(this, 'coverPreview')">
                            <div class="form-text">Leave blank to keep current image</div>
                            <div id="coverPreview" class="mt-2"></div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-chart-bar me-1"></i>Quick Stats
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <div class="h4 mb-0 text-primary">{{ count($tour['details']) }}</div>
                                    <div class="small text-muted">Days</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="h4 mb-0 text-info">{{ count($tour['photos']) }}</div>
                                <div class="small text-muted">Photos</div>
                            </div>
                        </div>
                        <hr>
                        <div class="d-grid gap-2">
                            <a href="{{ admin_url('tours/details?tour_id=' . $tour['id']) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-list me-1"></i>Manage Itinerary
                            </a>
                            <a href="{{ admin_url('tours/photos?tour_id=' . $tour['id']) }}" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-images me-1"></i>Manage Gallery
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-1"></i>Update Tour
                            </button>
                            <div class="btn-group">
                                <a href="{{ admin_url('tours/duplicate?id=' . $tour['id']) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-copy me-1"></i>Duplicate
                                </a>
                                <a href="{{ admin_url('tours/duplicate?id=' . $tour['id'] . '&language=' . ($tour['language'] === 'en' ? 'es' : 'en')) }}" 
                                   class="btn btn-outline-info">
                                    <i class="fas fa-language me-1"></i>Translate
                                </a>
                            </div>
                            <a href="{{ admin_url('tours') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Include Summernote CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Summernote
    $('.summernote').summernote({
        height: 200,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
});

function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    preview.innerHTML = '';
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'img-thumbnail mt-2';
            img.style.maxWidth = '200px';
            img.style.maxHeight = '150px';
            preview.appendChild(img);
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection

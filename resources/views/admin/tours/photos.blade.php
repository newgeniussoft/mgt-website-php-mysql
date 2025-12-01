@extends('layouts.admin')

@section('title', 'Tour Photos - ' . $tour['title'])

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-images me-2"></i>Tour Photos: {{ $tour['title'] }}
        </h1>
        <div class="d-flex gap-2">
            <button type="button" onclick="uploadPhotoDialog()" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i class="fas fa-upload me-1"></i>Upload Photos
            </button>
            <a href="{{ admin_url('tours/edit?id=' . $tour['id']) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Tour
            </a>
        </div>
    </div>

    <!-- Tour Info -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h5 class="mb-1">{{ $tour['title'] }}</h5>
                    <p class="text-muted mb-2">{{ $tour['subtitle'] ?? '' }}</p>
                    <div class="d-flex gap-3 text-sm">
                        <span><i class="fas fa-images me-1"></i>{{ count($photos) }} photos</span>
                        <span><i class="fas fa-calendar me-1"></i>{{ $tour['duration_days'] ?? 'N/A' }} days</span>
                        <span><i class="fas fa-map-marker-alt me-1"></i>{{ $tour['location'] ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <span class="badge bg-info fs-6">{{ strtoupper($tour['language']) }}</span>
                    <span class="badge bg-{{ $tour['status'] === 'active' ? 'success' : ($tour['status'] === 'draft' ? 'warning' : 'danger') }} fs-6">
                        {{ ucfirst($tour['status']) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Photo Type Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-1"></i>Filter by Type
            </h6>
        </div>
        <div class="card-body">
            <div class="btn-group" role="group" aria-label="Photo type filters">
                <input type="radio" class="btn-check" name="photoFilter" id="filterAll" value="all" checked>
                <label class="btn btn-outline-primary" for="filterAll">
                    All Photos ({{ count($photos) }})
                </label>

                @foreach($photoTypes as $type => $label)
                    @php $typeCount = count(array_filter($photos, fn($p) => $p['type'] === $type)); @endphp
                    <input type="radio" class="btn-check" name="photoFilter" id="filter{{ ucfirst($type) }}" value="{{ $type }}">
                    <label class="btn btn-outline-primary" for="filter{{ ucfirst($type) }}">
                        {{ $label }} ({{ $typeCount }})
                    </label>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Photos Grid -->
    @if(empty($photos))
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-images fa-3x text-gray-300 mb-3"></i>
                <h5 class="text-gray-600">No photos uploaded yet</h5>
                <p class="text-gray-500">Start building your tour gallery by uploading photos.</p>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="fas fa-upload me-1"></i>Upload First Photo
                </button>
            </div>
        </div>
    @else
        <div class="row" id="photosGrid">
            @foreach($photos as $photo)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4 photo-item" data-type="{{ $photo['type'] }}">
                    <div class="card shadow h-100">
                        <div class="position-relative">
                            <img src="/uploads/{{ $photo['image'] }}" 
                                 class="card-img-top" style="height: 200px; object-fit: cover;" 
                                 alt="{{ $photo['alt_text'] ?? $photo['title'] ?? 'Tour photo' }}">
                            
                            <!-- Featured Badge -->
                            @if($photo['is_featured'])
                                <span class="position-absolute top-0 start-0 badge bg-warning text-dark m-2">
                                    <i class="fas fa-star"></i> Featured
                                </span>
                            @endif

                            <!-- Type Badge -->
                            <span class="position-absolute top-0 end-0 badge bg-{{ 
                                $photo['type'] === 'gallery' ? 'primary' : 
                                ($photo['type'] === 'itinerary' ? 'info' : 
                                ($photo['type'] === 'accommodation' ? 'success' : 'warning')) 
                            }} m-2">
                                {{ $photoTypes[$photo['type']] ?? ucfirst($photo['type']) }}
                            </span>

                            <!-- Day Badge -->
                            @if($photo['day'])
                                <span class="position-absolute bottom-0 start-0 badge bg-dark m-2">
                                    Day {{ $photo['day'] }}
                                </span>
                            @endif

                            <!-- Actions Overlay -->
                            <div class="position-absolute bottom-0 end-0 m-2">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-light" 
                                            onclick="viewPhoto('{{ $photo['image'] }}', '{{ $photo['title'] ?? '' }}')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-light" 
                                            onclick="editPhoto({{ json_encode($photo) }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" 
                                            onclick="deletePhoto({{ $photo['id'] }}, '{{ $photo['title'] ?? 'Untitled' }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <h6 class="card-title mb-1">
                                {{ $photo['title'] ?? 'Untitled' }}
                            </h6>
                            
                            @if(!empty($photo['description']))
                                <p class="card-text small text-muted mb-2">
                                    {{ substr($photo['description'], 0, 80) }}{{ strlen($photo['description']) > 80 ? '...' : '' }}
                                </p>
                            @endif

                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ date('M j, Y', strtotime($photo['created_at'])) }}
                                </small>
                                
                                @if(!$photo['is_featured'])
                                    <button type="button" class="btn btn-sm btn-outline-warning" 
                                            onclick="setFeatured({{ $photo['id'] }})">
                                        <i class="fas fa-star"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>


<!-- Upload Photo Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">
                    <i class="fas fa-upload me-2"></i>Upload Tour Photo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="uploadForm" method="POST" action="{{ admin_url('tours/upload-photo') }}" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="{{ $_SESSION['csrf_token'] }}">
                    <input type="hidden" name="tour_id" value="{{ $tour['id'] }}">

                    <div class="mb-3">
                        <label for="photos" class="form-label">Select Photos <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="photos" name="photos[]" required multiple
                               accept="image/*" onchange="previewUploadImage(this)">
                        <div class="form-text">Supported formats: JPG, PNG, GIF, WebP. You can select multiple files.</div>
                        <div id="uploadPreview" class="mt-2 d-flex flex-wrap gap-2"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="title" class="form-label">Photo Title</label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   placeholder="e.g., Machu Picchu at sunrise">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="type" class="form-label">Photo Type</label>
                            <select class="form-select" id="type" name="type">
                                @foreach($photoTypes as $type => $label)
                                    <option value="{{ $type }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"
                                  placeholder="Brief description of the photo"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="alt_text" class="form-label">Alt Text</label>
                        <input type="text" class="form-control" id="alt_text" name="alt_text" 
                               placeholder="Descriptive text for accessibility">
                        <div class="form-text">Used for screen readers and SEO</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="day" class="form-label">Associated Day</label>
                            <select class="form-select" id="day" name="day">
                                <option value="">No specific day</option>
                                @for($i = 1; $i <= ($tour['duration_days'] ?? 10); $i++)
                                    <option value="{{ $i }}">Day {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured">
                                <label class="form-check-label" for="is_featured">
                                    <i class="fas fa-star text-warning me-1"></i>Set as Featured Photo
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="hideDialog('uploadModal')" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-1"></i>Upload Photo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Photo Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">Photo Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="viewImage" src="" class="img-fluid" style="max-height: 70vh;">
            </div>
        </div>
    </div>
</div>

<!-- Edit Photo Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">
                    <i class="fas fa-edit me-2"></i>Edit Photo Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST" action="{{ admin_url('tours/update-photo') }}">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="{{ $_SESSION['csrf_token'] }}">
                    <input type="hidden" name="tour_id" value="{{ $tour['id'] }}">
                    <input type="hidden" name="photo_id" id="edit_photo_id">

                    <div class="mb-3">
                        <label for="edit_title" class="form-label">Photo Title</label>
                        <input type="text" class="form-control" id="edit_title" name="title">
                    </div>

                    <div class="mb-3">
                        <label for="edit_type" class="form-label">Photo Type</label>
                        <select class="form-select" id="edit_type" name="type">
                            @foreach($photoTypes as $type => $label)
                                <option value="{{ $type }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="edit_alt_text" class="form-label">Alt Text</label>
                        <input type="text" class="form-control" id="edit_alt_text" name="alt_text">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_day" class="form-label">Associated Day</label>
                            <select class="form-select" id="edit_day" name="day">
                                <option value="">No specific day</option>
                                @for($i = 1; $i <= ($tour['duration_days'] ?? 10); $i++)
                                    <option value="{{ $i }}">Day {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="edit_is_featured" name="is_featured">
                                <label class="form-check-label" for="edit_is_featured">
                                    <i class="fas fa-star text-warning me-1"></i>Featured Photo
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Update Photo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>Confirm Deletion
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the photo <strong id="deletePhotoTitle"></strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    This action will permanently delete the photo file. This cannot be undone.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" action="{{ admin_url('tours/delete-photo') }}" style="display: inline;">
                    <input type="hidden" name="csrf_token" value="{{ $_SESSION['csrf_token'] }}">
                    <input type="hidden" name="tour_id" value="{{ $tour['id'] }}">
                    <input type="hidden" name="photo_id" id="deletePhotoId">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Delete Photo
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Filter photos by type
document.querySelectorAll('input[name="photoFilter"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const filterType = this.value;
        const photoItems = document.querySelectorAll('.photo-item');
        
        photoItems.forEach(item => {
            if (filterType === 'all' || item.dataset.type === filterType) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
});

function previewUploadImage(input) {
    const preview = document.getElementById('uploadPreview');
    preview.innerHTML = '';
    if (input.files && input.files.length > 0) {
        Array.from(input.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'img-thumbnail';
                img.style.maxWidth = '160px';
                img.style.maxHeight = '120px';
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    }
}

function viewPhoto(imagePath, title) {
    document.getElementById('viewImage').src = '/storage/uploads/' + imagePath;
    document.getElementById('viewModalLabel').textContent = title || 'Photo Preview';
    new bootstrap.Modal(document.getElementById('viewModal')).show();
}

function editPhoto(photo) {
    document.getElementById('edit_photo_id').value = photo.id;
    document.getElementById('edit_title').value = photo.title || '';
    document.getElementById('edit_type').value = photo.type || 'gallery';
    document.getElementById('edit_description').value = photo.description || '';
    document.getElementById('edit_alt_text').value = photo.alt_text || '';
    document.getElementById('edit_day').value = photo.day || '';
    document.getElementById('edit_is_featured').checked = photo.is_featured == 1;
    
    new bootstrap.Modal(document.getElementById('editModal')).show();
}

function deletePhoto(photoId, title) {
    document.getElementById('deletePhotoId').value = photoId;
    document.getElementById('deletePhotoTitle').textContent = title;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function uploadPhotoDialog() {
    new bootstrap.Modal(document.getElementById('uploadModal')).show();
}

function setFeatured(photoId) {
    if (confirm('Set this photo as the featured photo for this tour?')) {
        // Create a form and submit it
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ admin_url('tours/set-featured-photo') }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = 'csrf_token';
        csrfToken.value = '{{ $_SESSION['csrf_token'] }}';
        
        const tourId = document.createElement('input');
        tourId.type = 'hidden';
        tourId.name = 'tour_id';
        tourId.value = '{{ $tour['id'] }}';
        
        const photoIdInput = document.createElement('input');
        photoIdInput.type = 'hidden';
        photoIdInput.name = 'photo_id';
        photoIdInput.value = photoId;
        
        form.appendChild(csrfToken);
        form.appendChild(tourId);
        form.appendChild(photoIdInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Reset upload form when modal is closed
document.getElementById('uploadModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('uploadForm').reset();
    document.getElementById('uploadPreview').innerHTML = '';
});
</script>
@endsection

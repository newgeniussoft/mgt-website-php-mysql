@extends('layouts.admin')

@section('title', 'Tour Details - ' . $tour['title'])

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-list me-2"></i>Tour Details: {{ $tour['title'] }}
        </h1>
        <div class="d-flex gap-2">
            <button type="button" onclick="uploadDetailDialog()" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDetailModal">
                <i class="fas fa-plus me-1"></i>Add Day
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
                        <span><i class="fas fa-calendar me-1"></i>{{ $tour['duration_days'] ?? 'N/A' }} days</span>
                        <span><i class="fas fa-users me-1"></i>Max {{ $tour['max_participants'] ?? 'N/A' }} people</span>
                        <span><i class="fas fa-dollar-sign me-1"></i>${{ number_format($tour['price'] ?? 0, 2) }}</span>
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

    <!-- Tour Details -->
    @if(empty($details))
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-calendar-plus fa-3x text-gray-300 mb-3"></i>
                <h5 class="text-gray-600">No itinerary details yet</h5>
                <p class="text-gray-500">Start building your tour itinerary by adding daily details.</p>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDetailModal">
                    <i class="fas fa-plus me-1"></i>Add First Day
                </button>
            </div>
        </div>
    @else
        <div class="row">
            @foreach($details as $detail)
                <div class="col-lg-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-calendar-day me-1"></i>Day {{ $detail['day'] }}
                            </h6>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" 
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-cog"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="#" 
                                           onclick="editDetail({{ json_encode($detail) }})">
                                            <i class="fas fa-edit me-1"></i>Edit
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#" 
                                           onclick="deleteDetail({{ $detail['id'] }}, {{ $detail['day'] }})">
                                            <i class="fas fa-trash me-1"></i>Delete
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">{{ $detail['title'] }}</h6>
                            
                            @if(!empty($detail['description']))
                                <p class="card-text text-muted small">
                                    {{ substr($detail['description'], 0, 150) }}{{ strlen($detail['description']) > 150 ? '...' : '' }}
                                </p>
                            @endif

                            <!-- Activities -->
                            @if(!empty($detail['activities']))
                                <div class="mb-2">
                                    <small class="text-muted"><strong>Activities:</strong></small>
                                    <ul class="list-unstyled ms-3 mb-2">
                                        @foreach(array_slice($detail['activities'], 0, 3) as $activity)
                                            <li class="small"><i class="fas fa-check text-success me-1"></i>{{ $activity }}</li>
                                        @endforeach
                                        @if(count($detail['activities']) > 3)
                                            <li class="small text-muted">+ {{ count($detail['activities']) - 3 }} more activities</li>
                                        @endif
                                    </ul>
                                </div>
                            @endif

                            <!-- Details Grid -->
                            <div class="row g-2 text-sm">
                                @if(!empty($detail['meals']))
                                    <div class="col-6">
                                        <div class="bg-light p-2 rounded">
                                            <i class="fas fa-utensils text-primary me-1"></i>
                                            <strong>Meals:</strong><br>
                                            <small>{{ $detail['meals'] }}</small>
                                        </div>
                                    </div>
                                @endif

                                @if(!empty($detail['accommodation']))
                                    <div class="col-6">
                                        <div class="bg-light p-2 rounded">
                                            <i class="fas fa-bed text-info me-1"></i>
                                            <strong>Stay:</strong><br>
                                            <small>{{ $detail['accommodation'] }}</small>
                                        </div>
                                    </div>
                                @endif

                                @if(!empty($detail['transport']))
                                    <div class="col-6">
                                        <div class="bg-light p-2 rounded">
                                            <i class="fas fa-car text-warning me-1"></i>
                                            <strong>Transport:</strong><br>
                                            <small>{{ $detail['transport'] }}</small>
                                        </div>
                                    </div>
                                @endif

                                @if(!empty($detail['notes']))
                                    <div class="col-6">
                                        <div class="bg-light p-2 rounded">
                                            <i class="fas fa-sticky-note text-secondary me-1"></i>
                                            <strong>Notes:</strong><br>
                                            <small>{{ substr($detail['notes'], 0, 50) }}{{ strlen($detail['notes']) > 50 ? '...' : '' }}</small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer text-muted small">
                            <i class="fas fa-clock me-1"></i>Updated: {{ date('M j, Y', strtotime($detail['updated_at'])) }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Add/Edit Detail Modal -->
<div class="modal fade" id="addDetailModal" tabindex="-1" aria-labelledby="addDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDetailModalLabel">
                    <i class="fas fa-plus me-2"></i>Add Tour Detail
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="detailForm" method="POST" action="{{ admin_url('tours/save-detail') }}">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="{{ $_SESSION['csrf_token'] }}">
                    <input type="hidden" name="tour_id" value="{{ $tour['id'] }}">
                    <input type="hidden" name="detail_id" id="detail_id" value="">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="day" class="form-label">Day Number <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="day" name="day" required min="1" 
                                   value="{{ count($details) + 1 }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="sort_order" class="form-label">Sort Order</label>
                            <input type="number" class="form-control" id="sort_order" name="sort_order" 
                                   value="0" min="0">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Day Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" required
                               placeholder="e.g., Arrival in Cusco">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4"
                                  placeholder="Detailed description of the day's activities"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="activities" class="form-label">Activities</label>
                        <textarea class="form-control" id="activities" name="activities" rows="4"
                                  placeholder="Enter each activity on a new line:&#10;Airport transfer&#10;City tour&#10;Hotel check-in"></textarea>
                        <div class="form-text">Enter each activity on a separate line</div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="meals" class="form-label">Meals</label>
                            <input type="text" class="form-control" id="meals" name="meals" 
                                   placeholder="e.g., B,L,D">
                            <div class="form-text">B=Breakfast, L=Lunch, D=Dinner</div>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="accommodation" class="form-label">Accommodation</label>
                            <input type="text" class="form-control" id="accommodation" name="accommodation" 
                                   placeholder="e.g., Hotel in Cusco">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="transport" class="form-label">Transportation</label>
                        <input type="text" class="form-control" id="transport" name="transport" 
                               placeholder="e.g., Private transport, Train">
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Additional Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2"
                                  placeholder="Any additional notes or special instructions"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Save Detail
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
                <p>Are you sure you want to delete <strong>Day <span id="deleteDay"></span></strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    This action cannot be undone.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" action="{{ admin_url('tours/delete-detail') }}" style="display: inline;">
                    <input type="hidden" name="csrf_token" value="{{ $_SESSION['csrf_token'] }}">
                    <input type="hidden" name="tour_id" value="{{ $tour['id'] }}">
                    <input type="hidden" name="detail_id" id="deleteDetailId">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Delete Day
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function editDetail(detail) {
    // Populate form with existing data
    document.getElementById('detail_id').value = detail.id;
    document.getElementById('day').value = detail.day;
    document.getElementById('title').value = detail.title;
    document.getElementById('description').value = detail.description || '';
    document.getElementById('meals').value = detail.meals || '';
    document.getElementById('accommodation').value = detail.accommodation || '';
    document.getElementById('transport').value = detail.transport || '';
    document.getElementById('notes').value = detail.notes || '';
    document.getElementById('sort_order').value = detail.sort_order || 0;
    
    // Handle activities array
    if (detail.activities && Array.isArray(detail.activities)) {
        document.getElementById('activities').value = detail.activities.join('\n');
    } else {
        document.getElementById('activities').value = '';
    }
    
    // Update modal title
    document.getElementById('addDetailModalLabel').innerHTML = '<i class="fas fa-edit me-2"></i>Edit Day ' + detail.day;
    
    // Show modal
    new bootstrap.Modal(document.getElementById('addDetailModal')).show();
}

function uploadDetailDialog() {
    new bootstrap.Modal(document.getElementById('addDetailModal')).show();
}

function deleteDetail(detailId, day) {
    document.getElementById('deleteDetailId').value = detailId;
    document.getElementById('deleteDay').textContent = day;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Reset modal when closed
document.getElementById('addDetailModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('detailForm').reset();
    document.getElementById('detail_id').value = '';
    document.getElementById('day').value = {{ count($details) + 1 }};
    document.getElementById('addDetailModalLabel').innerHTML = '<i class="fas fa-plus me-2"></i>Add Tour Detail';
});
</script>
@endsection

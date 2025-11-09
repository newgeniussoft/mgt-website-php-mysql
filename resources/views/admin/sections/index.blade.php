@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Sections - {{ $page->title }}</h1>
        <div>
            <a href="{{ admin_url('sections/create?page_id=' . $page->id) }}" class="btn btn-primary mr-2">
                <i class="fas fa-plus"></i> Add Section
            </a>
            <a href="{{ admin_url('pages/edit?id=' . $page->id) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Page
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

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Page Sections</h5>
        </div>
        <div class="card-body">
            @if(empty($sections))
                <div class="text-center py-5">
                    <i class="fas fa-th-large fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No sections found for this page</p>
                    <a href="{{ admin_url('sections/create?page_id=' . $page->id) }}" class="btn btn-primary">
                        Create First Section
                    </a>
                </div>
            @else
                <div id="sectionsList">
                    @foreach($sections as $section)
                        <div class="card mb-3" data-section-id="{{ $section->id }}">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-1 text-center">
                                        <i class="fas fa-grip-vertical text-muted" style="cursor: move;"></i>
                                    </div>
                                    <div class="col-md-7">
                                        <h5 class="mb-1">{{ $section->name }}</h5>
                                        <p class="mb-1 text-muted">
                                            <small>
                                                <strong>Type:</strong> {{ ucfirst($section->type) }} | 
                                                <strong>Slug:</strong> <code>{{ $section->slug }}</code>
                                            </small>
                                        </p>
                                        @php
                                            $contents = $section->getContents();
                                        @endphp
                                        <small class="text-muted">
                                            <i class="fas fa-file-alt"></i> {{ count($contents) }} Content(s)
                                        </small>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <span class="badge badge-{{ $section->is_active ? 'success' : 'secondary' }} badge-lg">
                                            {{ $section->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ admin_url('sections/edit?id=' . $section->id) }}" class="btn btn-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger" onclick="deleteSection({{ $section->id }})" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST" action="{{ admin_url('sections/delete') }}" style="display: none;">
    <input type="hidden" name="csrf_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" id="deleteSectionId">
</form>

<!-- SortableJS for drag and drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
function deleteSection(id) {
    if (confirm('Are you sure you want to delete this section? This will also delete all content within it.')) {
        document.getElementById('deleteSectionId').value = id;
        document.getElementById('deleteForm').submit();
    }
}

// Initialize sortable
const sectionsList = document.getElementById('sectionsList');
if (sectionsList) {
    new Sortable(sectionsList, {
        animation: 150,
        handle: '.fa-grip-vertical',
        onEnd: function(evt) {
            // Get new order
            const sectionIds = [];
            document.querySelectorAll('[data-section-id]').forEach(function(el) {
                sectionIds.push(el.getAttribute('data-section-id'));
            });
            
            // Send AJAX request to update order
            fetch('{{ admin_url("sections/reorder") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'csrf_token={{ csrf_token() }}&page_id={{ $page->id }}&section_ids=' + JSON.stringify(sectionIds)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Sections reordered successfully');
                } else {
                    alert('Error reordering sections');
                }
            });
        }
    });
}
</script>
@endsection

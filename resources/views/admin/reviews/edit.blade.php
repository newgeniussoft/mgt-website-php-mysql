@extends('layouts.admin')

@section('title', 'Edit Review')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit me-2"></i>Edit Review
        </h1>
        <div>
            <a href="{{ admin_url('reviews') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Reviews
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-1"></i>Review Details
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ admin_url('reviews/update') }}">
                        <input type="hidden" name="csrf_token" value="{{ $_SESSION['csrf_token'] ?? '' }}">
                        <input type="hidden" name="id" value="{{ $review->id }}">

                        <div class="mb-3">
                            <label for="name_user" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name_user" name="name_user" value="{{ $review->name_user }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email_user" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email_user" name="email_user" value="{{ $review->email_user }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating <span class="text-danger">*</span></label>
                            <select class="form-select" id="rating" name="rating" required>
                                @for($i=5; $i>=1; $i--)
                                    <option value="{{ $i }}" {{ (int)$review->rating === $i ? 'selected' : '' }}>{{ str_repeat('★', $i) }} ({{ $i }})</option>
                                @endfor
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="message" name="message" rows="6" required>{{ $review->message }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="pending" class="form-label">Status</label>
                            <select class="form-select" id="pending" name="pending">
                                <option value="1" {{ (int)$review->pending === 1 ? 'selected' : '' }}>Pending</option>
                                <option value="0" {{ (int)$review->pending === 0 ? 'selected' : '' }}>Approved</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cog me-1"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <strong>Status:</strong>
                                @if((int)$review->pending === 1)
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @else
                                    <span class="badge bg-success">Approved</span>
                                @endif
                            </div>
                            <form method="POST" action="{{ admin_url('reviews/approve') }}" class="ms-2">
                                <input type="hidden" name="csrf_token" value="{{ $_SESSION['csrf_token'] ?? '' }}">
                                <input type="hidden" name="id" value="{{ $review->id }}">
                                <input type="hidden" name="pending" value="{{ (int)$review->pending === 1 ? 0 : 1 }}">
                                @if((int)$review->pending === 1)
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="fas fa-check me-1"></i>Approve
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-sm btn-warning">
                                        <i class="fas fa-undo me-1"></i>Mark Pending
                                    </button>
                                @endif
                            </form>
                        </div>
                    </div>

                    <hr>

                    <form method="POST" action="{{ admin_url('reviews/delete') }}" onsubmit="return confirmDelete('Delete this review?')">
                        <input type="hidden" name="csrf_token" value="{{ $_SESSION['csrf_token'] ?? '' }}">
                        <input type="hidden" name="id" value="{{ $review->id }}">
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-trash me-1"></i>Delete Review
                        </button>
                    </form>

                    <div class="mt-3 text-muted">
                        <small>
                            <strong>Created:</strong> {{ $review->daty }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

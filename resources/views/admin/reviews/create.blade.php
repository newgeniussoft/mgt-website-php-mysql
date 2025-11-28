@extends('layouts.admin')

@section('title', 'Create Review')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus me-2"></i>Create Review
        </h1>
        <a href="{{ admin_url('reviews') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Reviews
        </a>
    </div>

    <form method="POST" action="{{ admin_url('reviews/store') }}">
        <input type="hidden" name="csrf_token" value="{{ $_SESSION['csrf_token'] ?? '' }}">

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-info-circle me-1"></i>Review Details
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name_user" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name_user" name="name_user" required>
                        </div>

                        <div class="mb-3">
                            <label for="email_user" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email_user" name="email_user" required>
                        </div>

                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating <span class="text-danger">*</span></label>
                            <select class="form-select" id="rating" name="rating" required>
                                <option value="">Select rating</option>
                                <option value="5">★★★★★ (5)</option>
                                <option value="4">★★★★ (4)</option>
                                <option value="3">★★★ (3)</option>
                                <option value="2">★★ (2)</option>
                                <option value="1">★ (1)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="message" name="message" rows="6" required></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-cog me-1"></i>Settings
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="pending" class="form-label">Status</label>
                            <select class="form-select" id="pending" name="pending">
                                <option value="1" selected>Pending</option>
                                <option value="0">Approved</option>
                            </select>
                            <small class="text-muted">Pending reviews are hidden on the site until approved.</small>
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-1"></i>Create Review
                            </button>
                            <a href="{{ admin_url('reviews') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

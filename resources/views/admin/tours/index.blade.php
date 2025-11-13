@extends('layouts.admin')

@section('title', 'Tours Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-map-marked-alt me-2"></i>Tours Management
        </h1>
        <a href="{{ admin_url('tours/create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add New Tour
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Tours</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-map-marked-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Tours</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Featured Tours</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['featured'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Draft Tours</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['draft'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-edit fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-1"></i>Filters & Search
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ admin_url('tours') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Search Tours</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ $_GET['search'] ?? '' }}" placeholder="Search by title, name...">
                </div>
                
                <div class="col-md-2">
                    <label for="language" class="form-label">Language</label>
                    <select class="form-select" id="language" name="language">
                        <option value="">All Languages</option>
                        @foreach($languages as $code => $name)
                            <option value="{{ $code }}" {{ ($_GET['language'] ?? '') === $code ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="active" {{ ($_GET['status'] ?? '') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="draft" {{ ($_GET['status'] ?? '') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="inactive" {{ ($_GET['status'] ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-select" id="category" name="category">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ ($_GET['category'] ?? '') === $category ? 'selected' : '' }}>
                                {{ ucfirst($category) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="featured" class="form-label">Featured</label>
                    <select class="form-select" id="featured" name="featured">
                        <option value="">All Tours</option>
                        <option value="1" {{ ($_GET['featured'] ?? '') === '1' ? 'selected' : '' }}>Featured Only</option>
                        <option value="0" {{ ($_GET['featured'] ?? '') === '0' ? 'selected' : '' }}>Non-Featured</option>
                    </select>
                </div>
                
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tours Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-1"></i>Tours List ({{ count($tours) }} of {{ $stats['total'] ?? 0 }})
            </h6>
        </div>
        <div class="card-body">
            @if(empty($tours))
                <div class="text-center py-5">
                    <i class="fas fa-map-marked-alt fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">No tours found</h5>
                    <p class="text-gray-500">Start by creating your first tour.</p>
                    <a href="{{ admin_url('tours/create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Create First Tour
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Tour Details</th>
                                <th>Pricing</th>
                                <th>Status</th>
                                <th>Stats</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tours as $tour)
                                <tr>
                                    <td style="width: 80px;">
                                        @if($tour['image'])
                                            <img src="/storage/uploads/{{ $tour['image'] }}" 
                                                 class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                                 style="width: 60px; height: 60px;">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        @endif
                                    </td>
                                    
                                    <td>
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">{{ $tour['title'] }}</h6>
                                                @if($tour['subtitle'])
                                                    <small class="text-muted">{{ $tour['subtitle'] }}</small><br>
                                                @endif
                                                <small class="text-info">{{ $tour['name'] }}</small>
                                                @if($tour['location'])
                                                    <br><small><i class="fas fa-map-marker-alt me-1"></i>{{ $tour['location'] }}</small>
                                                @endif
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-info">{{ strtoupper($tour['language']) }}</span>
                                                @if($tour['featured'])
                                                    <span class="badge bg-warning"><i class="fas fa-star"></i></span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td style="width: 120px;">
                                        @if($tour['price'])
                                            <div class="text-success font-weight-bold">${{ number_format($tour['price'], 2) }}</div>
                                        @else
                                            <span class="text-muted">No price set</span>
                                        @endif
                                        @if($tour['duration_days'])
                                            <small class="text-muted">{{ $tour['duration_days'] }} days</small>
                                        @endif
                                    </td>
                                    
                                    <td style="width: 100px;">
                                        @if($tour['status'] === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($tour['status'] === 'draft')
                                            <span class="badge bg-warning">Draft</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                        @if($tour['category'])
                                            <br><small class="text-muted">{{ ucfirst($tour['category']) }}</small>
                                        @endif
                                    </td>
                                    
                                    <td style="width: 80px;" class="text-center">
                                        <div class="small">
                                            <div><i class="fas fa-images text-info"></i> {{ $tour['photo_count'] ?? 0 }}</div>
                                            <div><i class="fas fa-list text-primary"></i> {{ $tour['detail_count'] ?? 0 }}</div>
                                        </div>
                                    </td>
                                    
                                    <td style="width: 200px;">
                                        <div class="btn-group-vertical w-100" role="group">
                                            <div class="btn-group" role="group">
                                                <a href="{{ admin_url('tours/edit?id=' . $tour['id']) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a href="{{ admin_url('tours/details?tour_id=' . $tour['id']) }}" 
                                                   class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-list"></i> Details
                                                </a>
                                                <a href="{{ admin_url('tours/photos?tour_id=' . $tour['id']) }}" 
                                                   class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-images"></i> Photos
                                                </a>
                                            </div>
                                            <div class="btn-group mt-1" role="group">
                                                <a href="{{ admin_url('tours/duplicate?id=' . $tour['id']) }}" 
                                                   class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-copy"></i> Duplicate
                                                </a>
                                                <a href="{{ admin_url('tours/duplicate?id=' . $tour['id'] . '&language=' . ($tour['language'] === 'en' ? 'es' : 'en')) }}" 
                                                   class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-language"></i> Translate
                                                </a>
                                                <form method="POST" action="{{ admin_url('tours/delete') }}" style="display: inline;">
                                                    <input type="hidden" name="csrf_token" value="{{ $_SESSION['csrf_token'] }}">
                                                    <input type="hidden" name="id" value="{{ $tour['id'] }}">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                            onclick="return confirmDelete('Are you sure you want to delete this tour?')">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($totalPages > 1)
                    <nav aria-label="Tours pagination">
                        <ul class="pagination justify-content-center">
                            @if($page > 1)
                                <li class="page-item">
                                    <a class="page-link" href="?page={{ $page - 1 }}{{ http_build_query(array_filter($_GET, fn($k) => $k !== 'page'), '', '&') ? '&' . http_build_query(array_filter($_GET, fn($k) => $k !== 'page')) : '' }}">Previous</a>
                                </li>
                            @endif

                            @for($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++)
                                <li class="page-item {{ $i === $page ? 'active' : '' }}">
                                    <a class="page-link" href="?page={{ $i }}{{ http_build_query(array_filter($_GET, fn($k) => $k !== 'page'), '', '&') ? '&' . http_build_query(array_filter($_GET, fn($k) => $k !== 'page')) : '' }}">{{ $i }}</a>
                                </li>
                            @endfor

                            @if($page < $totalPages)
                                <li class="page-item">
                                    <a class="page-link" href="?page={{ $page + 1 }}{{ http_build_query(array_filter($_GET, fn($k) => $k !== 'page'), '', '&') ? '&' . http_build_query(array_filter($_GET, fn($k) => $k !== 'page')) : '' }}">Next</a>
                                </li>
                            @endif
                        </ul>
                    </nav>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection

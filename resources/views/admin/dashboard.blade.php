@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Tableau de bord</h1>
                <div>
                    <span class="text-muted">Welcome, <strong>{{ $admin_name }}</strong></span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total Users</h5>
                            <h2 class="mb-0">{{ $total_users }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-users fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total Pages</h5>
                            <h2 class="mb-0">{{ $total_pages }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-file-alt fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total Tours</h5>
                            <h2 class="mb-0">{{ $total_tours }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-route fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total Blogs</h5>
                            <h2 class="mb-0">{{ $total_blogs }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-file-alt fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>User</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($recent_reviews ?? []) as $r)
                                    <tr>
                                        <td>
                                            Review @if(!empty($r['rating'])) <span class="ml-2">⭐ {{ $r['rating'] }}/5</span>@endif
                                            <div class="text-muted small">{{ strlen($r['message'] ?? '') > 80 ? substr($r['message'], 0, 80) . '…' : ($r['message'] ?? '') }}</div>
                                        </td>
                                        <td>{{ $r['name_user'] ?? '—' }}</td>
                                        <td>{{ isset($r['daty']) ? date('Y-m-d H:i', strtotime($r['daty'])) : '—' }}</td>
                                        <td>
                                            @if((int)($r['pending'] ?? 0) === 1)
                                                <span class="badge badge-warning">Pending</span>
                                            @else
                                                <span class="badge badge-success">Approved</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-muted">No recent reviews.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="{{ admin_url('pages/create') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-plus-circle"></i> Create New Page
                        </a>
                        <a href="{{ admin_url('blogs/create') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-blog"></i> Add New Blog
                        </a>
                        <a href="{{ admin_url('filemanager?path=media') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-upload"></i> Upload Media
                        </a>
                        <a href="{{ admin_url('settings') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

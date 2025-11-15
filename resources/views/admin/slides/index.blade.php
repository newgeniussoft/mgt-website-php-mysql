@extends('layouts.admin')

@section('title', 'Slides')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-images me-2"></i>Slides
        </h1>
        <a href="{{ admin_url('slides/create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add New Slide
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-1"></i>Slides List
            </h6>
        </div>
        <div class="card-body">
            @if(empty($slides))
                <div class="text-center py-5">
                    <i class="fas fa-images fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">No slides found</h5>
                    <p class="text-gray-500">Start by creating your first slide.</p>
                    <a href="{{ admin_url('slides/create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Create First Slide
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Text</th>
                                <th>Link</th>
                                <th>Status</th>
                                <th>Order</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($slides as $slide)
                                <tr>
                                    <td style="width: 120px;">
                                        @if($slide->image)
                                            <img src="/uploads/{{ $slide->image }}" class="img-thumbnail" style="max-width: 100px; max-height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center" style="width: 100px; height: 60px;">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $slide->title }}</strong><br>
                                        @if($slide->subtitle)
                                            <small class="text-muted">{{ $slide->subtitle }}</small><br>
                                        @endif
                                        @if($slide->description)
                                            <small class="text-muted">{{ strlen($slide->description) > 80 ? substr($slide->description, 0, 77) . '...' : $slide->description }}</small>
                                        @endif
                                    </td>
                                    <td style="width: 200px;">
                                        @if($slide->link_url)
                                            <a href="{{ $slide->link_url }}" target="_blank">{{ $slide->link_url }}</a><br>
                                        @else
                                            <span class="text-muted">No link</span><br>
                                        @endif
                                        @if($slide->button_text)
                                            <small class="badge bg-info">{{ $slide->button_text }}</small>
                                        @endif
                                    </td>
                                    <td style="width: 100px;">
                                        @if($slide->status === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($slide->status === 'inactive')
                                            <span class="badge bg-danger">Inactive</span>
                                        @else
                                            <span class="badge bg-warning">Draft</span>
                                        @endif
                                    </td>
                                    <td style="width: 80px;">{{ $slide->sort_order }}</td>
                                    <td style="width: 160px;">
                                        <div class="btn-group" role="group">
                                            <a href="{{ admin_url('slides/edit?id=' . $slide->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ admin_url('slides/delete') }}" style="display: inline;">
                                                <input type="hidden" name="csrf_token" value="{{ $_SESSION['csrf_token'] }}">
                                                <input type="hidden" name="id" value="{{ $slide->id }}">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirmDelete('Delete this slide?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

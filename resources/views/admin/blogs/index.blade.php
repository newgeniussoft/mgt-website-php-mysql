@extends('layouts.admin')

@section('title', 'Blogs')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-blog me-2"></i>Blogs
        </h1>
        <a href="{{ admin_url('blogs/create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add New Blog
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-1"></i>Blog Posts
            </h6>
        </div>
        <div class="card-body">
            @if(empty($items))
                <div class="text-center py-5">
                    <i class="fas fa-blog fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">No blogs found</h5>
                    <p class="text-gray-500">Start by creating your first blog post.</p>
                    <a href="{{ admin_url('blogs/create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Create First Blog
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Title (EN)</th>
                                <th>Title (ES)</th>
                                <th>Short Text (EN)</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td style="width: 120px;">
                                        @if($item->image)
                                            <img src="/uploads/{{ $item->image }}" class="img-thumbnail" style="max-width: 100px; max-height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center" style="width: 100px; height: 60px;">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td><strong>{{ $item->title }}</strong></td>
                                    <td>{{ $item->title_es }}</td>
                                    <td>
                                        <small class="text-muted">{{ strlen($item->short_texte) > 80 ? substr($item->short_texte, 0, 77) . '...' : $item->short_texte }}</small>
                                    </td>
                                    <td style="width: 140px;">{{ $item->created_at }}</td>
                                    <td style="width: 160px;">
                                        <div class="btn-group" role="group">
                                            <a href="{{ admin_url('blogs/edit?id=' . $item->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ admin_url('blogs/delete') }}" style="display: inline;">
                                                <input type="hidden" name="csrf_token" value="{{ $_SESSION['csrf_token'] }}">
                                                <input type="hidden" name="id" value="{{ $item->id }}">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this blog?')">
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

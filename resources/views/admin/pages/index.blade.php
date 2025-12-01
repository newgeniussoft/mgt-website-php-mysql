@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Pages</h1>
        <a href="{{ admin_url('pages/create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Page
        </a>
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
            <form method="GET" action="{{ admin_url('pages') }}" class="form-inline">
                <input type="text" name="search" class="form-control mr-2" placeholder="Search pages..." value="{{ $search }}">
                <select name="status" class="form-control mr-2">
                    <option value="">All Status</option>
                    <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ $status === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="archived" {{ $status === 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
                <button type="submit" class="btn btn-secondary">
                    <i class="fas fa-search"></i> Search
                </button>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Template</th>
                            <th>Status</th>
                            <th>Sections</th>
                            <th>Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(empty($pages))
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <p class="text-muted mb-0">No pages found</p>
                                </td>
                            </tr>
                        @else
                            @foreach($pages as $page)
                                <tr>
                                    <td>
                                        <strong>{{ $page->title }}</strong>
                                        @if($page->is_homepage)
                                            <span class="badge badge-info ml-2">Homepage</span>
                                        @endif
                                    </td>
                                    <td><code>{{ $page->slug }}</code></td>
                                    <td>
                                        @php
                                            $template = $page->getTemplate();
                                        @endphp
                                        {{ $template ? $template->name : 'Default' }}
                                    </td>
                                    <td>
                                        @if($page->status === 'published')
                                            <span class="badge badge-success">Published</span>
                                        @elseif($page->status === 'draft')
                                            <span class="badge badge-warning">Draft</span>
                                        @else
                                            <span class="badge badge-secondary">Archived</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $sections = $page->getSections();
                                            $sectionCount = count($sections);
                                        @endphp
                                        <a href="{{ admin_url('sections?page_id=' . $page->id) }}" class="btn btn-sm btn-outline-primary">
                                            {{ $sectionCount }} Section{{ $sectionCount !== 1 ? 's' : '' }}
                                        </a>
                                    </td>
                                    <td>{{ date('M d, Y', strtotime($page->updated_at)) }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ admin_url('pages/edit?id=' . $page->id) }}" class="btn btn-info" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ admin_url('pages/preview?id=' . $page->id) }}" class="btn btn-secondary" title="Preview" target="_blank">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger" onclick="deletePage({{ $page->id }})" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        @if($totalPages > 1)
            <div class="card-footer">
                <nav>
                    <ul class="pagination mb-0">
                        @for($i = 1; $i <= $totalPages; $i++)
                            <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                <a class="page-link" href="{{ admin_url('pages?page=' . $i . ($search ? '&search=' . urlencode($search) : '') . ($status ? '&status=' . $status : '')) }}">
                                    {{ $i }}
                                </a>
                            </li>
                        @endfor
                    </ul>
                </nav>
            </div>
        @endif
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST" action="{{ admin_url('pages/delete') }}" style="display: none;">
    <input type="hidden" name="csrf_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" id="deletePageId">
</form>

<script>
function deletePage(id) {
    if (confirm('Are you sure you want to delete this page? This action cannot be undone.')) {
        document.getElementById('deletePageId').value = id;
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endsection

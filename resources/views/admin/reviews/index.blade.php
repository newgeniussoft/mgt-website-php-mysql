@extends('layouts.admin')

@section('title', 'Reviews')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-comments me-2"></i>Reviews
        </h1>
        <div>
            <a href="{{ admin_url('reviews/create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Add Review
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <div>
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-list me-1"></i>Reviews List
                </h6>
            </div>
            <div class="btn-group" role="group">
                <a href="{{ admin_url('reviews?status=pending') }}" class="btn btn-sm {{ ($status ?? 'pending') === 'pending' ? 'btn-primary' : 'btn-outline-primary' }}">Pending</a>
                <a href="{{ admin_url('reviews?status=approved') }}" class="btn btn-sm {{ ($status ?? '') === 'approved' ? 'btn-primary' : 'btn-outline-primary' }}">Approved</a>
                <a href="{{ admin_url('reviews?status=all') }}" class="btn btn-sm {{ ($status ?? '') === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">All</a>
            </div>
        </div>
        <div class="card-body">
            @if(empty($reviews))
                <div class="text-center py-5">
                    <i class="fas fa-comments fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">No reviews found</h5>
                    <p class="text-gray-500">You can add a new review or import from your site.</p>
                    <a href="{{ admin_url('reviews/create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Create Review
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered align-middle" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="width: 140px;">Date</th>
                                <th>Reviewer</th>
                                <th style="width: 120px;">Rating</th>
                                <th>Message</th>
                                <th style="width: 110px;">Status</th>
                                <th style="width: 190px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reviews as $review)
                                <tr>
                                    <td>{{ $review->daty }}</td>
                                    <td>
                                        <strong>{{ $review->name_user }}</strong><br>
                                        <small class="text-muted">{{ $review->email_user }}</small>
                                    </td>
                                    <td>
                                        @php $r = (int)($review->rating ?? 0); @endphp
                                        <span>
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $r)
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-muted"></i>
                                                @endif
                                            @endfor
                                        </span>
                                    </td>
                                    <td>
                                        @php $snippet = strlen($review->message) > 120 ? substr($review->message, 0, 117) . '...' : $review->message; @endphp
                                        <small>{{ $snippet }}</small>
                                    </td>
                                    <td>
                                        @if((int)$review->pending === 1)
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @else
                                            <span class="badge bg-success">Approved</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ admin_url('reviews/edit?id=' . $review->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ admin_url('reviews/approve') }}" style="display:inline-block;">
                                                <input type="hidden" name="csrf_token" value="{{ $_SESSION['csrf_token'] ?? '' }}">
                                                <input type="hidden" name="id" value="{{ $review->id }}">
                                                <input type="hidden" name="pending" value="{{ (int)$review->pending === 1 ? 0 : 1 }}">
                                                @if((int)$review->pending === 1)
                                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Approve">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @else
                                                    <button type="submit" class="btn btn-sm btn-outline-warning" title="Mark Pending">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                @endif
                                            </form>
                                            <form method="POST" action="{{ admin_url('reviews/delete') }}" style="display:inline-block;" onsubmit="return confirm('Delete this review?')">
                                                <input type="hidden" name="csrf_token" value="{{ $_SESSION['csrf_token'] ?? '' }}">
                                                <input type="hidden" name="id" value="{{ $review->id }}">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
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

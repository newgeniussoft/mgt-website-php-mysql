@extends('admin.dashboard')
@section('content')
<h1>Delete Page</h1>
@if($page)
    <p>Are you sure you want to delete the page <strong>{{ $page['menu_title'] }}</strong> ({{ $page['path'] }})?</p>
    <form method="POST">
        <button type="submit" class="btn btn-danger">Delete</button>
        <a href="/access/pages" class="btn btn-secondary">Cancel</a>
    </form>
@else
    <div class="alert alert-warning">Page not found.</div>
@endif
@endsection

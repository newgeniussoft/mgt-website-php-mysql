@extends('admin.dashboard')
@section('content')
<h1>Pages</h1>
<a href="/access/pages/create" class="btn btn-primary">Create New Page</a>
<table class="table table-striped mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>Path</th>
            <th>Menu Title (EN)</th>
            <th>Menu Title (ES)</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pages as $page)
        <tr>
            <td>{{ $page['id'] }}</td>
            <td>{{ $page['path'] }}</td>
            <td>{{ $page['menu_title'] }}</td>
            <td>{{ $page['menu_title_es'] }}</td>
            <td>
                <a href="/access/pages/edit?id={{ $page['id'] }}" class="btn btn-sm btn-warning">Edit</a>
                <a href="/access/pages/delete?id={{ $page['id'] }}" class="btn btn-sm btn-danger">Delete</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection

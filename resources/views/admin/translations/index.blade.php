@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Translations</h1>
        <a href="{{ admin_url('translations/create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Translation
        </a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ admin_url('translations') }}" class="row g-2">
                <div class="col-md-6">
                    <input type="text" name="q" class="form-control" placeholder="Search by key" value="{{ $q ?? '' }}">
                </div>
                <div class="col-md-3">
                    <select name="locale" class="form-control">
                        <option value="">All locales</option>
                        <option value="en" {{ ($filter_locale ?? '') === 'en' ? 'selected' : '' }}>en</option>
                        <option value="es" {{ ($filter_locale ?? '') === 'es' ? 'selected' : '' }}>es</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-secondary" type="submit"><i class="fas fa-search"></i> Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="width: 35%">Key</th>
                        <th style="width: 10%">Locale</th>
                        <th>Value</th>
                        <th style="width: 160px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($translations as $t)
                        <tr>
                            <td><code>{{ $t->key }}</code></td>
                            <td><span class="badge badge-info">{{ $t->locale }}</span></td>
                            <td style="white-space: pre-wrap">{{ $t->value }}</td>
                            <td>
                                <a class="btn btn-sm btn-primary" href="{{ admin_url('translations/edit?id=' . $t->id) }}"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="{{ admin_url('translations/delete') }}" style="display:inline-block" onsubmit="return confirm('Delete this translation?')">
                                    <input type="hidden" name="csrf_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="id" value="{{ $t->id }}">
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No translations found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

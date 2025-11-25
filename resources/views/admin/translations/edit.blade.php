@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ isset($translation) ? 'Edit Translation' : 'Create Translation' }}</h1>
        <a href="{{ admin_url('translations') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ isset($translation) ? admin_url('translations/update') : admin_url('translations/store') }}">
                <input type="hidden" name="csrf_token" value="{{ csrf_token() }}">
                @if(isset($translation))
                    <input type="hidden" name="id" value="{{ $translation->id }}">
                @endif

                <div class="form-group mb-3">
                    <label>Key</label>
                    <input type="text" name="key" class="form-control" value="{{ $translation->key ?? old('key') }}" required>
                </div>
                <div class="form-group mb-3">
                    <label>Locale</label>
                    <select name="locale" class="form-control" required {{ isset($translation) ? '' : '' }}>
                        <option value="en" {{ (isset($translation) && $translation->locale==='en') ? 'selected' : '' }}>en</option>
                        <option value="es" {{ (isset($translation) && $translation->locale==='es') ? 'selected' : '' }}>es</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label>Value</label>
                    <textarea name="value" class="form-control" rows="5" required>{{ $translation->value ?? old('value') }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                    @if(isset($translation))
                    <form method="POST" action="{{ admin_url('translations/delete') }}" onsubmit="return confirm('Delete this translation?')" class="ms-2">
                        <input type="hidden" name="csrf_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" value="{{ $translation->id }}">
                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button>
                    </form>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

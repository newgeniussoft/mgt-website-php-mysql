@include(admin.partials.head)
<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
    <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
        @include(admin.partials.header)
        <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
            <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                @include(admin.partials.sidebar)
                <div class="d-flex flex-column flex-column-fluid">
                    <div id="kt_app_content" class="app-content px-lg-3">
                        <div id="kt_app_content_container" class="app-container container-fluid">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h3 class="mb-0">Add New Tour</h3>
                                    <a href="{{ route('access/tours') }}" class="btn btn-secondary">Back to Tours</a>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('access/tours/store') }}" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label>Name (EN)</label>
                                                <input type="text" name="name" class="form-control" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Name (ES)</label>
                                                <input type="text" name="name_es" class="form-control">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Title (EN)</label>
                                                <input type="text" name="title" class="form-control">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Title (ES)</label>
                                                <input type="text" name="title_es" class="form-control">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Subtitle (EN)</label>
                                                <input type="text" name="subtitle" class="form-control">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Subtitle (ES)</label>
                                                <input type="text" name="subtitle_es" class="form-control">
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label>Description (EN)</label>
                                                <textarea name="description" class="form-control"></textarea>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label>Description (ES)</label>
                                                <textarea name="description_es" class="form-control"></textarea>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label>Text for Customer (EN)</label>
                                                <textarea name="text_for_customer" class="form-control"></textarea>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label>Text for Customer (ES)</label>
                                                <textarea name="text_for_customer_es" class="form-control"></textarea>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label>Itinerary</label>
                                                <textarea name="itinerary" class="form-control"></textarea>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>Image</label>
                                                <input type="file" name="image" class="form-control">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>Image Cover</label>
                                                <input type="file" name="image_cover" class="form-control">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>Map</label>
                                                <input type="file" name="map" class="form-control">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Meta Title (EN)</label>
                                                <input type="text" name="meta_title" class="form-control">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Meta Title (ES)</label>
                                                <input type="text" name="meta_title_es" class="form-control">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Meta Description (EN)</label>
                                                <textarea name="meta_description" class="form-control"></textarea>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Meta Description (ES)</label>
                                                <textarea name="meta_description_es" class="form-control"></textarea>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Meta Keywords (EN)</label>
                                                <input type="text" name="meta_keywords" class="form-control">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Meta Keywords (ES)</label>
                                                <input type="text" name="meta_keywords_es" class="form-control">
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label>Path</label>
                                                <input type="text" name="path" class="form-control">
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <button type="submit" class="btn btn-primary">Create Tour</button>
                                            <a href="{{ route('access/tours') }}" class="btn btn-secondary">Cancel</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include(admin.partials.footer)
            </div>
        </div>
    </div>
</div>

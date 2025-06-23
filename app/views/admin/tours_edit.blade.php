@import('app/utils/helpers/helper.php')
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
                                    <h3 class="mb-0">Edit Tour</h3>
                                    <a href="{{ route('access/tours') }}" class="btn btn-secondary">Back to Tours</a>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('access/tours/update/' . $tour['id']) }}" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="old_image" value="{{ $tour['image'] }}">
                                        <input type="hidden" name="old_image_cover" value="{{ $tour['image_cover'] }}">
                                        <input type="hidden" name="old_map" value="{{ $tour['map'] }}">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label>Name (EN)</label>
                                                <input type="text" name="name" class="form-control" value="{{ $tour['name'] }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Name (ES)</label>
                                                <input type="text" name="name_es" class="form-control" value="{{ $tour['name_es'] }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Title (EN)</label>
                                                <input type="text" name="title" class="form-control" value="{{ $tour['title'] }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Title (ES)</label>
                                                <input type="text" name="title_es" class="form-control" value="{{ $tour['title_es'] }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Subtitle (EN)</label>
                                                <input type="text" name="subtitle" class="form-control" value="{{ $tour['subtitle'] }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Subtitle (ES)</label>
                                                <input type="text" name="subtitle_es" class="form-control" value="{{ $tour['subtitle_es'] }}">
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label>Description (EN)</label>
                                                <textarea name="description" class="form-control">{{ $tour['description'] }}</textarea>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label>Description (ES)</label>
                                                <textarea name="description_es" class="form-control">{{ $tour['description_es'] }}</textarea>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label>Text for Customer (EN)</label>
                                                <textarea name="text_for_customer" class="form-control">{{ $tour['text_for_customer'] }}</textarea>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label>Text for Customer (ES)</label>
                                                <textarea name="text_for_customer_es" class="form-control">{{ $tour['text_for_customer_es'] }}</textarea>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label>Itinerary</label>
                                                <textarea name="itinerary" class="form-control">{{ $tour['itinerary'] }}</textarea>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>Image</label>
                                                @if($tour['image']) <img src="{{ assets($tour['image']) }}" style="width:60px;display:block;"> @endif
                                                <input type="file" name="image" class="form-control">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>Image Cover</label>
                                                @if($tour['image_cover']) <img src="{{ assets($tour['image_cover']) }}" style="width:60px;display:block;"> @endif
                                                <input type="file" name="image_cover" class="form-control">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>Map</label>
                                                @if($tour['map']) <img src="{{ assets($tour['map']) }}" style="width:60px;display:block;"> @endif
                                                <input type="file" name="map" class="form-control">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Meta Title (EN)</label>
                                                <input type="text" name="meta_title" class="form-control" value="{{ $tour['meta_title'] }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Meta Title (ES)</label>
                                                <input type="text" name="meta_title_es" class="form-control" value="{{ $tour['meta_title_es'] }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Meta Description (EN)</label>
                                                <textarea name="meta_description" class="form-control">{{ $tour['meta_description'] }}</textarea>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Meta Description (ES)</label>
                                                <textarea name="meta_description_es" class="form-control">{{ $tour['meta_description_es'] }}</textarea>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Meta Keywords (EN)</label>
                                                <input type="text" name="meta_keywords" class="form-control" value="{{ $tour['meta_keywords'] }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Meta Keywords (ES)</label>
                                                <input type="text" name="meta_keywords_es" class="form-control" value="{{ $tour['meta_keywords_es'] }}">
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label>Path</label>
                                                <input type="text" name="path" class="form-control" value="{{ $tour['path'] }}">
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <button type="submit" class="btn btn-primary">Update Tour</button>
                                            <a href="{{ route('access/tours') }}" class="btn btn-secondary">Cancel</a>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Tour Highlights Management -->
                            <div class="card mt-4">
                                <div class="card-header"><h4>Tour Highlights</h4></div>
                                <div class="card-body">
                                    <form action="{{ route('access/tours/highlight/store/' . $tour['id']) }}" method="POST">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="name_tour" value="{{ $tour['name'] }}">
                                        <div class="row">
                                            <div class="col-md-4 mb-2">
                                                <input type="text" name="texte" class="form-control" placeholder="Highlight (EN)" required>
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <input type="text" name="texte_es" class="form-control" placeholder="Highlight (ES)">
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <button type="submit" class="btn btn-success">Add Highlight</button>
                                            </div>
                                        </div>
                                    </form>
                                    <table class="table table-bordered mt-3">
                                        <thead><tr><th>EN</th><th>ES</th><th>Actions</th></tr></thead>
                                        <tbody>
                                            <?php foreach($highlights as $h): ?>
                                            <tr>
                                                <form action="{{ route('access/tours/highlight/update/' . $h->id . '/' . $tour['id']) }}" method="POST" style="display:inline-block;">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <td><input type="text" name="texte" value="{{ $h->texte }}" class="form-control"></td>
                                                    <td><input type="text" name="texte_es" value="{{ $h->texte_es }}" class="form-control"></td>
                                                    <td>
                                                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                                </form>
                                                <form action="{{ route('access/tours/highlight/delete/' . $h->id . '/' . $tour['id']) }}" method="POST" style="display:inline-block;">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this highlight?')">Delete</button>
                                                </form>
                                                    </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Tour Details Management -->
                            <div class="card mt-4">
                                <div class="card-header"><h4>Tour Details</h4></div>
                                <div class="card-body">
                                    <form action="{{ route('access/tours/detail/store/' . $tour['id']) }}" method="POST">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="name_tour" value="{{ $tour['name'] }}">
                                        <div class="row">
                                            <div class="col-md-2 mb-2"><input type="text" name="title" class="form-control" placeholder="Title (EN)"></div>
                                            <div class="col-md-1 mb-2"><input type="text" name="day" class="form-control" placeholder="Day"></div>
                                            <div class="col-md-2 mb-2"><input type="text" name="title_es" class="form-control" placeholder="Title (ES)"></div>
                                            <div class="col-md-2 mb-2"><input type="text" name="details_es" class="form-control" placeholder="Details (ES)"></div>
                                            <div class="col-md-2 mb-2"><input type="text" name="name_tours_es" class="form-control" placeholder="Name Tours (ES)"></div>
                                            <div class="col-md-2 mb-2"><button type="submit" class="btn btn-success">Add Detail</button></div>
                                        </div>
                                    </form>
                                    <table class="table table-bordered mt-3">
                                        <thead><tr><th>Title (EN)</th><th>Day</th><th>Title (ES)</th><th>Details (ES)</th><th>Name Tours (ES)</th><th>Actions</th></tr></thead>
                                        <tbody>
                                            <?php foreach($details as $d): ?>
                                            <tr>
                                                <form action="{{ route('access/tours/detail/update/' . $d['id'] . '/' . $tour['id']) }}" method="POST" style="display:inline-block;">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <td><input type="text" name="title" value="{{ $d['title'] }}" class="form-control"></td>
                                                    <td><input type="text" name="day" value="{{ $d['day'] }}" class="form-control"></td>
                                                    <td><input type="text" name="title_es" value="{{ $d['title_es'] }}" class="form-control"></td>
                                                    <td><input type="text" name="details_es" value="{{ $d['details_es'] }}" class="form-control"></td>
                                                    <td><input type="text" name="name_tours_es" value="{{ $d['name_tours_es'] }}" class="form-control"></td>
                                                    <td>
                                                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                                </form>
                                                <form action="{{ route('access/tours/detail/delete/' . $d['id'] . '/' . $tour['id']) }}" method="POST" style="display:inline-block;">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this detail?')">Delete</button>
                                                </form>
                                                    </td>
                                            </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Tour Price Management -->
                            <div class="card mt-4">
                                <div class="card-header"><h4>Tour Prices</h4></div>
                                <div class="card-body">
                                    <form action="{{ route('access/tours/price/store/' . $tour['id']) }}" method="POST">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="name_tour" value="{{ $tour['name'] }}">
                                        <div class="row">
                                            <div class="col-md-3 mb-2"><input type="text" name="type" class="form-control" placeholder="Type"></div>
                                            <div class="col-md-3 mb-2"><input type="text" name="texte" class="form-control" placeholder="Text (EN)"></div>
                                            <div class="col-md-3 mb-2"><input type="text" name="texte_es" class="form-control" placeholder="Text (ES)"></div>
                                            <div class="col-md-3 mb-2"><button type="submit" class="btn btn-success">Add Price</button></div>
                                        </div>
                                    </form>
                                    <table class="table table-bordered mt-3">
                                        <thead><tr><th>Type</th><th>Text (EN)</th><th>Text (ES)</th><th>Actions</th></tr></thead>
                                        <tbody>
                                            <?php foreach($prices as $p): ?>
                                            <tr>
                                                <form action="{{ route('access/tours/price/update/' . $p['id'] . '/' . $tour['id']) }}" method="POST" style="display:inline-block;">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <td><input type="text" name="type" value="{{ $p['type'] }}" class="form-control"></td>
                                                    <td><input type="text" name="texte" value="{{ $p['texte'] }}" class="form-control"></td>
                                                    <td><input type="text" name="texte_es" value="{{ $p['texte_es'] }}" class="form-control"></td>
                                                    <td>
                                                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                                </form>
                                                <form action="{{ route('access/tours/price/delete/' . $p['id'] . '/' . $tour['id']) }}" method="POST" style="display:inline-block;">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this price?')">Delete</button>
                                                </form>
                                                    </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @include(admin.partials.footer)
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

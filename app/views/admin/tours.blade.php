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
                                    <h3 class="mb-0">Manage Tours</h3>
                                    <a href="{{ route('access/tours/create') }}" class="btn btn-primary">Add New Tour</a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Title</th>
                                                    <th>Subtitle</th>
                                                    <th>Image</th>
                                                    <th>Cover</th>
                                                    <th>Map</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($tours as $tour): ?>
                                                <tr>
                                                    <td><?php echo $tour['name']; ?></td>
                                                    <td><?php echo $tour['title']; ?></td>
                                                    <td><?php echo $tour['subtitle']; ?></td>
                                                    <td><?php if($tour['image']): ?> <img src="<?php echo assets($tour['image']); ?>" style="width:60px;"> <?php endif; ?></td>
                                                    <td><?php if($tour['image_cover']): ?> <img src="<?php echo assets($tour['image_cover']); ?>" style="width:60px;"> <?php endif; ?></td>
                                                    <td><?php if($tour['map']): ?> <img src="<?php echo assets($tour['map']); ?>" style="width:60px;"> <?php endif; ?></td>
                                                    <td>
    <a href="{{ route('access/tours/edit/' . $tour['id']) }}" class="btn btn-sm btn-warning">Edit</a>
    <form action="{{ route('access/tours/delete/' . $tour['id']) }}" method="POST" style="display:inline-block;">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this tour?')">Delete</button>
    </form>
</td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- Add Tour Modal -->
                            <div class="modal fade" id="addTourModal" tabindex="-1" aria-labelledby="addTourModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="addTourModalLabel">Add New Tour</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <form action="{{ url('admin/tours/store') }}" method="POST" enctype="multipart/form-data">
                                    <div class="modal-body">
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
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>
                            <!-- End Add Tour Modal -->
                        </div>
                    </div>
                </div>
                @include(admin.partials.footer)
            </div>
        </div>
    </div>
</div>

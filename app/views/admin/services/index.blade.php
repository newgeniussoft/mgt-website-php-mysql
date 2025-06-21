@include(admin.partials.head)
<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
    <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
        @include(admin.partials.header)
        <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
            <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                @include(admin.partials.sidebar)
                <div class="d-flex flex-column flex-column-fluid">
                    <div id="kt_app_content" class="app-content px-lg-3 ">
                        <div id="kt_app_content_container" class="app-container container-fluid ">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Services</h5>
                                    <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#addServiceForm">Add Service</button>
                                </div>
                                <div class="collapse" id="addServiceForm">
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="service_action" value="create">
                                            <div class="row mb-2">
                                                <div class="col-md-2">
                                                    <label class="form-label">Image</label>
                                                    <input type="file" name="service_image" class="form-control" accept="image/*" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Title (EN)</label>
                                                    <input type="text" name="title" class="form-control" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Title (ES)</label>
                                                    <input type="text" name="title_es" class="form-control" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Subtitle (EN)</label>
                                                    <input type="text" name="subtitle" class="form-control" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Subtitle (ES)</label>
                                                    <input type="text" name="subtitle_es" class="form-control" required>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-success">Add Service</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="card-body">
                                <?php if(isset($success) && $success): ?>
                                        <div class="alert alert-success"><?php echo $success; ?></div>
                                    <?php endif; ?>
                                    <?php if(isset($error) && $error): ?>
                                        <div class="alert alert-danger"><?php echo $error; ?></div>
                                    <?php endif; ?>
                                    <div class="table-responsive mt-3">
                                        <table class="table table-bordered align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Image</th>
                                                    <th>Title (EN)</th>
                                                    <th>Title (ES)</th>
                                                    <th>Subtitle (EN)</th>
                                                    <th>Subtitle (ES)</th>
                                                    <th style="width:180px">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php if(isset($services) && count($services) > 0): ?>
                                                <?php foreach($services as $service): ?>
                                                <tr>
                                                    <form action="" method="POST" enctype="multipart/form-data">
                                                        <input type="hidden" name="service_action" value="edit">
                                                        <input type="hidden" name="service_id" value="<?= htmlspecialchars($service['id']) ?>">
                                                        <td><?= htmlspecialchars($service['id']) ?></td>
                                                        <td>
                                                            <?php if(!empty($service['image'])): ?>
                                                                <img src="<?= assets($service['image']) ?>" alt="Service Image" style="height:40px;">
                                                            <?php endif; ?>
                                                            <input type="file" name="service_image" class="form-control mt-1" accept="image/*">
                                                            <input type="hidden" name="old_image" value="<?= htmlspecialchars($service['image']) ?>">
                                                        </td>
                                                        <td><input type="text" name="title" value="{{ $service['title'] }}" class="form-control" required></td>
                                                        <td><input type="text" name="title_es" value="{{ $service['title_es'] }}" class="form-control" required></td>
                                                        <td><input type="text" name="subtitle" value="{{ $service['subtitle'] }}" class="form-control" required></td>
                                                        <td><input type="text" name="subtitle_es" value="{{ $service['subtitle_es'] }}" class="form-control" required></td>
                                                        <td class="d-flex gap-1">
                                                            <button type="submit" class="btn btn-sm btn-primary me-1">Save</button>
                                                    </form>
                                                    <form action="" method="POST" onsubmit="return confirm('Delete this service?');">
                                                        <input type="hidden" name="service_action" value="delete">
                                                        <input type="hidden" name="service_id" value="{{ $service['id'] }}">
                                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                    </form>
                                                        </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="7" class="text-center">No services found.</td>
                                                </tr>
                                            <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
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

@import('app/utils/helpers/helper.php')
@include(admin.partials.head)
<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
    <div class="app-page  flex-column flex-column-fluid " id="kt_app_page">
        @include(admin.partials.header)
        <div class="app-wrapper  flex-column flex-row-fluid " id="kt_app_wrapper">
            <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
                @include(admin.partials.sidebar)
                <div class="d-flex flex-column flex-column-fluid">
                    <div id="kt_app_content" class="app-content  px-lg-3 ">
                        <div id="kt_app_content_container" class="app-container  container-fluid ">
                            <div class="card card-flush shadow-sm">
                                <div class="card-header">
                                    <h3 class="card-title">Images</h3>
                                    <div class="card-toolbar">
                                    </div>
                                </div>
                                <div class="card-body py-5">

                                    <?php if(isset($success) && $success): ?>
                                    <div class="alert alert-success">
                                        <?php echo $success; ?>
                                    </div>
                                    <?php endif; ?>
                                    <?php if(isset($error) && $error): ?>
                                    <div class="alert alert-danger">
                                        <?php echo $error; ?>
                                    </div>
                                    <?php endif; ?>

                                    <form action="" method="POST" enctype="multipart/form-data" class="mb-4">
                                        <input type="hidden" name="gallery_action" value="create">
                                        <div class="row g-2 align-items-end">
                                            <div class="col-md-9">
                                                <label class="form-label">New image</label>
                                                <input type="file" name="gallery_image" class="form-control"
                                                    accept="image/*" required>
                                            </div>
                                            <div class="col-md-3">
                                                <button type="submit" class="btn btn-success">Add Image</button>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="overflow-auto pb-5">
                                        <div
                                            class="d-flex align-items-center border border-dashed border-gray-300 rounded min-w-700px p-7">
                                            <?php if(isset($galleries) && count($galleries) > 0): ?>
                                                <?php foreach($galleries as $gallery): ?>
                                                    <div class="image-input image-input-empty me-5" data-kt-image-input="true">
                                                        <div class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ assets($gallery['image']) }}');">
                                                        </div>
                                                        <form action="" method="POST" enctype="multipart/form-data">
                                                            <input type="hidden" name="gallery_action" value="delete">
                                                            <input type="hidden" name="gallery_id" value="<?php echo $gallery['id']; ?>">
                                                            <button type="submit" style="position: absolute; top: 0; left: 0;" class="btn btn-sm btn-danger btn-icon">
                                                                <i class="bi bi-x-lg"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                <?php endforeach; ?>
                                                
                                                <?php else: ?>
                                                    <h3 class="text-center block">
                                                    <i class="bi bi-file-earmark-fill fs-2"></i> <br>
                                                    No images found.
                                                    </h3>

                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    function setImage(path, id) {
                        document.getElementById('gallery_image_' + id).value = path;
                    }
                </script>
                @include(admin.partials.footer)
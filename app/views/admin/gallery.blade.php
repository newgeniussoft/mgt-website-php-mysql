@import('app/utils/helpers/helper.php')
@include(admin.partials.head)
@include(admin.partials.sidebar)
<section class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h5>Gallery</h5>
        </div>
        <div class="card-body">
            <?php if(isset($success) && $success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <?php if(isset($error) && $error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Create Gallery Image Form -->
            <form action="" method="POST" enctype="multipart/form-data" class="mb-4">
                <input type="hidden" name="gallery_action" value="create">
                <div class="row g-2 align-items-end">
                    <div class="col-md-9">
                        <label class="form-label">Image</label>
                        <input type="file" name="gallery_image" class="form-control" accept="image/*" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-success">Add Image</button>
                    </div>
                </div>
            </form>

            <!-- Gallery Images Grid -->
            <div class="row">
        <?php if(isset($galleries) && count($galleries) > 0): ?>
                    <?php foreach($galleries as $gallery): ?>
                        <div class="col-6 col-md-4 col-lg-3 mb-4">
                            <div class="card h-100 text-center">
                                <?php if(!empty($gallery['image'])): ?>
                                    <img src="<?php echo assets($gallery['image']); ?>" alt="Gallery Image" class="card-img-top img-fluid" style="max-height:180px;object-fit:cover;">
                                <?php endif; ?>
                                <div class="card-body p-2">
                                    <form action="" method="POST" enctype="multipart/form-data" class="mb-2">
                                        <input type="hidden" name="gallery_action" value="edit">
                                        <input type="hidden" name="gallery_id" value="<?php echo $gallery['id']; ?>">
                                        <input type="file" name="gallery_image" class="form-control form-control-sm mb-2" accept="image/*">
                                        <input type="hidden" name="old_image" value="<?php echo htmlspecialchars($gallery['image']); ?>">
                                        <button type="submit" class="btn btn-sm btn-primary w-100">Replace</button>
                                    </form>
                                    <form action="" method="POST" onsubmit="return confirm('Delete this image?');">
                                        <input type="hidden" name="gallery_action" value="delete">
                                        <input type="hidden" name="gallery_id" value="<?php echo $gallery['id']; ?>">
                                        <input type="hidden" name="old_image" value="<?php echo htmlspecialchars($gallery['image']); ?>">
                                        <button type="submit" class="btn btn-sm btn-danger w-100">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center text-muted">No gallery images found.</div>
                <?php endif; ?>
                </div>
        </div>
    </div>
</section>
@include(admin.partials.footer)
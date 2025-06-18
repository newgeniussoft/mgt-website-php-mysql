@import('app/utils/helpers/helper.php')
@include(admin.partials.head)
@include(admin.partials.sidebar)
<section class="content">
<div class="content">
    <div class="content-header d-flex justify-content-between align-items-center">
        <h2>Website Info</h2>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Website Info</h5>
                </div>
                <div class="card-body">
                <?php if(isset($success)): ?>
                        <div class="alert alert-success">{{ $success }}</div>
                        <?php endif;
                        if(isset($error)): ?>
                        <div class="alert alert-danger">{{ $error }}</div>
                        <?php endif ?>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ $info['phone'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label for="whatsapp" class="form-label">WhatsApp</label>
                            <input type="text" class="form-control" id="whatsapp" name="whatsapp" value="{{ $info['whatsapp'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $info['email'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo</label>
                            <?php if(!empty($info['logo'])): ?>
                                <div class="mb-2">
                                    <img src="{{ assets($info['logo']) }}" alt="Logo" style="height:60px;">
                                </div>
                                <?php  endif  ?>
                            <input type="file" class="form-control" id="logo" name="logo">
                        </div>
                        <div class="mb-3">
                            <label for="short_about" class="form-label">Short About</label>
                            <textarea class="form-control" id="short_about" name="short_about" rows="2">{{ $info['short_about'] ?? '' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Main Image</label>
                            <?php if(!empty($info['image'])): ?>
                                <div class="mb-2">
                                    <img src="{{ assets($info['image']) }}" alt="Main Image" style="height:80px;">
                                </div>
                            <?php endif ?>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>
                        <div class="mb-3">
    <label for="image_property" class="form-label">Image Property</label>
    <?php if(!empty($info['image_property'])): ?>
        <div class="mb-2">
            <img src="{{ assets($info['image_property']) }}" alt="Image Property" style="height:80px;">
        </div>
    <?php endif ?>
    <input type="file" class="form-control" id="image_property" name="image_property">
</div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Slides</h5>
        </div>
        <div class="card-body">
            <?php if(isset($success) && $success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <?php if(isset($error) && $error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Create Slide Form -->
            <form action="" method="POST" enctype="multipart/form-data" class="mb-4">
                <input type="hidden" name="slide_action" value="create">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Caption (EN)</label>
                        <input type="text" name="caption" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Caption (ES)</label>
                        <input type="text" name="caption_es" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Image</label>
                        <input type="file" name="slide_image" class="form-control" accept="image/*" required>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-success">Add</button>
                    </div>
                </div>
            </form>

            <!-- Slides Table -->
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Caption (EN)</th>
                            <th>Caption (ES)</th>
                            <th>Image</th>
                            <th style="width:140px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($slides) && count($slides) > 0): ?>
                            <?php foreach($slides as $slide): ?>
                                <tr>
                                    <form action="" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="slide_action" value="edit">
                                        <input type="hidden" name="slide_id" value="<?php echo $slide['id']; ?>">
                                        <td><?php echo $slide['id']; ?></td>
                                        <td><input type="text" name="caption" value="<?php echo htmlspecialchars($slide['caption']); ?>" class="form-control" required></td>
                                        <td><input type="text" name="caption_es" value="<?php echo htmlspecialchars($slide['caption_es']); ?>" class="form-control" required></td>
                                        <td>
                                            <?php if(!empty($slide['image'])): ?>
                                                <img src="<?php echo assets($slide['image']); ?>" alt="Slide Image" style="height:40px;">
                                            <?php endif; ?>
                                            <input type="file" name="slide_image" class="form-control mt-1" accept="image/*">
                                            <input type="hidden" name="old_image" value="<?php echo htmlspecialchars($slide['image']); ?>">
                                        </td>
                                        <td class="d-flex gap-1">
                                            <button type="submit" class="btn btn-sm btn-primary me-1">Save</button>
                                    </form>
                                    <form action="" method="POST" onsubmit="return confirm('Delete this slide?');">
                                        <input type="hidden" name="slide_action" value="delete">
                                        <input type="hidden" name="slide_id" value="<?php echo $slide['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                        </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center">No slides found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Videos</h5>
        </div>
        <div class="card-body">
            <?php if(isset($success) && $success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <?php if(isset($error) && $error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Create Video Form -->
            <form action="" method="POST" class="mb-4">
                <input type="hidden" name="video_action" value="create">
                <div class="row g-2 align-items-end">
                    <div class="col-md-2">
                        <label class="form-label">Title (EN)</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Subtitle (EN)</label>
                        <input type="text" name="subtitle" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Title (ES)</label>
                        <input type="text" name="title_es" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Subtitle (ES)</label>
                        <input type="text" name="subtitle_es" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Video Link</label>
                        <input type="text" name="link" class="form-control" required placeholder="https://...">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-success">Add</button>
                    </div>
                </div>
            </form>

            <!-- Videos Table -->
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Title (EN)</th>
                            <th>Subtitle (EN)</th>
                            <th>Title (ES)</th>
                            <th>Subtitle (ES)</th>
                            <th>Link</th>
                            <th style="width:140px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($videos) && count($videos) > 0): ?>
                            <?php foreach($videos as $video): ?>
                                <tr>
                                    <form action="" method="POST">
                                        <input type="hidden" name="video_action" value="edit">
                                        <input type="hidden" name="video_id" value="<?php echo $video['id']; ?>">
                                        <td><?php echo $video['id']; ?></td>
                                        <td><input type="text" name="title" value="<?php echo htmlspecialchars($video['title']); ?>" class="form-control" required></td>
                                        <td><input type="text" name="subtitle" value="<?php echo htmlspecialchars($video['subtitle']); ?>" class="form-control"></td>
                                        <td><input type="text" name="title_es" value="<?php echo htmlspecialchars($video['title_es']); ?>" class="form-control" required></td>
                                        <td><input type="text" name="subtitle_es" value="<?php echo htmlspecialchars($video['subtitle_es']); ?>" class="form-control"></td>
                                        <td><input type="text" name="link" value="<?php echo htmlspecialchars($video['link']); ?>" class="form-control" required></td>
                                        <td class="d-flex gap-1">
                                            <button type="submit" class="btn btn-sm btn-primary me-1">Save</button>
                                    </form>
                                    <form action="" method="POST" onsubmit="return confirm('Delete this video?');">
                                        <input type="hidden" name="video_action" value="delete">
                                        <input type="hidden" name="video_id" value="<?php echo $video['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                        </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-center">No videos found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Social Media</h5>
        </div>
        <div class="card-body">
            <?php if(isset($success) && $success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <?php if(isset($error) && $error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Create Social Media Form -->
            <form action="" method="POST" enctype="multipart/form-data" class="mb-4">
                <input type="hidden" name="social_action" value="create">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Link</label>
                        <input type="text" name="link" class="form-control" required placeholder="https://...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Image</label>
                        <input type="file" name="social_image" class="form-control" accept="image/*" required>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-success">Add</button>
                    </div>
                </div>
            </form>

            <!-- Social Media Table -->
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Link</th>
                            <th>Image</th>
                            <th style="width:140px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($social_media) && count($social_media) > 0): ?>
                            <?php foreach($social_media as $sm): ?>
                                <tr>
                                    <form action="" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="social_action" value="edit">
                                        <input type="hidden" name="social_id" value="<?php echo $sm['id']; ?>">
                                        <td><?php echo $sm['id']; ?></td>
                                        <td><input type="text" name="name" value="<?php echo htmlspecialchars($sm['name']); ?>" class="form-control" required></td>
                                        <td><input type="text" name="link" value="<?php echo htmlspecialchars($sm['link']); ?>" class="form-control" required></td>
                                        <td>
                                            <?php if(!empty($sm['image'])): ?>
                                                <img src="<?php echo assets($sm['image']); ?>" alt="Social Image" style="height:40px;">
                                            <?php endif; ?>
                                            <input type="file" name="social_image" class="form-control mt-1" accept="image/*">
                                            <input type="hidden" name="old_image" value="<?php echo htmlspecialchars($sm['image']); ?>">
                                        </td>
                                        <td class="d-flex gap-1">
                                            <button type="submit" class="btn btn-sm btn-primary me-1">Save</button>
                                    </form>
                                    <form action="" method="POST" onsubmit="return confirm('Delete this entry?');">
                                        <input type="hidden" name="social_action" value="delete">
                                        <input type="hidden" name="social_id" value="<?php echo $sm['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                        </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center">No social media found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</section>
@include(admin.partials.footer)

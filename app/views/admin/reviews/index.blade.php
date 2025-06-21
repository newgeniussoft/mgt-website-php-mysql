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
                                    <h5 class="mb-0">Reviews</h5>
                                    <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#addReviewForm">Add Review</button>
                                </div>
                                <div class="collapse" id="addReviewForm">
                                    <div class="card-body">
                                        <form action="" method="POST">
                                            <input type="hidden" name="review_action" value="create">
                                            <div class="row mb-2">
                                                <div class="col-md-1">
                                                    <label class="form-label">Rating</label>
                                                    <input type="number" name="rating" class="form-control" min="0" max="5" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" name="name_user" class="form-control" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" name="email_user" class="form-control" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Message</label>
                                                    <input type="text" name="message" class="form-control" required>
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="form-label">Pending?</label>
                                                    <input type="checkbox" name="pending" value="1">
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-success">Add Review</button>
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
                                                    <th>Rating</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Message</th>
                                                    <th>Pending</th>
                                                    <th style="width:180px">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php if(isset($reviews) && count($reviews) > 0): ?>
                                                <?php foreach($reviews as $review): ?>
                                                <tr>
                                                    <form action="" method="POST">
                                                        <input type="hidden" name="review_action" value="edit">
                                                        <input type="hidden" name="review_id" value="<?= htmlspecialchars($review['id']) ?>">
                                                        <td><?= htmlspecialchars($review['id']) ?></td>
                                                        <td><input type="number" name="rating" value="<?= htmlspecialchars($review['rating']) ?>" min="0" max="5" class="form-control" required></td>
                                                        <td><input type="text" name="name_user" value="<?= htmlspecialchars($review['name_user']) ?>" class="form-control" required></td>
                                                        <td><input type="email" name="email_user" value="<?= htmlspecialchars($review['email_user']) ?>" class="form-control" required></td>
                                                        <td><input type="text" name="message" value="<?= htmlspecialchars($review['message']) ?>" class="form-control" required></td>
                                                        <td><input type="checkbox" name="pending" value="1" <?= $review['pending'] ? 'checked' : '' ?>></td>
                                                        <td class="d-flex gap-1">
                                                            <button type="submit" class="btn btn-sm btn-primary me-1">Save</button>
                                                    </form>
                                                    <form action="" method="POST" onsubmit="return confirm('Delete this review?');">
                                                        <input type="hidden" name="review_action" value="delete">
                                                        <input type="hidden" name="review_id" value="<?= htmlspecialchars($review['id']) ?>">
                                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                    </form>
                                                        </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="8" class="text-center">No reviews found.</td>
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

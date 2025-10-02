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
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h1>List of Reviews</h1>
                                        </div>
                                        <div class="card-toolbar">
                                            <a href="<?= url_admin('reviews/create') ?>" class="btn btn-sm btn-primary">Add Review</a>
                                        </div>
                                    </div>
                                    <div class="card-body">
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Rating</th>
            <th>Name</th>
            <th>Email</th>
            <th>Message</th>
            <th>Pending</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($reviews as $review): ?>
        <tr>
            <td><?= htmlspecialchars($review->rating) ?></td>
            <td><?= htmlspecialchars($review->name_user) ?></td>
            <td><?= htmlspecialchars($review->email_user) ?></td>
            <td><?= htmlspecialchars($review->message) ?></td>
            <td><?= $review->pending ? 'Yes' : 'No' ?></td>
            <td>
                <a href="<?= url_admin('reviews/edit/'.$review->id) ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="<?= url_admin('reviews/delete/'.$review->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
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
                </div>
            </div>
        </div>
    </div>
</div>
@include(admin.partials.footer)
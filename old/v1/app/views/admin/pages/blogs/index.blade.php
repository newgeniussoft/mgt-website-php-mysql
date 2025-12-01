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
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h1>List of Blogs</h1>
                                        </div>
                                        <div class="card-toolbar">
                                            <a href="<?= url_admin('blogs/create') ?>" class="btn btn-sm btn-primary">Add Blog</a>
                                        </div>
                                    </div>
                                    <div class="card-body">
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Image</th>
            <th>Title</th>
            <th>Title (ES)</th>
            <th>Short Text</th>
            <th>Short Text (ES)</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($blogs as $blog): ?>
        <tr>
            <td><?php if ($blog->image): ?><img src="<?= assets($blog->image) ?>" width="100"><?php endif; ?></td>
            <td><?= htmlspecialchars($blog->title) ?></td>
            <td><?= htmlspecialchars($blog->title_es) ?></td>
            <td><?= htmlspecialchars($blog->short_texte) ?></td>
            <td><?= htmlspecialchars($blog->short_texte_es) ?></td>
            <td>
                <a class="btn btn-sm btn-secondary" href="/<?= $_ENV['PATH_ADMIN'] ?>/blogs/edit/<?= $blog->id ?>">Edit</a>
                <form method="POST" action="/<?= $_ENV['PATH_ADMIN'] ?>/blogs/delete/<?= $blog->id ?>" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
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

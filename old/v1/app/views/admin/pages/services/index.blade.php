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
                                        <h1>List of Services</h1>
                                    </div>
                                    <div class="card-toolbar">
                                        <a href="<?= url_admin('services/create') ?>" class="btn btn-sm btn-primary">Add Service</a>
                                    </div>
                                </div>
                                <div class="card-body">

                            
                                
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Image</th>
            <th>Title</th>
            <th>Title (ES)</th>
            <th>Subtitle</th>
            <th>Subtitle (ES)</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($services as $service): ?>
        <tr>
            <td><?php if ($service->image): ?><img src="<?= assets($service->image) ?>" width="100"><?php endif; ?></td>
            <td><?= htmlspecialchars($service->title) ?></td>
            <td><?= htmlspecialchars($service->title_es) ?></td>
            <td><?= htmlspecialchars($service->subtitle) ?></td>
            <td><?= htmlspecialchars($service->subtitle_es) ?></td>
            <td>
                <a class="btn btn-sm btn-secondary" href="/<?= $_ENV['PATH_ADMIN'] ?>/services/edit/<?= $service->id ?>">Edit</a>
                <form method="POST" action="/<?= $_ENV['PATH_ADMIN'] ?>/services/delete/<?= $service->id ?>" style="display:inline;" onsubmit="return confirm('Are you sure?');">
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


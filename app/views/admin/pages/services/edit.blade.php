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
                                        <h1>Edit Service</h1>
                                    </div>
                                    <div class="card-toolbar">
                                        <a href="/<?= $_ENV['PATH_ADMIN'] ?>/services" class="btn btn-sm btn-primary">Back</a>
                                    </div>
                                </div>
                                <div class="card-body"> 
<form method="POST" enctype="multipart/form-data" action="/<?= $_ENV['PATH_ADMIN'] ?>/services/update/<?= $service['id'] ?>">
    <label>Image: <input type="file" name="image"></label>
    <?php if ($service['image']): ?><img src="<?= assets($service['image']) ?>" width="100"><?php endif; ?><br>
    <div class="row">
        <div class="col-md-6">
<div class="form-floating mb-7">
    <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($service['title']) ?>" id="floatingTitle" placeholder="Title"/>
    <label for="floatingTitle">Title</label>
</div>
        </div>
        <div class="col-md-6">
<div class="form-floating mb-7">
    <input type="text" class="form-control" name="title_es" value="<?= htmlspecialchars($service['title_es']) ?>" id="floatingTitleES" placeholder="Title (ES)"/>
    <label for="floatingTitleES">Title (ES)</label>
</div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
<div class="form-floating mb-7">
    <input type="text" class="form-control" name="subtitle" value="<?= htmlspecialchars($service['subtitle']) ?>" id="floatingSubtitle" placeholder="Subtitle"/>
    <label for="floatingSubtitle">Subtitle</label>
</div>
        </div>
        <div class="col-md-6">
<div class="form-floating mb-7">
    <input type="text" class="form-control" name="subtitle_es" value="<?= htmlspecialchars($service['subtitle_es']) ?>" id="floatingSubtitleES" placeholder="Subtitle (ES)"/>
    <label for="floatingSubtitleES">Subtitle (ES)</label>
</div>
        </div>
    </div>
<button type="submit" class="btn btn-primary">Update</button>
    </form>
    </div>
    </div>
                            </div>
                        </div>
                    </div>
@include(admin.partials.footer)


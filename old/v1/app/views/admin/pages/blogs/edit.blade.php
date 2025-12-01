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
                                        <h1>Edit Blog</h1>
                                    </div>
                                    <div class="card-toolbar">
                                        <a href="/<?= $_ENV['PATH_ADMIN'] ?>/blogs" class="btn btn-sm btn-primary">Back</a>
                                    </div>
                                </div>
                                <div class="card-body"> 
<form method="POST" enctype="multipart/form-data" action="/<?= $_ENV['PATH_ADMIN'] ?>/blogs/update/<?= $blog['id'] ?>">
    <label>Image: <input type="file" name="image"></label>
    <?php if ($blog['image']): ?><img src="<?= assets($blog['image']) ?>" width="100"><?php endif; ?><br>
    <div class="row mt-5">
        <div class="col-md-6">
<div class="form-floating mb-7">
    <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($blog['title']) ?>" id="floatingTitle" placeholder="Title"/>
    <label for="floatingTitle">Title</label>
</div>
    </div>
    <div class="col-md-6">
<div class="form-floating mb-7">
    <input type="text" class="form-control" name="title_es" value="<?= htmlspecialchars($blog['title_es']) ?>" id="floatingTitleES" placeholder="Title (ES)"/>
    <label for="floatingTitleES">Title (ES)</label>
</div>
    </div>
    </div>
    <div class="row">
        <div class="col-md-6">
<div class=" mb-7">
<label for="floatingDescription">Description</label>
    <textarea class="form-control summernote" name="description" id="floatingDescription" placeholder="Description"><?= htmlspecialchars($blog['description']) ?></textarea>

</div>
    </div>
    <div class="col-md-6">
<div class=" mb-7">
<label for="floatingDescriptionES">Description (ES)</label>
    <textarea class="form-control summernote" name="description_es" id="floatingDescriptionES" placeholder="Description (ES)"><?= htmlspecialchars($blog['description_es']) ?></textarea>
  
</div>
    </div>
    </div>
    <div class="row">
        <div class="col-md-6">
<div class="form-floating mb-7">
    <input type="text" class="form-control" name="short_texte" value="<?= htmlspecialchars($blog['short_texte']) ?>" id="floatingShortText" placeholder="Short Text"/>
    <label for="floatingShortText">Short Text</label>
</div>
    </div>
    <div class="col-md-6">
<div class="form-floating mb-7">
    <input type="text" class="form-control" name="short_texte_es" value="<?= htmlspecialchars($blog['short_texte_es']) ?>" id="floatingShortTextES" placeholder="Short Text (ES)"/>
    <label for="floatingShortTextES">Short Text (ES)</label>
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
<!-- Summernote CSS/JS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    $('.summernote').summernote({
        height: 200
    });
});
</script>
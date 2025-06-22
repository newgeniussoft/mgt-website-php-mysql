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
                            <form action="" method="POST" enctype="multipart/form-data"
                                id="kt_ecommerce_add_product_form"
                                class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework">
    <input type="hidden" name="info_action" value="update">
                                <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
                                    <div class="card card-flush py-4">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Logo</h2>
                                            </div>
                                        </div>
                                        <div class="card-body text-center pt-0">
                                            <?php if(!empty($info['logo'])): ?>
                                            <div class="mb-2">
                                                <img src="{{ assets($info['logo']) }}" alt="Logo" style="height:60px;">
                                            </div>
                                            <?php  endif  ?>
                                            <input type="file" class="form-control" id="logo" name="logo">
                                            <div class="text-muted fs-7">
                                                Set the logo image. Only *.png,
                                                *.jpg and *.jpeg image files are accepted
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">

                                    <div class="d-flex flex-column gap-7 gap-lg-10">

                                        <!--begin::General options-->
                                        <div class="card card-flush py-4">
                                            <!--begin::Card header-->
                                            <div class="card-header">
                                                <div class="card-title">
                                                    <h2>General</h2>
                                                </div>
                                            </div>
                                            <div class="card-body pt-0">
                                                <div class="mb-10 fv-row fv-plugins-icon-container">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                                            <label class="form-label" for="phone">Phone</label>
                                                            <input type="text" class="form-control" id="phone"
                                                                name="phone" value="{{ $info['phone'] ?? '' }}">
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                                            <label class="form-label" for="phone">Whatsapp</label>
                                                            <input type="text" class="form-control" id="whatsapp"
                                                                name="whatsapp" value="{{ $info['whatsapp'] ?? '' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>

                                                    <div class="mb-3">
                                                        <label for="email" class="form-label">Email</label>
                                                        <input type="email" class="form-control" id="email" name="email"
                                                            value="{{ $info['email'] ?? '' }}">
                                                    </div>

                                                    <div class="mb-3">
    <label for="short_about" class="form-label">About</label>
    <textarea class="form-control" id="short_about"
        name="short_about"
        rows="2">{{ $info['short_about'] ?? '' }}</textarea>
</div>
<div class="mb-3">
    <label for="short_about_es" class="form-label">About (Spanish)</label>
    <textarea class="form-control" id="short_about_es"
        name="short_about_es"
        rows="2">{{ $info['short_about_es'] ?? '' }}</textarea>
</div>
<div class="mb-3">
    <label for="address" class="form-label">Address</label>
    <textarea class="form-control" id="address"
        name="address"
        rows="2">{{ $info['address'] ?? '' }}</textarea>
</div>
                                                    <!--end::Editor-->
                                                    <!--end::Description-->
                                                </div>
                                                <!--end::Input group-->
                                            </div>
                                            <!--end::Card header-->
                                        </div>
                                    </div>
                                    <!--begin:::Tabs-->
                                    <!--begin::Tab content-->

                                    <!--end::Tab content-->
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="card card-flush py-4">
                                                <div class="card-header">
                                                    <div class="card-title">
                                                        <h2>Main image</h2>
                                                    </div>
                                                    <div class="card-toolbar">
                                                        <div class="rounded-circle bg-success w-15px h-15px"
                                                            id="kt_ecommerce_add_product_status"></div>
                                                    </div>
                                                </div>
                                                <div class="card-body pt-0">
                                                    <?php if(!empty($info['image'])): ?>
                                                    <div class="mb-2">
                                                        <img src="{{ assets($info['image']) }}" alt="Main Image"
                                                            style="width:100%;">
                                                    </div>
                                                    <?php endif ?>
                                                    <input type="file" class="form-control" id="image" name="image">
                                                    <div class="text-muted fs-7">
                                                        Set the main image. Only *.png, *.jpg and *.jpeg image files are
                                                        accepted
                                                    </div>
                                                    <div class="d-none mt-10">
                                                        <label for="kt_ecommerce_add_product_status_datepicker"
                                                            class="form-label">
                                                            Select publishing date and time
                                                        </label>
                                                        <input class="form-control flatpickr-input"
                                                            id="kt_ecommerce_add_product_status_datepicker"
                                                            placeholder="Pick date &amp; time" type="text"
                                                            readonly="readonly">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="card card-flush py-4">
                                                <div class="card-header">
                                                    <div class="card-title">
                                                        <h2>Property image</h2>
                                                    </div>
                                                </div>
                                                <div class="card-body pt-0">
                                                    <?php if(!empty($info['image_property'])): ?>
                                                    <div class="mb-2">
                                                        <img src="{{ assets($info['image_property']) }}"
                                                            alt="Image Property" style="width:100%;">
                                                    </div>
                                                    <?php endif ?>
                                                    <input type="file" class="form-control" id="image_property"
                                                        name="image_property">
                                                </div>
                                            </div>

                                        </div>
                                    </div>



                                    <div class="d-flex justify-content-end">

                                        <!--begin::Button-->
                                        <button type="submit" class="btn btn-primary">
                                            <span class="indicator-label">
                                                Save Changes
                                            </span>
                                        </button>
                                        <!--end::Button-->
                                    </div>
                                </div>
                            </form>
                            <div id="admin-ajax-messages"></div>
<div class="card card-dashed">

                                <div class="card-header">
                                    <h3 class="card-title">Edit Website Info</h3>
                                    <div class="card-toolbar">
                                        <button type="button" class="btn btn-sm btn-light">
                                            Action
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">

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

                                    <form action="" method="POST" enctype="multipart/form-data" class="mb-4" id="slide-create-form">
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
                                                <input type="file" name="slide_image" class="form-control"
                                                    accept="image/*" required>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="submit" class="btn btn-success">Add</button>
                                            </div>
                                        </div>
                                    </form>


                                    <div class="row">


                                        <?php if(isset($slides) && count($slides) > 0): ?>
                                        <?php foreach($slides as $slide): ?>
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-12" id="slide-card-<?php echo $slide['id']; ?>">
                                            <div class="card-body pb-5">
                                                <!--begin::Overlay-->
                                                <a class="d-block overlay" data-fslightbox="lightbox-hot-sales"
                                                    href="{{ assets($slide['image']) }}">
                                                    <!--begin::Image-->

                                                    <?php if(!empty($slide['image'])): ?>
                                                    <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded mb-7"
                                                        style="height: 266px;background-image:url('{{ assets($slide['image']) }}')">
                                                    </div>

                                                    <?php endif; ?>
                                                    <!--end::Image-->

                                                    <!--begin::Action-->
                                                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25">
                                                        <i class="ki-duotone ki-eye fs-3x text-white"><span
                                                                class="path1"></span><span class="path2"></span><span
                                                                class="path3"></span></i>
                                                    </div>
                                                    <!--end::Action-->
                                                </a>
                                                <!--end::Overlay-->

                                                <!--begin::Info-->

                                                <form action="" method="POST" enctype="multipart/form-data" class="slide-edit-form">
                                                    <input type="hidden" name="slide_action" value="edit">
                                                    <input type="hidden" name="slide_id"
                                                        value="<?php echo $slide['id']; ?>">

                                                    <label for="caption">Caption (EN)</label>
                                                    <input type="text" name="caption"
                                                        value="<?php echo htmlspecialchars($slide['caption']); ?>"
                                                        class="form-control" required>


                                                    <label for="caption_es">Caption (ES)</label>
                                                    <input type="text" name="caption_es"
                                                        value="<?php echo htmlspecialchars($slide['caption_es']); ?>"
                                                        class="form-control" required>

                                                    <input type="file" name="slide_image" class="form-control mt-1"
                                                        accept="image/*">
                                                    <input type="hidden" name="old_image"
                                                        value="<?php echo htmlspecialchars($slide['image']); ?>">


                                                    <button type="submit"
                                                        class="btn btn-sm btn-primary me-1">Save</button>
                                                </form>
                                                <form action="" method="POST" class="slide-delete-form" onsubmit="return confirm('Delete this slide?');">
                                                    <input type="hidden" name="slide_action" value="delete">
                                                    <input type="hidden" name="slide_id"
                                                        value="<?php echo $slide['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                                <!--end::Info-->
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Videos</h5>
                                </div>
                                <div class="card-body">
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

                                    <form action="" method="POST" class="mb-4" id="video-create-form">
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
                                                <input type="text" name="link" class="form-control" required
                                                    placeholder="https://...">
                                            </div>
                                            <div class="col-md-1">
                                                <button type="submit" class="btn btn-success">Add</button>
                                            </div>
                                        </div>
                                    </form>
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
                                                    <form action="" method="POST" class="video-edit-form">
                                                        <input type="hidden" name="video_action" value="edit">
                                                        <input type="hidden" name="video_id"
                                                            value="<?php echo $video['id']; ?>">
                                                        <td>
                                                            <?php echo $video['id']; ?>
                                                        </td>
                                                        <td><input type="text" name="title"
                                                                value="<?php echo htmlspecialchars($video['title']); ?>"
                                                                class="form-control" required></td>
                                                        <td><input type="text" name="subtitle"
                                                                value="<?php echo htmlspecialchars($video['subtitle']); ?>"
                                                                class="form-control"></td>
                                                        <td><input type="text" name="title_es"
                                                                value="<?php echo htmlspecialchars($video['title_es']); ?>"
                                                                class="form-control" required></td>
                                                        <td><input type="text" name="subtitle_es"
                                                                value="<?php echo htmlspecialchars($video['subtitle_es']); ?>"
                                                                class="form-control"></td>
                                                        <td><input type="text" name="link"
                                                                value="<?php echo htmlspecialchars($video['link']); ?>"
                                                                class="form-control" required></td>
                                                        <td class="d-flex gap-1">
                                                            <button type="submit"
                                                                class="btn btn-sm btn-primary me-1">Save</button>
                                                    </form>
                                                    <form action="" method="POST" class="video-delete-form" onsubmit="return confirm('Delete this video?');">
                                                        <input type="hidden" name="video_action" value="delete">
                                                        <input type="hidden" name="video_id"
                                                            value="<?php echo $video['id']; ?>">
                                                        <button type="submit"
                                                            class="btn btn-sm btn-danger">Delete</button>
                                                    </form>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                                <?php else: ?>
                                                <tr>
                                                    <td colspan="7" class="text-center">No videos found.</td>
                                                </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Social Media</h5>
                                </div>
                                <div class="card-body">
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

                                    <form action="" method="POST" enctype="multipart/form-data" class="mb-4" id="social-create-form">
                                        <input type="hidden" name="social_action" value="create">
                                        <div class="row g-2 align-items-end">
                                            <div class="col-md-4">
                                                <label class="form-label">Name</label>
                                                <input type="text" name="name" class="form-control" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Link</label>
                                                <input type="text" name="link" class="form-control" required
                                                    placeholder="https://...">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Image</label>
                                                <input type="file" name="social_image" class="form-control"
                                                    accept="image/*" required>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="submit" class="btn btn-success">Add</button>
                                            </div>
                                        </div>
                                    </form>

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
                                                    <form action="" method="POST" enctype="multipart/form-data" class="social-edit-form">
                                                        <input type="hidden" name="social_action" value="edit">
                                                        <input type="hidden" name="social_id"
                                                            value="<?php echo $sm['id']; ?>">
                                                        <td>
                                                            <?php echo $sm['id']; ?>
                                                        </td>
                                                        <td><input type="text" name="name"
                                                                value="<?php echo htmlspecialchars($sm['name']); ?>"
                                                                class="form-control" required></td>
                                                        <td><input type="text" name="link"
                                                                value="<?php echo htmlspecialchars($sm['link']); ?>"
                                                                class="form-control" required></td>
                                                        <td>
                                                            <?php if(!empty($sm['image'])): ?>
                                                            <img src="<?php echo assets($sm['image']); ?>"
                                                                alt="Social Image" style="height:40px;">
                                                            <?php endif; ?>
                                                            <input type="file" name="social_image"
                                                                class="form-control mt-1" accept="image/*">
                                                            <input type="hidden" name="old_image"
                                                                value="<?php echo htmlspecialchars($sm['image']); ?>">
                                                        </td>
                                                        <td class="d-flex gap-1">
                                                            <button type="submit"
                                                                class="btn btn-sm btn-primary me-1">Save</button>
                                                    </form>
                                                    <form action="" method="POST" class="social-delete-form" onsubmit="return confirm('Delete this entry?');">
                                                        <input type="hidden" name="social_action" value="delete">
                                                        <input type="hidden" name="social_id"
                                                            value="<?php echo $sm['id']; ?>">
                                                        <button type="submit"
                                                            class="btn btn-sm btn-danger">Delete</button>
                                                    </form>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                                <?php else: ?>
                                                <tr>
                                                    <td colspan="5" class="text-center">No social media found.</td>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function() {
    // DOM update helpers
    function updateSlideDOM(action, html, id) {
        if (action === 'create') {
            $('#slides-list').append(html);
        } else if (action === 'edit') {
            $('#slide-card-' + id).replaceWith(html);
        } else if (action === 'delete') {
            $('#slide-card-' + id).remove();
        }
    }
    function updateVideoDOM(action, html, id) {
        if (action === 'create') {
            $('#videos-list').append(html);
        } else if (action === 'edit') {
            $('#video-row-' + id).replaceWith(html);
        } else if (action === 'delete') {
            $('#video-row-' + id).remove();
        }
    }
    function ajaxFormHandler(formSelector, isCreateForm) {
    $(document).on('submit', formSelector, function(e) {
        e.preventDefault();
        var form = this;
        var formData = new FormData(form);
        var btn = $(form).find('[type=submit]');
        btn.prop('disabled', true);
        $('#admin-ajax-messages').html('');
        $.ajax({
            url: '',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                var json;
                try { json = typeof response === 'string' ? JSON.parse(response) : response; } catch (e) { json = null; }
                if(json && json.success) {
                    Swal.fire({icon: 'success',title: 'Success',text: json.success,timer: 1800,showConfirmButton: false});
                    // If this is the main info form, reload the page after success
                    if (formSelector === '#kt_ecommerce_add_product_form') {
                        setTimeout(function() {
                            location.reload();
                        }, 1000); // Wait for the success message to show
                    }
                    if(json.type === 'slide') {
                        updateSlideDOM(json.action, json.html, json.id);
                    }
                    if(json.type === 'video') {
                        updateVideoDOM(json.action, json.html, json.id);
                    }
                    if(isCreateForm) form.reset();
                } else if(json && json.error) {
                    Swal.fire({icon: 'error',title: 'Error',text: json.error});
                } else {
                    Swal.fire({icon: 'error',title: 'Error',text: 'Unexpected server response.'});
                }
            },
            error: function(xhr) {
                Swal.fire({icon: 'error',title: 'Error',text: 'Server error. Please try again.'});
            },
            complete: function() {
                btn.prop('disabled', false);
            }
        });
    });
}
    // Slides
    ajaxFormHandler('#slide-create-form', true);
    ajaxFormHandler('.slide-edit-form', false);
    ajaxFormHandler('.slide-delete-form', false);
    // Videos
    ajaxFormHandler('#video-create-form', true);
    ajaxFormHandler('.video-edit-form', false);
    ajaxFormHandler('.video-delete-form', false);
    // Social Media
    ajaxFormHandler('#social-create-form', true);
    ajaxFormHandler('.social-edit-form', false);
    ajaxFormHandler('.social-delete-form', false);

});
</script>
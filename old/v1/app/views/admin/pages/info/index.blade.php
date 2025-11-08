@import('app/utils/helpers/helper.php')
@include(admin.partials.head)
<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
    <div class="app-page  flex-column flex-column-fluid " id="kt_app_page">
        @include(admin.partials.header)
        <div class="app-wrapper  flex-column flex-row-fluid " id="kt_app_wrapper">
            <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
                @include(admin.partials.sidebar)
                <div class="d-flex flex-column flex-column-fluid">
                    <div id="kt_app_content" class="app-content">
                        <div id="kt_app_content_container" class="app-container  container-fluid ">
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="info_action" value="update">
                                <?php if(isset($success)): ?>
                                <div
                                    class="notice mb-3 d-flex bg-light-success rounded border-success border border-dashed  p-6">
                                    <!--begin::Icon-->
                                    <i class="bi bi-info fs-2tx text-success me-4"></i> <!--end::Icon-->

                                    <!--begin::Wrapper-->
                                    <div class="d-flex flex-stack flex-grow-1 ">
                                        <!--begin::Content-->
                                        <div class=" fw-semibold">
                                            <h4 class="text-gray-900 fw-bold">Success!</h4>

                                            <div class="fs-6 text-gray-700 ">{{ $success }}</div>
                                        </div>
                                        <!--end::Content-->

                                    </div>
                                    <!--end::Wrapper-->
                                </div>
                                <?php endif; ?>
                                <?php if(isset($message_error)) : ?>
                                <span class="badge py-3 px-4 fs-7 badge-light-danger">{{ $message_error }}</span>
                                <?php endif; ?>
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2>Information website</h2>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                                                <div
                                                    class="card h-100 flex-center bg-light-primary border-primary border border-dashed p-8">
                                                    <img style="width: 100%"
                                                        src="{{ empty($info->logo) ? vendor('media/svg/files/upload.svg') : assets($info->logo) }}"
                                                        class="mb-5" alt="upload">
                                                    <label for="logo" class="text-hover-primary fs-5 fw-bold mb-2">
                                                        Logo Upload
                                                    </label>
                                                    <div class="text-muted fs-7 mb-3">
                                                        Set the logo image. Only *.png,
                                                        *.jpg and *.jpeg image files are accepted
                                                    </div>
                                                    <input type="file" class="form-control" id="logo" name="logo">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                                                <div
                                                    class="card h-100 flex-center bg-light-warning border-warning border border-dashed p-8">
                                                    <div class="mb-5 card"
                                                        style="width: 100%; height: 190px; background-size: 100% auto; background-image: url('{{ empty($info->image) ? vendor('media/svg/files/upload.svg') : assets($info->image) }}')">

                                                    </div>
                                                    <label for="image" class="text-hover-primary fs-5 fw-bold mb-2">
                                                        Main image Upload
                                                    </label>
                                                    <div class="text-muted fs-7 mb-3">
                                                        Set the logo image. Only *.png,
                                                        *.jpg and *.jpeg image files are accepted
                                                    </div>
                                                    <input type="file" class="form-control" id="image" name="image">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                                                <div
                                                    class="card h-100 flex-center bg-light-success border-success border border-dashed p-8">
                                                    <div class="mb-5 card"
                                                        style="width: 100%; height: 190px; background-size: 100% auto; background-image: url('{{ empty($info->image_property) ? vendor('media/svg/files/upload.svg') : assets($info->image_property) }}')">

                                                    </div>
                                                    <label for="image_property"
                                                        class="text-hover-primary fs-5 fw-bold mb-2">
                                                        Main image Upload
                                                    </label>
                                                    <div class="text-muted fs-7 mb-3">
                                                        Set the logo image. Only *.png,
                                                        *.jpg and *.jpeg image files are accepted
                                                    </div>
                                                    <input type="file" class="form-control" id="image_property"
                                                        name="image_property">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                                                <label class="form-label" for="phone">Phone</label>
                                                <input type="text" class="form-control" id="phone" name="phone"
                                                    value="{{ $info->phone ?? '' }}">
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                                                <label class="form-label" for="phone">Whatsapp</label>
                                                <input type="text" class="form-control" id="whatsapp" name="whatsapp"
                                                    value="{{ $info->whatsapp ?? '' }}">
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    value="{{ $info->email ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                                <label for="short_about" class="form-label">About</label>
                                                <textarea class="form-control" id="short_about" name="short_about"
                                                    rows="5">
                                                {{ $info->short_about ?? '' }}
                                            </textarea>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                                <label for="short_about_es" class="form-label">About (Spanish)</label>
                                                <textarea class="form-control" id="short_about_es" name="short_about_es"
                                                    rows="5">
                                                {{ $info->short_about_es ?? '' }}
                                            </textarea>
                                            </div>
                                        </div>
                                        <div class="mt-2 mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea class="form-control" id="address" name="address"
                                                rows="2">{{ $info->address ?? '' }}</textarea>
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
                                </div>
                            </form>

                            <div class="card mt-5">
                                <div class="card-header">
                                    <h3 class="card-title">Social media</h3>
                                </div>
                                <div class="card-body">
                                    
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
                                                <input type="file" name="image" class="form-control"
                                                    accept="image/*" required>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="submit" class="btn btn-success">Add</button>
                                            </div>
                                        </div>
                                    </form>
                                    

                                <div id="content_social">
                                </div>
                                </div>
                            </div>

                            
                            <div class="card mt-5">
                                <div class="card-header">
                                    <h3 class="card-title">Videos</h3>
                                </div>
                                <div class="card-body">
                                    
                                <form action="" method="POST" class="mb-4" id="video-create-form">
                                        <input type="hidden" name="video_action" value="create">
                                        
                                        <div class="input-group mb-5">
                                            <input type="text" class="form-control" placeholder="Title" name="title" aria-label="Title"/>
                                            <input type="text" class="form-control" placeholder="Title (Spanish)" name="title_es" aria-label="Title"/>
                                            <input type="text" class="form-control" placeholder="Subtitle" name="subtitle" aria-label="Title"/>
                                            <input type="text" class="form-control" placeholder="Subtitle (Spanish)" name="subtitle_es" aria-label="Title"/>
                                            <input type="text" class="form-control" placeholder="Link" name="link" aria-label="Link"/>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Add</button>
                                    </form>
                                    

                                <div id="content_video">
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include(admin.pages.info.social_media_js)
                @include(admin.pages.info.video_js)
                @include(admin.partials.footer)
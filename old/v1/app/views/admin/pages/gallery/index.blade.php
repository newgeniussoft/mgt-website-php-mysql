@import('app/utils/helpers/helper.php')
@include(admin.partials.head)
<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
    <div class="app-page  flex-column flex-column-fluid " id="kt_app_page">
        @include(admin.partials.header)
        <div class="app-wrapper  flex-column flex-row-fluid " id="kt_app_wrapper">
            <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
                @include(admin.partials.sidebar)
                <div class="d-flex flex-column flex-column-fluid">
                    <div id="kt_app_content" class="app-content px-4">
                        <div class="card  bg-light-secondary border-primary border border-dashed">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Image gallery</h2>
                                    <?php if(isset($error)): ?>
                                        {{ $error }}
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-end h-100 gx-5 gx-xl-10">
                                    <div class="col-md-3 mb-11 mb-md-0">
                                        <div class="overlay-wrapper px-4 py-4 pt-8 text-center bg-light-primary bgi-position-center bgi-no-repeat bgi-size-cover h-200px card-rounded mb-3">
                                            <form action="" method="POST" enctype="multipart/form-data">
                                                <input type="hidden" name="gallery_action" value="create">
                                                <label for="gallery_image" class="form-label text-hover-primary">New
                                                    image</label>
                                                <input type="file" id="gallery_image" name="gallery_image"
                                                    class="form-control" accept="image/*" required>
                                                <div class="mt-3">
                                                    <label for="gallery_type" class="form-label">Type</label>
                                                    <input type="text" id="gallery_type" name="gallery_type"
                                                        class="form-control" placeholder="Enter gallery type">
                                                </div>
                                                <button type="submit" class="btn btn-success btn-sm mt-3">Add
                                                    Image</button>
                                            </form>
                                        </div>
                                    </div>
                                    <?php foreach($galleries as $gallery): ?>
                                    <div class="col-md-3 mb-11 mb-md-0">
                                        <div class="d-block overlay" data-fslightbox="lightbox-hot-sales">
                                            <div class="overlay-wrapper bgi-position-center bgi-no-repeat bgi-size-cover h-200px card-rounded mb-3"
                                                style="height: 266px;background-image:url('{{ assets(thumbnail($gallery->image)) }}'); z-index:1">
                                                <div class="position-absolute bottom-0 start-0 p-3">
                                                    <span class="badge bg-primary">{{ $gallery->type }}</span>
                                                </div>
                                            </div>
                                            <div class="overlay-layer card-rounded bg-dark bg-opacity-25">
                                                <a href="{{ assets($gallery->image) }}"
                                                    class="btn me-3 btn-primary btn-icon btn-sm">
                                                    <i class="bi bi-eye text-white"></i>
                                                </a> <br>
                                                <button onclick="deleteGallery('{{ $gallery->id }}')" class="btn btn-danger btn-icon btn-sm">
                                                    <i class="bi bi-trash text-white"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    function deleteGallery(id) {
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            icon: 'warning',
                            showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, delete it!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: '{{ url_admin("gallery") }}',
                                        type: 'POST',
                                        data: {
                                            gallery_delete: id
                                        },
                                        success: function(response) {
                                            if (response.success) {
                                                Swal.fire(
                                                    'Deleted!',
                                                    'Gallery deleted successfully.',
                                                    'success'
                                                );
                                                setTimeout(() => {
                                                    location.reload();
                                                }, 1500);
                                            } else {
                                                Swal.fire(
                                                    'Error!',
                                                    'Failed to delete gallery.',
                                                    'error'
                                                );
                                            }
                                        },
                                        error: function(xhr, status, error) {
                                            Swal.fire(
                                                'Error!',
                                                'Failed to delete gallery.',
                                                'error'
                                            );
                                        }
                                    });
                                }
                            })
                        }
                </script>
                @include(admin.partials.footer)
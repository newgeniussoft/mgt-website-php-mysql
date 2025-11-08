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
                                        <h2>Images</h2>
                                    </div>
                                </div>
                                <div class="card-body">
                                    
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
                                    <div class="row align-items-end h-100 gx-5 gx-xl-10">
                                        <?php foreach($slides as $slide): ?>
                                    <div class="col-md-3 mb-11 mb-md-0">
                                        <div class="d-block overlay" data-fslightbox="lightbox-hot-sales">
                                            <div class="overlay-wrapper bgi-position-center bgi-no-repeat bgi-size-cover h-200px card-rounded mb-3"
                                                style="height: 266px;background-image:url('{{ assets(thumbnail($slide->image)) }}'); z-index:1">
                                            </div>
                                            <div class="overlay-layer card-rounded bg-dark bg-opacity-25">
                                                <a href="{{ assets($slide->image) }}"
                                                    class="btn me-3 btn-primary btn-icon btn-sm">
                                                    <i class="bi bi-eye text-white"></i>
                                                </a> <br>
                                                <button onclick="deleteSlide('{{ $slide->id }}')" class="btn btn-danger btn-icon btn-sm">
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
                    </div>
                    <script>
                        function deleteSlide(id){
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
                                        url: '{{ url_admin("slide") }}',
                                        type: 'POST',
                                        data: {
                                            slide_delete: id
                                        },
                                        success: function(response) {
                                            if (response.success) {
                                                Swal.fire(
                                                    'Deleted!',
                                                    'Slide deleted successfully.',
                                                    'success'
                                                );
                                                setTimeout(() => {
                                                    location.reload();
                                                }, 1500);
                                            } else {
                                                Swal.fire(
                                                    'Error!',
                                                    'Failed to delete slide.',
                                                    'error'
                                                );
                                            }
                                        },
                                        error: function(xhr, status, error) {
                                            Swal.fire(
                                                'Error!',
                                                'Failed to delete slide.',
                                                'error'
                                            );
                                        }
                                    });
                                }
                            })
                            
                        }
                    </script>
@include(admin.partials.footer)

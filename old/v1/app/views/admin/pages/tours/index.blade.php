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
                            <div class="container">
        <a href="{{ url_admin('tours/create') }}" class="btn btn-primary btm-sm mb-3">Add Tour</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Title</th>
                    <th>Subtitle</th>
                    <th>Meta Title</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tours as $tour)
                    <tr>
                        <td>{{ $tour->name }}</td>
                        <td>{{ $tour->title }}</td>
                        <td>{{ $tour->subtitle }}</td>
                        <td>{{ $tour->meta_title }}</td>
                        <td>
                            <form onSubmit="return confirmDelete(event, this)" action="{{ url_admin('tours/destroy/'.$tour->id) }}" method="POST" style="display:inline-block;">
                               
                            <a href="{{ url_admin('tours/edit/'.$tour->id) }}" class="btn mb-2 btn-sm btn-primary btn-icon">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="submit" class="btn btn-sm btn-danger btn-icon">
                                <i class="bi bi-trash"></i>
                            </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        function confirmDelete(e, form) {
                            e.preventDefault();
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
                                    form.submit();
                                }
                            })
                        }
                    </script>
@include(admin.partials.footer)

   

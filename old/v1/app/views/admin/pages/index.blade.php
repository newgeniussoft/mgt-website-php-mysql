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
                            <a href="{{ url_admin('page/create') }}" class="btn btn-primary">Create New Page</a>
                            <table class="table table-striped mt-3">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Path</th>
                                        <th>Menu Title (EN)</th>
                                        <th>Menu Title (ES)</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pages as $page)
                                    <tr>
                                        <td>{{ $page->id }}</td>
                                        <td>{{ $page->path }}</td>
                                        <td>{{ $page->menu_title }}</td>
                                        <td>{{ $page->menu_title_es }}</td>
                                        <td>
                                            <a href="{{ url_admin('page/edit/'.$page->id) }}"
                                                class="btn btn-sm btn-warning">Edit</a>
                                            <button onclick="deletePage({{ $page->id }})"
                                                class="btn btn-sm btn-danger">Delete</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <script>
                   
                    function deletePage(id) {
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
                                window.location.href = "{{ url_admin('page/delete/') }}" + id;
                            }
                        })
                    }
                </script>
                @include(admin.partials.footer)
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

    <h1 class="mb-4">Add Tour</h1>
    <form action="{{ url_admin('tours/store') }}" method="POST" enctype="multipart/form-data">
        <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab"
                    href="#kt_tab_pane_desc">Description</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab"
                    href="#kt_tab_pane_details">Details</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab"
                    href="#kt_tab_pane_photos">Photos</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="kt_tab_pane_desc" role="tabpanel">
                @include(admin.pages.tours.tour_description_fields)
                @include(admin.pages.tours.tour_price_fields)
                @include(admin.pages.tours.tour_highlights_fields)
            </div>
            <div class="tab-pane fade" id="kt_tab_pane_details" role="tabpanel">
                @include(admin.pages.tours.tour_details_fields)
            </div>
            <div class="tab-pane fade" id="kt_tab_pane_photos" role="tabpanel">
                @include(admin.pages.tours.tour_photo_fields)
            </div>
        </div>
        <div class="form-group mb-2 mt-2">
            <label>
                <input type="checkbox" name="show_in_home" value="1"> Show in Home
            </label>
            <input type="hidden" name="show_in_home" value="0">
        </div>
        <button type="submit" class="btn btn-success mt-3">Save</button>
        <a href="{{ route('admin.tours.index') }}" class="btn btn-secondary mt-3">Cancel</a>
    </form>
</div>
                            </div>
                        </div>
                    </div>
@include(admin.partials.footer)


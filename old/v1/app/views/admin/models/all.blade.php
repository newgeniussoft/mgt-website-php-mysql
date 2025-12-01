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
    <h1>All models</h1>
    <ul>
        @foreach($models as $model)
            <li><a href="{{ url_admin('model/'.$model['name']) }}"><?php echo $model['name']; ?></a></li>
        @endforeach
    </ul>
</div>

                            </div>
                        </div>
                    </div>
@include(admin.partials.footer)

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
                            @import('app/utils/helpers/helper.php')
<div class="container">
    <h2>Table: {{ $model }}</h2>
    <a href="{{ url_admin('model') }}">Back to Models</a>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                @foreach($columns as $col)
                    <th>{{ $col }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    <form method="POST" action="{{ url_admin('model/' . $model . '/update/' . $row->id) }}">
                        @foreach($columns as $col)
                            <td>
                                <input type="text" name="{{ $col }}" value="{{ $row->$col ?? '' }}" style="width:100px;" />
                            </td>
                        @endforeach
                        <td>
                            <button type="submit">Update</button>
                        </td>
                    </form>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

                            </div>
                        </div>
                    </div>
@include(admin.partials.footer)

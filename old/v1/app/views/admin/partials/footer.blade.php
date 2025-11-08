@import('app/utils/helpers/helper.php')
<div id="kt_app_footer" class="app-footer  px-lg-3 ">
                        <!--begin::Footer container-->
                        <div
                            class="app-container  container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3 ">
                            <!--begin::Copyright-->
                            <div class="text-gray-900 order-2 order-md-1">
                                <span class="text-muted fw-semibold me-1">2025&copy;</span>
                                Admin Madagascar Green Tours
                            </div>
                            <!--end::Copyright-->
                        </div>
                        <!--end::Footer container-->
                    </div>
                    <!--end::Footer-->
                </div>
                </div>
<!--end::Modal - Users Search--> <!--end::Modals-->

    <!--begin::Javascript-->
    <script>
        var hostUrl = "vendor/index.html";        </script>

    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{ vendor('plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ vendor('js/scripts.bundle.js') }}"></script>
    <!--end::Global Javascript Bundle-->

    <!--begin::Vendors Javascript(used for this page only)-->
    <script src="{{ vendor('plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
    <script src="{{ vendor('plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <!--end::Vendors Javascript-->

    <!--begin::Custom Javascript(used for this page only)-->
    <script src="{{ vendor('js/widgets.bundle.js') }}"></script>
    <script src="{{ vendor('js/custom/widgets.js') }}"></script>
    <script src="{{ vendor('js/custom/apps/chat/chat.js') }}"></script>
    <script src="{{ vendor('js/custom/utilities/modals/upgrade-plan.js') }}"></script>
    <script src="{{ vendor('js/custom/utilities/modals/create-app.js') }}"></script>
    <script src="{{ vendor('js/custom/utilities/modals/users-search.js') }}"></script>
    <!--end::Custom Javascript-->
    <!--end::Javascript-->
</body>
</html>
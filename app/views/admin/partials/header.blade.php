<div id="kt_app_header" class="app-header " data-kt-sticky="true" data-kt-sticky-activate="{default: true, lg: true}"
    data-kt-sticky-name="app-header-minimize" data-kt-sticky-offset="{default: '200px', lg: '300px'}"
    data-kt-sticky-animation="false">
    <!--begin::Header container-->
    <div class="app-container  container-fluid d-flex align-items-stretch flex-stack " id="kt_app_header_container">
        <!--begin::Sidebar toggle-->
        <div class="d-flex align-items-center d-block d-lg-none ms-n3" title="Show sidebar menu">
            <div class="btn btn-icon btn-color-gray-600 btn-active-color-primary w-35px h-35px me-1"
                id="kt_app_sidebar_mobile_toggle">
                <i class="ki-duotone ki-abstract-14 fs-2 fs-md-1"><span class="path1"></span><span
                        class="path2"></span></i>
            </div>
            <a href="index.html">
                <img alt="Logo" src="vendor/media/logos/star.svg" class="h-30px theme-light-show" />
                <img alt="Logo" src="vendor/media/logos/star-dark.svg" class="h-30px theme-dark-show" />
            </a>
        </div>
        <!--end::Sidebar toggle-->


        <!--begin::Toolbar wrapper-->
        <div class="app-navbar flex-lg-grow-1" id="kt_app_header_navbar">
            <!--begin::Search-->
            <div class="app-navbar-item d-flex align-items-stretch flex-lg-grow-1 me-2 me-lg-0">
                <div class="py-8">
                    <h2>Tableau de bord</h2>
                </div>
            </div>
            <div class="app-navbar-item me-2 ms-1 ms-md-2" id="kt_header_user_menu_toggle">
                <!--begin::Menu wrapper-->
                <div class="cursor-pointer symbol symbol-circle symbol-35px ms-3"
                    data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
                    data-kt-menu-placement="bottom-end">
                    <img src="vendor/media/avatars/300-3.jpg" alt="user" />
                </div>

                <!--begin::User account menu-->
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                    data-kt-menu="true">
                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                        <div class="menu-content d-flex align-items-center px-3">
                            <!--begin::Avatar-->
                            <div class="symbol symbol-50px me-5">
                                <img alt="Logo" src="vendor/media/avatars/300-3.jpg" />
                            </div>
                            <!--end::Avatar-->

                            <!--begin::Username-->
                            <div class="d-flex flex-column">
                                <div class="fw-bold d-flex align-items-center fs-5">
                                    Baba solomon <span
                                        class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">Admin</span>
                                </div>

                                <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">
                                    johnson@kt.com </a>
                            </div>
                            <!--end::Username-->
                        </div>
                    </div>
                    <!--end::Menu item-->

                    <!--begin::Menu separator-->
                    <div class="separator my-2"></div>
                    <!--end::Menu separator-->

                    <!--begin::Menu item-->
                    <div class="menu-item px-5">
                        <a href="account/overview.html" class="menu-link px-5">
                            My Profile
                        </a>
                    </div>
                    <!--begin::Menu separator-->
                    <div class="separator my-2"></div>
                    <!--end::Menu separator-->
                    <!--begin::Menu item-->
                    <div class="menu-item px-5">
                        <a href="authentication/sign-in/basic.html" class="menu-link px-5">
                            Sign Out
                        </a>
                    </div>
                    <!--end::Menu item-->
                </div>
                <!--end::User account menu-->
                <!--end::Menu wrapper-->
            </div>
        </div>
    </div>
</div>
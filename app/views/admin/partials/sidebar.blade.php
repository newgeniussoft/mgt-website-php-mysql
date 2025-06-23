@import('app/utils/helpers/helper.php')
<div id="kt_app_sidebar" class="app-sidebar  flex-column " data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <div class="app-sidebar-logo position-relative" id="kt_app_sidebar_logo">
        <a href="index.html">
            <img alt="Logo" src="{{ assets('img/logos/apple-touch-icon.png') }}"
                class="h-30px app-sidebar-logo-default theme-light-show" />
            <img alt="Logo" src="{{ assets('img/logos/apple-touch-icon.png') }}"
                class="h-30px app-sidebar-logo-default theme-dark-show" />
            <img alt="Logo" src="{{ assets('img/logos/apple-touch-icon.png') }}" class="h-20px app-sidebar-logo-minimize" />
        </a>
        <div id="kt_app_sidebar_toggle"
            class="app-sidebar-toggle btn btn-icon btn-sm btn-color-gray-600 btn-active-color-primary border border-gray-300 h-30px w-30px position-absolute rounded-circle top-50 start-100 translate-middle rotate "
            data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
            data-kt-toggle-name="app-sidebar-minimize">
            <i class="bi bi-arrow-left fs-1 rotate-180"></i>
        </div>
    </div>
    <div class="app-sidebar-user-default app-sidebar-user-minimize bg-light border border-gray-300 rounded mx-9 mt-9 mt-lg-2"
        id="kt_app_sidebar_user">
        <a href="#" class="d-flex align-items-center w-200px p-4 parent-hover">
            <span class="cursor-pointer symbol symbol-circle symbol-40px me-4">
                <img src="{{ vendor('media/avatars/300-3.jpg') }}" alt="image" />
            </span>
            <span class="d-flex flex-column">
                <span class="text-gray-800 fs-7 fw-bold parent-hover-primary">Baba solomon</span>
                <span class="text-gray-500 fs-8 fw-semibold">Administrateur</span>
            </span>
        </a>
    </div>
    
    <div class="app-sidebar-menu overflow-hidden">
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper my-5">
            <div id="kt_app_sidebar_menu_scroll" class="hover-scroll-overlay-y my-5 mx-4" data-kt-scroll="true"
                data-kt-scroll-activate="true" data-kt-scroll-height="auto"
                data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_user" data-kt-scroll-offset="5px">
                <div class="
                    menu 
                    menu-column 
                    menu-rounded 
                    menu-sub-indention       
                    fw-semibold       
                    px-1
                " id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
                    <div class="menu-item pt-5 ms-2">
                        <div class="menu-content">
                            <span class="menu-heading fw-bold text-uppercase fs-5">Menu</span>
                        </div>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link {{ currentPage() == '' ? 'active' : '' }}" href="{{ route('access/') }}">
                            <span class="menu-icon">
                                <i class="bi bi-house fs-2">
                                </i>
                            </span>
                            <span class="menu-title">Tableau de bord</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link  {{ currentPage() == 'info' ? 'active' : '' }}" href="{{ route('access/info') }}">
                            <span class="menu-icon">
                                <i class="bi bi-info-square fs-3">
                                </i>
                            </span>
                            <span class="menu-title">Information</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link {{ currentPage() == 'gallery' ? 'active' : '' }}" href="{{ route('access/gallery') }}">
                            <span class="menu-icon">
                                <i class="bi bi-image fs-3">
                                </i>
                            </span>
                            <span class="menu-title">Gallery</span>
                        </a>
                    </div>
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ (isInCurrentPath(['pages', 'create']) || currentPage() == 'pages') ? 'show hover' : '' }}">
                        <span class="menu-link {{ (isInCurrentPath(['pages', 'create']) || currentPage() == 'pages') ? 'active' : '' }}">
                            <span class="menu-icon">
                                <i class="bi bi-file-earmark fs-2"></i>
                            </span>
                            <span class="menu-title">Pages</span>
                            <span class="menu-arrow"></span></span>
                            <div class="menu-sub menu-sub-accordion">
                                <div class="menu-item">
                                    <a class="menu-link {{ currentPage() == 'pages' ? 'active' : '' }}"href="{{ route('access/pages') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">List of pages</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link  {{ isInCurrentPath(['pages', 'create']) ? 'active' : '' }}" href="{{ route('access/pages/create') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">New page</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ (isInCurrentPath(['tours', 'create']) || currentPage() == 'tours') ? 'show hover' : '' }}">
                            <span class="menu-link  {{ (isInCurrentPath(['tours', 'create']) || currentPage() == 'tours') ? 'active' : '' }}">
                                <span class="menu-icon">
                                    <i class="bi bi-folder fs-2"></i>
                                </span>
                                <span class="menu-title">Tours</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion"><!--begin:Menu item-->
                                <div class="menu-item">
                                    <a class="menu-link {{ currentPage() == 'tours' ? 'active' : '' }}" href="{{ route('access/tours') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">List</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link {{ isInCurrentPath(['tours', 'create']) ? 'active' : '' }}" href="{{ route('access/tours/create') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Create</span>
                                    </a>
                                </div>
                            
                        </div><!--end:Menu sub-->
                    </div><!--end:Menu item-->
                                  <!--end:Menu item-->
                </div>
                <!--end::Menu-->
            </div>
            <!--end::Menu scroll-->
        </div>
        <!--end::Menu wrapper-->
    </div>
    <!--end::sidebar menu-->
</div>
@import('app/utils/helpers/helper.php')
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login Admin Madagascar Green Tours</title>
    <meta charset="utf-8" />
    <meta name="description" content="Admin Madagascar Green Tours" />
    <meta name="keywords" content="Admin Madagascar Green Tours" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Login Admin Madagascar Green Tours" />
    <meta property="og:url" content="{{ url_admin('login') }}" />
    <meta property="og:site_name" content="Admin Madagascar Green Tours" />
    <link rel="canonical" href="{{ url_admin('login') }}" />
    <link rel="shortcut icon" href="{{ assets('img/logos/apple-touch-icon.png') }}" />
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" /> <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ vendor('plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ vendor('css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
</head>
<!--end::Head-->

<!--begin::Body-->
<body id="kt_body" class="app-blank">
    <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light";
        var themeMode;

        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }

            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }

            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }            
    </script>
    <!--end::Theme mode setup on page load-->
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!--begin::Authentication - Sign-in -->
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <!--begin::Aside-->
            <div class="d-flex flex-column flex-lg-row-auto bg-secondary w-xl-600px positon-xl-relative">
                <!--begin::Wrapper-->
                <div class="d-flex flex-column position-xl-fixed top-0 bottom-0 w-xl-600px scroll-y">
                    <!--begin::Header-->
                    <div class="d-flex flex-row-fluid flex-column text-center p-5 p-lg-10 pt-lg-20">
                        <!--begin::Logo-->
                        <a href="{{ url_admin('login') }}" class="py-2 py-lg-20">
                            <img alt="Logo" src="{{ assets('img/logos/apple-touch-icon.png') }}"
                                class="theme-light-show h-40px h-lg-50px" />
                            <img alt="Logo" src="{{ assets('img/logos/apple-touch-icon.png') }}"
                                class="theme-dark-show h-40px h-lg-50px" />
                        </a>
                        <!--end::Logo-->

                        <!--begin::Title-->
                        <h1 class="d-none d-lg-block fw-bold text-success fs-2qx pb-5 pb-md-10">
                            Welcome to Admin <br>Madagascar Green Tours </h1>
                        <!--end::Title-->
                    </div>
                    <!--end::Header-->

                    <!--begin::Illustration-->
                    <!--end::Illustration-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--begin::Aside-->

            <!--begin::Body-->
            <div class="d-flex flex-column flex-lg-row-fluid py-10">
                <!--begin::Content-->
                <div class="d-flex flex-center flex-column flex-column-fluid">
                    <!--begin::Wrapper-->
                    <div class="w-lg-500px p-10 p-lg-15 mx-auto">

                        <!--begin::Form-->
                        <form class="form w-100" action="{{ url_admin('login') }}" method="POST">
                            <!--begin::Heading-->
                            <div class="text-center mb-10">
                                <!--begin::Title-->
                                <h1 class="text-gray-900 mb-3">
                                    Sign In </h1>
                                <!--end::Title-->
                            </div>
                            <!--begin::Heading-->
<!--begin::Input group-->
<div class="form-floating mb-7">
    <input type="email" class="form-control" name="email" id="floatingInput" placeholder="name@example.com"/>
    <label for="floatingInput">Email address</label>
</div>
<!--end::Input group-->

<!--begin::Input group-->
<div class="form-floating mb-7">
    <input type="password" class="form-control" name="password" id="floatingPassword" placeholder="Password"/>
    <label for="floatingPassword">Password</label>
</div>
<!--end::Input group-->

                            <!--begin::Actions-->
                            <div class="text-center">
                                <!--begin::Submit button-->
                                <button type="submit" class="btn btn-lg btn-primary w-100 mb-5">
                                  Continue
                                </button>
                                <!--end::Submit button-->


                            </div>
                            <!--end::Actions-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Content-->

            </div>
            <!--end::Body-->
        </div>
        <!--end::Authentication - Sign-in-->
    </div>

    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{ vendor('plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ vendor('js/scripts.bundle.js') }}"></script>
    <!--end::Global Javascript Bundle-->
    <?php if (isset($error)) : ?>
    <script>
        Swal.fire(
            'Error!',
            '<?= $error ?>',
            'error'
        )
    </script>
    <?php endif ?>    
    <!--end::Custom Javascript-->
    <!--end::Javascript-->
</body>
<!--end::Body-->
</html>
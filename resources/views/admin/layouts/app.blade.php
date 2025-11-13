<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title', 'Admin Panel') - CMS Admin</title>

    <!-- Custom fonts for this template-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Admin CSS -->
    <style>
        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            background-color: #f8f9fc;
        }
        
        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1rem;
            border-radius: 0.35rem;
            margin: 0.1rem 0.5rem;
        }
        
        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .sidebar .sidebar-brand {
            color: #fff;
            text-decoration: none;
            padding: 1.5rem 1rem;
            display: block;
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 2rem;
        }
        
        .topbar {
            background-color: #fff;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-left: 250px;
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        .card {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border: 1px solid #e3e6f0;
        }
        
        .border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
        }
        
        .border-left-success {
            border-left: 0.25rem solid #1cc88a !important;
        }
        
        .border-left-info {
            border-left: 0.25rem solid #36b9cc !important;
        }
        
        .border-left-warning {
            border-left: 0.25rem solid #f6c23e !important;
        }
        
        .text-primary {
            color: #4e73df !important;
        }
        
        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        
        .btn-primary:hover {
            background-color: #224abe;
            border-color: #224abe;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            
            .main-content,
            .topbar {
                margin-left: 0;
            }
            
            .sidebar.show {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/admin">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-cogs"></i>
                </div>
                <div class="sidebar-brand-text mx-3">CMS Admin</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ strpos($_SERVER['REQUEST_URI'], '/admin/dashboard') !== false || $_SERVER['REQUEST_URI'] === '/admin' || $_SERVER['REQUEST_URI'] === '/admin/' ? 'active' : '' }}">
                <a class="nav-link" href="/admin/dashboard">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading" style="color: rgba(255, 255, 255, 0.4); font-size: 0.65rem; font-weight: 800; padding: 0.25rem 1rem; text-transform: uppercase; letter-spacing: 0.1rem;">
                Content Management
            </div>

            <!-- Nav Item - Pages -->
            <li class="nav-item {{ strpos($_SERVER['REQUEST_URI'], '/admin/pages') !== false ? 'active' : '' }}">
                <a class="nav-link" href="/admin/pages">
                    <i class="fas fa-fw fa-file-alt"></i>
                    <span>Pages</span>
                </a>
            </li>

            <!-- Nav Item - Tours -->
            <li class="nav-item {{ strpos($_SERVER['REQUEST_URI'], '/admin/tours') !== false ? 'active' : '' }}">
                <a class="nav-link" href="/admin/tours">
                    <i class="fas fa-fw fa-map-marked-alt"></i>
                    <span>Tours</span>
                </a>
            </li>

            <!-- Nav Item - Sections -->
            <li class="nav-item {{ strpos($_SERVER['REQUEST_URI'], '/admin/sections') !== false ? 'active' : '' }}">
                <a class="nav-link" href="/admin/sections">
                    <i class="fas fa-fw fa-th-large"></i>
                    <span>Sections</span>
                </a>
            </li>

            <!-- Nav Item - Templates -->
            <li class="nav-item {{ strpos($_SERVER['REQUEST_URI'], '/admin/templates') !== false ? 'active' : '' }}">
                <a class="nav-link" href="/admin/templates">
                    <i class="fas fa-fw fa-code"></i>
                    <span>Templates</span>
                </a>
            </li>

            <!-- Nav Item - Template Items -->
            <li class="nav-item {{ strpos($_SERVER['REQUEST_URI'], '/admin/template-items') !== false ? 'active' : '' }}">
                <a class="nav-link" href="/admin/template-items">
                    <i class="fas fa-fw fa-puzzle-piece"></i>
                    <span>Template Items</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading" style="color: rgba(255, 255, 255, 0.4); font-size: 0.65rem; font-weight: 800; padding: 0.25rem 1rem; text-transform: uppercase; letter-spacing: 0.1rem;">
                Media & Files
            </div>

            <!-- Nav Item - Media -->
            <li class="nav-item {{ strpos($_SERVER['REQUEST_URI'], '/admin/media') !== false ? 'active' : '' }}">
                <a class="nav-link" href="/admin/media">
                    <i class="fas fa-fw fa-images"></i>
                    <span>Media Library</span>
                </a>
            </li>

            <!-- Nav Item - File Manager -->
            <li class="nav-item {{ strpos($_SERVER['REQUEST_URI'], '/admin/filemanager') !== false ? 'active' : '' }}">
                <a class="nav-link" href="/admin/filemanager">
                    <i class="fas fa-fw fa-folder-open"></i>
                    <span>File Manager</span>
                </a>
            </li>

            <!-- Nav Item - Code Editor -->
            <li class="nav-item {{ strpos($_SERVER['REQUEST_URI'], '/admin/codeeditor') !== false ? 'active' : '' }}">
                <a class="nav-link" href="/admin/codeeditor">
                    <i class="fas fa-fw fa-code"></i>
                    <span>Code Editor</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading" style="color: rgba(255, 255, 255, 0.4); font-size: 0.65rem; font-weight: 800; padding: 0.25rem 1rem; text-transform: uppercase; letter-spacing: 0.1rem;">
                System
            </div>

            <!-- Nav Item - Database -->
            <li class="nav-item {{ strpos($_SERVER['REQUEST_URI'], '/admin/database') !== false ? 'active' : '' }}">
                <a class="nav-link" href="/admin/database">
                    <i class="fas fa-fw fa-database"></i>
                    <span>Database Manager</span>
                </a>
            </li>

            <!-- Nav Item - Users -->
            <li class="nav-item {{ strpos($_SERVER['REQUEST_URI'], '/admin/users') !== false ? 'active' : '' }}">
                <a class="nav-link" href="/admin/users">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Users</span>
                </a>
            </li>

            <!-- Nav Item - Settings -->
            <li class="nav-item {{ strpos($_SERVER['REQUEST_URI'], '/admin/settings') !== false ? 'active' : '' }}">
                <a class="nav-link" href="/admin/settings">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Settings</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="btn btn-link btn-sm text-white" id="sidebarToggle" onclick="toggleSidebar()">
                    <i class="fas fa-angle-left"></i>
                </button>
            </div>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Topbar -->
            <nav class="navbar navbar-expand topbar mb-4 static-top shadow">
                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3" onclick="toggleSidebar()">
                    <i class="fa fa-bars"></i>
                </button>

                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                {{ $_SESSION['user_name'] ?? 'Admin User' }}
                            </span>
                            <i class="fas fa-user-circle fa-lg text-gray-400"></i>
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="/admin/profile">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                Profile
                            </a>
                            <a class="dropdown-item" href="/admin/settings">
                                <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="/admin/logout">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="main-content">
                <!-- Display Flash Messages -->
                @if(isset($_SESSION['success']))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ $_SESSION['success'] }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @php unset($_SESSION['success']); @endphp
                @endif

                @if(isset($_SESSION['error']))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ $_SESSION['error'] }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @php unset($_SESSION['error']); @endphp
                @endif

                @if(isset($_SESSION['warning']))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ $_SESSION['warning'] }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @php unset($_SESSION['warning']); @endphp
                @endif

                @if(isset($_SESSION['info']))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle me-2"></i>{{ $_SESSION['info'] }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @php unset($_SESSION['info']); @endphp
                @endif

                <!-- Page Content -->
                @yield('content')
            </div>
            <!-- End Page Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; CMS Admin {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top" style="display: none; position: fixed; bottom: 20px; right: 20px; width: 2.75rem; height: 2.75rem; text-align: center; color: #fff; background: rgba(90, 92, 105, 0.5); line-height: 46px; border-radius: 100%;">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script>
        // Toggle sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('accordionSidebar');
            sidebar.classList.toggle('show');
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                if (alert.classList.contains('show')) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            });
        }, 5000);

        // Scroll to top functionality
        $(window).scroll(function() {
            if ($(this).scrollTop() > 100) {
                $('.scroll-to-top').fadeIn();
            } else {
                $('.scroll-to-top').fadeOut();
            }
        });

        $('.scroll-to-top').click(function() {
            $('html, body').animate({scrollTop: 0}, 800);
            return false;
        });

        // Initialize tooltips
        $(document).ready(function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });

        // Confirm delete actions
        function confirmDelete(message) {
            return confirm(message || 'Are you sure you want to delete this item? This action cannot be undone.');
        }

        // Auto-generate CSRF token for forms
        $(document).ready(function() {
            // Add CSRF token to all forms that don't have it
            $('form').each(function() {
                if (!$(this).find('input[name="csrf_token"]').length) {
                    $(this).append('<input type="hidden" name="csrf_token" value="{{ $_SESSION['csrf_token'] ?? '' }}">');
                }
            });
        });
    </script>

    <!-- Page-specific scripts can be added here -->
    @yield('scripts')

</body>
</html>

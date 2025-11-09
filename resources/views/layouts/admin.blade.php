<!DOCTYPE html>
<html lang="{{ app_locale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Panel' }} - Madagascar Green Tours</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 0;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
        }
        .sidebar-sticky {
            position: relative;
            top: 0;
            height: calc(100vh - 48px);
            padding-top: .5rem;
            overflow-x: hidden;
            overflow-y: auto;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, .8);
            padding: 12px 20px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, .1);
        }
        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, .15);
            border-left: 3px solid #007bff;
        }
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .sidebar-heading {
            font-size: .75rem;
            text-transform: uppercase;
            color: rgba(255, 255, 255, .5);
            padding: 12px 20px;
            margin-top: 20px;
        }
        .navbar-brand {
            padding-top: .75rem;
            padding-bottom: .75rem;
            font-size: 1rem;
            background-color: rgba(0, 0, 0, .25);
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .25);
            color: white !important;
            font-weight: 600;
        }
        .navbar-brand:hover {
            color: white !important;
        }
        main {
            padding-top: 20px;
        }
        .card {
            border: none;
            box-shadow: 0 0 10px rgba(0,0,0,.05);
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #fff;
            border-bottom: 2px solid #f8f9fa;
            font-weight: 600;
        }
        .opacity-50 {
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block sidebar">
                <div class="sidebar-sticky">
                    <a class="navbar-brand col-sm-3 col-md-12 mr-0" href="{{ url($_ENV['APP_ADMIN_PREFIX'] . '/dashboard') }}">
                        🌴 Admin Panel
                    </a>
                    
                    <ul class="nav flex-column mt-3">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ url($_ENV['APP_ADMIN_PREFIX'] . '/dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url($_ENV['APP_ADMIN_PREFIX'] . '/users') }}">
                                <i class="fas fa-users"></i>
                                Users
                            </a>
                        </li>
                    </ul>
                    
                    <h6 class="sidebar-heading">Content Management</h6>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-file-alt"></i>
                                Pages
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ admin_url('media') }}">
                                <i class="fas fa-images"></i>
                                Media Library
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ admin_url('media/folders') }}">
                                <i class="fas fa-folder"></i>
                                Folders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ admin_url('filemanager') }}">
                                <i class="fas fa-folder-open"></i>
                                File Manager
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ admin_url('codeeditor') }}">
                                <i class="fas fa-code"></i>
                                Code Editor
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-blog"></i>
                                Blog Posts
                            </a>
                        </li>
                    </ul>
                    
                    <h6 class="sidebar-heading">Settings</h6>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ admin_url('settings') }}">
                                <i class="fas fa-cog"></i>
                                General Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url($_ENV['APP_ADMIN_PREFIX'] . '/logout') }}">
                                <i class="fas fa-sign-out-alt"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">{{ $title ?? 'Dashboard' }}</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group mr-2">
                            <a href="{{ url('/') }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                                <i class="fas fa-external-link-alt"></i> View Site
                            </a>
                        </div>
                    </div>
                </div>
                
                @yield('content')
            </main>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>
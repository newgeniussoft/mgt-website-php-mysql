<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Dashboard' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
        }
        
        .sidebar {
            width: 280px;
            background-color: #212529;
            color: #fff;
            min-height: 100vh;
            transition: all 0.3s;
        }
        
        .sidebar-header {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-menu {
            padding: 1rem 0;
        }
        
        .sidebar-menu a {
            display: block;
            padding: 0.75rem 1.5rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: rgba(255,255,255,0.1);
            color: #fff;
        }
        
        .sidebar-menu i {
            margin-right: 0.5rem;
        }
        
        .content {
            flex: 1;
            padding: 2rem;
            background-color: #f8f9fa;
        }
        
        .content-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #dee2e6;
        }
        
        .card {
            margin-bottom: 1.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        }
        
        .stats-card {
            border-left: 4px solid #0d6efd;
        }
        
        .stats-card.orange {
            border-left-color: #fd7e14;
        }
        
        .stats-card.green {
            border-left-color: #198754;
        }
        
        .stats-card.red {
            border-left-color: #dc3545;
        }
        
        .stats-icon {
            font-size: 2rem;
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h4>MGT Admin</h4>
            <p class="mb-0">Welcome, {{ $username ?? 'Admin' }}</p>
        </div>
        
        <div class="sidebar-menu">
            <a href="/access" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="/access/users"><i class="bi bi-people"></i> Users</a>
            <a href="/access/settings"><i class="bi bi-gear"></i> Settings</a>
            <a href="/access/logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
    </div>
    
    <div class="content">
        <div class="content-header d-flex justify-content-between align-items-center">
            <h2>Dashboard</h2>
            <div>
                <span class="text-muted">Today: {{ date('F j, Y') }}</span>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-0">Total Users</h6>
                            <h3 class="mt-2 mb-0">1,250</h3>
                        </div>
                        <div class="stats-icon text-primary">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card stats-card orange">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-0">New Users</h6>
                            <h3 class="mt-2 mb-0">28</h3>
                        </div>
                        <div class="stats-icon text-warning">
                            <i class="bi bi-person-plus"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card stats-card green">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-0">Active Sessions</h6>
                            <h3 class="mt-2 mb-0">42</h3>
                        </div>
                        <div class="stats-icon text-success">
                            <i class="bi bi-activity"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card stats-card red">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-0">Server Load</h6>
                            <h3 class="mt-2 mb-0">24%</h3>
                        </div>
                        <div class="stats-icon text-danger">
                            <i class="bi bi-cpu"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Recent Activity</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>John Doe</td>
                                    <td>Updated profile</td>
                                    <td>10 minutes ago</td>
                                </tr>
                                <tr>
                                    <td>Jane Smith</td>
                                    <td>Created new account</td>
                                    <td>1 hour ago</td>
                                </tr>
                                <tr>
                                    <td>Bob Johnson</td>
                                    <td>Changed password</td>
                                    <td>2 hours ago</td>
                                </tr>
                                <tr>
                                    <td>Alice Williams</td>
                                    <td>Uploaded new document</td>
                                    <td>3 hours ago</td>
                                </tr>
                                <tr>
                                    <td>Charlie Brown</td>
                                    <td>Deleted account</td>
                                    <td>5 hours ago</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">System Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>CPU Usage</span>
                                <span>24%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 24%" aria-valuenow="24" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Memory Usage</span>
                                <span>65%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Disk Usage</span>
                                <span>42%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 42%" aria-valuenow="42" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Network Usage</span>
                                <span>18%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 18%" aria-valuenow="18" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

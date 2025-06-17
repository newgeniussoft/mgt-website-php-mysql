<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h4>MGT Admin</h4>
            <p class="mb-0">Welcome, {{ $username ?? 'Admin' }}</p>
        </div>
        
        <div class="sidebar-menu">
            <a href="/access" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="/access/users"><i class="bi bi-people"></i> Users</a>
            <a href="/access/pages"><i class="bi bi-file-earmark-text"></i> Pages</a>
            <a href="/access/settings"><i class="bi bi-gear"></i> Settings</a>
            <a href="/access/logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
    </div>
    
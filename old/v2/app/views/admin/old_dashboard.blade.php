<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page_title ?? 'Dashboard' }} - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-xl font-bold text-gray-900">
                            <i class="fas fa-tachometer-alt mr-2 text-blue-600"></i>
                            Admin Panel
                        </h1>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="user-menu-button">
                            <div class="h-8 w-8 bg-blue-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <span class="ml-2 text-gray-700">{{ $user->username }}</span>
                            <i class="fas fa-chevron-down ml-1 text-gray-400"></i>
                        </button>
                        
                        <div class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50" id="user-menu">
                            <a href="/admin/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user-cog mr-2"></i>Profile
                            </a>
                            <a href="/admin/logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg min-h-screen">
            <div class="py-4">
                <nav class="mt-4">
                    <a href="/admin/dashboard" class="flex items-center px-4 py-2 text-gray-700 bg-blue-50 border-r-4 border-blue-600">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        Dashboard
                    </a>
                    <a href="/admin/users" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-50 hover:text-gray-700">
                        <i class="fas fa-users mr-3"></i>
                        Users
                    </a>
                    <a href="/admin/pages" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-50 hover:text-gray-700">
                        <i class="fas fa-file-alt mr-3"></i>
                        Pages
                    </a>
                    <a href="/admin/media" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-50 hover:text-gray-700">
                        <i class="fas fa-images mr-3"></i>
                        Media
                    </a>
                    <a href="/admin/settings" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-50 hover:text-gray-700">
                        <i class="fas fa-cog mr-3"></i>
                        Settings
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Welcome back, {{ $user->username }}!</h2>
                <p class="text-gray-600">Here's what's happening with your website today.</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Users</p>
                            <p class="text-2xl font-bold text-gray-900">1</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <i class="fas fa-file-alt text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pages</p>
                            <p class="text-2xl font-bold text-gray-900">0</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-yellow-100 rounded-lg">
                            <i class="fas fa-images text-yellow-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Media Files</p>
                            <p class="text-2xl font-bold text-gray-900">0</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <i class="fas fa-eye text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Page Views</p>
                            <p class="text-2xl font-bold text-gray-900">0</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <a href="/admin/pages/create" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition duration-200">
                                <i class="fas fa-plus-circle text-blue-600 mr-3"></i>
                                <span class="text-blue-700 font-medium">Create New Page</span>
                            </a>
                            <a href="/admin/media/upload" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition duration-200">
                                <i class="fas fa-upload text-green-600 mr-3"></i>
                                <span class="text-green-700 font-medium">Upload Media</span>
                            </a>
                            <a href="/admin/users/create" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition duration-200">
                                <i class="fas fa-user-plus text-purple-600 mr-3"></i>
                                <span class="text-purple-700 font-medium">Add New User</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">System Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">PHP Version</span>
                                <span class="font-medium">{{ phpversion() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Server</span>
                                <span class="font-medium">{{ $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Last Login</span>
                                <span class="font-medium">{{ date('M d, Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle user menu
        document.getElementById('user-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('user-menu');
            menu.classList.toggle('hidden');
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const button = document.getElementById('user-menu-button');
            const menu = document.getElementById('user-menu');
            
            if (!button.contains(event.target) && !menu.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
